<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['index'])){
   header('location:login_form.php');
}

?>

<?php
    ob_start();
    // include header.php file
    include ('header.php');
?>

<?php

    /*  include top sale section */
        include ('Template/_checkout_template.php');
    /*  include top sale section */

?>

<?php
// include footer.php file
include ('footer.php');
?>