<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'Facility Page';
    $user_id=$_SESSION['user_id'];
    $query=mysqli_query($con,"select user_name,name,position from login where user_id='$user_id'")or die ("query 1 incorrect.......");
    list($username,$name, $Position)=mysqli_fetch_array($query);
    if($Position != 'Team Leader')
    {
        header("location: index.php");
    }
    
    $query1 = mysqli_query($con, "SELECT * FROM facility ORDER BY f_id ASC");
    
    if(isset($_GET['delFacility']))
    {
        $Facility_id = $_GET['delFacility'];
        mysqli_query($con, "DELETE FROM facility WHERE f_id='$Facility_id'");
        header("location: admin-facility.php");
    }

    if(isset($_POST['add']))
    {
        $Fac_ID = $_POST['fID'];
        $FName = $_POST['fName'];
        $Fac_Nick = $_POST['Nick'];
        
        mysqli_query($con, "INSERT INTO facility (f_id, f_name, nick_name) VALUES ('$Fac_ID', '$FName', '$Fac_Nick')");
        header("location: admin-facility.php");
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
              <h1 align="center" style="color:black" class="h3 mb-0"><b><u>Facility Information</u></b></h1>
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
          
        

     <button type="button" name="" id="" data-toggle="modal" data-target="#add_data_Modal" class="btn btn-warning">Add Facility</button>

	<div id="add_data_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
        <h5 class="modal-title">Add Facility</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <div class="modal-body">
    <form method="post" action="admin-facility.php">
     <input type="number" name="fID" id="txtfID" class="form-control" placeholder="Facility ID" min="1" required/>
     <br>
     <input type="text" name="fName" id="txtfName" class="form-control" placeholder="Facility Name" required/>
     <br>
     <input type="text" name="Nick" id="txtNick" class="form-control" placeholder="Nick Name" required/>
     <br> 
	 
     <input type="submit" name="add" id="insert" value="Add" class="btn btn-success"/>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </form>
   </div>
  </div>
 </div>
</div>        
                


<!-- Update -->
	<div id="Update_data_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
        <h5 class="modal-title">Update Facility</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <div class="modal-body">
    <form method="post" id="update_form" action="admin-update-facility.php">
    
    <input type="hidden" name="update_ID" id="update_ID">
     <input type="number" name="u_fID" id="u_txtfID" class="form-control" placeholder="Facility ID" min="1" required/>
     <br>
     <input type="text" name="u_fName" id="u_txtfName" class="form-control" placeholder="Facility Name" required/>
     <br>
     <input type="text" name="u_Nick" id="u_txtNick" class="form-control" placeholder="Nick Name" required/>
     <br> 
	 
     <input type="submit" name="update" id="update" value="Update" class="btn btn-success"/>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </form>
   </div>
  </div>
 </div>
</div>               
                    
                      
                        
                          
                            
                              
                                  
    <div class="card mt-5 container" style="width:80%; text-align:center;">
    <table class="table table-sm table-dark table-bordered table-hover">
		<thead>
			<tr style="color:#ecffb3; font-size:17px;">
                <th>ID</th>
				<th>Facility Name</th>
				<th>Nick Name</th>
				<th colspan="2">Action</th>
			</tr>
		</thead>
		<tbody>
           <?php
            while($row = mysqli_fetch_array($query1)) {?>
            <tr>
                <td hidden><?php echo $row['f_id']; ?></td>
                <td><?php echo $row['f_id']; ?></td>
                <td><?php echo $row['f_name']; ?></td>
                <td><?php echo $row['nick_name']; ?></td>
               
                <td><button type="button" class="btn btn-success editbtn" data-toggle="modal" data-target="#Update_data_Modal">Edit</button></td>
                <td><a class="btn btn-danger btn-circle btn-sm" onclick="return checkDelete()" href="admin-facility.php?delFacility=<?php echo $row['f_id']; ?>">
                <i class="fas fa-trash"></i>
                </a></td>
            </tr>
            <?php } ?>
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


<script language="JavaScript" type="text/javascript">
function checkDelete(){
    return confirm('Are you sure to Delete?');
}
</script>

<script>
	$(document).ready(function(){
		$('.editbtn').on('click', function(){
			//$('#Update_data_Modal').modal('show');
            
            $tr = $(this).closest('tr');
             var data = $tr.children("td").map(function(){
                 return $(this).text();
             }).get();
            
            console.log(data);
            
            $('#update_ID').val(data[0]);
            $('#u_txtfID').val(data[1]);
            $('#u_txtfName').val(data[2]);
            $('#u_txtNick').val(data[3]);
		});
	});
</script>