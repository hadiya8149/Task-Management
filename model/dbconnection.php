<?php
class DbConnection{
    private $host = 'localhost';
    private $username = 'root';
    private $database = 'task_management';
    private $password = '';
    protected $connection;
    
    public function connectDatabase(){
        $host = $this->host;
        $username=$this->username;
        $database=$this->database;
        $password = $this->password;
        try{
            $this->connection = new mysqli($host, $username, $password, $database);
            return $this->connection;
        }
        catch(mysqli_sql_exception $exception){
            var_dump($exception);
        }
    }
}