<?php

namespace DatabaseBackups\Storage;

use DatabaseBackups\Entity\Object;
use DatabaseBackups\Interfaces\ObjectInterface;
use DatabaseBackups\Service\OptionsService;
use DatabaseBackups\Interfaces\StorageInterface;

class Filesystem implements StorageInterface {

	/**
	 * @var string
	 */
	protected static $name = 'Filesystem';

	/**
	 * @var string
	 */
	protected $directory;

	/**
	 * @var array
	 */
	protected $objects;

	/**
	 * @return bool
	 */
	public function connect() {
		$this->directory = WP_CONTENT_DIR . '/' . OptionsService::getOption( 'directory' ) . '/';

		return true;
	}

	/**
	 * @param ObjectInterface $object
	 *
	 * @return bool
	 */
	public function putObject( ObjectInterface $object ) {
		$handle = fopen( $this->directory . $object->getName(), 'wb+' );
		fwrite( $handle, $object->getBody() );
		fclose( $handle );

		return true;
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function getObject( $key ) {

	}

	/**
	 * @return array
	 */
	public function getObjectsList() {
		$this->objects = [];
		$dh            = opendir( $this->directory );
		$files         = [];

		$date_format = get_option( 'date_format' );

		while ( ( $name = readdir( $dh ) ) !== false ) {
			if ( $name !== '.' && $name !== '..' ) {
				$file = $this->directory . $name;
				if ( filetype( $file ) === 'file' && ( substr( $file, - 4 ) === '.sql' || substr( $file,
							- 7 ) === '.sql.gz' ) ) {
					$format = '';

					if ( substr( $file, - 4 ) === '.sql' ) {
						$format = 'sql';
					}

					if ( substr( $file, - 7 ) === '.sql.gz' ) {
						$format = 'gz';
					}

					$files[] = [
						'name'      => $name,
						'icon'      => $format === 'gz' ? 'dashicons-portfolio' : 'dashicons-media-spreadsheet',
						'size'      => filesize( $file ),
						'size_mb'   => round( filesize( $file ) / 1024 / 1024, 2 ),
						'url'       => $this->url . $name,
						'date'      => filemtime( $file ),
						'date_i18n' => date_i18n( $date_format . ' H:i:s', filemtime( $file ) ),
						'format'    => $format,
					];
				}
			}
		}

		rsort( $files );

		return $files;
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function deleteObject( $key ) {
		$filename = str_replace( [ '../', './', '/' ], '', $key );

		if ( ! is_file( $this->directory . $filename ) ||
		     ! mb_substr( $filename, - 4 ) === '.sql' || ! mb_substr( $filename, - 7 ) === '.sql.gz'
		) {
			return false;
		}

		return unlink( $this->directory . $filename );
	}

	/**
	 * @return bool
	 */
	public function isConnected() {
		return true;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return static::$name;
	}
}