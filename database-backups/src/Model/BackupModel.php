<?php

namespace DatabaseBackups\Model;

use DatabaseBackups\Core\AbstractModel;

/**
 * Class AdminModel
 * @package DatabaseBackups\Model
 */
class BackupModel extends AbstractModel {

	/**
	 * @var array
	 */
	protected $tables = [];

	/**
	 * @param bool $isPrefix
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getTables( $isPrefix = false, $limit = 0 ) {
		/**
		 * @var $tables array
		 */
		$tables = $this->db->query( 'SHOW TABLES' )->fetchAll( 3 );

		if ( true === $isPrefix ) {
			foreach ( $tables as $key => $table ) {
				if ( 0 !== strpos( $table[0], $this->wpdb->prefix ) ) {
					unset( $tables[ $key ] );
				}
			}
		}

		foreach ( $tables as $table ) {
			$name = $table[0];

			$this->tables[ $name ]['name']   = $name;
			$this->tables[ $name ]['create'] = $this->getColumns( $name );

			$table_count = $this->getDataCount( $name );

			$limit = $limit > 0 ? $limit : 1000;

			if ( $table_count > $limit ) // if table have record more than limit
			{
				$steps                             = ceil( $table_count / $limit );
				$this->tables[ $name ]['data_raw'] = '';

				for ( $step = 1; $step <= $steps; $step ++ ) {
					$start                             = $step * $limit - $limit;
					$this->tables[ $name ]['data_raw'] .= $this->getData( $name, $start, $limit );
				}
			} else {
				$this->tables[ $name ]['data_raw'] = $this->getData( $name, 0, $table_count );
			}
		}

		return $this->tables;
	}

	/**
	 * Get all columns of a table
	 *
	 * @param $tableName
	 *
	 * @return mixed
	 */
	protected function getColumns( $tableName ) {
		$query   = $this->db->query( 'SHOW CREATE TABLE ' . $tableName );
		$result  = $query->fetchAll();
		$q[0][1] = preg_replace( "/AUTO_INCREMENT=[\w]*./", '', $result[0][1] );

		return $q[0][1];
	}

	/**
	 * Get count of records in table
	 *
	 * @param $tableName
	 *
	 * @return mixed
	 */
	protected function getDataCount( $tableName ) {
		$query = $this->db->prepare( 'SELECT COUNT(*) FROM ' . $tableName );
		$query->execute( [ $tableName ] );

		return $query->fetchColumn();
	}

	/**
	 * @param $tableName
	 * @param $start
	 * @param $limit
	 *
	 * @return mixed
	 */
	protected function getData( $tableName, $start, $limit ) {
		$query = $this->db->query( 'SELECT * FROM ' . $tableName . ' LIMIT ' . (int) $start . ', ' . (int) $limit . ';' );

		return $query->fetchAll( \PDO::FETCH_NUM );
	}
}