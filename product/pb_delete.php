<?php
include('../connectdb.php');

$idproduct = isset($_GET['p_id']) ? $_GET['p_id']:'';

    if ($idproduct!='') {
        $sql1 = "DELETE FROM pvc WHERE product_id ='".$idproduct."'";
        if (mysqli_query($conn, $sql1)==true) {
            $sql2 = "DELETE FROM plumbling WHERE product_id ='".$idproduct."'";
            if(mysqli_query($conn, $sql2)==true){
                $sql3="DELETE FROM costprice WHERE product_id = '$idproduct'";
                if(mysqli_query($conn, $sql3)==true){
                    $sql4="DELETE FROM product WHERE product_id = '$idproduct'";
                    mysqli_query($conn, $sql4);
                    echo "<script>";
                    echo "window.location.href='pb_show.php';";
                    echo "</script>";
                }
            }
        }
    }else{
        echo "<script>";
        echo "alert('ERROR!');";
        echo "window.location.href='pb_show.php';";
        echo "</script>";
    }
