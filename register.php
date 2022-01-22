<?php
    require_once("connection.php");
    
    $user = true;
    $result = false;
    if(isset($_POST['register']))
    {
        $name = $_POST['name'];
        $User_name = $_POST['u_name'];
        $Password = $_POST['pass'];
        $Re_Pass = $_POST['re_pass'];
        
        $list = mysqli_query($con, "SELECT user_name FROM login");
        while(list($temp_User) = mysqli_fetch_array($list))
        {
            if($temp_User == $User_name)
            {
                $user = false;
                break;
            }
        }
        
        if($user == true)
        {
            $result = mysqli_query($con, "INSERT INTO login (name, user_name, user_password,active) VALUES ('$name','$User_name','$Password',0)") or die ("Query is inncorrect........");
        }
        if($result)
        {
            $_SESSION['Success_Reg'] = "Registration Successful!";
        }
        else
            $_SESSION['Success_Reg'] = "Registration Not Successful. Try Again!";
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Charge Entry - Register</title>
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">


</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-4 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-8">
            <div class="p-5">
              <div class="text-center">
                <h1 class="text-success mb-4">
                <?php if(isset($_SESSION['Success_Reg'])){
                    echo $_SESSION['Success_Reg']; unset($_SESSION['Success_Reg']);}?> </h1> 
                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
              </div>
              
              <form class="user" method="post" onsubmit="return submitForm(this);" action="register.php">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" name="name" placeholder="Full Name" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" name="u_name" placeholder="User Name" required>
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" name="pass" id="password" onkeyup='check_pass()' placeholder="Password" required>
                  </div>
                  
                  <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" name="re_pass" id="confirm_password" onkeyup='check_pass()' placeholder="Repeat Password" required>
                  </div>
                </div>
                  <div class="col-sm-12">
                      <p id="check_pass">
                          
                      </p>
                  </div>
                <button type="submit" class="btn btn-primary btn-user btn-block" name="register" id="btn_submit" disabled>
                  Register Account
                  </button>
                <hr>
              </form>
              
              <div class="text-center">
                <a class="small" href="login.php">Already have an account? Login!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>


  <!-- Custom scripts for all pages-->
  
  <script type="text/javascript">
function submitForm() {
  return confirm('Do you really want to submit the form?');
}
	</script>
	
	
  
  <script src="js/sb-admin-2.min.js"></script>
  <script>
function check_pass() {
    var x= $('#password').val();
    var y= $('#confirm_password').val();
    //console.log(z);
    if(x == '' || y == '')
{
     $("#btn_submit").prop("disabled",true);
     $('#check_pass').text("");
     return;
}
    //alert(z);
    
    if (x == y) {
        $('#btn_submit').prop("disabled",false);
        $('#check_pass').text("Password match");
        $('#check_pass').css("color", "green");
    } else {
         $("#btn_submit").prop("disabled",true);
         $('#check_pass').text("Password not match");
         $('#check_pass').css("color", "red");
    }
}
</script>

</body>

</html>