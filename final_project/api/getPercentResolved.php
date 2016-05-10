<?php
       include('../../includes/database.php');
       $connection = getDatabaseConnection('h2o');
       
       $sql = "SELECT ((count(*) * 100) / (SELECT count(*) FROM claim)) as percent
                FROM claim WHERE isresolved = 1";
       
       $statement = $connection->prepare($sql);
       $statement->execute();
       $result = $statement->fetch(PDO::FETCH_ASSOC);
       echo ($result['percent']);
?>