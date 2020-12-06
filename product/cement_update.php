<?php
    include('../connectdb.php');

    if (isset($_POST['update'])) {
        $idproduct=$_POST['idproduct'];
        $name=$_POST['name']; 
        $saleprice=$_POST['saleprice'];
        $unit=$_POST['unit'];
        $brand=$_POST['brand'];
        $color=$_POST['color'];
        $detail=$_POST['detail'];
        $repoint=$_POST['reorderpoint'];
        $volume=$_POST['volume'];

            $sql = "SELECT product.product_id,count(product_name) AS p_name,count(brand_id) AS b_brand,count(color_id) AS c_color,count(cm_volume) AS v_volume
            FROM cement INNER JOIN product 
            ON cement.product_id = product.product_id 
            WHERE product.product_name = '$name' AND product.brand_id = '$brand' AND cement.color_id = '$color' AND cement.product_id != '$idproduct' AND cement.cm_volume = '$volume'";
            $query = mysqli_query($conn, $sql);
            $row  = mysqli_fetch_array($query);
            $p_name=$row['p_name'];
            $b_brand=$row['b_brand'];
            $c_color=$row['c_color'];
            $v_volume=$row['v_volume'];
            // echo $sql;
            if ($p_name == '0' && $b_brand == '0' && $c_color == '0' && $v_volume == '0' && $name != '' && $unit != '' && $brand != '' && $color != '' && $repoint != '' && $detail != '' && $volume != '') {
                $sql = "UPDATE product SET 
                product_name='".$name."', 
                brand_id='".$brand."' , 
                unit_id='".$unit."',
                product_saleprice='".$saleprice."',
                product_reorder='".$repoint."',
                product_detail='".$detail."'
                WHERE product_id = '".$idproduct."'";

                if ($conn->query($sql)==true) {
                    $sql = "UPDATE cement SET color_id='" . $color . "',cm_volume='" . $volume . "' WHERE product_id = '" . $idproduct . "'";
                    mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
                    echo "<script>";
                    echo "alert('Update Succcess!');";
                    echo "window.location.href='cement_show.php';";
                    echo "</script>";
                } else {
                    echo "<script>";
                    echo "alert('กรอกใหม่อีกครั้ง!');";
                    echo "window.location.href='cement_edit.php?p_id=$idproduct';";
                    echo "</script>";
                }
            }elseif($name == '' || $saleprice == '' || $unit == '' || $brand == '' || $repoint == '' || $color == '' || $detail == '' || $volume == ''){
                echo "<script>";
                echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
                echo "window.location.href='cement_edit.php?p_id=$idproduct';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('มีสินค้านี้ในระบบแล้วกรุณากรอกใหม่อีกครั้ง!');";
                echo "window.location.href='cement_edit.php?p_id=$idproduct';";
                echo "</script>";
            }
        }
