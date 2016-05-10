<?php
    include("../../includes/database.php");
    $dbConnection = getDatabaseConnection("h2o");

    $name = $_POST['name'];
    $zipcode =$_POST['zipcode'];
    $sql = "INSERT INTO wateragency
            (name, zipcode)
            VALUES(:name,:zipcode)";
    $namedParameters = array();
    $namedParameters[':name'] = $name;   
    $namedParameters[':zipcode'] = $zipcode;      
    $statement = $dbConnection->prepare($sql);
    $statement->execute($namedParameters);
    
?>