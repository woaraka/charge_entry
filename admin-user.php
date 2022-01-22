<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'User Control';
    $user_id=$_SESSION['user_id'];
    $query=mysqli_query($con,"select user_name,name,position from login where user_id='$user_id'")or die ("query 1 incorrect.......");
    list($username,$name, $Position)=mysqli_fetch_array($query);
    if($Position != 'Team Leader')
    {
        header("location: index.php");
    }
    
    $query1 = mysqli_query($con, "SELECT * FROM login ORDER BY user_id ASC");
    
    if(isset($_GET['activation']))
    {
        $UserID = $_GET['activation'];
        $sql = mysqli_query($con, "SELECT active FROM login WHERE user_id='$UserID'");
        list($Act) = mysqli_fetch_array($sql);
        if($Act == 1)
        {
            mysqli_query($con, "UPDATE login SET active = 0 WHERE user_id = '$UserID'");
        }
        else
        {
            mysqli_query($con, "UPDATE login SET active = 1 WHERE user_id = '$UserID'");
        }
        header("location: admin-user.php");
    }
        

    if(isset($_POST['ChangePass']))
    {
        $ChangePassID = $_POST['PasswordChange'];
        $password = $_POST['con_pass'];
        
        mysqli_query($con, "UPDATE login SET user_password = '$password' WHERE user_id='$ChangePassID'");
        header("location: admin-user.php");
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
              <h1 align="center" style="color:black" class="h3 mb-0"><b><u>User Information</u></b></h1>
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
    
                         
                               
                                     
          
                          
    <!-- Password Change -->              
         	<div id="Change_Pass_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <div class="modal-body">
    <form method="post" action="admin-user.php">
    <input type="hidden" id="PasswordChange" name="PasswordChange">
    <input type="password" class="form-control form-control-user" name="pass" id="password" onkeyup='check_pass()' placeholder="New Password" required>
     <br>
    <input type="password" class="form-control form-control-user" name="con_pass" id="confirm_password" onkeyup='check_pass()' placeholder="Repeat Password" required>
	 <div class="col-sm-12">
                      <p id="check_pass">
                          
                      </p>
                  </div>
     <input type="submit" name="ChangePass" id="btn_ChangePass" value="Change" class="btn btn-primary" disabled/>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </form>
   </div>
  </div>
 </div>
</div>  

  
  
  

  
  
    <!-- Edit Information -->              
<div id="Edit_Information_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
        <h5 class="modal-title">Update User Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <div class="modal-body">
    <form method="post" action="admin-update-user.php">
        <input type="hidden" id="txtUpdateID" name="UpdateID">
    
        <input type="text" class="form-control" name="UpdateFullName" id="btnUpdateFullName" placeholder="Full Name" required>
        <br>
        <input type="text" class="form-control" name="UpdateUserName" id="btnUpdateUserName" placeholder="User Name" required>
        <br>
        <select class="form-control" name="UpdatePosition" id="UpdateSelectPosition">
            <option value="">Select Position</option>
            <option value="Team Leader">Team Leader</option>
            <option value="Team Member">Team Member</option>
        </select>
	    <br>
        <input type="submit" name="UpdateInformation" id="btn_UpdateInformation" value="Update" class="btn btn-primary"/>
	    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </form>
   </div>
  </div>
 </div>
</div> 
   
   
   
  
   
   
   
   
    <!-- Table Information -->
    <div class="table-responsive">     
    <table class="table table-bordered table-striped table-secondary">
		<thead>
			<tr style="color:black; font-size:17px;">
                <th>Full Name</th>
                <th>User Name</th>
				<th>Position</th>
				<th colspan="2" style="text-align:center">Action</th>
				<th>Activation</th>
			</tr>
		</thead>
		<tbody class="text-dark">
           <?php
            while($row = mysqli_fetch_array($query1)) {
            if($row['user_id'] != 0){?>
            <tr>
               <?php
                $tempID = $row['user_id'];
                $FullName = $row['name'];
                $UserName = $row['user_name'];
                $UserPosition = $row['position'];
                $UserPassword = $row['user_password'];
                $active = "";
                if($row['active'] == 1)
                {
                    $active = "Enable";
                }
                else
                {
                    $active = "Disable";
                }
                
                ?>
                <td hidden><?php echo $tempID; ?></td>
                <td><?php echo $FullName; ?></td>
                <td><?php echo $UserName; ?></td>
                <td><?php echo $UserPosition; ?></td>
                <td hidden><?php echo $UserPassword; ?></td>
                <td><button type="button" class="btn btn-info btnPasswordChange" data-toggle="modal" data-target="#Change_Pass_Modal">Change Password</button></td>
                
                <td><button type="button" class="btn btn-primary btnInfoChange" data-toggle="modal" data-target="#Edit_Information_Modal">Edit Information</button></td>
                
                <td><a href="admin-user.php?activation=<?php echo $tempID;?>"><button type="button" class="btn btn-outline-info"><?php echo $active;?></button></a></td>
            </tr>
            <?php }} ?>
		</tbody>
	</table>
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
    
function check_pass() {
    var x= $('#password').val();
    var y= $('#confirm_password').val();
    //console.log(z);
    if(x == '' || y == '')
{
     $("#btn_ChangePass").prop("disabled",true);
     $('#check_pass').text("");
     return;
}
    //alert(z);
    
    if (x == y) {
        $('#btn_ChangePass').prop("disabled",false);
        $('#check_pass').text("Password match");
        $('#check_pass').css("color", "green");
    } else {
         $("#btn_ChangePass").prop("disabled",true);
         $('#check_pass').text("Password not match");
         $('#check_pass').css("color", "red");
    }
}
</script>


<script>
	$(document).ready(function(){
		$('.btnPasswordChange').on('click', function(){
			//$('#Update_data_Modal').modal('show');
            
            $tr = $(this).closest('tr');
             var data = $tr.children("td").map(function(){
                 return $(this).text();
             }).get();
            
            //console.log(data);
            
            $('#PasswordChange').val(data[0]);
		});
	});
</script>


<script>
	$(document).ready(function(){
		$('.btnInfoChange').on('click', function(){
			//$('#Update_data_Modal').modal('show');
            
            $tr = $(this).closest('tr');
             var data = $tr.children("td").map(function(){
                 return $(this).text();
             }).get();
            
            //console.log(data);
            
            $('#txtUpdateID').val(data[0]);
            $('#btnUpdateFullName').val(data[1]);
            $('#btnUpdateUserName').val(data[2]);
            $('#UpdateSelectPosition').val(data[3]);
		});
	});
</script>