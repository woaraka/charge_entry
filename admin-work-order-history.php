<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'Work Order History';
    $user_id=$_SESSION['user_id'];
    $query=mysqli_query($con,"select user_name,name,position from login where user_id='$user_id'")or die ("query 1 incorrect.......");
    list($username,$name, $Position)=mysqli_fetch_array($query);

    if($Position != 'Team Leader')
    {
        header("location: index.php");
    }


    if(isset($_GET['del']))
    {
        $prob_id = $_GET['del'];
        mysqli_query($con, "DELETE FROM workorder_problem WHERE id='$prob_id'");
        header("location: admin-work-order-history.php");
    }

    if(isset($_GET['delTotal']))
    {
        $total_id = $_GET['delTotal'];
        mysqli_query($con, "DELETE FROM total_work_order WHERE id='$total_id'");
        header("location: admin-work-order-history.php");
    }

    if(isset($_SESSION['f-date']) && isset($_SESSION['t-date']))
    {
        $from_date = $_SESSION['f-date'];
       //$from_date = date("m/d/Y",strtotime($from_date));
        $to_date = $_SESSION['t-date'];
    }
    else
    {
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
    }
	$from_date = date("Y-m-d",strtotime($from_date));
    $to_date = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($to_date)));
        
    $query1 = mysqli_query($con, "SELECT * FROM workorder_problem WHERE (c_date>= '$from_date' AND c_date<= '$to_date') ORDER BY c_date ASC");

    $query2 = mysqli_query($con, "SELECT * FROM total_work_order WHERE (date>= '$from_date' AND date<= '$to_date') ORDER BY date ASC");

    if(isset($_POST['generate']))
    {
        $from_date = $_POST['fdate'];
        $from_date = date("Y-m-d",strtotime($from_date));
        $to_date = $_POST['tdate'];
        $to_date = date('Y-m-d H:i:s',strtotime('+23 hour +59 minutes +59 seconds',strtotime($to_date)));
        $_SESSION['f-date'] = date("m/d/Y",strtotime($from_date));
        $_SESSION['t-date'] = date("m/d/Y",strtotime($to_date));
        $TempID = $_POST['Name'];
        
        if($TempID == null){
            $query1 = mysqli_query($con, "SELECT * FROM workorder_problem WHERE (c_date>= '$from_date' AND c_date<= '$to_date') ORDER BY c_date ASC");

            $query2 = mysqli_query($con, "SELECT * FROM total_work_order WHERE (date>= '$from_date' AND date<= '$to_date') ORDER BY date ASC");
        }
        else{
            $query1 = mysqli_query($con, "SELECT * FROM workorder_problem WHERE u_id = '$TempID' AND (c_date>= '$from_date' AND c_date<= '$to_date') ORDER BY c_date ASC");
            $query2 = mysqli_query($con, "SELECT * FROM total_work_order WHERE u_id = '$TempID' AND (date>= '$from_date' AND date<= '$to_date') ORDER BY date ASC");
            
            $_SESSION['tempName'] = $TempID;
        }
        
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
              <h1 align="center" class="h3 mb-0 text-gray-800"><b><u>Work Order History</u></b></h1>
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
          
          
          
          
          
         
          
           
            
         <!-- Edit Total Entries Modal -->              

<div id="Edit_Work-Order_Entry" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
        <h5 class="modal-title">Update Work Order Entry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <div class="modal-body">
    <form method="post" action="update-work-order.php">
        <input type="hidden" name="TotalID" id="txtTotalID">
        
        <input type="text" id="txtUserName" name="UpdateUserName" placeholder="User Name" disabled><br>
       
        <input type="text" id="datepicker4" name="UpdateTotalDate" placeholder="Date of Service" autocomplete="off"><br>
        
        
        <input type="number" name="UpdateTotal" id="UpdatetxtTotal" placeholder="Total Entry" min="1" required><br><br>
        

        <input type="submit" name="UpdateTotalInformation" id="btn_UpdateTotalInformation" value="Update" class="btn btn-primary"/>
	    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </form>
   </div>
  </div>
 </div>
</div> 










<!-- Edit Problems Modal --> 

<div id="Edit_Work-Order_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
        <h5 class="modal-title">Update Work Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <div class="modal-body">
    <form method="post" action="update-work-order.php">
       <input type="hidden" name="ProblemID" id="txtProblemID">
        <select class="selectpicker" name="UpdateSeleleFac" id="UpdateSelectFacility">
                     <option value="">Select Facility</option>
                     <?php
                        $list = mysqli_query($con,"SELECT f_id, f_name FROM facility");
                        while(list($fid,$fname) = mysqli_fetch_array($list))
                        {
                            echo "<option value='$fid'>$fname</option>";
                        }
                     ?>
                     
                 </select><br>
       
        <input type="text" id="datepicker3" name="UpdatetxtDate" placeholder="Date of Service" autocomplete="off">
        
        <input type="text" name="Update_Pname" id="UpdateTxtPname" style="display:block" placeholder="Patient Name" required>
        
        <input class="quantity" type="number" name="UpdateMRN" id="UpdateTxtPid" style="display:block" placeholder="MRN" min="1" required>
        
        <textarea type="text" name="UpdateProblem" id="UpdateTxtProblem" placeholder="Problem" required></textarea><br>

        <input type="submit" name="UpdateInformation" id="btn_UpdateInformation" value="Update" class="btn btn-primary"/>
	    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </form>
   </div>
  </div>
 </div>
</div> 
          
          
          
          
          
          
          
          
          
          
          
          <!-- Date Search Form -->
          <div>
           <form method="post" action="admin-work-order-history.php">
               Date of Service (MM/DD/YYYY)<br>
               <input type="text" id="datepicker1" name="fdate" placeholder="FROM DATE" autocomplete="off" value="<?php if(isset($_SESSION['f-date'])) echo $_SESSION['f-date']; else echo date("m/d/Y"); ?>" required>
			   <input type="text" id="datepicker2" name="tdate" placeholder="TO DATE" autocomplete="off" value="<?php if(isset($_SESSION['f-date'])) echo $_SESSION['t-date']; else echo date("m/d/Y"); ?>" required>
               
               
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
          </div>
          
          
          
          
          
          
          
          
    <!-- Table Information -->      
              <div class="card mt-5 container" style="width:80%; text-align:center;">
    <table class="table table-sm table-dark table-bordered">
        <h4 style="text-align:center;color:#000099;"><b>Work Summary</b></h4>
		<thead>
			<tr style="color:black; font-size:17px;">
                <th>Date</th>
                <th>User Name</th>
				<th>Total Entry</th>
				<th colspan="2">Action</th>
			</tr>
		</thead>
		<tbody>
           <?php
            while($row2 = mysqli_fetch_array($query2)) {?>
            <tr>
               <?php
                $tempUserID = $row2['u_id'];
                $list = mysqli_query($con,"SELECT user_name FROM login where user_id = '$tempUserID'");
                list($tempUserName) = mysqli_fetch_array($list);
                $Work_Date = date("m/d/Y",strtotime($row2['date']));
                $total_Entry = $row2['total'];
                ?>
                <td hidden><?php echo $row2['id']; ?></td>
                <td><b><?php echo $tempUserName;?></b></td>
                <td><?php echo $Work_Date; ?></td>
                <td><?php echo $total_Entry; ?></td>
                <td><button type="button" class="btn btn-primary btnUpdateTotal" data-toggle="modal" data-target="#Edit_Work-Order_Entry">Edit</button></td>
                <td><a class="btn btn-danger btn-circle btn-sm" onclick="return checkDelete()" href="admin-work-order-history.php?delTotal=<?php echo $row2['id']; ?>">
                <i class="fas fa-trash"></i>
                </a></td>
            </tr>
            <?php } ?>
		</tbody>
	</table>
    </div>
       
     
       
         
        <div class="card mt-5">
    <table class="table table-bordered table-striped">
        <h4 class="font-weight-bold" style="text-align:center;color:#004d00;">Problem Summary</h4>
		<thead>
			<tr style="color:black; font-size:17px;">
                <th>Date</th>
                <th>User Name</th>
                <th>Facility Name</th>
				<th>Date of Service</th>
				<th>Patient Name</th>
				<th>MRN</th>
				<th>Problem</th>
				<th colspan="2">Action</th>
			</tr>
		</thead>
		<tbody class="text-dark">
           <?php
            while($row = mysqli_fetch_array($query1)) {?>
            <tr>
               <?php
                $tempUserID = $row['u_id'];
                $list = mysqli_query($con,"SELECT user_name FROM login where user_id = '$tempUserID'");
                list($tempUserName) = mysqli_fetch_array($list);
                $temp_id = $row['f_id'];
                $list = mysqli_query($con,"SELECT f_name FROM facility where f_id = '$temp_id'");
                list($fac) = mysqli_fetch_array($list);
                $date = date("m/d/Y",strtotime($row['date']));
                $Entry_Date = date("m/d/Y",strtotime($row['c_date']));
                ?>
                <td hidden><?php echo $row['id']; ?></td>
                <td hidden><?php echo $temp_id; ?></td>
                <td><?php echo $Entry_Date; ?></td>
                <td><b><?php echo $tempUserName;?></b></td>
                <td><?php echo $fac; ?></td>
                <td><?php echo $date; ?></td>
                <td><?php echo $row['p_name']; ?></td>
                <td><?php echo $row['mrn']; ?></td>
                <td><?php echo $row['prob']; ?></td>
                <td hidden><?php echo $tempUserID; ?></td>
                <td><button type="button" class="btn btn-primary btnUpdateWorkOrder" data-toggle="modal" data-target="#Edit_Work-Order_Modal">Edit</button></td>
                <td><a class="btn btn-danger btn-circle btn-sm" onclick="return checkDelete()" href="admin-work-order-history.php?del=<?php echo $row['id']; ?>">
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



<script>
	$(document).ready(function(){
		$('.btnUpdateWorkOrder').on('click', function(){    
            $tr = $(this).closest('tr');
             var data = $tr.children("td").map(function(){
                 return $(this).text();
             }).get();
            
            $('#txtProblemID').val(data[0]);
            $('#UpdateSelectFacility').val(data[1]);
            $('#datepicker3').val(data[5]);
            $('#UpdateTxtPname').val(data[6]);
            $('#UpdateTxtPid').val(data[7]);
            $('#UpdateTxtProblem').val(data[8]); 
		});
        
        
        $('.btnUpdateTotal').on('click', function(){    
            $tr = $(this).closest('tr');
             var data = $tr.children("td").map(function(){
                 return $(this).text();
             }).get();
            
            $('#txtTotalID').val(data[0]);
            $('#txtUserName').val(data[1]);
            $('#datepicker4').val(data[2]);
            $('#UpdatetxtTotal').val(data[3]);
		});
	});
</script>



<script language="JavaScript" type="text/javascript">
function checkDelete(){
    return confirm('Are you sure to Delete?');
}
</script>

<script>
    $( function() {
    $( "#datepicker1" ).datepicker();
        format: 'mm/dd/yyyy'
  } );
  
   $( function() {
    $( "#datepicker2" ).datepicker();
       format: 'mm/dd/yyyy'
  } );
    
    $( function() {
    $( "#datepicker3" ).datepicker();
  } );
    
    $( function() {
    $( "#datepicker4" ).datepicker();
  } );
    
</script>