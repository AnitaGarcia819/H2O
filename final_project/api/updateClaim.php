 <?php
    include('../../includes/database.php');
    $connection = getDatabaseConnection('h2o');
    $claim = $_GET['claimnumber'];
    $timeresolved = date("Y-m-d H:i:s");
    $sql = "UPDATE claim SET isresolved = 1, timeresolved = :timeresolved WHERE claimnumber = :claimnumber";
    $statement = $connection->prepare($sql);
    $statement->execute(array(":claimnumber" => $claim, ":timeresolved"=>$timeresolved));
    echo $claim . " ++ " . $timeresolved;
?>