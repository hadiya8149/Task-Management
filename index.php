<?php 
include 'task.php';
include 'user.php';

$allUsernames = getAllUsernames();
//TODO: to complete the task table we can join and group by task title to return assigned members of the task
// for now add a constraint of one-to-one a task can only be assigned to one user


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
    <table>
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
            
        <form id='assignMember' action='task.assign.php' method='post'>
            <td>
                <!-- get assigned member if already assigned then display the username as selected -->
           
                <select style="width:100px" name="member">
                    <option></option>
                    <?php
                        foreach($allUsernames as $user){
                        echo '<option value='.implode(array_values($user)).'> '.implode(array_values($user)).'</option>';
                                                    }
                    ?>                
                </select>
                <input type="hidden" value="<?php echo $id?>">
                <input type="submit" value="assign">
            </td>
        </form>
        </td>
      </td>
    </tr>
<?php endforeach; ?>
  
        </tbody>

</table>

</div>
 

</body>

    </html>