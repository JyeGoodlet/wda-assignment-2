<?php

class DbConnect
{

    public $DB_USER = 'root';
    public $DB_PW = 'A@password';
    public $databaseName = 'msgboard';
    public $hostName = 'ec2-54-153-161-207.ap-southeast-2.compute.amazonaws.com';
    public $dsn;

    function __construct()
    {

        $this->dsn = "mysql:dbname=$this->databaseName;host=$this->hostName";
    }

    public function connect()
    {

        $pdo = new PDO($this->dsn, $this->DB_USER, $this->DB_PW);
        return $pdo;
    }
}


?>
