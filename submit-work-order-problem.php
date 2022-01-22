   <?php
    include("check_session.php");
    require_once("connection.php");
    if(isset($_POST['save'])){
        $id = $_SESSION['user_id'];
        
        $fac_id = $_POST['fac'];
        $date = $_POST['fdate'];
        $date = date("Y-m-d",strtotime($date));
        $PName = $_POST['p_name'];
        $PMrn = $_POST['mrn'];
        $prob = $_POST['p_prob'];
        $query = mysqli_query($con, "INSERT INTO workorder_problem (u_id, f_id, date, p_name, mrn, prob) VALUES ('$id', '$fac_id', '$date', '$PName', '$PMrn', '$prob')");
        }
        
    if(isset($_POST['submit'])){
        $id = $_SESSION['user_id'];
        $total_entry = $_POST['entry'];
        $Entry_date = date("Y/m/d");
        $query = mysqli_query($con, "INSERT INTO total_work_order (u_id, total, date) VALUES ('$id', '$total_entry','$Entry_date')");
    }
        
        
        if($query)
        {
            $_SESSION['Success'] = "Added Successfully!";
        }
        
        //header("Refresh: 2;url= work-order-problem.php");
        header('location: work-order-problem.php');
    //header('location: index.php');
?>
  