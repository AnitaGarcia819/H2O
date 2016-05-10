<?php
       include('../../includes/database.php');
       $connection = getDatabaseConnection('h2o');
       $zipcode = $_GET['zipcode'];
       $sql = "SELECT name, zipcode FROM wateragency WHERE :zipcode = zipcode";
       $named = array();
       $named[':zipcode'] = $zipcode;
       $statement = $connection->prepare($sql);
       $statement->execute($named);
       $resultSet = $statement->fetchAll(PDO::FETCH_ASSOC);
       echo json_encode($resultSet);
?>