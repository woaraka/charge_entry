<?php
    include("check_session.php");
    include("connection.php");

    if(isset($_POST['UpdateInformation'])){
        $user_id = $_POST['UID'];
        $prob_id = $_POST['ProblemID'];
        $FacID = $_POST['UpdateFacility'];
        $Date = $_POST['UpdateFacilityDate'];
        $Date = date("Y-m-d",strtotime($Date));
        $totalFacEntry = $_POST['UpdateFacilityNo'];
        $PName = $_POST['Update_Pname'];
        $PMrn = $_POST['UpdateMRN'];
        $PProblem = $_POST['UpdateProblem'];
		$InputDate = $_POST['UpdateInputDate'];
        
        $query = mysqli_query($con, "SELECT id, total FROM total_work WHERE f_id='$FacID' AND date='$Date'");
        $record = mysqli_fetch_array($query);
        $TempTotalID = $record['id'];
        $TempFacilityID = $record['total'];
        
		if($TempFacilityID == null)
		{
			$query = mysqli_query($con, "INSERT INTO total_work (u_id,f_id,date,total,time) values ('$user_id','$FacID','$Date','$totalFacEntry','$InputDate')");
		}

        else
        {
            $query = mysqli_query($con, "UPDATE total_work set total='$totalFacEntry' WHERE id='$TempTotalID'") or die ("Query 3 is inncorrect........");
        }

        if(isset($_POST['UpdateCheckBox']))
        {
            $PName = null;
            $PMrn = null;
            $PProblem = $_POST['UpdateCheckBox'];
            $query = mysqli_query($con, "UPDATE charge_problem set p_name='$PName', mrn=NULL, prob='$PProblem' WHERE id='$prob_id'") or die ("Query 1 is inncorrect........");
        }
        else
        {
            $query = mysqli_query($con, "UPDATE charge_problem set p_name='$PName', mrn='$PMrn', prob='$PProblem' WHERE id='$prob_id'") or die ("Query 2 is inncorrect........");
        }
        if($query)
        {
            header("location: admin-charge-history.php");
        }

}


if(isset($_POST['UpdateInformation1'])){
        $user_id = $_POST['UID'];
        $prob_id = $_POST['ProblemID'];
        $FacID = $_POST['UpdateFacility'];
        $Date = $_POST['UpdateFacilityDate'];
        $Date = date("Y-m-d",strtotime($Date));
        $totalFacEntry = $_POST['UpdateFacilityNo'];
        $PName = $_POST['Update_Pname'];
        $PMrn = $_POST['UpdateMRN'];
        $PProblem = $_POST['UpdateProblem'];
        
        $query = mysqli_query($con, "SELECT id, total FROM total_work WHERE f_id='$FacID' AND date='$Date'");
        $record = mysqli_fetch_array($query);
        $TempTotalID = $record['id'];
        $TempFacilityID = $record['total'];
        
		if($TempFacilityID == null)
		{
			$query = mysqli_query($con, "INSERT INTO total_work (u_id,f_id,date,total) values ('$user_id','$FacID','$Date','$totalFacEntry')");
		}

        else
        {
            $query = mysqli_query($con, "UPDATE total_work set total='$totalFacEntry' WHERE id='$TempTotalID'") or die ("Query 3 is inncorrect........");
        }

        if(isset($_POST['UpdateCheckBox']))
        {
            $PName = null;
            $PMrn = null;
            $PProblem = $_POST['UpdateCheckBox'];
            $query = mysqli_query($con, "UPDATE charge_problem set p_name='$PName', mrn=NULL, prob='$PProblem' WHERE id='$prob_id'") or die ("Query 1 is inncorrect........");
        }
        else
        {
            $query = mysqli_query($con, "UPDATE charge_problem set p_name='$PName', mrn='$PMrn', prob='$PProblem' WHERE id='$prob_id'") or die ("Query 2 is inncorrect........");
        }
        if($query)
        {
            header("location: mywork-charge.php");
        }

}

?>



