<?php
# change these settings according to your DB username and password
define("DB_HOST", "127.0.0.1"); # localhost

define("DB_USER", "root");
define("DB_PASS", "root");

define("DB_NAME", "tutorme");
define("CHARSET", "utf8");

class DBManager
{
    private static $dbFactory;
    private $conn;

    public static function getConn()
    {
        return DBManager::getDBFactory()->getConnection();
    }

    public function getConnection()
    {
        if (!$this->conn) {
            $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . CHARSET, DB_USER, DB_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            # $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return $this->conn;
    }

    public static function getDBFactory()
    {
        if (!self::$dbFactory)
            self::$dbFactory = new DBManager;
        return self::$dbFactory;
    }
}

?>
