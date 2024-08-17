<?php 
require_once 'model/dbconnection.php';

include 'task.php';
include 'user.php';
include 'task.assign.php';
$connection = connectDatabase();
$allUsernames = getAllUsernames($connection);
function getAssignedTaskByUserId($username, $connection){
    $getUserId = "select user_id from user_profile where username = '$username';";
    $userId = $connection->query($getUserId)->fetch_assoc()['user_id'];
    // join with 
    $sql = "select * from task_assignment join task on task_assignment.task_id=task.id where task_assignment.assignee_id=$userId;";
    $result = $connection->query($sql);
    $allTaskAssignedToUser  = mysqli_fetch_all($result, MYSQLI_ASSOC);
    var_dump($allTaskAssignedToUser);

}
//TODO: to complete the task table we can join and group by task id to return assigned members of the task


?>
<html>
    <head>
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 
    </head>
<body>
<h1>Task Management System</h1>
<div>

<div>

    <h2>Create Task</h2>
    <form action='task.php' method='post'>
        <input type='text' name='title' placeholder='title'>
        <textarea name='description' placeholder="enter description"></textarea>
        <select name='status'>
            <option value='TODO'>TODO</option>
            <option value='IN PROGRESS'>IN PROGRESS</option>
            <option value ='DONE'>DONE</option>
        </select>
        <select name='tag'>
            <option value="bug">type: bug</option>
            <option  value="documentation">type: documentation</option>
            <option value="question">type: question</option>
            <option  value="feature request">type: feature request</option>
            <option  value="high">priority: high</option>
            <option  value="critical">priority: critical</option>
            <option value="medium">priority: medium</option>
            <option value="low">priority: low</option>
            <option value="enhancement">type: enhancement</option>
        </select>
        <input type='submit' >
    </form>
</div>
<div > <input type="text" placeholder="Filter table" id="filterUsername"></div>
    <table id="myTable">
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Tag</th>
                <th>Action</th>
                <th>Assignees</th>

            </tr>
        </thead>
        <tbody>
          
        <?php 
        $tasks = getAllTasks($connection);
        foreach ($tasks as $row): array_map('htmlentities', $row); ?>
    <tr>
      <td>
        <?php echo implode('</td><td>', array_values($row));
        $id = $row['id']; 
        ?>
        <td>
            <?php echo "<a href='editTask.php?id=$id'>Edit</a>";?>
        
           <?php echo "<a href='deletetask.php?id=$id'>Delete</a>" ?> 
        </td>
        <td>
            
            <td>
                <!-- assignment table here -->

        <form id='assignMember' action='task.assign.php' method='post'>
           <?php
           $assignedMember = getAssignedMembers($id, $connection);
           ?>
                <select style="width:100px" name="member">
                    <option></option>
                    <?php
                        foreach($allUsernames as $user){
                            if($assignedMember['username'] == $user['username']){
                        echo '<option selected  value='.implode(array_values($user)).'> '.implode(array_values($user)).'</option>';
                    
                    }
                            else{
                                echo '<option  value='.implode(array_values($user)).'> '.implode(array_values($user)).'</option>';
                            }
                                                    }
                    ?>                
                </select>
                <input type = "hidden" name ="method"  value="<?php echo isset($assignedMember)?'update':'assign'; ?>">
                <input type="hidden" name='task_id' value="<?php echo $id?>">
                <!-- add logic to add update and assign hidden values -->
                <input type="submit" value=<?php echo isset($assignedMember)?'update':'assign' ?>>
                
            </td>
            
        </form>
        </td>
      </td>
      <td>
      <form  action='task.assign.php' style=<?php echo isset($assignedMember['username'])?'display:block':'display:none'?> method='post'>
            <input type="hidden" name="task_id" value=<?php echo $id?>>
        <input type="hidden" name="member" value=<?php echo $assignedMember['username'] ?>>  
        <input type="hidden" name="method" value="delete">
        <input type="submit"  value="Remove Assignee" ></form>
                                                </td>
    </tr>
<?php endforeach; ?>
  
        </tbody>

</table>
<h3>View how many task a user has</h3>
<div>
    <form method="get" action="index.php">
<select id="showTaskByUser" name="searchUser">

<?php
 foreach($allUsernames  as $user){
    $username = $user['username'];
    echo "<option value=".$username.">".$username."</option>";
 }
?>
</select>

</form>
<!-- fix it -->
<?php getAssignedTaskByUserId('hadiya8149', $connection);?>
<!-- TODO: call function   -->
<div>

</div>
</div>
</div>
<script>
$(document).ready(function(){
  $("#filterUsername").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

</body>

    </html>