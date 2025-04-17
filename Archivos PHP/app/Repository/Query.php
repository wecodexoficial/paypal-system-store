<?php

/**
 * Created by PhpStorm.
 * User: Eduardo Mtz
 * Date: 26/09/2017
 * Time: 00:04
 */
class Query extends \MasterController
{


    /**
     * @param $table2
     * @param $key
     * @return array|FALSE|int
     */

    public function joinQuery($table2, $key)
    {
        $q = "SELECT * FROM $this->table INNER JOIN $table2 ON $table2.$key = $this->table.$key";
        return self::dbQuery($q);

    }

    /**
     * @param $table2
     * @param $key
     * @param $field
     * @param $value
     * @return array|FALSE|int
     */
    public function joinQueryWhere($table2, $key, $field, $value)
    {
        $q = "SELECT * FROM $this->table INNER JOIN $table2 ON $table2.$key = $this->table.$key WHERE $this->table.$field ='$value'";
        return self::dbQuery($q);
    }


    /**
     * @param $table
     * @param $set_field
     * @param $set_value
     * @param $where_field
     * @param $where_value
     * @return array|FALSE|int
     */
    public static function qUpdateWhere($table, $set_field, $set_value, $where_field, $where_value)
    {
        $q = "UPDATE $table SET $set_field = '$set_value' WHERE $where_field='$where_value'";
        return self::dbQuery($q);
    }


    public static function qDeleteWhere($table, $field, $value)
    {
        $q = "DELETE FROM $table WHERE $field='$value'";
        return self::dbQuery($q);
    }


    /**
     * @param $table
     * @param $values
     * @param $where_field
     * @param $where_value
     * @return array|FALSE|int
     */
    public static function qUpdateAllWhere($table, $values, $where_field, $where_value)
    {
        $condition = null;
        foreach ($values as $key => $value) {
            $condition .= "$key = '$value',";
        }
        $condition = substr($condition, 0, -1);

        $q = "UPDATE $table SET $condition WHERE $where_field='$where_value'";
        return self::dbQuery($q);
    }


    /**
     * Consulta multi condicion
     * @param $table
     * @param $arr_conditions
     * @param $type_condition
     * @param bool $order_value
     * @param bool $order
     * @return array|FALSE|int
     */
    public static function qMultiWhere($table, $arr_conditions, $type_condition, $order_value = false, $order = false, $limit = 10000)
    {
        $condition = null;
        foreach ($arr_conditions as $field => $value) {
            $condition .= ($field . " = '" . $value . "'  " . $type_condition . " ");
        }
        $condition = substr($condition, 0, -4);
        if (!empty($order_value) || !empty($order)) {
            $q = "SELECT * FROM $table WHERE $condition ORDER BY $order_value $order LIMIT $limit";
        } else {
            $q = "SELECT * FROM $table WHERE $condition  LIMIT $limit";
        }
        return self::dbQuery($q);
    }


    /**
     * @param $table
     * @param null $select
     * @param null $type_select
     * @param $arr_conditions
     * @param string $type_condition
     * @return array|FALSE|int
     */
    public static function qSelectMultiWhere($table, $select = null, $type_select = null, $arr_conditions, $type_condition = "AND")
    {
        $condition = null;
        foreach ($arr_conditions as $field => $value) {
            $condition .= ($field . " = '" . $value . "'  " . $type_condition . " ");
        }
        $condition = substr($condition, 0, -4);

        if (!empty($type_select and !empty($select))) {
            $q = "SELECT $type_select($select) FROM $table WHERE $condition";
        } elseif (!empty($select)) {
            $q = "SELECT $select FROM $table WHERE $condition";
        } else {
            $q = "SELECT * FROM $table WHERE $condition";
        }

        return self::dbQuery($q);
    }


    /**
     * Consulta con parametro en espesifico
     * @param $table
     * @param $field
     * @param $value
     * @return mixed
     */
    public static function qWhere($table, $field, $value, $return_array = false, $order_field = null, $order = null)
    {
        if (empty($order)) {
            $q = "SELECT * FROM $table WHERE $field ='$value'";
        } else {
            $q = "SELECT * FROM $table WHERE $field ='$value' ORDER BY $order_field $order";
        }

        $response = self::dbQuery($q);
        if (count($response) >= 2) {
            return $response;
        } else {
            if ($return_array == true) {
                return $response;
            } else {
                return $response[0];
            }
        }
    }

    public static function qWhereLike($table, $field, $like_fields,$return_array = false, $order_field = null, $order = null)
    {
        if (empty($order)) {
            $q = "SELECT * FROM $table WHERE $field LIKE '%$like_fields%'";
        } else {
            $q = "SELECT * FROM $table WHERE $field LIKE '%$like_fields%' ORDER BY $order_field $order";
        }

        $response = self::dbQuery($q);
        if (count($response) >= 2) {
            return $response;
        } else {
            if ($return_array == true) {
                return $response;
            } else {
                return $response[0];
            }
        }
    }


    /**
     * Obtiene el ultimo registro de una tabla
     * @param $table
     * @param $field
     * @return mixed
     */
    public static function qLastTable($table, $field)
    {
        $q = "SELECT * FROM $table order by $field desc limit 1";
        $response = self::dbQuery($q);
        return $response[0];
    }


    /**
     * @param $tables
     * @param $field
     * @param $value
     * @return array|null
     */
    public static function qUniqueWhere($tables, $field, $value, $return_array = false)
    {
        $request = array();
        if (!empty($tables) || !empty($field)) {
            foreach ($tables as $table) {
                foreach (self::qWhere($table, $field, $value, true) as $key) {
                    $request = array_merge($request, $key);
                }
            }
            if ($return_array == true) {
                return array(0 => $request);
            } else {
                return $request;
            }
        } else {
            return null;
        }
    }

    /**
     * @param $tables
     * @param $arr_conditions
     * @param $type_condition
     * @param bool $order_value
     * @param bool $order
     * @return array|null
     */
    public static function qUniqueMultiWhere($tables, $arr_conditions, $type_condition, $order_value = false, $order = false)
    {
        $request = array();
        if (!empty($tables) || !empty($field)) {
            foreach ($tables as $table) {
                foreach (self::qMultiWhere($table, $arr_conditions, $type_condition, $order_value, $order) as $key) {
                    $request = array_merge($request, $key);
                }
            }
            return $request;
        } else {
            return null;
        }
    }

}