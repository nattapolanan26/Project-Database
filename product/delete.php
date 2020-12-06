<?php
include('../connectdb.php');

$id = isset($_GET['quo_id']) ? $_GET['quo_id']:'';
$idproduct = isset($_GET['p_id']) ? $_GET['p_id']:'';

    if ($id!='') {
        $sqldelete = "DELETE FROM detailquotation WHERE quo_id ='".$id."'";

        $conn->query($sqldelete)==true;
        if ($id!='') {
            $sqldelete = "DELETE FROM quotation WHERE quo_id ='".$id."'";
            $conn->query($sqldelete)==true;
      
            echo "<script>";
            echo "alert('DELETE SUCCESS!');";
            echo "window.location.href='quo_status.php';";
            echo "</script>";
        }
    }

?>