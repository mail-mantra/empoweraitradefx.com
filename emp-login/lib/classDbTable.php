<?php
include_once __DIR__.'/../class/DbClass.php';

class classDbTable extends Database {
    public $mysql;
    public $table = null;
    public $error = null;
    public $lastQuery = '';
    function __construct($table, $con=false) {
        $this->table = $table;
        $this->mysql = $con?$con:$this->connect();
    }



    function __destruct()
    {
        // if($this->con) {
        try {
            // if(is_resource($this->mysql) && get_resource_type($this->mysql) === 'mysql link') {
            if(isset($this->mysql->server_info)) {
                /* if server is alive */

                // $page_name = basename($_SERVER['PHP_SELF']);
                // $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                // $page_name = $_SERVER['PHP_SELF'];
                // $query = "INSERT IGNORE INTO `log_connection_not_closed`(`page_name`) VALUES ('$page_name')";
                // $this->mysql->query($query);

                $this->mysql->close();
            }
            // else {
            //
            // }
        }
        catch(Exception $e) {
            // echo 'Message: ' .$e->getMessage();
        }
        // }
    }

    public function getLastQuery()
    {
        return $this->lastQuery;
    }

    public function update($data_arr, $where = 0)
    {
        if((is_array($data_arr) && count($data_arr)) || (is_string($data_arr) && strlen(trim($data_arr))) ) {
            $set_string = "";
            if(is_array($data_arr) && count($data_arr)) {
                foreach($data_arr as $key => $value) {
                    if(count(explode("=",$key)) === 2) {
                        $set_string .= $key; // e.g. array("deleted_at=NOW()" => NULL)
                    }
                    else {
                        $set_string .= " `$key`=";
                        if($value === null || $value === 'null' || $value === 'NULL') {
                            $set_string .= "NULL";
                        }
                        else {
                            $set_string .= "'" . addslashes($value) . "'";
                        }
                    }

                    $set_string .= ",";
                }
                $set_string = substr($set_string, 0, -1); // remove last comma
            }
            else {
                $set_string = $data_arr;
            }

            $where_string = "";
            if(is_numeric($where)) {
                $where_string = "`id`= '$where' ";
            }
            elseif(is_array($where) && count($where)) {
                foreach($where as $key => $value) {
                    if($value === null || $value === 'null' || $value === 'NULL') {
                        $where_string .= " `$key` IS NULL ";
                    }
                    else {
                        $where_string .= " `$key`=";
                        $where_string .= "'".addslashes($value)."'";
                    }

                    $where_string .= " AND";
                }
                $where_string = substr($where_string, 0, -3); // remove last AND
            }
            else {
                $where_string = $where;
            }

            $this->lastQuery = "UPDATE `" . $this->table . "` SET $set_string WHERE $where_string ;";
            return $this->mysql->query($this->lastQuery);
//                if($this->mysql->affected_rows) {
//                    return true;
//                }
//                else {
//                    return false;
//                }
        }
        else {
            throw new Exception("data array required", __LINE__);
        }
    }


    public function select_by_id($id, $column_arr = null, $where = null) {
        if(is_array($where)) {
            $where_string = array_merge($where, ['id' => $id]);
        }
        elseif(is_string($where)) {
            $where_string = $where." AND `id`={$id}";
        }
        else {
            $where_string = ['id' => $id];
        }

        $result = $this->select($column_arr, $where_string,null,1);
        return isset($result[0])?$result[0]:null;
    }

    public function select($column_arr = null, $where = null, $order_by=null, $limit=null, $offset=0)
    {
        $column_string = (is_array($column_arr) && count($column_arr)) ? implode(", ", $column_arr) : (is_string($column_arr) ? $column_arr : '*');

        if(is_numeric($where)) {
            $where_string = "`id`= '$where' ";
        }
        elseif(is_array($where) && count($where)) {
            $where_string = "";
            foreach($where as $key => $value) {
                if($value === null || $value === 'null' || $value === 'NULL') {
                    $where_string .= " `$key` IS NULL ";
                }
                else {
                    $where_string .= " `$key`=";
                    $where_string .= "'".addslashes($value)."'";
                }

                $where_string .= " AND";
            }

            $where_string = substr($where_string, 0, -3); // remove last AND
        }
        elseif(is_string($where)) {
            $where_string = $where;
        }
        else {
            $where_string = '';
        }
        $where_string = $where_string ? "WHERE $where_string" : '';

        if(is_array($order_by) && count($order_by)) {
            $order_by_arr = [];
            foreach($order_by as $key => $value) {
                if(is_numeric($key)) {
                    $order_by_arr[] = "{$value}, ";
                }
                elseif(strtoupper($value)==='ASC' || strtoupper($value)==='DESC') {
                    $order_by_arr[] = "{$key} {$value}, ";
                }
            }

            $order_by_string = implode(", ", $order_by_arr);
        }
        elseif(is_string($order_by)) {
            $order_by_string = $order_by;
        }
        else {
            $order_by_string = '';
        }

        $order_by_string = $order_by_string ? "ORDER BY $order_by_string" : '';

        $limit_string = ($limit)?"LIMIT $offset, $limit":'';


        $this->lastQuery = "SELECT {$column_string} FROM `{$this->table}` {$where_string} {$order_by_string} {$limit_string};";
        $res = $this->mysql->query($this->lastQuery);
        if($res->num_rows) {
            return $res->fetch_all(MYSQLI_ASSOC);
        }
        else {
            return false;
        }
//            }
//            else {
        // throw new Exception("data array required", __LINE__);
//            }
    }


    public function add($data_arr, $extras=null) {
        if(is_array($data_arr) && count($data_arr)) {
            $values = "";
            $temp = 0;
            foreach($data_arr as $value) {
                $temp++;
                if(is_array($value)) {
                    if($this->add($value,$extras) === true) {
                        if(count($data_arr)===$temp) {
                            return true;
                        }
                        else {
                            // continue;
                        }
                    }
                    else {
                        return false;
                    }
                }
                else {
                    if($value === null || $value === 'null' || $value === 'NULL') {
                        $values .= " NULL ";
                    }
                    else {
                        $values .= "'".addslashes($value)."'";
                    }

                    $values .= ",";
                }
            }

            $values = substr($values,0,-1); // remove last comma

            if(isset($extras['ignore']) && $extras['ignore']) {
                $sql_ignore = " IGNORE ";
            }
            else {
                $sql_ignore = " ";
            }

            $this->lastQuery = "INSERT{$sql_ignore}INTO `".$this->table."`(`".implode("`, `", array_keys($data_arr))."`) VALUES ($values)";
            /*
            $this->mysql->query($this->lastQuery);
            if($this->mysql->insert_id) {
                return true;
            }
            else {
                return false;
            }
            */
            return $this->mysql->query($this->lastQuery);
        }
        else {
            throw new Exception("data array required", __LINE__);
        }
    }

    public function addBulk($data_arr,$extras) {
        $temp = 0;
        if(is_array($data_arr) && count($data_arr)) {
            $this->mysql->autocommit(false);
            foreach($data_arr as $value) {
                $temp++;
                if(is_array($value) && count($value)) {
                    if($this->add($value,$extras) === true) {
                        if(count($data_arr) === $temp) {
                            return true;
                        }
                        else {
                            // continue;
                        }
                    }
                    else {
                        $this->mysql->rollback();
                        return false;
                    }
                }
                else {
                    throw new Exception("data array required", __LINE__);
                }
            }
            $this->mysql->rollback();
            return false;
        }
        else {
            throw new Exception("data multi array required", __LINE__);
        }
    }


}