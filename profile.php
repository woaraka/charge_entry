<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'Profile';
    $user_id=$_SESSION['user_id'];
    $query=mysqli_query($con,"select user_name,name, position from login where user_id='$user_id'")or die ("query 1 incorrect.......");
    list($username,$name, $Position)=mysqli_fetch_array($query);

    if(isset($_POST['Pass_Submit']))
    {
        $password = $_POST['con_pass'];
        mysqli_query($con, "UPDATE login SET user_password='$password' WHERE user_id='$user_id'");
        header('Location: logout.php');
    }

    if(isset($_POST['profile_update']))
    {
        $FullName = $_POST['name'];
        $UserName = $_POST['u_name'];
        mysqli_query($con, "UPDATE login SET name='$FullName', user_name='$UserName' WHERE user_id='$user_id'");
        header('Location: profile.php');
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
              <h1 class="text-success mb-4">
                <?php if(isset($_SESSION['Success'])){
                    echo $_SESSION['Success']; unset($_SESSION['Success']);}?> </h1> 

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
              <div><h3 style="color:#99cc00">#Personal Information</h3></div><br>
                <form method="post" action="profile.php">
                  <div class="col-sm-8 mb-3 mb-sm-0">
                    <b>Full Name:</b> <input type="text" class="form-control form-control-user" name="name" id="btnFullName" onkeyup='changeProfile()' value="<?php echo $name;?>" required><br>
                  </div>
                  <div class="col-sm-8">
                    <b>User Name:</b><input type="text" class="form-control form-control-user" name="u_name" id="btnUserName" onkeyup='changeProfile()' value="<?php echo $username;?>" required><br>
                  </div>
                  <div class="col-sm-8">
                    <b>Position:</b><input type="text" class="form-control form-control-user" value="<?php echo $Position;?>" disabled>
                  </div><br>
                  <div class="col-sm-2 mb-3 mb-sm-0">
                  <button name="profile_update" class="btn btn-success" id="btnUpdate" disabled>Update Profile</button>
                    </div>
              </form> 
          </div>
          
          
          
          <div><br><br>
              <div><h3 style="color:#ff3300">#Password Change</h3></div>
                <form method="post" action="profile.php">
                  <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" name="pass" id="password" onkeyup='check_pass()' placeholder="New Password" required>
                  </div>
                  
                  <div class="col-sm-4">
                    <input type="password" class="form-control form-control-user" name="con_pass" id="confirm_password" onkeyup='check_pass()' placeholder="Repeat Password" required>
                  </div>
                    <div class="col-sm-12">
                      <p id="check_pass">
                          
                      </p>
                  </div>
                  <div class="col-sm-2 mb-3 mb-sm-0">
                  <button name="Pass_Submit" id="btn_submit" class="btn btn-primary" disabled>Update Password</button>
                    </div>
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
    var a = $('#btnFullName').val();
    var b = $('#btnUserName').val();
    function changeProfile(){
        
        if($('#btnFullName').val() != a || $('#btnUserName').val() != b){
            $('#btnUpdate').prop("disabled",false);
        }
        else{
            $('#btnUpdate').prop("disabled",true);
        }
    }
    
    
function check_pass() {
    var x= $('#password').val();
    var y= $('#confirm_password').val();
    //console.log(z);
    if(x == '' || y == '')
{
     $("#btn_submit").prop("disabled",true);
     $('#check_pass').text("");
     return;
}
    //alert(z);
    
    if (x == y) {
        $('#btn_submit').prop("disabled",false);
        $('#check_pass').text("Password match");
        $('#check_pass').css("color", "green");
    } else {
         $("#btn_submit").prop("disabled",true);
         $('#check_pass').text("Password not match");
         $('#check_pass').css("color", "red");
    }
}
</script>
