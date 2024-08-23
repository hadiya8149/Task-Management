<?php
include_once 'model/dbconnection.php';
$dbInstance = new DbConnection();
define('DBCONNECTION', $dbInstance->connectDatabase());
// PROBLEM constant connection already defined;
function assignTask($username, $taskId, $connection=DBCONNECTION){
     try{
        $getUserId = $connection->prepare("SELECT user_id from user_profile where username = ? ");
        $getUserId->bind_param('s', $username);
        $getUserId->execute();
        $getUserIdResult = $getUserId->get_result();
        if($getUserIdResult){
            $userId = $getUserIdResult->fetch_assoc()['user_id'];
        }
        $assignTaskQuery = $connection->prepare("INSERT INTO task_assignment (task_id, assignee_id) VALUES(?,?);");
        $assignTaskQuery->bind_param('ii',$taskId, $userId);
        $assignTaskQuery->execute();
        $result = $assignTaskQuery->get_result();
            header("location: index.php?success=task assigned");
            exit;
    }
    catch(mysqli_sql_exception $exception){
        header('location: index.php?error=please try again later');
    }
}
function deleteAssignedMember($username, $taskId, $connection=DBCONNECTION){
    $taskId = (int) $taskId;
    
    $getUserId = $connection->prepare("select user_id from user_profile where username = ?;");
    $getUserId->bind_param('s', $username);
    $getUserId->execute();
    $getUserIdResult = $getUserId->get_result();
    if($getUserIdResult){
        $userId = $getUserIdResult->fetch_assoc()['user_id'];
        $deleteQuery = $connection->prepare("DELETE FROM task_assignment where (task_id = ? AND assignee_id = ?)");
        $deleteQuery->bind_param('ii', $taskId, $userId);
        $deleteQuery->execute();
        $result =$deleteQuery->get_result();
        header("location: index.php?success=removed assignee successfully");   
    }
}
function editAssignedMember($taskId, $username, $connection=DBCONNECTION){
    $taskId = (int) $taskId;
    $getUserId = $connection->prepare("select user_id from user_profile where username = ?;");
    $getUserId->bind_param('s', $username);
    $getUserId->execute();
    $getUserIdResult = $getUserId->get_result();
    $userId = $getUserIdResult->fetch_assoc()['user_id'];

    $editAssignedMember = $connection->prepare("UPDATE task_assignment SET assignee_id=? where task_id=?;");
    $editAssignedMember->bind_param('ii',$userId, $taskId);
    $editAssignedMember->execute();
    $result = $editAssignedMember->get_result();
    header("location: index.php?success=updated successfully");
}
function getAssignedMembers($taskId, $connection=DBCONNECTION){
    $getAssignedMemberByTask =$connection->prepare("SELECT username from user_profile WHERE user_id=(SELECT assignee_id FROM task_assignment where task_id=?)");
    $getAssignedMemberByTask->bind_param('i', $taskId);
    $getAssignedMemberByTask->execute();

    $result = $getAssignedMemberByTask->get_result();
    $assignedMemberByTask = $result->fetch_assoc();
    return $assignedMemberByTask;
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['member'];
    $taskId = $_POST['task_id'];
    if(isset($_POST['method']) && $_POST['method']=='update'){
        editAssignedMember($taskId, $username);
    }
    else if (isset($_POST['method']) && $_POST['method']=='assign'){
        assignTask($username, $taskId);
    }
    else if (isset($_POST['method']) && $_POST['method']=='delete'){

        deleteAssignedMember($username, $taskId);
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

