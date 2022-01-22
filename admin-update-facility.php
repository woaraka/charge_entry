<?php
    include("check_session.php");
    include("connection.php");

    if(isset($_POST['update'])){
        $TempID = $_POST['update_ID'];
        $Fac_id = $_POST['u_fID'];
        $Fac_Name = $_POST['u_fName'];
        $Fac_Nick = $_POST['u_Nick'];
        
        
        $query = mysqli_query($con, "UPDATE facility set  f_id='$Fac_id', f_name='$Fac_Name', nick_name='$Fac_Nick' WHERE f_id='$TempID'") or die ("Query 1 is inncorrect........");
        
        $query1 = mysqli_query($con, "UPDATE charge_problem set  f_id='$Fac_id' WHERE f_id='$TempID'") or die ("Query 1 is inncorrect........");
        
        $query2 = mysqli_query($con, "UPDATE total_work set  f_id='$Fac_id' WHERE f_id='$TempID'") or die ("Query 2 is inncorrect........");
        
        $query3 = mysqli_query($con, "UPDATE workorder_problem set  f_id='$Fac_id' WHERE f_id='$TempID'") or die ("Query 2 is inncorrect........");
        
        if($query)
        {
            echo "Successfully Updated";
            header("Location: admin-facility.php");
        }
    }
        
?>
   