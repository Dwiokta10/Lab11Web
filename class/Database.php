<?php

class Database {
    protected $host;
    protected $user;
    protected $password;
    protected $db_name;
    protected $conn;

    public function __construct() {
        $this->getConfig();
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function getConfig() {
        // Pakai path absolut berdasarkan lokasi file Database.php,
        // supaya tetap ketemu config.php walau dipanggil dari folder modul manapun.
        include_once __DIR__ . '/../config.php';
        $this->host     = $config['host'];
        $this->user     = $config['username'];
        $this->password = $config['password'];
        $this->db_name  = $config['db_name'];
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    public function get($table, $where = null) {
        $where_sql = "";
        if ($where) {
            $where_sql = " WHERE " . $where;
        }

        $sql = "SELECT * FROM " . $table . $where_sql;
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function insert($table, $data) {
        if (is_array($data)) {
            $column = [];
            $values = [];
            foreach ($data as $key => $val) {
                $column[] = "$key";
                $values[] = "'$val'";
            }

            $columns = implode(",", $column);
            $values  = implode(",", $values);

            $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
            $sql = $this->conn->query($sql);
            if ($sql == true) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function update($table, $data, $where) {
        $update_value = [];
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $update_value[] = "$key='$val'";
            }

            $update_value = implode(",", $update_value);

            $sql = "UPDATE " . $table . " SET " . $update_value . " WHERE " . $where;
            $sql = $this->conn->query($sql);
            if ($sql == true) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function delete($table, $filter) {
        $sql = "DELETE FROM " . $table . " " . $filter;
        $sql = $this->conn->query($sql);
        if ($sql == true) {
            return true;
        } else {
            return false;
        }
    }
}

?>
