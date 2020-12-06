<?php
    include('../connectdb.php');
    include('../h.php');
        $idproduct = isset($_GET['p_id']) ? $_GET['p_id']:'';


        if($idproduct != ''){
            $sql1="DELETE FROM costprice WHERE product_id = '$idproduct'"; 
            if (mysqli_query($conn, $sql1)) {
                $sql2="DELETE FROM product WHERE product_id = '$idproduct'";
                if (mysqli_query($conn, $sql2)) {
                    echo "<script>";
                    echo "window.location.href='sbs_show.php';";
                    echo "</script>";
                }
            }
        }else{
            echo "<script>";
            echo "alert('ERROR!');";
            echo "window.location.href='javascript:history.back(1)';";
            echo "</script>";
        }
    ?>
