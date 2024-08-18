<?php 
require_once 'model/dbconnection.php';
include 'task.php';
include 'user.php';
include 'task.assign.php';
$dbInstance = new DbConnection;
define('CONNECTION', $dbInstance->connectDatabase());
$allUsernames = getAllUsernames();

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
    <form enctype="multipart/form-data" action='task.php' method='post'>
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
    
 <span>Allowed type is docx and txt</span>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
   <input name="document" type="file" />
    <button type="submit" name="create-task">Submit</button>
    </form>
</div>
<div> 
    <input type="text" placeholder="Search table" id="filterTask"></div>
    <table id="allTasksTable">
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
        $tasks = getAllTasks();
        foreach ($tasks as $task): array_map('htmlentities', $task); ?>
    <tr>
      <td>
        <?php 
        $id = $task['id']; 

        $title = $task['title'];
        $description = $task['description'];
        $tag = $task['tag'];
        $status = $task['status'];
        $filename = $task['filename'];
        echo "<td>$title</td>";
        echo "<td>$description</td>";
        echo "<td>$status</td>";
        echo "<td>$tag</td>";
        echo "<td ><a href=downloadFile.php?taskid=$id>Download file</a></td>";            

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
           $assignedMember = getAssignedMembers($id, CONNECTION);
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
<select id="showTaskByUser" name="searchUser">
<option ></option>
<?php
// render all usernames in a select option
 foreach($allUsernames  as $user){
    $username = $user['username'];
    echo "<option value=".$username.">".$username."</option>";
 }
?>
</select>
<div class="userTasks">
<table id="noOfTaskUser">
<script>

$(document).ready(function(){
    // bind keyup function on filterTask input field
  $("#filterTask").on("keyup", function() {
    // filter task table by row values i.e task title, description,tag or status.
    var value = $(this).val().toLowerCase();
    $("#allTasksTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  
  var selectElement = document.getElementById('showTaskByUser');
  // add event listener on change on select username to view how many task a user has?
  selectElement.addEventListener('change', function handleChange(event){
    var username = event.target.value;
    var table = document.getElementById("noOfTaskUser");
    table.innerHTML = '';
    getAllTasksAssignedByUserId(username);
  })

  function getAllTasksAssignedByUserId(username){
    // call viewTaskByUserId.php 
    var settings = {
                    "url": "http://localhost/100phpProjects/taskmanagement/viewTaskByUserId.php",
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    "data": {
                        "username": username
                    }
                    };
    $.ajax(settings).done(function (response) {
        var assignedTasksToUser =response;
        var assignedTasksArray = JSON.parse(assignedTasksToUser);
        var table = document.getElementById("noOfTaskUser");
        for(const key in assignedTasksArray){
            console.log(assignedTasksArray[key]);
            var table_row = table.insertRow(0);
            var row=Object.values(assignedTasksArray[key]);  
            for(const index in row){
                var cell = table_row.insertCell(index)
                cell.innerHTML = row[index];
            }
        }
    });
    }
});
</script>
</table>
</div>
</div>
</div>
</body>
    </html>