<?php
include_once 'model/dbconnection.php';
$connection = connectDatabase();
function assignTask($username, $taskId, $connection){

    $getUserId = "SELECT user_id from user_profile where username = '$username' ";
    
     try{
        $userId = $connection->query($getUserId)->fetch_assoc()['user_id'];
        $assignTaskQuery = "INSERT INTO task_assignment (task_id, assignee_id) VALUES($taskId, $userId);";
     
        $result= $connection->query($assignTaskQuery);
        header("location: index.php?success=task assigned");
        exit;
    }
    catch(mysqli_sql_exception $exception){
        header('location: index.php?error=please try again later');
    }
}
function deleteAssignedMember($username, $taskId, $connection){
    $taskId = (int) $taskId;
    $getUserId = "select user_id from user_profile where username = '$username';";
    $userId = $connection->query($getUserId)->fetch_assoc()['user_id'];
    $deleteQuery = "DELETE FROM task_assignment where (task_id = $taskId AND assignee_id = $userId)";
    $result= $connection->query($deleteQuery);
    if($result ==true){
        header("location: index.php?success=removed assignee successfully");
    }
}
function editAssignedMember($taskId, $username, $connection){
    $taskId = (int) $taskId;
    $getUserId = "select user_id from user_profile where username = '$username';";
    $userId = $connection->query($getUserId)->fetch_assoc()['user_id'];
    $editAssignedMember ="UPDATE task_assignment SET assignee_id=$userId where task_id=$taskId;";
    $result = $connection->query($editAssignedMember);
    header("location: index.php?success=updated successfully");
}
function getAssignedMembers($taskId, $connection){
    $getAssignedMemberByTask = "SELECT username from user_profile WHERE user_id=(SELECT assignee_id FROM task_assignment where task_id=$taskId)";
    $result = $connection->query($getAssignedMemberByTask);
    $assignedMemberByTask = $result->fetch_assoc();
    return $assignedMemberByTask;
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['member'];
    $taskId = $_POST['task_id'];
    if(isset($_POST['method']) && $_POST['method']=='update'){
        editAssignedMember($taskId, $username, $connection);
    }
    else if (isset($_POST['method']) && $_POST['method']=='assign'){
        assignTask($username, $taskId, $connection);
    }
    else if (isset($_POST['method']) && $_POST['method']=='delete'){

        deleteAssignedMember($username, $taskId, $connection);
    }

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

