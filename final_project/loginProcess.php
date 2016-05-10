<?php 
    session_start();
    include("../includes/database.php");
    $connection = getDatabaseConnection("h2o");
    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    // Retrives username and psw from daatabase
     $sql = "SELECT *
            FROM admin
            WHERE username = :username
            AND password = :password";
    
    // Prevents SQL Injection 
    $namedParameters = array();
    $namedParameters[':username'] = $username;
    $namedParameters[':password'] = $password;
    // Prepares, executes, and fetches SQL 
    $statement = $connection -> prepare($sql);
    $statement -> execute($namedParameters);
    $result = $statement->fetch(PDO::FETCH_ASSOC); // fetch for one(100% sure), fetch all for many  
    echo $result;
    if(empty($result)){
        echo "Wrong username/password! ". $username . "  " . $password ;
    }else{
       $_SESSION['username'] = $username;
       $_SESSION['adminName'] = $result['firstName'] . " " . $result['lastName'];
        header("Location: resolve.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title> </title>
    </head>
    <body>

    </body>
</html>