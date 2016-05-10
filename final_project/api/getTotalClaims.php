<?php
       include('../../includes/database.php');
       $connection = getDatabaseConnection('h2o');
       
       $sql = "SELECT zipcode, count(*) as total FROM wateragency GROUP BY zipcode";

       $statement = $connection->prepare($sql);
       $statement->execute();
       $resultSet = $statement->fetchAll(PDO::FETCH_ASSOC);
       echo json_encode($resultSet);
?>