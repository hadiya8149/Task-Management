<?php 
include 'model/dbconnection.php';
$dbInstance = new DbConnection;
define('CONNECTION', $dbInstance->connectDatabase());
function deleteTask($taskId){ // delete request for delete task
    $deleteTaskQuery = "DELETE FROM task where id =$taskId;";
    try{
        CONNECTION->query($deleteTaskQuery);
        header("location: task.php?success=task deleted successfully");

    }
    catch(mysqli_sql_exception $exception){
        return $exception;
    }
};
if(isset($_GET['id'])){
    $id =(int) $_GET['id'];
    deleteTask($id);
}
