<?php
    include('../../includes/database.php');
    $connection = getDatabaseConnection('h2o');
    $claim = $_GET['claimnumber'];

    $sql = "DELETE FROM claim WHERE claimnumber = :claimnumber";
    $statement = $connection->prepare($sql);
    $statement->execute(array(":claimnumber" => $claim));
    
    echo "Product has been deleted!";

?>