<?php 
    include('../connectdb.php');
    if (isset($_POST['update'])) {
        $idproduct = $_POST['idproduct'];
        $name = $_POST['name'];
        $saleprice = $_POST['saleprice'];
        $unit = $_POST['unit'];
        $brand = $_POST['brand'];
        $color = $_POST['color'];
        $material = $_POST['material'];
        $detail = $_POST['detail'];
        $repoint = $_POST['reorderpoint'];
        $size = $_POST['size'];
    

        $sql = "SELECT craftmantool.product_id,count(product_name) AS p_name,count(color_id) AS c_color,count(product.brand_id) AS b_brand,count(craftmantool.mt_id) AS m_material,count(ct_size) AS s_size
		FROM product
        INNER JOIN craftmantool ON craftmantool.product_id = product.product_id
        WHERE product.product_name = '$name' AND craftmantool.product_id != '$idproduct' AND product.brand_id = '$brand' AND color_id = '$color' AND craftmantool.mt_id = '$material' AND craftmantool.ct_size = '$size'";
        $query = mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
        $row  = mysqli_fetch_array($query);
        $p_name = $row['p_name'];
        $c_color = $row['c_color'];
        $b_brand = $row['b_brand'];
        $m_mateial=$row['m_material'];
        $s_size=$row['s_size'];

        if ($p_name == '0' && $c_color == '0' && $b_brand == '0' && $m_mateial == '0' && $s_size == '0' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $repoint != '' && $size != '')  {
            $sql = "UPDATE product 
            SET product_name='" . $name . "',
            brand_id='" . $brand . "',
            unit_id='" . $unit . "' ,
            product_saleprice='" . $saleprice . "',
            product_reorder='".$repoint."',
            product_detail='".$detail."'
            WHERE product_id = '" . $idproduct . "'";
        // echo $sql1;
        if ($conn->query($sql)==true) {
            $sql = "UPDATE craftmantool SET color_id='" . $color . "',mt_id='" . $material . "',ct_size='" . $size . "' WHERE product_id = '" . $idproduct . "'";
            mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
                echo "<script>";
                echo "alert('Update Success!');";
                echo "window.location.href='ct_show.php';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('ERROR!');";
                echo "window.location.href='ct_edit.php?ct_id=$idcategory&p_id=$idproduct';";
                echo "</script>";
            }
        } elseif($name == '' || $saleprice == '' || $unit == '' || $brand == '' || $repoint == '' || $material == '' || $color == '' || $size == '' || $detail == ''){
            echo "<script>";
            echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
            echo "window.location.href='ct_edit.php?p_id=$idproduct';";
            echo "</script>";
        }else {
            echo "<script>";
            echo "alert('สินค้าซ้ำในระบบกรุณากรอกใหม่อีกครั้ง !!');";
            echo "window.location.href='ct_edit.php?p_id=$idproduct';";
            echo "</script>";
        }
    }
?>