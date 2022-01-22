<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'Report (Charge Entry)';
    $user_id=$_SESSION['user_id'];
    $query=mysqli_query($con,"select user_name,name,position from login where user_id='$user_id'")or die ("query 1 incorrect.......");
    list($username,$name, $Position)=mysqli_fetch_array($query);
    $from_date = date("Y-m-d");
	$to_date = date("Y-m-d");
    $to_date = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($to_date)));
    if(isset($_POST['generate']))
    {
        $from_date = $_POST['fdate'];
        $from_date = date("Y-m-d",strtotime($from_date));
        $to_date = $_POST['tdate'];
        $to_date = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($to_date)));
    }

    include('includes/header.php');
    include('includes/navbar.php');
?>
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
              <h1 align="center" class="h3 mb-0 text-gray-800"><b><u>Reports</u></b></h1>
            <div class="dropdown">
              <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Charge Entry Reports
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="charge-report.php">Charge Entry</a>
                <a class="dropdown-item" href="work-order-report.php">Work Order</a>
              </div>
            </div> 
          </div>
          
          
          
          <div>
           <form method="post" action="charge-report.php">
               Date of Service (MM/DD/YYYY)<br>
			   <input type="text" id="datepicker1" name="fdate" placeholder="FROM DATE" autocomplete="off" value="<?php if(isset($_POST['fdate'])) echo $_POST['fdate']; else echo date("m/d/Y");?>" required>
			   <input type="text" id="datepicker2" name="tdate" placeholder="TO DATE" autocomplete="off" value="<?php if(isset($_POST['tdate'])) echo $_POST['tdate']; else echo date("m/d/Y");?>" required>

               <button name="generate" class="btn btn-success">Generate</button>
           </form>
          </div>
          
          
          
          <div>
            <?php
                $tempCount = 0;
                $temp_FacID = 0;
                $temp_date = '';
              
                $list = mysqli_query($con,"SELECT f_id, date, p_name, mrn, prob FROM charge_problem WHERE c_date>= '$from_date' && c_date<= '$to_date' ORDER BY f_id ASC, date ASC");
				while(list($fac,$date, $P_name, $mrn, $problem) = mysqli_fetch_array($list))
                {
                    $date = date("m/d/Y",strtotime($date));
                    $query = mysqli_query($con,"SELECT f_name FROM facility WHERE f_id = '$fac'");
                    list($fname)=mysqli_fetch_array($query);
                    if($temp_FacID != $fac)
                    {
						$tempCount++;
                        echo
                        "<br><br><span style='font-size:22px; color:black;text-transform: uppercase;'><b>$fname</b></span><br><br> <span style='color:black;'><b>DATE: $date</b></span><br><br>";
                            $temp_FacID = $fac;
                            $temp_date = $date;
                    }
                    else{ 
                        if($temp_date != $date)
                        {
							$tempCount++;
                            echo "
                            <br><span style='color:black;'><b>DATE: $date</b></span><br><br>";
                            $temp_date = $date;
                        }
                    }
                    if($P_name != null && $mrn != null)
                    {
                           echo "
                    *<span style='color:black;text-transform: uppercase;'>$P_name -- MRN -- </span>
                    <span style='color:black;'>$mrn</span><br>
                    <span style='color:black;text-transform: uppercase;'>$problem</span><br><br>
                    ";
					
                    }
                    else
                    {
                            echo "
                    <span style='color:black;text-transform: uppercase;'>$problem</span><br><br>		
                    ";
					
                    }
                    //echo $count.$fac;
					
                }
				echo "<br><br><b>$tempCount dates work</b>";
              ?>
                 
          </div>
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
    $( "#datepicker1" ).datepicker();
  } );
  
   $( function() {
    $( "#datepicker2" ).datepicker();
  } );
</script>
