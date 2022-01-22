<?php
    include("check_session.php");
    include("connection.php");

    if(isset($_POST['UpdateInformation'])){
        $ID = $_POST['UpdateID'];
        $FullName = $_POST['UpdateFullName'];
        $UserName = $_POST['UpdateUserName'];
        $Position = $_POST['UpdatePosition'];
        
        
        $query = mysqli_query($con, "UPDATE login set  name='$FullName', user_name='$UserName', position='$Position' WHERE user_id='$ID'") or die ("Query 1 is inncorrect........");
        
        if($query)
        {
            echo "Successfully Updated";
            header("Location: admin-user.php");
        }
    }
        
?>
   