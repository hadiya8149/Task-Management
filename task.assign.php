<?php
include 'model/dbconnection.php';

$dbInstance = new DbConnection;
define ("CONNECTION",  $dbInstance->connectDatabase());
function assignTask($connection = CONNECTION,$username, $taskId){

    $getUserId = "SELECT user_id from user_profile where username = $username ";
    
     try{
        $userId = $connection->query($getUserId)->fetch_assoc()['id'];

        $assignTaskQuery = "INSERT INTO task_assignment (task_id, assignee_id) VALUES($taskId, $userId);";
     
        $result= $connection->query($assignTaskQuery);
        header("location: index.php?success=task assigned");
        exit;
    }
    catch(mysqli_sql_exception $exception){
        //PROBLEM: how does the developer know which error occured
        //SOLUTION: save the error in logs file for debugging purpose
        var_dump($exception);
        // header('location: index.php?error=please try again later');
    }
}


function deleteAssignedMember(){
    $deleteQuery = "DELETE FROM task_assignment where (task_id = ? AND assignee_id = ?)";
}
function editAssignedMember($taskId, $userId){
    $editAssignedMember ="UPDATE task_assignment SET assignee_id=$userId where task_id=$taskId;";
}

function getAssignedMembers($taskId){
    $getAssignedMemberByTask = "SELECT assignee_id FROM task_assignment where task_id=$taskId";
    $result = CONNECTION->query($getAssignedMemberByTask);
    $assignedMemberByTask = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $assignedMemberByTask;

}

if($_SERVER['REQUEST_METHOD']=='post'){
    var_dump($_POST);
    assignTask($username, $taskId);
}
// implement filters for  filtering 
// read database
// how it works
// classical model (erd diagram)
// erd e.g 
// phpmyadmin upload erd model 
/// write raw queries
// good frontend
// how it works
//// production database raw queries
//javascript or php
// mysql server php query

