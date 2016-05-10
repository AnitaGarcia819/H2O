<?php
  session_start();
    // Check whether session variable exists
    function isAdminLoggedin(){
        if(!isset($_SESSION['username'])){// checking whether admin has authenticated
           return false;
        }return true;
    } 
    include('../includes/database.php');
    $dbConnection = getDatabaseConnection('h2o');
    
    // function below should not ideally be duplicated
    function getClaimTypes() {
    global $dbConnection;
    echo "getClaimTypes";
    
    $sql = "SELECT *
            FROM claimtype";
    $statement = $dbConnection->prepare($sql);
    $statement->execute();
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    //print_r($records);
    return $records;
    }
if(isset($_POST['report_form'])){
    $timereported = date("Y-m-d H:i:s");
    // to insert new record
    $sql = "INSERT INTO claim
           (timereported, claimtype, address, zipcode, isresident, isresolved) 
           VALUES (:timereported, :claimtype, :address, :zipcode, :isresident, :isresolved)";
    $namedParameters = array();
    $namedParameters[':timereported'] = $timereported; 
    $namedParameters[':claimtype'] = $_POST['claimtype'];
    $namedParameters[':address'] = $_POST['address'];
    $namedParameters[':zipcode'] = $_POST['zipcode'];
    if($_POST['isresident'] != "1")
      $namedParameters[':isresident'] = 0;
    else
      $namedParameters[':isresident'] = 1;
    $namedParameters[':isresolved'] = 0;;
    $statement = $dbConnection -> prepare($sql);
    $statement->execute($namedParameters);
    echo "Record has been successfully added";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title> </title>
        <!-- STYLE -->
        <link href ="css/style.css" rel="stylesheet"/>
        <!-- JQUERY -->
        <script src="https://code.jquery.com/jquery-2.2.3.js" integrity="sha256-laXWtGydpwqJ8JA+X9x2miwmaiKhn8tVmOVEigRNtP4="  crossorigin="anonymous"></script>
        <!-- BOOTSTRAP CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <!-- BOOTSTRAP JAVASCRIPT -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
    </head>
    <body>
         <!-- Navigation Bar --> 
        <nav id="navbar" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php"> Home </a></li>
                    <li><a href="report.php"> Save Water </a></li>
                    <?php
                        if(isAdminLoggedin()){
                            echo "<li><a href='resolve.php'> Manage Reports </a></li>";
                            echo "<li><a href='logout.php'> Log out </a></li>";
                        }else{
                            echo "<li><a href='sign_in.php'> Log in </a></li>";
                        }
                    ?>
                </ul>
            </div>
        </nav>
        <br>
        <br>
    </body>
    <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-title">
            <i class="glyphicon glyphicon-wrench pull-right"></i>
            <h4>Who can we remind to be eco-friendly?</h4>
          </div>
        </div>
        <div class="panel-body">
          
          <form class="form form-vertical" method="POST">
            <div class="control-group">
              <label>Address </label>
              <div class="controls">
                <input type="text" class="form-control" placeholder="Enter Address" name="address">
              </div>
            </div>      
            <div class="control-group">
              <label>Zip code</label>
              <div class="controls">
                <input type="text" class="form-control" placeholder="Zip Code" name="zipcode">
              </div>
            </div>   
          
            <div class="control-group">
              <label>Select</label>
              <div class="controls">
                <select name="claimtype" class="form-control">
                    <option value='3'> -- Select One -- </option>
                    <?php
                     $claimTypes = getClaimTypes();
                     foreach($claimTypes as $claimType) {
                       echo "<option value='".$claimType['claimtypeid']."'>" . $claimType['claimtype'] . " </option>";  
                     }
                   ?>
                </select>
              </div>
            </div>    
             <div class="control-group">
              <label>Select</label>
              <div class="controls">
                <select class="form-control" name="isresident">
                  <option value="0">-- Select One -- </option>
                  <option value="1">Resident</option>
                  <option value="0">Business</option>
                  <option>Other</option></select>
              </div>
            </div> 
            <div class="control-group">
              <label></label>
              <div class="controls">
                <button name="report_form" type="submit" class="btn btn-primary">
                  S u b m i t
                </button>
              </div>
            </div>   
          </form>
        </div><!--/panel content-->
      </div><!--/panel-->
</html>