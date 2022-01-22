<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'Admin Dashboard';
    $user_id=$_SESSION['user_id'];
    $query=mysqli_query($con,"select user_name,name,position from login where user_id='$user_id'")or die ("query 1 incorrect.......");
    list($username,$name, $Position)=mysqli_fetch_array($query);
    
    if($Position != 'Team Leader')
    {
        header("location: index.php");
    }

	$from_date = date('Y-m-d',strtotime('-7 days'));
    //$from_date = date('Y-m-d',strtotime($from_date));
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
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [ {label: 'Name',},
         {label: 'Charge Entry', type: 'number'}, // Use object notation to explicitly specify the data type.
         {label: 'Work Order', type: 'number'} ],
            <?php
            $query1 = mysqli_query($con, "SELECT * FROM login");
            while($row1 = mysqli_fetch_array($query1))
            {
                $UID = $row1['user_id'];
                if($UID != 0)
                {
                    $query2 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE u_id = '$UID' AND (time BETWEEN '$from_date' AND '$to_date')");
                
                    $query3 = mysqli_query($con, "SELECT SUM(total) FROM total_work_order WHERE u_id = '$UID' AND (date BETWEEN '$from_date' AND '$to_date')");
                    list($total_charge1) = mysqli_fetch_array($query2);
                    list($total_wo_order1) = mysqli_fetch_array($query3);
                
                    
                    echo "['".$row1['user_name']."','".$total_charge1."','".$total_wo_order1."'],";
                }  
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
        title: "Employee Wise",
          hAxis: {
      title: 'Name'
    },
          vAxis: {
      title: 'Entries'
    },
    backgroundColor: {
        fill:'#ffffcc'     
        },

      };
      var chart = new google.visualization.ColumnChart(document.getElementById("all_employee-dashboard"));
      chart.draw(view, options);
  }
  </script>
   
   
   
   
   
       <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [ {label: 'Date',},
         {label: 'Charge Entry', type: 'number'}, // Use object notation to explicitly specify the data type.
         {label: 'Work Order', type: 'number'} ],
            <?php
            if(isset($_POST['Name']))
            {
                $temp_user_id = $_POST['Name'];
                $_SESSION['tempName'] = $temp_user_id;
            }
                
            $temp_from_date = $from_date;
            $temp_to_date = $to_date;
             while(strtotime($temp_from_date) <= strtotime($temp_to_date))
            {
                $start_date = date('Y-m-d 00:00:00',strtotime($temp_from_date));
                
                $work_order_date = date('Y-m-d',strtotime($temp_from_date));
                
                $temp_date = date('Y-m-d 23:59:59',strtotime($temp_from_date));
                
                $query7 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE u_id = '$temp_user_id' AND (time BETWEEN '$start_date' AND '$temp_date')");
                
                $query8 = mysqli_query($con, "SELECT total FROM total_work_order WHERE u_id = '$temp_user_id' AND date = '$work_order_date'");
                
                list($total_charge2) = mysqli_fetch_array($query7);
                list($total_wo_order2) = mysqli_fetch_array($query8);
                
                if(isset($total_charge2) || isset($total_wo_order2)){
                    $temp_date = date("m/d/Y",strtotime($temp_date));
                    echo "['".$temp_date."', '".$total_charge2."', '".$total_wo_order2."'],";
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
        title: "Date Wise",
          hAxis: {
      title: 'Date'
    },
    
     vAxis: {
      title: 'Entries'
    },
    
    backgroundColor: {
        fill:'#ffffcc'     
        },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("Individual_dashboard"));
      chart.draw(view, options);
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
		//pieSliceText: 'value',
		pieSliceText: 'percentage',
		//pieSliceText: 'label',
		//pieSliceText: 'label-and-percentage',
          //tooltip: {trigger: 'none'},
		
		legend: {position: 'bottom'},
		//pieSliceTextStyle: {
      //color: 'black',
      //fontSize:10
		//},
		
		backgroundColor: {
        fill:'#ffffcc'     
        },

                       is3D: false,
};

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
                       legend: {position: 'bottom'},
                       backgroundColor: {
        fill:'#ffffcc'     
        },
                       is3D: true,};

        // Instantiate and draw the chart for Work Order
        var chart = new google.visualization.PieChart(document.getElementById('Pie_Work'));
        chart.draw(data, options);
      }
    </script>
   
   
   
   
   <!-- Facility Wise Entry Chart -->
   
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [ {label: 'Facility',},
         {label: 'Entry', type: 'number'} ],
            <?php
                 $query14 = mysqli_query($con, "SELECT f_id, nick_name FROM facility");
                 while(list($fac_ID, $Fac_Name) = mysqli_fetch_array($query14))
                 {
                     $query15 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE f_id = '$fac_ID' AND time BETWEEN '$from_date' AND '$to_date'");
                     list($FacEntry) = mysqli_fetch_array($query15);
                     
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
        title: "Facility Wise",
          hAxis: {
      title: 'Facility'
    },
          
    vAxis: {
      title: 'Entries'
    },
    
     backgroundColor: {
        fill:'#ffffcc'     
        },

      };
      var chart = new google.visualization.ColumnChart(document.getElementById("FacilityEntryChart"));
      chart.draw(view, options);
  }
  </script>
   
   
   
   
   
   
   
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [ {label: 'Facility',},
         {label: 'Problems', type: 'number'} ],
        <?php
                 $query16 = mysqli_query($con, "SELECT f_id, nick_name FROM facility");
                 while(list($fac_ID, $Fac_Name) = mysqli_fetch_array($query16))
                 {
                     $query17 = mysqli_query($con, "SELECT COUNT(mrn) FROM charge_problem WHERE f_id = '$fac_ID' AND c_date BETWEEN '$from_date' AND '$to_date'");
                     list($ChargeProblem) = mysqli_fetch_array($query17);
                     
                     $query18 = mysqli_query($con, "SELECT COUNT(mrn) FROM workorder_problem WHERE f_id = '$fac_ID' AND c_date BETWEEN '$from_date' AND '$to_date'");
                     
                     list($WorkProblem) = mysqli_fetch_array($query18);
                    
                     $FacProblem = $ChargeProblem + $WorkProblem;
                     
                     
                        echo "['".$Fac_Name."', '".$FacProblem."'],";
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
        title: "Facility Wise",
          legend: {position: 'right'},
        //legend: { position: "none" },
		colors: ['#e60000'],
          hAxis: {
      title: 'Facility'
    },
    
     vAxis: {
      title: 'Problems'
    },
          
    backgroundColor: {
        fill:'#ffffcc'     
        },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("ChartProblemFacilityWise"));
      chart.draw(view, options);
  }
  </script>
   
   
   
   
   
   
   
       <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [ {label: 'Facility',},
         {label: 'Problems', type: 'number'} ],
        <?php
                 $query19 = mysqli_query($con, "SELECT * FROM login");
            while($row4 = mysqli_fetch_array($query19))
            {
                $UID = $row4['user_id'];
                if($UID != 0)
                {
                    $query20 = mysqli_query($con, "SELECT COUNT(mrn) FROM charge_problem WHERE u_id = '$UID' AND c_date BETWEEN '$from_date' AND '$to_date'");
                
                    list($ChargeProblem) = mysqli_fetch_array($query20);
                    
                    $query21 = mysqli_query($con, "SELECT COUNT(mrn) FROM workorder_problem WHERE u_id = '$UID' AND c_date BETWEEN '$from_date' AND '$to_date'");
                    
                    list($WorkProblem) = mysqli_fetch_array($query21);
                    
                    $FacProblem = $ChargeProblem + $WorkProblem;
                    
                    echo "['".$row4['user_name']."','".$FacProblem."'],";
                }  
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
        title: "Employee Wise",
          legend: {position: 'right'},
        //legend: { position: "none" },
colors: ['#ff4d4d'],
          hAxis: {
      title: 'Employee'
    },
    
     vAxis: {
      title: 'Problems'
    },
          
    backgroundColor: {
        fill:'#ffffcc'     
        },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("ChartProblemEmployeeWise"));
      chart.draw(view, options);
  }
  </script>
   
   
   
   
   
   
   
      <!-- Facility Wise Individual Entry Chart -->
   
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [ {label: 'Facility',},
         {label: 'Entry', type: 'number'} ],
            <?php
                if(isset($_POST['Name']))
                {
                    $temp_user_id = $_POST['Name'];
                    $_SESSION['tempName'] = $temp_user_id;
                }
                 $query22 = mysqli_query($con, "SELECT f_id, nick_name FROM facility");
                 while(list($fac_ID, $Fac_Name) = mysqli_fetch_array($query22))
                 {
                     $query23 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE f_id = '$fac_ID' AND u_id = '$temp_user_id' AND time BETWEEN '$from_date' AND '$to_date'");
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
        title: "Facility Wise",
          hAxis: {
      title: 'Facility'
    },
          
    vAxis: {
      title: 'Entries'
    },
          
    backgroundColor: {
        fill:'#ffffcc'     
        },

      };
      var chart = new google.visualization.ColumnChart(document.getElementById("FacilityIndividualEntryChart"));
      chart.draw(view, options);
  }
  </script>
   
   
   
   
   <!-- DateWise Wise Individual Problem -->
          <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [ {label: 'Name',},
         {label: 'Problems', type: 'number'} ],
            <?php
            if(isset($_POST['Name']))
            {
                $temp_user_id = $_POST['Name'];
                $_SESSION['tempName'] = $temp_user_id;
            }
                
            $temp_from_date = $from_date;
            $temp_to_date = $to_date;
            while(strtotime($temp_from_date) <= strtotime($temp_to_date))
            {
                $start_date = date('Y-m-d 00:00:00',strtotime($temp_from_date));
                
                $work_order_date = date('Y-m-d',strtotime($temp_from_date));
                
                $temp_date = date('Y-m-d 23:59:59',strtotime($temp_from_date));
				
				$query24 = mysqli_query($con, "SELECT COUNT(mrn) FROM charge_problem WHERE u_id = '$temp_user_id' AND c_date BETWEEN '$start_date' AND '$temp_date'");
                
                $query25 = mysqli_query($con, "SELECT COUNT(mrn) FROM workorder_problem WHERE u_id = '$temp_user_id' AND c_date BETWEEN '$start_date' AND '$temp_date'");
                
                list($total_charge3) = mysqli_fetch_array($query24);
                list($total_wo_order3) = mysqli_fetch_array($query25);
                
                $total = $total_charge3 + $total_wo_order3;
				if($total != 0)
				{
                    $temp_date = date("m/d/Y",strtotime($temp_date));
                    echo "['".$temp_date."', '".$total."'],";
                }
                $temp_from_date = date ("Y-m-d", strtotime("+1 day", strtotime($temp_from_date)));
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
        title: "Date Wise",
		colors: ['#ff4d4d'],
          hAxis: {
      title: 'Date'
    },
    
     vAxis: {
      title: 'Problems'
    },
          
    backgroundColor: {
        fill:'#ffffcc'     
        },

      };
	  
	  var chart = new google.visualization.ColumnChart(document.getElementById("IndividualProblemDateWise"));
      chart.draw(view, options);
  }
  </script>
  
  
  
  
  
  
  
  <!-- Facility Wise Individual Problem -->
          <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        [ {label: 'Name',},
         {label: 'Problems', type: 'number'} ],
            <?php
            if(isset($_POST['Name']))
            {
                $temp_user_id = $_POST['Name'];
                $_SESSION['tempName'] = $temp_user_id;
            }
                
           $query26 = mysqli_query($con, "SELECT f_id, nick_name FROM facility");
           while(list($fac_ID, $Fac_Name) = mysqli_fetch_array($query26))
                 {
                     $query27 = mysqli_query($con, "SELECT COUNT(mrn) FROM charge_problem WHERE f_id = '$fac_ID' AND u_id = '$temp_user_id' AND c_date BETWEEN '$from_date' AND '$to_date'");
                     list($ChargeProblem) = mysqli_fetch_array($query27);
                     
                     $query28 = mysqli_query($con, "SELECT COUNT(mrn) FROM workorder_problem WHERE f_id = '$fac_ID' AND u_id = '$temp_user_id' AND c_date BETWEEN '$from_date' AND '$to_date'");
                     
                     list($WorkProblem) = mysqli_fetch_array($query28);
                    
                     $FacProblem = $ChargeProblem + $WorkProblem;
                     
                     
                        echo "['".$Fac_Name."', '".$FacProblem."'],";
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
        title: "Facility Wise",
		colors: ['#e60000'],
          hAxis: {
      title: 'Facility'
    },
    
     vAxis: {
      title: 'Problems'
    },
          
    backgroundColor: {
        fill:'#ffffcc'     
        },

      };
	  
	  var chart = new google.visualization.ColumnChart(document.getElementById("IndividualProblemFacilityWise"));
      chart.draw(view, options);
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
              <h1 align="center" class="h3 mb-0 text-gray-800"><b><u>Dashboard</u></b></h1>
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
           <form method="post" action="admin-dashboard.php">
               Date of Service (MM/DD/YYYY)<br>
               <input type="text" id="datepicker1" name="fdate" placeholder="FROM DATE" autocomplete="off" value="<?php if(isset($_POST['fdate'])) echo $_POST['fdate']; else echo date("m/d/Y",strtotime($from_date));?>" required>
			   <input type="text" id="datepicker2" name="tdate" placeholder="TO DATE" autocomplete="off" value="<?php if(isset($_POST['tdate'])) echo $_POST['tdate']; else echo date('m/d/Y',strtotime($to_date));?>" required>
              
              <select class="selectpicker" name="Name">
                     <option value="" selected>All Employee</option>
                     <?php
                        $list = mysqli_query($con,"SELECT user_id, user_name FROM login");
                        while(list($ID,$U_Name) = mysqli_fetch_array($list))
                        {
                            if($ID != 0)
                            {
                                if($ID == $_SESSION['tempName'])
                                {
                                    echo "<option value='$ID' selected>$U_Name</option>";
                                    unset($_SESSION['tempName']);
                                }
                                else
                                {
                                    echo "<option   value='$ID'>$U_Name</option>";
                                } 
                            }
                              
                        }
                     ?>
                     
                 </select>
              
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
                <th>User Name</th>
                <th>Charge Entry</th>
                <th>Work Order</th>
			</tr>
		</thead>
		<tbody>
           <?php
            $query4 = mysqli_query($con, "SELECT * FROM login");
            $sumCharge = 0;
            $sumWorkOrder = 0;
            while($row = mysqli_fetch_array($query4))
            {
                $UID = $row['user_id'];
                if($UID != 0)
                {
                    $query5 = mysqli_query($con, "SELECT SUM(total) FROM total_work WHERE u_id = '$UID' AND (time BETWEEN '$from_date' AND '$to_date')");
                
                    $query6 = mysqli_query($con, "SELECT SUM(total) FROM total_work_order WHERE u_id = '$UID' AND (date BETWEEN '$from_date' AND '$to_date')");
                
                    list($total_charge) = mysqli_fetch_array($query5);
                    list($total_wo_order) = mysqli_fetch_array($query6);
                    $sumCharge = $sumCharge + $total_charge;
                    $sumWorkOrder = $sumWorkOrder + $total_wo_order;
                    echo "<tr>
                    
                          <td>" . $row['user_name'] . "</td>
                          <td>" . $total_charge . "</td>
                          <td>" . $total_wo_order . "</td> 
                          
                          </tr>";
                }
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
       
       <div class="col-sm-3 col-md-12 col-md-offset-2 " style=" text-align:left;"><br><h4 class="font-weight-bold" style="text-align:center;color:#004d00;">Work Progress</h4>
    <div id="all_employee-dashboard" style="width: 100%; height: 500px;"></div>
    </div>
    
    <div class="col-sm-3 col-md-12 col-md-offset-2 " style=" text-align:left;"><br>
    <div id="FacilityEntryChart" style="width: 100%; height: 500px;"></div>
    </div>
    
    <div class="col-sm-3 col-md-12 col-md-offset-2 " style=" text-align:left;"><br><h2 class="font-weight-bold" style="text-align:center;color:red;">Problems</h2>
    <div id="ChartProblemEmployeeWise" style="width: 100%; height: 500px;"></div><br>
    <div id="ChartProblemFacilityWise" style="width: 100%; height: 500px;"></div>
    </div>
    
    
    <?php if(isset($_POST['Name'])){
        $TempID = $_POST['Name'];
        $query13 = mysqli_query($con, "SELECT user_name FROM login WHERE user_id='$TempID'");
        if($TempID != null){
            $query13 = mysqli_query($con, "SELECT user_name FROM login WHERE user_id='$TempID'");
            list($TempID) = mysqli_fetch_array($query13);?>
    <br>
    <div class="col-sm-3 col-md-12 col-md-offset-2 " style=" text-align:left;"><br><h4 class="font-weight-bold" style="text-align:center;color:#004d00;"><?php echo $TempID;?> Work Progress</h4>
    <div id="Individual_dashboard" style="width: 100%; height: 500px;"></div><br>
    <div id="FacilityIndividualEntryChart" style="width: 100%; height: 500px;"></div>
    </div>
    
     <div class="col-sm-3 col-md-12 col-md-offset-2 " style=" text-align:left;"><br><h2 class="font-weight-bold" style="text-align:center;color:red;"><?php echo $TempID;?> Problems</h2>
    <div id="IndividualProblemDateWise" style="width: 100%; height: 500px;"></div><br>
    <div id="IndividualProblemFacilityWise" style="width: 100%; height: 500px;"></div>
    </div>
      
       <?php }}?>
       
           <table class="columns">
      
    </table>
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