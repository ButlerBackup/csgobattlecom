<?php

/**
 * MODEL
 */
class Model
{

    protected $_db;

    public function __construct()
    {
        Mysqli_DB::Database();
        $this->_db = Mysqli_DB::$_db;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function query($query)
    {
        return $this->_db->query($query);
    }

    /**
     * @param object $result
     * @param string $mode
     * @return array|null|object
     */
    public function fetch($result, $mode = 'object')
    {
        switch ($mode) {
            case 'row':
                return mysqli_fetch_row($result);
                break;

            case 'assoc':
                return mysqli_fetch_assoc($result);
                break;

            case 'array':
                return mysqli_fetch_array($result);
                break;

            default:
                return mysqli_fetch_object($result);
                break;
        }
    }

    /**
     * @param object $result
     * @param string $mode
     * @return array
     */
    public function getAll($result, $mode = 'object')
    {
        while ($row = $this->fetch($result, $mode))
            $return[] = $row;

        return $return;
    }

    /**
     * @param $result
     * @return int
     */
    public function numRows($result)
    {
        return mysqli_num_rows($result);
    }

    /**
     * @return int|string
     */
    public function insertID()
    {
        return mysqli_insert_id($this->_db);
    }

    /**
     * @return mixed
     */
    public function error()
    {
        $this->_db->error;
    }

    /**
     * @param $query
     * @param bool $delay
     * @return bool
     */
    public function multiQuery($query, $delay = false)
    {
        $this->_db->multi_query($query);

        if ($delay) {
            $result = true;

            while (true) {
                if ($r = $this->_db->store_result()) {
                    while ($row = $r->fetch_row()) {
                        $result = $result && $r;
                    }
                    $r->free();
                }

                if ($this->_db->more_results())
                    $this->_db->next_result();
                else
                    break;
            }

            return $result;
        } else
            return true;
    }

    /**
     * @param string $table
     * @param $data
     * @return string
     */
    public function getInsertQuery($table, $data)
    {
        $fields = null;
        $values = null;
        $prefix = null;
        $n = 0;

        foreach ($data as $key => $value) {
            if ($n != 0)
                $prefix = ', ';
            $fields .= "$prefix`$key`";
            $values .= "$prefix'$value'";
            $n++;
        }

        return "INSERT INTO `$table` ($fields) VALUES ($values);";
    }

    /**
     * @param string $table
     * @param array $data
     * @return int
     */
    public function insert($table, $data)
    {
        $fields = null;
        $values = null;
        $prefix = null;
        $n = 0;

        foreach ($data as $key => $value) {
            if ($n != 0)
                $prefix = ', ';
            $fields .= "$prefix`$key`";
            $values .= "$prefix'$value'";
            $n++;
        }

        $query = "INSERT INTO `$table` ($fields) VALUES ($values);";
        $this->_db->query($query);

        return $this->_db->insert_id;
    }

    /**
     * @param string $table
     * @param null $where
     * @param string $select
     * @return null|object
     */
    public function select($table, $where = null, $select = '*')
    {
        $query = "SELECT $select FROM `$table`";

        if (!empty($where))
            $query .= " WHERE $where";

        return $this->_db->query($query);
    }

    /**
     * @param string $table
     * @param array $fields
     * @param $where
     * @return mixed
     */
    public function update($table, $fields, $where = false, $return = false)
    {
        $n = 0;
        $query = "UPDATE `$table` SET ";

        foreach ($fields as $key => $value) {
            if ($n != 0)
                $prefix = ', ';
            else
                $prefix = null;

            // TODO ++ !!!
            if (!is_numeric($value) && $value == '++')
                $query .= "$prefix`$key` = `$key` +1";
            elseif (!is_numeric($value) && $value == '--')
                $query .= "$prefix`$key` = `$key` -1";
            else {
                if ($value === null)
                    $query .= "$prefix`$key` = NULL";
                else
                    $query .= "$prefix`$key` = '$value'";
            }

            $n++;
        }

        if ($where !== false)
            $query .= " WHERE $where";

        if ($return)
            return $query . ';';
        else
            return $this->_db->query($query);
    }

    /**
     * @param string $table
     * @param null $where
     * @return mixed
     */
    public function delete($table, $where = null)
    {
        $query = "DELETE FROM `$table`";

        if (!empty($where))
            $query .= " WHERE $where";

        return $this->_db->query($query);
    }
}

/* End of file */