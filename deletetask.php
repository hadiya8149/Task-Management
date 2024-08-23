<?php 
include 'model/dbconnection.php';
$dbInstance = new DbConnection;
define('CONNECTION', $dbInstance->connectDatabase());
function deleteTask($taskId){ // delete request for delete task
    $deleteTaskQuery = CONNECTION->prepare("DELETE FROM task where id =?;");
    $deleteTaskQuery->bind_param('i', $taskId);

    try{
        $deleteTaskQuery->execute();
        $result = $deleteTaskQuery->get_result();
        header("location: index.php?success=task deleted successfully");

    }
    catch(mysqli_sql_exception $exception){
        return $exception;
    }
};
if(isset($_GET['id'])){
    $id =(int) $_GET['id'];
    deleteTask($id);
}
