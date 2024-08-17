<?php 
function getAllUsernames($connection=CONNECTION){
    $getUsernameQuery = "SELECT username from user_profile";
    $result = $connection->query($getUsernameQuery);
    $allUsernames = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $allUsernames;
}