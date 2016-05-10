<?php
       include('../../includes/database.php');
       $connection = getDatabaseConnection('h2o');
       $sql = "SELECT * FROM wateragency";

       $statement = $connection->prepare($sql);
       $statement->execute();
       $resultSet = $statement->fetchAll(PDO::FETCH_ASSOC);
       echo json_encode($resultSet);
?>