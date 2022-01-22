   <?php
    include("check_session.php");
    require_once("connection.php");
    if(isset($_POST['save'])){
        $id = $_SESSION['user_id'];
        $fac_id = $_POST['fac'];
        $date = $_POST['fdate'];
        $date = date("Y-m-d",strtotime($date));
        $total = $_POST['no_fac'];
        $PName = null;
        $PMrn = null;
        
        if(isset($total))
        {
            $query = mysqli_query($con, "INSERT INTO total_work (u_id, f_id, date, total) VALUES ('$id', '$fac_id', '$date', '$total' )");
        }
        
        if(isset($_POST['pbox']))
        {
            $prob = $_POST['pbox'];
            $query = mysqli_query($con, "INSERT INTO charge_problem (u_id, f_id, date, prob) VALUES ('$id', '$fac_id', '$date', '$prob')");
        }
        else
        {
            $PName = $_POST['p_name'];
            $PMrn = $_POST['mrn'];
            $prob = $_POST['p_prob'];
            $query = mysqli_query($con, "INSERT INTO charge_problem (u_id, f_id, date, p_name, mrn, prob) VALUES ('$id', '$fac_id', '$date', '$PName', '$PMrn', '$prob')");
        }
        
        if($query)
        {
            $_SESSION['Success'] = "Added Successfully!";
        }
		$_SESSION['tempFac'] = $fac_id;
		$_SESSION['tempDate'] = $date;
        //echo "<br>" .$id . "<br>";
        //echo $fac_id . "<br>";
        //echo $date . "<br>";
        //echo $PName . "<br>";
        //echo $PMrn . "<br>";
        //echo $prob . "<br>";
        header("location:charge-problem.php");	
         
    //header('location: index.php');
}
?>
  