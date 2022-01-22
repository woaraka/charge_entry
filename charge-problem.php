<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'Problem (Charge Entry)';
    $user_id=$_SESSION['user_id'];
    $query=mysqli_query($con,"select user_name,name,position from login where user_id='$user_id'")or die ("query 1 incorrect.......");
    list($username,$name, $Position)=mysqli_fetch_array($query);
    
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
              <h1 class="text-success mb-4">
                <?php if(isset($_SESSION['Success'])){
                    echo $_SESSION['Success']; unset($_SESSION['Success']);}?> </h1> 
              <h1 align="center" class="h3 mb-0 text-gray-800"><b>Charge Entry Problems</b></h1>
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
              <form action="submit-charge-problem.php" method="post">
                 <select name="fac" required>
                     <option value="" selected>Select Facility</option>
                     <?php
                        $list = mysqli_query($con,"SELECT f_id, f_name FROM facility");
                        while(list($fid,$fname) = mysqli_fetch_array($list))
                        {  
							if($fid == $_SESSION['tempFac'])
							{
								echo "<option value='$fid' selected>$fname</option>";
								unset($_SESSION['tempFac']);
							}
							else
							{
								echo "<option value='$fid'>$fname</option>";
							}
                        }
                     ?>
                     
                 </select><br>
                 
              	<div>
					<input type="text" id="datepicker" name="fdate" placeholder="DD/MM/YYYY" autocomplete="off" value="<?php if(isset($_SESSION['tempDate'])){ echo $_SESSION['tempDate']; unset($_SESSION['tempDate']);}?>" required>
				</div>
                  
                  <input type="number" name="no_fac" min="1" placeholder="Total Entry" value="<?php if(isset($_POST['no_fac'])) echo $_POST['no_fac'];?>"><br>
                  
                  <input type="checkbox" id="myCheck" name="pbox" value="No Problem" onclick="checkboxFun()"> No Problem<br>
                  
                  <input type="text" name="p_name" id="pname" style="display:block" placeholder="Patient Name" required>
                  
                  <input class="quantity" type="number" name="mrn" id="pid" style="display:block" placeholder="MRN" min="1" required>
                  
                  <textarea name="p_prob" id="pprob" style="display:block" autocomplete="on" placeholder="Problem" required ></textarea>
                  
                  <button name="save" onclick="myFunction()">Submit</button>
              </form>
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
    $( "#datepicker" ).datepicker();
  } );

    
  function checkboxFun() {
      var checkBox = document.getElementById("myCheck");
      var text1 = document.getElementById("pname");
      var text2 = document.getElementById("pid");
      var text3 = document.getElementById("pprob");
      if (checkBox.checked != true){
		  $(text1).prop('required',true);
          text1.style.display = "block";
		  $(text2).prop('required',true);
          text2.style.display = "block";
		  $(text3).prop('required',true);
          text3.style.display = "block";
      } 
      else {
          $(text1).prop('required',false);
          text1.style.display = "none";
          $(text2).prop('required',false);
          text2.style.display = "none";
          $(text3).prop('required',false);
          text3.style.display = "none";
  }
}
</script>

