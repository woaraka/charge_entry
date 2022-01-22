<?php
    
    $con = mysqli_connect('localhost','root','nowfel','lng_charge');
    if(!$con){
        echo "Connection Fail" . mysqli_connect_error();
    }
?>