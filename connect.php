<?php
    $HOSTNAME = 'localhost';
    $USERNAME = 'root';
    $PASSWORD = '';
    $DATABASE = 'sunday130425';

    $con = mysqli_connect($HOSTNAME,$USERNAME,$PASSWORD,$DATABASE);

    if(!$con){
        die(mysqli_error($con));
    }

?>