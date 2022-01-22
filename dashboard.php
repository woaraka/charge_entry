<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'Dashboard';
    $user_id=$_SESSION['user_id'];
    $query=mysqli_query($con,"select user_name,name,position from login where user_id='$user_id'")or die ("query 1 incorrect.......");
    list($username,$name, $Position)=mysqli_fetch_array($query);

	$from_date = date('Y-m-d',strtotime('-7 days'));
	
    $to_date = date("Y-m-d 23:59:59");
	
    if(isset($_POST['generate']))
    {
        $from_date = $_POST['fdate'];
        $from_date = date("Y-m-d",strtotime($from_date));
        $to_date = $_POST['tdate'];
        $to_date = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($to_date))); 
    }
?>
  
    <?php include('includes/header.php');?>
    
    
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        [ {label: 'Name',},
         {label: 'Charge Entry', type: 'number'}, // Use object notation to explicitly specify the data type.
         {label: 'Work Order', type: 'number'} ],
            <?php
            $temp_from_date = $from_date;
            $temp_to_date = $to_date;
             while(strtotime($temp_from_date) <= strtotime($temp_to_date))
            {
                $start_date = date('Y-m-d 00:00:00',strtotime($temp_from_date));
                
                $work_order_date = date('Y-m-d',strtotime($temp_from_date));
                
                $temp_date = date('Y-m-d 23:59:59',strtotime($temp_from_date));
                
                $query1 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE u_id = '$user_id' AND (time BETWEEN '$start_date' AND '$temp_date')");
                
                $query2 = mysqli_query($con, "SELECT total FROM total_work_order WHERE u_id = '$user_id' AND date = '$work_order_date'");
                
                list($total_charge) = mysqli_fetch_array($query1);
                list($total_wo_order) = mysqli_fetch_array($query2);
                
                if(isset($total_charge) || isset($total_wo_order)){
                    $temp_date = date("m/d/Y",strtotime($temp_date));
                    echo "['".$temp_date."', '".$total_charge."', '".$total_wo_order."'],";
                }
                $temp_from_date = date ("Y-m-d", strtotime("+1 day", strtotime($temp_from_date)));
            }
            
            ?>
        ]);
          
        var view = new google.visualization.DataView(data);
      view.setColumns([0,1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2, {
						calc: 'stringify',
						sourceColumn: 2,
						type: 'string',
						role: "annotation"
						}]);
          
          

        var options = {
          chart: {
            title: 'Entries',
            subtitle: '',
          },
            backgroundColor: {
        fill:'#ffffcc'     
        },
        };
          

        var chart = new google.charts.Bar(document.getElementById('columnchart_DateWise'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
    
    
    
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
            [ {label: 'Facility',},
         {label: 'Entry', type: 'number'} ],
            <?php
                 $query22 = mysqli_query($con, "SELECT f_id, nick_name FROM facility");
                 while(list($fac_ID, $Fac_Name) = mysqli_fetch_array($query22))
                 {
                     $query23 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE f_id = '$fac_ID' AND u_id = '$user_id' AND time BETWEEN '$from_date' AND '$to_date'");
                     list($FacEntry) = mysqli_fetch_array($query23);
                     
                        echo "['".$Fac_Name."', '".$FacEntry."'],";
                }
            ?>
        ]);
          
        var view = new google.visualization.DataView(data);
      view.setColumns([0, 1, {
        calc: 'stringify',
        role: 'annotation',
        sourceColumn: 1,
        type: 'string'
        }]);
          
          

        var options = {
          chart: {
            title: 'Entries',
            subtitle: '',
          },
            backgroundColor: {
        fill:'#ffffcc'     
        },
        };
          

        var chart = new google.charts.Bar(document.getElementById('columnchart_FacilityWise'));
         //chart.draw(view, options);
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
    
    
    
    
    
    
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart for Charge Entry when Charts is loaded.
      google.charts.setOnLoadCallback(drawCharge);

      // Draw the pie chart for Work Order when Charts is loaded.
      google.charts.setOnLoadCallback(drawWorkOrder);

      // Callback that draws the pie chart for Charge Entry
      function drawCharge() {

        // Create the data table for Charge Entry
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('number', 'Charge Entry');
        data.addRows([
          <?php
            $query9 = mysqli_query($con, "SELECT * FROM login");
            while($row2 = mysqli_fetch_array($query9))
            {
                $UID = $row2['user_id'];
                if($UID != 0)
                {
                    $query10 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE u_id = '$UID' AND (time BETWEEN '$from_date' AND '$to_date')");
                
                    list($total_charge2) = mysqli_fetch_array($query10);
                    if(isset($total_charge2))
                    echo "
                    ['".$row2['user_name']."', ".$total_charge2."],";
                }  
            }
            
            ?>
        ]);

        // Set options for Charge Entry.
        var options = {title:'Charge Entry',
                       is3D: false,
                      legend: {position: 'bottom'},
                      backgroundColor: {
        fill:'#ffffcc'     
        },};

        // Instantiate and draw the chart for Charge Entry
        var chart = new google.visualization.PieChart(document.getElementById('Pie_Charge'));
        chart.draw(data, options);
      }

      // Callback that draws the pie chart for Anthony's pizza.
      function drawWorkOrder() {

        // Create the data table for Anthony's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('number', 'Work Order');
        data.addRows([
          <?php
            $query11 = mysqli_query($con, "SELECT * FROM login");
            while($row3 = mysqli_fetch_array($query11))
            {
                $UID = $row3['user_id'];
                if($UID != 0)
                {
                    $query12 = mysqli_query($con, "SELECT SUM(total) FROM total_work_order WHERE u_id = '$UID' AND (date BETWEEN '$from_date' AND '$to_date')");
                
                    list($total_wo_order3) = mysqli_fetch_array($query12);
                    if(isset($total_wo_order3))
                    echo "
                    ['".$row3['user_name']."', ".$total_wo_order3."],";
                }  
            }
            
            ?>
        ]);

        // Set options for Work Order pie chart.
        var options = {title:'Work Order',
                       is3D: true,
                      legend: {position: 'bottom'},
                      backgroundColor: {
        fill:'#ffffcc'     
        },};

        // Instantiate and draw the chart for Work Order
        var chart = new google.visualization.PieChart(document.getElementById('Pie_Work'));
        chart.draw(data, options);
      }
    </script>
    
    
    
    
    
    
    
    
    <?php include('includes/navbar.php');?>

   <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>



            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter"></span>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Message Center
                </h6>

                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="profile.php" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $name;?></span>
                <img class="img-profile rounded-circle" src="img/man.png">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profile.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  <?php echo $name?>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <p style=color:red></p>
              <h1 align="center" class="h3 mb-0 text-gray-800"><b><u>Dashboard for <?php echo $username;?></u></b></h1>
            <div class="dropdown">
              <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reports
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="charge-report.php">Charge Entry</a>
                <a class="dropdown-item" href="work-order-report.php">Work Order</a>
              </div>
            </div> 
          </div>
          <div>
           <form method="post" action="dashboard.php">
               Date of Service (MM/DD/YYYY)<br>
               <input type="text" id="datepicker1" name="fdate" placeholder="FROM DATE" autocomplete="off" value="<?php if(isset($_POST['fdate'])) echo $_POST['fdate']; else echo date("m/d/Y",strtotime($from_date));?>" required>
			   <input type="text" id="datepicker2" name="tdate" placeholder="TO DATE" autocomplete="off" value="<?php if(isset($_POST['tdate'])) echo $_POST['tdate']; else echo date('m/d/Y',strtotime($to_date));?>" required>
               <button name="generate" class="btn btn-success">Generate</button>
           </form>
           <button style="float: right;" name="" id="" data-toggle="modal" data-target="#ShowDataModal" class="btn btn-secondary">Show Data</button> <br><br>
          </div>
          
    
    
     <!-- Data Information -->
    <div id="ShowDataModal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
        <h4 class="modal-title font-weight-bold w-100 text-center">Work History</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <div class="modal-body">
 <table class="table table-bordered table-sm table-striped" style="80%">
		<thead>
			<tr style="color:black; font-size:18px;">
                <th>Date</th>
                <th>Charge Entry</th>
                <th>Work Order</th>
			</tr>
		</thead>
		<tbody>
           <?php
            $sumCharge = 0;
            $sumWorkOrder = 0;
            while(strtotime($from_date) <= strtotime($to_date))
            {
                $start_date = date('Y-m-d 00:00:00',strtotime($from_date));
                
                $work_or_date = date('Y-m-d',strtotime($from_date));
                
                $temp_date = date('Y-m-d 23:59:59',strtotime($from_date));
                
                $query3 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE u_id = '$user_id' AND (time BETWEEN '$start_date' AND '$temp_date')");
                
                $query4 = mysqli_query($con, "SELECT total FROM total_work_order WHERE u_id = '$user_id' AND date = '$work_or_date'");
                
                list($total_charge) = mysqli_fetch_array($query3);
                list($total_wo_order) = mysqli_fetch_array($query4);
                $sumCharge = $sumCharge + $total_charge;
                $sumWorkOrder = $sumWorkOrder + $total_wo_order;
                
                
                if(isset($total_charge) || isset($total_wo_order)){
                $temp_date = date("m/d/Y",strtotime($temp_date));
                echo "<tr>
                    
                          <td>" . $temp_date . "</td>
                          <td>" . $total_charge . "</td>
                          <td>" . $total_wo_order . "</td> 
                          
                          </tr>";
                }
                $from_date = date ("Y-m-d", strtotime("+1 day", strtotime($from_date)));
            }
            ?>
            <td class="bg-dark"><b>Total</b></td>
            <td class="bg-dark"><?php echo $sumCharge;?></td>
            <td class="bg-dark"><?php echo $sumWorkOrder;?></td>
		</tbody>
	</table>
   </div>
  </div>
 </div>
</div>       
    
    
    
    
    <div class="container">
    <div class="row">

    <div class="col-md-6 col-sm-3" id="Pie_Charge" style="height:500px; width: 50%;"></div>
    <div class="col-md-6 col-sm-3" id="Pie_Work" style="height:500px; width: 50%;"></div>
    </div>
      </div>
<br>
            <h3 style="text-align:center;"><b>DateWise</b></h3>
       <div id="columnchart_DateWise" style="width: 100%; height: 450px;"></div><br>
       
       <h3 style="text-align:center;"><b>FacilityWise</b></h3>
       <div id="columnchart_FacilityWise" style="width: 100%; height: 450px;"></div>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

  <?php
    include('includes/script.php');
    include('includes/footer.php');
  ?>

    </div>
    <!-- End of Content Wrapper -->

  <!-- End of Page Wrapper -->

  
  


  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  
  
 <script>
    $( function() {
    $( "#datepicker1" ).datepicker({dateFormat: 'mm/dd/yy'});
  } );
  
   $( function() {
    $( "#datepicker2" ).datepicker({dateFormat: 'mm/dd/yy'});
  } );
</script>