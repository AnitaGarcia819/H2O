<?php
    include('../includes/database.php');
    session_start();
    // Check whether session variable exists
    function isAdminLoggedin(){
        if(!isset($_SESSION['username'])){// checking whether admin has authenticated
           return false;
        }return true;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>h2o </title>
        <!-- STYLE -->
        <link href ="css/style.css" rel="stylesheet"/>
        <!-- JQUERY -->
        <script src="https://code.jquery.com/jquery-2.2.3.js" integrity="sha256-laXWtGydpwqJ8JA+X9x2miwmaiKhn8tVmOVEigRNtP4="  crossorigin="anonymous"></script>
        <!-- BOOTSTRAP CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <!-- BOOTSTRAP JAVASCRIPT -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
        <!--AJAX-->
        <script type="text/javascript" src="">
                
        </script>
        <script>
            $(document).ready(function(){
                function isValidZip(){
                    return true;
                    /*if(/^[0-9]{5}$/).test($("#search-query").val()){
                            return true;
                    }return false;*/
                }
                $("#search-button").click( function(){
                   // alert("Button was pressed");
                    if(isValidZip()){
                        
                        $.ajax({
                            type: "GET",
                            url: "api/getLocalAgencies.php",
                            dataType: "json",
                            data: {"zipcode": $("#search-query").val()},
                            success: function(data,status) {
                                for (var i = 0; i < data.length; i++) {
                                    $("#dataTable").append("");
                                }
                                drawTable(data);
                            
                            function drawTable(data) {
                                $("#dataTable").append("<th> Agency Name </th> <th> Zip Code</th>");
                                for (var i = 0; i < data.length; i++) {
                                    drawRow(data[i]);
                                }
                            }
                            
                            function drawRow(rowData) {
                                //alert(rowData.name + " name");
                                var row = $("<tr />")
                                $("#dataTable").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
                                row.append($("<td>" + rowData.name + "</td>"));
                                row.append($("<td>" + rowData.zipcode + "</td>"));
                            }
                            },
                            complete: function(data,status) { //optional, used for debugging purposes
                                       // alert(status);
                            }
                        });//Ajax
                    }else
                        $("#result").html("Sorry, there are no water agencies in your zip");
    
                });//petName.click
            });//document.ready
        </script> 
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
        <!-- Header --> 
        <div class="jumbotron">
            <h1>H2O</h1>
            <h3> Making the world a better place </h3>
        </div>
        <!-- Search Bar -->
       <div class="container">
	       <div class="row">
		       <h2>Water Agencies Near You</h2>
               <div id="custom-search-input">
                    <div class="input-group col-md-12">
                        <input id="search-query" type="text" class="search-query form-control" placeholder="Search" />
                        <span class="input-group-btn">
                            <button id="search-button"class="btn btn-danger" type="button">
                                <span class=" glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
	        </div>
        </div>
    <!-- Display Results -->
    <br>
    <br>
    <div class="container">
        <h2>Your Local Agencies</h2>
        <p id="result"> These are your search results</p> 
        <div id="table-wrapper">
            <div id="table-scroll">
                <table id="dataTable"class="table table-striped">
                     <!-- Filled Dynamically -->
                </table>
            </div>
        </div>
        <form action="report.php">
            <button id="button" type="submit" class="btn btn-default">R E P O R T</button>
        </form>
    </div>
    </body>

</html>

