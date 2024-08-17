<?php
class DbConnection{
    protected $connection;
        public function connectDatabase(){
            $env = parse_ini_file(".env");
            $host = $env['HOST'];
            $username=$env['USERNAME'];
            $database=$env['DATABASE'];
            $password = $env['PASSWORD'];
        try{
            $this->connection = new mysqli($host, $username, $password, $database);
            return $this->connection;
        }
        catch(mysqli_sql_exception $exception){
            var_dump($exception);
        }
    }
}
