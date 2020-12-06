<?php
include('../connectdb.php');

$productid = isset($_GET['p_id']) ? $_GET['p_id']:'';
$cpnid = isset($_GET['cpn_id']) ? $_GET['cpn_id']:'';

$sql = "DELETE FROM costprice WHERE product_id ='".$productid."' AND cpn_id = '".$cpnid."'";

if($conn->query($sql)==true){
    echo "<script>";
    echo "window.location.href='pb_costprice_show.php?p_id=$productid';";
    echo "</script>";
}
?>
