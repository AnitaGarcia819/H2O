<?php
    include('../includes/database.php');
    session_start();
    // Check whether session variable exists
    if(!isset($_SESSION['username'])){// checking whether admin has authenticated
        header("Location: index.php");
        exit;
    }
    function getClaims(){
       $connection = getDatabaseConnection('h2o');
       $sql = "SELECT * FROM claim";
       $statement = $connection->prepare($sql);
       $statement->execute();
       $resultSet = $statement->fetchAll(PDO::FETCH_ASSOC);
       return($resultSet);
    }
    function getClaimType($type){
        $connection = getDatabaseConnection('h2o');
        $sql = "SELECT claimtype FROM claimtype where claimtypeid = $type";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return($result['claimtype']);
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
        <!-- JS -->
        <script>
            $.fn.pageMe = function(opts){
                var $this = this,
                defaults = {
                    perPage: 7,
                    showPrevNext: false,
                    hidePageNumbers: false
                },
                settings = $.extend(defaults, opts);
                
                var listElement = $this;
                var perPage = settings.perPage; 
                var children = listElement.children();
                var pager = $('.pager');
                
                if (typeof settings.childSelector!="undefined") {
                    children = listElement.find(settings.childSelector);
                }
                
                if (typeof settings.pagerSelector!="undefined") {
                    pager = $(settings.pagerSelector);
                }
                
                var numItems = children.size();
                var numPages = Math.ceil(numItems/perPage);
            
                pager.data("curr",0);
                
                if (settings.showPrevNext){
                    $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
                }
                
                var curr = 0;
                while(numPages > curr && (settings.hidePageNumbers==false)){
                    $('<li><a href="#" class="page_link">'+(curr+1)+'</a></li>').appendTo(pager);
                    curr++;
                }
                
                if (settings.showPrevNext){
                    $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
                }
                
                pager.find('.page_link:first').addClass('active');
                pager.find('.prev_link').hide();
                if (numPages<=1) {
                    pager.find('.next_link').hide();
                }
                  pager.children().eq(1).addClass("active");
                
                children.hide();
                children.slice(0, perPage).show();
                
                pager.find('li .page_link').click(function(){
                    var clickedPage = $(this).html().valueOf()-1;
                    goTo(clickedPage,perPage);
                    return false;
                });
                pager.find('li .prev_link').click(function(){
                    previous();
                    return false;
                });
                pager.find('li .next_link').click(function(){
                    next();
                    return false;
                });
                
                function previous(){
                    var goToPage = parseInt(pager.data("curr")) - 1;
                    goTo(goToPage);
                }
                 
                function next(){
                    goToPage = parseInt(pager.data("curr")) + 1;
                    goTo(goToPage);
                }
                
                function goTo(page){
                    var startAt = page * perPage,
                        endOn = startAt + perPage;
                    
                    children.css('display','none').slice(startAt, endOn).show();
                    
                    if (page>=1) {
                        pager.find('.prev_link').show();
                    }
                    else {
                        pager.find('.prev_link').hide();
                    }
                    
                    if (page<(numPages-1)) {
                        pager.find('.next_link').show();
                    }
                    else {
                        pager.find('.next_link').hide();
                    }
                    
                    pager.data("curr",page);
                  	pager.children().removeClass("active");
                    pager.children().eq(page+1).addClass("active");
                
                }
            };
            
        $(document).ready(function(){
            
            $('#myTable').pageMe({pagerSelector:'#myPager',showPrevNext:true,hidePageNumbers:false,perPage:2});

            $("#claimTab").click( function(){
                    // Ask for confirmation
                        $.ajax({
                                type: "GET",
                                url: "api/getTotalClaims.php",
                                dataType: "json",
                                data: {},
                                success: function(data,status) {
                                         drawTable(data);
                                         
                                function drawTable(data) {
                                    $("#totalClaims").append("<th> Zip Code  </th> <th> No. of Claims </th>");
                                    for (var i = 0; i < data.length; i++) {
                                        //alert(data[i]);
                                        drawRow(data[i]);
                                    }
                                }
                                
                                function drawRow(rowData) {
                                    //alert(rowData.name + " name");
                                    var row = $("<tr />")
                                    $("#totalClaims").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
                                    row.append($("<td>" + rowData.zipcode + "</td>"));
                                    row.append($("<td>" + rowData.total + "</td>"));
                                }
                                 },
                                  complete: function(data,status) { //optional, used for debugging purposes
                                      //alert(status);
                                  }
                             });
             });
              $("#resolveTab").click( function(){
                    // Ask for confirmation
                    
                    $.ajax({
                            type: "GET",
                            url: "api/getPercentResolved.php",
                            dataType: "text",
                            data: { },
                            success: function(data,status) {
                                $('#claimsResolved').html(data+" % of claims have been resolved");
                             },
                              complete: function(data,status) { //optional, used for debugging purposes
                                  //alert(status);
                              }
                         });
                });
             
              $("#agencyTab").click( function(){
                    // Ask for confirmation
                $.ajax({
                    type: "GET",
                    url: "api/getAllLocalAgencies.php",
                    dataType: "json",
                    data: { },   
                    success: function(data,status) {
                        //alert(data);
                        drawTable(data);
                    
                        function drawTable(data) {
                            $("#waterAgencies").append("<th> Agency Name </th> <th> Zip Code</th>");
                            for (var i = 0; i < data.length; i++) {
                                drawRow(data[i]);
                            }
                        }
                        
                        function drawRow(rowData) {
                            //alert(rowData.name + " name");
                            var row = $("<tr />")
                            $("#waterAgencies").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
                            row.append($("<td>" + rowData.name + "</td>"));
                            row.append($("<td>" + rowData.zipcode + "</td>"));
                        }
                    }
                });
             });
             $(".deleteButton").click( function(){
                    // Ask for confirmation
                    alert($(this).attr("value"));
                        $.ajax({
                                type: "GET",
                                url: "api/delete.php",
                                dataType: "text",
                                data: { "claimnumber":$(this).attr("value")},
                                success: function(data,status) {
                                        alert("Your calim has been deleted.");
                                 },
                                  complete: function(data,status) { //optional, used for debugging purposes
                                      //alert(status);
                                  }
                             });
             });
             
             $(".editButton").click(function () {
                    //alert($(this).attr("value"));
                    $.ajax({
                        type:"GET",
                        url: "api/updateClaim.php",
                        dataType: "text",
                        data: { "claimnumber":$(this).attr("value")},
                        success: function(data,status) {
                                alert("Your claim has been edited.");
                                //Change status color
                         },
                          complete: function(data,status) { //optional, used for debugging purposes
                              //alert(status);
                          }
                     });
             });
             $("#insertData").click(function () {
                   //alert($('#inputName').val() + "     " + $('#inputZip').val());
                    $.ajax({
                        type:"POST",
                        url: "api/insertData.php",
                        dataType: "text",
                        data: { "name": $('#inputName').val(), "zipcode": $('#inputZip').val()},
                        success: function(data,status) {
                               // alert(data);
                                alert("Your water agency has been inserted.");
                         },
                          complete: function(data,status) { //optional, used for debugging purposes
                              //alert(status);
                          }
                     });
             });
        });

        </script>
    </head>
    <body>
         <!-- Navigation Bar --> 
        <nav id="navbar" class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php"> Home </a></li>
                    <li><a href="report.php"> Save Water </a></li>
                    <li><a href="logout.php"> Log Out </a></li>
                </ul>
            </div>
        </nav>
        <br>
        <br>
        <br>
        <br>
        <!--
Bootstrap Line Tabs by @keenthemes
A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: http://j.mp/metronictheme
Licensed under MIT
-->

<div class="container">
    <div class="row">
		<div class="col-md-12">
			<h3>Reports Submitted</h3>

			<div class="tabbable-panel">
				<div class="tabbable-line">
					<ul class="nav nav-tabs ">
						<li class="active">
							<a id="claimTab"href="#tab_default_1" data-toggle="tab">
							No. Claims</a>
						</li>
						<li>
							<a id="resolveTab"href="#tab_default_2" data-toggle="tab">
							% Resolved </a>
						</li>
						<li>
							<a id="agencyTab" href="#tab_default_3" data-toggle="tab">
							+ Water Agency </a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_default_1">
							<div id="table-wrapper">
                                <div id="table-scroll">
                                <h3>Total No. of Claims Reported By Zip Code</h3>
                                <p>
                                    <table id="totalClaims"class="table table-striped">
                                         <!-- Filled Dynamically -->
                                    </table>
                                </p>
                                </div>
                            </div>
						</div>
						<div class="tab-pane" id="tab_default_2">
						    <div class="container">
							    <h3>Pecent of Claims Resolved</h3>
							    <p>
							        <h4 id="claimsResolved"></h4>
							    </p>
							    
							</div>
						</div>
						<div class="tab-pane" id="tab_default_3">
    						<div id="table-wrapper">
                                <div id="table-scroll">
                                    <h3>Water Agencies</h3>
                                    <p>
                                        
                                        <table id="waterAgencies"class="table table-striped" style="overflow: auto">
                                             <!-- Filled Dynamically -->
                                        </table>
                                    </p>
                                </div>
                                <div class="center">
							                <button data-toggle="modal" data-target="#squarespaceModal" class="btn btn-info"> 	A D D </button>
							     </div>
                            </div>
							<br>
							
						</div>
						
							
						</div>
					</div>
				</div>
			</div>

<br>			
<div class="container">
	<div class="row">
        <div class="col-md-12">
        <h4>Water Reports</h4>
        <div class="table-responsive">
            <table id="mytable" class="table table-bordred table-striped">
                <thead>
                    <th>Status </th>
                    <th>Date</th>
                    <th>Claim</th>
                    <th>Address</th>
                    <th>Zip Code</th>
                    <th>Resolve</th>
                    <th>Delete</th>
                </thead>
                <tbody id="myTable">
                     <?php 
                        $claims = getClaims();
                        foreach($claims as $claim) {
                            echo "<tr>";
                            if($claim['isresolved'] == 0)
                                echo "<td><span class='label label-danger'>Not Resolved</span></td>";
                            else {
                                echo "<td><span class='label label-success'>Resolved</span></td>";
                            }
                            echo "<td>" . $claim['timereported']. "</td>";
                            echo "<td>" . getClaimType($claim['claimtype']) .  "</td>";
                            echo "<td>" . $claim['address'] . "</td>";
                            echo "<td>" . $claim['zipcode']. "</td>";
                            echo "<td> <button class='editButton' value='".$claim['claimnumber']."' data-title='Edit'><span class='glyphicon glyphicon-check'></span></button></td>";
                            echo "<td> <button class='deleteButton' value='".$claim['claimnumber']."' data-title='Delete'><span class='glyphicon glyphicon-trash'></span></button></td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 text-center">
            <ul class="pagination pagination-sm" id="myPager"></ul>
        </div>
        </div>
	</div>
</div>
</div>


<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align" id="Heading">Edit Your Detail</h4>
      </div>
          <div class="modal-body">
          <div class="form-group">
        <input class="form-control " type="text" placeholder="Mohsin">
        </div>
        <div class="form-group">
        
        <input class="form-control " type="text" placeholder="Irshad">
        </div>
        <div class="form-group">
        <textarea rows="2" class="form-control" placeholder="CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan"></textarea>
    
        
        </div>
      </div>
          <div class="modal-footer ">
        <button id="editModalButton" type="button" class="btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
      </div>
        </div>
    <!-- /.modal-content --> 
  </div>
      <!-- /.modal-dialog --> 
    </div>
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
      </div>
          <div class="modal-body">
       
       <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?</div>
       
      </div>
        <div class="modal-footer ">
            <button id ="deleteModalButton" type="button" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
      </div>
        </div>
    <!-- /.modal-content --> 
  </div>
      <!-- /.modal-dialog --> 
    </div>
    
<!-- line modal -->
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			<h3 class="modal-title" id="lineModalLabel">+ Add Water Agency </h3>
		</div>
		<div class="modal-body">
			
            <!-- content goes here -->
			<form method="GET">
              <div class="form-group">
                <label for="exampleInputEmail1">Agency address</label>
                <input type="text" class="form-control" id="inputName" placeholder="Enter Water Agency Name">
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Zipcode </label>
                <input type="text" class="form-control" id="inputZip" placeholder="Zip Code">
              </div>
              <button id="insertData" type="button" class="btn btn-default">Submit</button>
            </form>

		</div>
		<div class="modal-footer">
			<div class="btn-group btn-group-justified" role="group" aria-label="group button">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" data-dismiss="modal"  role="button">Close</button>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>
    </body>
</html>
