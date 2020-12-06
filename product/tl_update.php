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
        $size=$_POST['size'];

            $sql = "SELECT product.product_id,count(product_name) AS p_name,count(brand_id) AS b_brand,count(color_id) AS c_color,count(toilet.tl_size) AS tl_size
            FROM toilet INNER JOIN product 
            ON toilet.product_id = product.product_id 
            WHERE product.product_name = '$name' AND product.brand_id = '$brand' AND toilet.color_id = '$color' AND toilet.product_id != '$idproduct'";
            $query = mysqli_query($conn, $sql);
            $row  = mysqli_fetch_array($query);
            $p_name=$row['p_name'];
            $b_brand=$row['b_brand'];
            $c_color=$row['c_color'];
            $tl_size=$row['tl_size'];
// echo $sql;
            if ($p_name == '0' && $b_brand == '0' && $c_color == '0' && $tl_size == '0' && $name != '' && $unit != '' && $brand != '' && $color != '' && $repoint != '' && $detail != '' && $size != '') {
                $sql = "UPDATE product SET 
                product_name='".$name."', 
                brand_id='".$brand."' , 
                unit_id='".$unit."',
                product_saleprice='".$saleprice."',
                product_reorder='".$repoint."',
                product_detail='".$detail."'
                WHERE product_id = '".$idproduct."'";

                if ($conn->query($sql)==true) {
                    $sql = "UPDATE toilet SET color_id='" . $color . "',tl_size='" . $size . "' WHERE product_id = '" . $idproduct . "'";
                    mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
                    echo "<script>";
                    echo "alert('Update Success!');";
                    echo "window.location.href='tl_show.php';";
                    echo "</script>";
                } else {
                    echo "<script>";
                    echo "alert('กรอกใหม่อีกครั้ง!');";
                    echo "window.location.href='tl_edit.php?p_id=$idproduct';";
                    echo "</script>";
                }
            }elseif($name == '' || $saleprice == '' || $unit == '' || $brand == '' || $repoint == '' || $color == '' || $detail == '' || $size == ''){
                echo "<script>";
                echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
                echo "window.location.href='tl_edit.php?p_id=$idproduct';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('มีสินค้านี้ในระบบแล้วกรุณากรอกใหม่อีกครั้ง!');";
                echo "window.location.href='tl_edit.php?p_id=$idproduct';";
                echo "</script>";
            }
        }
