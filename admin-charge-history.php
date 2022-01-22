<?php
    include("check_session.php");
    include("connection.php");
    $_SESSION['header'] = 'Charge Entry History';
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
		$list = mysqli_query($con, "SELECT f_id, date FROM charge_problem where id='$prob_id'");
		list($temp_Fac, $temp_Date) = mysqli_fetch_array($list);
		mysqli_query($con, "DELETE FROM total_work WHERE f_id ='$temp_Fac' && date = '$temp_Date'");
        mysqli_query($con, "DELETE FROM charge_problem WHERE id='$prob_id'");
        header("location: admin-charge-history.php");
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
    $query = mysqli_query($con, "SELECT * FROM charge_problem WHERE (c_date>= '$from_date' AND c_date<= '$to_date') ORDER BY u_id ASC, c_date ASC");

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
            $query = mysqli_query($con, "SELECT * FROM charge_problem WHERE (c_date>= '$from_date' AND c_date<= '$to_date') ORDER BY u_id ASC, c_date ASC");
        }
        else{
            $query = mysqli_query($con, "SELECT * FROM charge_problem WHERE u_id = '$TempID' AND (c_date>= '$from_date' AND c_date<= '$to_date') ORDER BY f_id ASC, c_date ASC");
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
              <h1 align="center" class="h3 mb-0 text-gray-800"><b><u>Charge Entry History</u></b></h1>
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
          
          
          
          
          
         
          
           
            
              <!-- Edit Information -->              
<div id="Edit_Charge_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
        <h5 class="modal-title">Update Charge Entry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <div class="modal-body">
    <form method="post" action="update-charge.php">
       <input type="hidden" name="ProblemID" id="txtProblemID">
       <input type="hidden" name="UID" id="txtUserID">
        <select class="selectpicker" name="" id="UpdateSelectFacility1" disabled>
                     <option value="">Select Facility</option>
                     <?php
                        $list = mysqli_query($con,"SELECT f_id, f_name FROM facility");
                        while(list($fid,$fname) = mysqli_fetch_array($list))
                        {
                            echo "<option value='$fid'>$fname</option>";
                        }
                     ?>
                     
                 </select>
                 
                 <select class="selectpicker" name="UpdateFacility" id="UpdateSelectFacility2" hidden>
                     <option value="">Select Facility</option>
                     <?php
                        $list = mysqli_query($con,"SELECT f_id, f_name FROM facility");
                        while(list($fid,$fname) = mysqli_fetch_array($list))
                        {
                            echo "<option value='$fid'>$fname</option>";
                        }
                     ?>
                     
                 </select><br>
       
        <input type="text" id="datepicker3" name="" placeholder="Date of Service" autocomplete="off" disabled>
        
        <input type="text" id="datepicker4" name="UpdateFacilityDate" hidden><br>
		
		<input type="text" id="txtDate" name="UpdateInputDate" hidden>
		
        <input type="number" name="UpdateFacilityNo" id="UpdateTxtFacilityNO" min="1" placeholder="Total Entry" required><br>
        
        <input type="checkbox" id="myCheck" name="UpdateCheckBox" value="No Problem" onclick="checkboxFun()"> No Problem
        
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
           <form method="post" action="admin-charge-history.php">
               Date of Service (MM/DD/YYYY)<br>
               <input type="text" id="datepicker1" name="fdate" placeholder="FROM DATE" autocomplete="off" value="<?php if(isset($_SESSION['f-date'])) echo $_SESSION['f-date']; else echo date("m/d/Y"); ?>" required>
			   <input type="text" id="datepicker2" name="tdate" placeholder="TO DATE" autocomplete="off" value="<?php if(isset($_SESSION['t-date'])) echo $_SESSION['t-date']; else echo date("m/d/Y"); ?>" required>
               
               
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
    <div class="card mt-5">
    <table class="table table-bordered table-striped table-sm">
    <h4 class="font-weight-bold" style="text-align:center;color:#004d00;">Problem Summary</h4>
		<thead>
			<tr style="color:black; font-size:18px;">
                <th>Date</th>
                <th>User Name</th>
                <th>Facility Name</th>
				<th>Date of Service</th>
				<th>Entry</th>
				<th>Patient Name</th>
				<th>MRN</th>
				<th>Problem</th>
				<th colspan="2">Action</th>
			</tr>
		</thead>
		<tbody>
           <?php
            while($row = mysqli_fetch_array($query)) {?>
            <tr>
               <?php
                $tempUserID = $row['u_id'];
                $list = mysqli_query($con,"SELECT user_name FROM login where user_id = '$tempUserID'");
                list($tempUserName) = mysqli_fetch_array($list);
                $temp_id = $row['f_id'];
                $list = mysqli_query($con,"SELECT f_name FROM facility where f_id = '$temp_id'");
                list($fac) = mysqli_fetch_array($list);
                $date = date("Y-m-d",strtotime($row['date']));
                $list = mysqli_query($con,"SELECT total FROM total_work where f_id ='$temp_id' AND date='$date'");
                list($entry) = mysqli_fetch_array($list);
                $date = date("m/d/Y",strtotime($row['date']));
                $Entry_Date = date("m/d/Y",strtotime($row['c_date']));
                ?>
                <td hidden><?php echo $row['id']; ?></td>
                <td hidden><?php echo $temp_id; ?></td>
                <td><?php echo $Entry_Date; ?></td>
                <td><b><?php echo $tempUserName;?></b></td>
                <td><?php echo $fac; ?></td>
                <td><?php echo $date; ?></td>
                <td><?php echo $entry; ?></td>
                <td><?php echo $row['p_name']; ?></td>
                <td><?php echo $row['mrn']; ?></td>
                <td><?php echo $row['prob']; ?></td>
                <td hidden><?php echo $tempUserID; ?></td>
				<td hidden><?php echo $row['c_date']; ?></td>
                
                <td><button type="button" class="btn btn-primary btnUpdateCharge" data-toggle="modal" data-target="#Edit_Charge_Modal">Edit</button></td>
                
                <td><a class="btn btn-danger btn-circle btn-sm" onclick="return checkDelete()" href="admin-charge-history.php?del=<?php echo $row['id']; ?>">
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
		$('.btnUpdateCharge').on('click', function(){
			//$('#Update_data_Modal').modal('show');
            
            $tr = $(this).closest('tr');
             var data = $tr.children("td").map(function(){
                 return $(this).text();
             }).get();
            
            //console.log(data);
            
            $('#txtProblemID').val(data[0]);
            $('#UpdateSelectFacility1').val(data[1]);
            $('#UpdateSelectFacility2').val(data[1]);
            $('#datepicker3').val(data[5]);
            $('#datepicker4').val(data[5]);
            $('#UpdateTxtFacilityNO').val(data[6]);
            $('#UpdateTxtPname').val(data[7]);
            $('#UpdateTxtPid').val(data[8]);
            $('#UpdateTxtProblem').val(data[9]);
            $('#txtUserID').val(data[10]);
			$('#txtDate').val(data[11]);
            var aaa =data[9];
            //console.log(aaa);
            if(aaa == "No Problem")
            {
                $( "#myCheck").prop('checked', true);
                $('#UpdateTxtProblem').val('');
            }
            else
            {
                $( "#myCheck").prop('checked', false); 
            }
             $('#UpdateSelectFacility').prop('readonly', true);
    $('#datepicker3').prop('readonly', true);   
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
    
    
    
    
    
    function checkboxFun() {
      var checkBox = document.getElementById("myCheck");
      var text1 = document.getElementById("UpdateTxtPname");
      var text2 = document.getElementById("UpdateTxtPid");
      var text3 = document.getElementById("UpdateTxtProblem");
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