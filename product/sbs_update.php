<?php
    include('../connectdb.php');
    if (isset($_POST['update'])) {
        $idproduct = $_POST['idproduct'];
        $name = $_POST['name'];
        $saleprice = $_POST['saleprice'];
        $brand = $_POST['brand'];
        $unit = $_POST['unit'];
        $repoint = $_POST['reorderpoint'];
        $detail = $_POST['detail'];


        // SQL ตาราง 1 กรองเพื่อเช็คค่าที่จะแก้ไขใน form ของสินค้า
        $sql = "SELECT product_id,count(product_name) AS p_name,count(brand_id) AS b_brand FROM product WHERE product_name = '$name' AND brand_id = '$brand' AND product_id != '$idproduct'";
        $query = mysqli_query($conn, $sql);
        $row  = mysqli_fetch_array($query);
        // echo $sql;
        $p_name=$row['p_name'];
        $b_brand=$row['b_brand'];

        if ($p_name == '0' && $b_brand == '0' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $repoint != '' && $detail != '') {

            $sql = "UPDATE product SET product_name='" . $name . "' , 
            brand_id='" . $brand . "', 
            unit_id='" . $unit . "', 
            product_saleprice='" . $saleprice . "',
            product_reorder='".$repoint."',
            product_detail='".$detail."'
            WHERE product_id = '" . $idproduct . "'";

            if ($conn->query($sql)==true) {
                echo "<script>";
                echo "alert('Update Success!');";
                echo "window.location.href='sbs_show.php';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('ERROR!');";
                echo "window.location.href='sbs_edit.php';";
                echo "</script>";
            }
        }elseif($name == '' || $saleprice == '' || $unit == '' || $brand == '' || $repoint == '' || $detail == ''){
            echo "<script>";
            echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
            echo "window.location.href='sbs_edit.php';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('มีสินค้านี้ในระบบแล้วกรุณากรอกใหม่อีกครั้ง!');";
            echo "window.location.href='sbs_edit.php?p_id=$idproduct';";
            echo "</script>";
        }
    }
?>
