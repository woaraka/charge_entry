<?php
    include("check_session.php");
    include("connection.php");

    if(isset($_POST['UpdateInformation'])){
        $prob_id = $_POST['ProblemID'];
        $FacID = $_POST['UpdateSeleleFac'];
        $Date = $_POST['UpdatetxtDate'];
        $Date = date("Y-m-d",strtotime($Date));
        //$totalFacEntry = $_POST['UpdateFacilityNo'];
        $PName = $_POST['Update_Pname'];
        $PMrn = $_POST['UpdateMRN'];
        $PProblem = $_POST['UpdateProblem'];

        $query = mysqli_query($con, "UPDATE workorder_problem set f_id='$FacID',date='$Date', p_name='$PName', mrn='$PMrn', prob='$PProblem' WHERE id='$prob_id'") or die ("Query 2 is inncorrect........");
        if($query)
        {
            header("location: admin-work-order-history.php");
        }
        else
        {
            echo "Sorry! Please Check Again";
        }

}
    
    if(isset($_POST['UpdateTotalInformation'])){
        $prob_id = $_POST['TotalID'];
        $Date = $_POST['UpdateTotalDate'];
        $Date = date("Y-m-d",strtotime($Date));
        $Entry = $_POST['UpdateTotal'];
        
        $query = mysqli_query($con, "UPDATE total_work_order set date='$Date', total='$Entry' WHERE id='$prob_id'") or die ("Query 2 is inncorrect........");
        if($query)
        {
            header("location: admin-work-order-history.php");
        }
        else
        {
            echo "Sorry! Please Check Again";
        }
    }



    if(isset($_POST['UpdateInformation1'])){
        $prob_id = $_POST['ProblemID'];
        $FacID = $_POST['UpdateSeleleFac'];
        $Date = $_POST['UpdatetxtDate'];
        $Date = date("Y-m-d",strtotime($Date));
        //$totalFacEntry = $_POST['UpdateFacilityNo'];
        $PName = $_POST['Update_Pname'];
        $PMrn = $_POST['UpdateMRN'];
        $PProblem = $_POST['UpdateProblem'];

        $query = mysqli_query($con, "UPDATE workorder_problem set f_id='$FacID',date='$Date', p_name='$PName', mrn='$PMrn', prob='$PProblem' WHERE id='$prob_id'") or die ("Query 2 is inncorrect........");
        if($query)
        {
            header("location: mywork-work-order.php");
        }
        else
        {
            echo "Sorry! Please Check Again";
        }

}
    
    if(isset($_POST['UpdateTotalInformation1'])){
        $prob_id = $_POST['TotalID'];
        $Date = $_POST['UpdateTotalDate'];
        $Date = date("Y-m-d",strtotime($Date));
        $Entry = $_POST['UpdateTotal'];
        
        $query = mysqli_query($con, "UPDATE total_work_order set date='$Date', total='$Entry' WHERE id='$prob_id'") or die ("Query 2 is inncorrect........");
        if($query)
        {
            header("location: mywork-work-order.php");
        }
        else
        {
            echo "Sorry! Please Check Again";
        }
    }

?>



