<?php
include('../connectdb.php');

$id = isset($_GET['delete_id']) ? $_GET['delete_id']:'';

    if ($id!='') {
        $sqlcostprice = "DELETE FROM costprice where cpn_id ='".$id."'";
        if($conn->query($sqlcostprice)==true){
            echo "<script>";
            echo "alert('Delete Success!');";
            echo "window.location.href='cpn_showpro.php';";
            echo "</script>";
        }
    }
?>
