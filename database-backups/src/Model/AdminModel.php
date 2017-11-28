<?php

namespace DatabaseBackups\Model;


class AdminModel
{
    protected $db;

    public function __construct()
    {
        global $wpdb;

        $this->db = $wpdb;
    }

    /**
     *
     * @return bool
     */
    public function getTables()
    {
        $query = $this->db->query('SHOW TABLES')->fetchAll();

        if (DatabaseBackupsOptions::instance()->getOption('prefix')) {
            foreach ($query as $key => $table) {
                if (substr($table[0], 0, strlen(DatabaseBackupsOptions::instance()->getTablePrefix())) !==
                    DatabaseBackupsOptions::instance()->getTablePrefix()
                )
                    unset($query[$key]);
            }
        }

        foreach ($query as $table) {
            $name = $table[0];
            $this->tables[$name]['name'] = $name;
            $this->tables[$name]['create'] = $this->_getColumns($name);
            $table_count = $this->_getDataCount($name);
            $limit = DatabaseBackupsOptions::instance()->getOption('limit');
            $limit = ($limit > 0) ? $limit : 1000;

            if ($table_count > $limit) // if table have record more than limit
            {
                $steps = ceil($table_count / $limit);
                $this->tables[$name]['data_raw'] = "";

                for ($step = 1; $step <= $steps; $step++) {
                    $start = $step * $limit - $limit;
                    $this->tables[$name]['data_raw'] .= $this->_getData($name, $start, $limit);
                }
            } else
                $this->tables[$name]['data_raw'] = $this->_getData($name, 0, $table_count);
        }
    }

    /**
     * Get all columns of table
     * @param $tableName
     * @return mixed
     */
    public function getColumns($tableName)
    {
        $query = $this->db->query('SHOW CREATE TABLE ' . $tableName);
        $q = $query->fetchAll();
        $q[0][1] = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $q[0][1]);
        return $q[0][1];
    }

    /**
     * Get count of records in table
     * @param $tableName
     * @return mixed
     */
    public function getDataCount($tableName)
    {
        return $this->db->query('SELECT count(*) FROM ' . $tableName)->fetchColumn();
    }
}