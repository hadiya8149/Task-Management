<?php 
if(isset($_SESSION['email'])){
    exit();
}

include 'model/dbconnection.php';
$connection = new DbConnection;
$conn = $connection->connectDatabase();
function validateForm($email, $password){

    if((isset($email) && isset($password))){
        $email = htmlspecialchars($email);
        if(empty($email)){
            header("Location: index.php?error=User Name is required");
            exit();
        }
        if (empty($password)){
            header("Location : index.php?error=Password is required");
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("location: login.php?error=Invalid email format");
            exit();
            }
    }
    else{
        header("location: login.php?error=All fields are reqiured.");
        exit;
    }
    
   }

function getUser($email, $password, $connection){
    $query = "SELECT * FROM user where email='$email';";
    $result = $connection->query($query);

    if($result->num_rows==0){

        header("location: login.php?error=Account not found. Please signup first");
        exit;
    }
    else{
        return $result;
            }
          
        
    }

function authenticateUser($user,$password, $connection){
    $resultArray = $user->fetch_assoc();
    $storedHash = $resultArray['password'];
        if(password_verify($password, $storedHash)){
            // don't store email in cookies use another way 
            session_start();
            $_SESSION['email']=$email;
            setcookie("usertoken", "noice", time()+86400 * 30);
            header("location: index.php");
            exit;
            }
            else{
                header("location: login.php?error='incorrect password'");
                exit;
            }
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email  = $_POST['email'];
    $password = $_POST['password'];
    validateForm($email, $password);
    $user = getUser($email, $password, $conn);
    authenticateUser($user, $password, $conn);
    

}