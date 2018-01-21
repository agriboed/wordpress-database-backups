<?php

namespace DatabaseBackups\Service;

use DatabaseBackups\Core\AbstractService;
use DatabaseBackups\Entity\Object;
use DatabaseBackups\Exceptions\Exception;
use DatabaseBackups\Interfaces\ObjectInterface;
use DatabaseBackups\Model\BackupModel;
use DatabaseBackups\Core\Container;

/**
 * Class Backup
 * @package DatabaseBackups\Service
 */
class BackupService extends AbstractService {

	/**
	 * @var array
	 */
	protected $backups = [];

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var string
	 */
	protected $directory;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var string
	 */
	protected $sql = '';

	/**
	 * @var string
	 */
	protected $filename = '';

	/**
	 * @var ObjectInterface
	 */
	protected $object;

	/**
	 * @return bool
	 *
	 * @throws \Exception
	 */
	public function createBackup() {
		try {
			/**
			 * @var $backupModel BackupModel
			 */
			$backupModel = $this->container->get( BackupModel::class );

			$this->data = $backupModel->getTables(
				OptionsService::getOption( 'prefix' ),
				OptionsService::getOption( 'limit', 0 )
			);

			$this->filename  = 'db-backup-' . date( 'd_m_Y_H-i-s' ) . '_' . mt_rand( 0, 10000 ) . '.sql';
			$this->directory = WP_CONTENT_DIR . '/' . OptionsService::getOption( 'directory' ) . '/';

			$this
				->checkDirectory()
				->cleanDataBeforeSave()
				->convertDataToSql()
				->createSql()
				->convertGzip()
				->convertEncoding()
				->createObject()
				->putStorages()
				->sendNotification();

			return $this->filename;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Delete from generating data posts in trash,
	 * posts revisions, comments in trash and comment what marked as spam
	 *
	 * @throws \Exception
	 */
	protected function cleanDataBeforeSave() {
		if ( true !== OptionsService::getOption( 'clean' ) ) {
			return $this;
		}

		$deletedComments = [];
		$deletedPosts    = [];

		/**
		 * @var $backupModel BackupModel
		 */
		$backupModel = $this->container->get( BackupModel::class );
		$prefix_len  = strlen( $backupModel->getPrefix() );

		foreach ( $this->data as $name => $table ) {
			if ( mb_substr( $name, $prefix_len ) === 'posts' ) {
				$i = 0;
				if ( is_array( $table['data_raw'] ) && count( $table['data_raw'] ) > 0 ) {
					foreach ( $table['data_raw'] as $d ) {
						if ( $d[7] === 'trash' ) {
							unset( $this->data[ $name ]['data_raw'][ $i ] );
							$deletedPosts[] = $d[0];
						}
						if ( $d[20] === 'revision' ) {
							unset( $table['data_raw'][ $i ] );
							$deletedPosts[] = $d[0];
						}
						$i ++;
					}
				}
			}
			if ( mb_substr( $name, $prefix_len ) === 'comments' ) {
				$i = 0;
				if ( is_array( $table['data_raw'] ) && count( $table['data_raw'] ) > 0 ) {
					foreach ( $table['data_raw'] as $d ) {
						if ( $d[10] === 'spam' || $d['10'] === 'trash' ) {
							unset( $this->data[ $name ]['data_raw'][ $i ] );
							$deletedComments[] = $d[0];
						}
						$i ++;
					}
				}
			}
		}

		foreach ( $this->data as $name => $table ) {

			if ( mb_substr( $name, $prefix_len ) === 'commentmeta' ) {
				$i = 0;
				if ( is_array( $table['data_raw'] ) && count( $table['data_raw'] ) > 0 ) {
					foreach ( $table['data_raw'] as $d ) {
						if ( in_array( (int) $d[1], $deletedComments, true ) ) {
							unset( $this->data[ $name ]['data_raw'][ $i ] );
						}
						$i ++;
					}
				}
			}

			if ( mb_substr( $name, $prefix_len ) === 'postmeta' ) {
				$i = 0;
				if ( is_array( $table['data_raw'] ) && count( $table['data_raw'] ) > 0 ) {
					foreach ( $table['data_raw'] as $d ) {
						if ( in_array( (int) $d[1], $deletedPosts, true ) ) {
							unset( $this->data[ $name ]['data_raw'][ $i ] );
						}
						$i ++;
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function createSql() {
		$this->sql = "--\r\n";
		$this->sql .= "-- MYSQL DUMP BY Database Backups Plugin\r\n";
		$this->sql .= "-- https://wordpress.org/plugins/database-backups/\r\n";
		$this->sql .= "--\r\n";
		$this->sql .= 'CREATE DATABASE  IF NOT EXISTS `' . DB_NAME . "`;\r\n";
		$this->sql .= 'USE `' . DB_NAME . "`;\r\n";
		$this->sql .= "/* SET SQL_MODE='ALLOW_INVALID_DATES'; */\r\n";
		$this->sql .= '/* DEFAULT CHARACTER SET ' . DB_CHARSET . "; */\r\n";

		foreach ( $this->data as $table ) {
			$this->sql .= "\r\n";
			$this->sql .= "--\r\n";
			$this->sql .= '-- Table structure for table `' . $table['name'] . "`\r\n";
			$this->sql .= "--\r\n";
			$this->sql .= "\r\n";
			$this->sql .= $table['create'] . ';';
			$this->sql .= "\r\n\r\n";

			if ( ! empty( $table['data'] ) ) {
				$this->sql .= "--\r\n";
				$this->sql .= '-- Dumping data for table `' . $table['name'] . "`\r\n";
				$this->sql .= "--\r\n";
				$this->sql .= "\r\n";
				$this->sql .= $table['data'] . ';';
				$this->sql .= "\r\n";
			}
		}

		return $this;
	}

	/**
	 *
	 * @throws \DatabaseBackups\Exceptions\Exception
	 */
	protected function checkDirectory() {
		$directory_name = OptionsService::getOption( 'directory' );

		if ( ! empty( $directory_name ) ) {
			$this->url       = get_site_url() . '/wp-content/' . $directory_name . '/';
			$this->directory = WP_CONTENT_DIR . '/' . $directory_name . '/';
		} else {
			$this->url       = get_site_url() . '/wp-content/database-backups/';
			$this->directory = WP_CONTENT_DIR . '/database-backups/';
		}

		if ( ! is_dir( $this->directory ) ) {
			if ( ! mkdir( $this->directory ) && ! is_dir( $this->directory ) ) {
				throw new Exception( 'Directory "%s" was not created' );
			}
		}

		return $this;
	}

	/**
	 *
	 */
	protected function convertDataToSql() {
		foreach ( $this->data as $key => $table ) {
			$table['data'] = '';
			$pieces_count  = 0;

			if ( ! is_array( $table['data_raw'] ) || count( $table['data_raw'] ) === 0 ) {
				continue;
			}

			$table['data'] .= 'INSERT INTO `' . $table['name'] . "` VALUES \r\n";

			foreach ( $table['data_raw'] as $pieces ) {
				$pieces_count ++;
				$pieces = str_replace( array( "\n", "\r", "'" ), array( '\n', '\r', "''" ), $pieces );

				$table['data'] .= '(\'' . implode( '\',\'', $pieces ) . '\')';
				$table['data'] .= ( $pieces_count < count( $table['data_raw'] ) ) ? ',' . "\n" : ';' . "\n";
			}

			$this->data[ $key ] = $table;
		}


		return $this;
	}

	/**
	 * @return $this
	 */
	protected function convertGzip() {
		if ( function_exists( 'gzencode' ) && true === (bool) OptionsService::getOption( 'gzip' ) ) {
			$this->filename .= '.gz';
			$this->sql      = gzencode( $this->sql, 9 );
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function convertEncoding() {
		if ( ! function_exists( 'mb_convert_encoding' ) || true !== OptionsService::getOption( 'utf8' ) ) {
			return $this;
		}

		$this->sql = mb_convert_encoding( $this->sql, 'utf-8' );

		return $this;
	}

	/**
	 * @return $this
	 * @throws \RuntimeException
	 */
	protected function createObject() {
		if ( empty( $this->sql ) ) {
			throw new \RuntimeException( 'SQL is empty' );
		}

		$this->object = new Object();
		$this
			->object
			->setName($this->filename)
			->setBody($this->sql);

		return $this;
	}

	/**
	 * @return $this
	 * @throws \InvalidArgumentException
	 * @throws \DatabaseBackups\Exceptions\Exception
	 */
	protected function putStorages() {

		/**
		 * @var $storageService StorageService
		 */
		$storageService = $this->container->get(StorageService::class);
		$storageService->connect();
		$storageService->putObject($this->object );

		return $this;
	}

	/**
	 * @return $this
	 * @throws \RuntimeException
	 */
	protected function sendNotification() {
		if ( true !== OptionsService::getOption( 'notify' ) ) {
			return $this;
		}

		$backup    = $this->getBackup( $this->filename );
		$blog_name = get_option( 'blogname' );

		$html        = '';
		$date        = date_i18n( get_option( 'date_format' ) . ' H:i:s' );
		$backup_date = date_i18n( get_option( 'date_format' ) . ' H:i:s', $backup['date'] );

		if ( null === $backup || $backup['size'] === 0 ) {
			$subject = __( 'Backup not created on', Container::key() ) . ' ' . $blog_name;
			$html    .= '<p></p><p>' . __( 'Backup not created. Please check your settings.',
					'database-backups' ) . '</p>';
			$html    .= '<p>' . __( 'Date' ) . ': ' . $date . '</p>';
		} else {
			$subject = __( 'Database Backup was created at ', 'database-backups' ) . $blog_name;
			$html    .= '<p></p><p>' . __( 'Database Backup successfully created.', 'database-backups' ) . '</p>';
			$html    .= '<br><strong>' . __( 'Date', Container::key() ) . '</strong>: ' . $backup_date;
			$html    .= '<br><strong>' . __( 'Size',
					Container::key() ) . '</strong>: ' . round( $backup['size'] / 1024 / 1024, 2 ) . ' MB';
			$html    .= '<br><strong>' . __( 'Extension', Container::key() ) . '</strong>: ' . $backup['format'];
			$html    .= '<br><strong>' . __( 'Download', Container::key() ) . '</strong>: ' . $backup['url'];
		}
		$html .= '<p></p><hr><p><a href="' . get_option( 'siteurl' ) . '">' . $blog_name . '</a></p>';

		wp_mail( get_option( 'admin_email' ), $subject, $html, [ 'content-type: text/html' ] );

		return $this;
	}

	/**
	 *
	 * @throws Exception
	 */
	protected function readBackups() {
		/**
		 * @var $storageService StorageService
		 */
		$storageService = $this->container->get(StorageService::class);
		$storageService->getObjectsList();

		return $this;
	}

	/**
	 * @param $name
	 *
	 * @return bool|mixed
	 * @throws Exception
	 */
	public function getBackup( $name ) {
		$this
			->checkDirectory()
			->readBackups();

		foreach ( $this->backups as $backup ) {
			if ( $backup['name'] === $name ) {
				return $backup;
			}
		}

		return null;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getBackups() {
		$this->backups = [];

		$this
			->checkDirectory()
			->readBackups();

		return $this->backups;
	}

	/**
	 * @param $filename
	 *
	 * @return $this
	 *
	 * @throws \InvalidArgumentException
	 * @throws \DatabaseBackups\Exceptions\Exception
	 */
	public function deleteBackup( $filename ) {
		/**
		 * @var $storageService StorageService
		 */
		$storageService = $this->container->get(StorageService::class);

		$storageService->deleteObject($filename);
		return $this;
	}

	/**
	 *
	 * @throws \DatabaseBackups\Exceptions\Exception
	 * @throws \InvalidArgumentException
	 */
	public function checkOldCopies() {
		$days   = (int) OptionsService::getOption( 'delete_days' );
		$copies = (int) OptionsService::getOption( 'delete_copies' );

		if ( $days === 0 && $copies === 0 ) {
			return $this;
		}

		$this
			->checkDirectory()
			->getBackups();

		if ( $copies > 0 && count( $this->backups ) > $copies ) {
			$i = 0;

			foreach ( $this->backups as $backup ) {
				$i ++;
				if ( $i > $copies ) {
					$this->deleteBackup( $backup['name'] );
				}
			}
		}

		if ( $days > 0 ) {
			foreach ( $this->backups as $backup ) {
				$check_date = new \DateTime( '-' . $days . ' ' );
				$file_date  = new \DateTime();
				$file_date->setTimestamp( $backup['date'] );
				$diff = $file_date->diff( $check_date );
				if ( $diff->days > $days ) {
					$this->deleteBackup( $backup['name'] );
				}
			}
		}

		return $this;
	}

	/**
	 * @return float|int
	 * @throws Exception
	 */
	public function getOccupiedSpace() {
		$this->readBackups();

		if ( empty( $this->backups ) ) {
			return 0;
		}

		$return = 0;

		foreach ( $this->backups as $backup ) {
			$return += $backup['size'];
		}

		return round( $return / 1024 / 1024, 2 );
	}

	/**
	 * @return float
	 */
	public function getTotalFreeSpace() {
		return round( disk_free_space( $_SERVER['DOCUMENT_ROOT'] ) / 1024 / 1024, 2 );
	}
}