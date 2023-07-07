<?php
namespace Task\Bid\DBconnection;

class Db
{
    protected $serverName;
    protected $userName;
    protected $password;
    protected $db;
    protected $conn;

    public function __construct()
    {
        $this->serverName = "localhost";
        $this->userName = "root";
        $this->password = "";
        $this->db = "bid";
        $this->conn = mysqli_connect($this->serverName, $this->userName, $this->password, $this->db);
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
