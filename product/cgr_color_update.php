<?php
    include('../connectdb.php');
    if (isset($_POST['update'])) {
        $idproduct = $_POST['idproduct'];
        $name = $_POST['name'];
        $saleprice = $_POST['saleprice'];
        $unit = $_POST['unit'];
        $brand = $_POST['brand'];
        $color = $_POST['color'];
        $repoint = $_POST['reorderpoint'];
        $detail = $_POST['detail'];
        $volume = $_POST['volume'];
        $material = $_POST['material'];

        $sql = "SELECT product.product_id,count(product_name) AS p_name,count(brand_id) AS b_brand,count(color_id) AS c_color,count(material.mt_id) AS m_material,count(cc_volume) AS v_volume
        FROM categorycolor 
        INNER JOIN product ON categorycolor.product_id = product.product_id 
        INNER JOIN material ON material.mt_id = categorycolor.mt_id
        WHERE product_name = '$name' AND categorycolor.product_id != '$idproduct' AND brand_id = '$brand' AND color_id = '$color' AND categorycolor.mt_id = '$material' AND categorycolor.cc_volume = '$volume'";
        // echo $sql;
        $query = mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
        $row  = mysqli_fetch_array($query);
        $p_name = $row['p_name'];
        $b_brand = $row['b_brand'];
        $c_color = $row['c_color'];
        $m_material = $row['m_material'];
        $v_volume = $row['v_volume'];

    if ($c_color == '0' && $p_name == '0' && $b_brand == '0' && $m_material == '0' && $v_volume == '0' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $repoint != '' && $detail != '' && $volume != '' && $material != '' && $color != '') {
        $sql1 = "UPDATE product 
        SET product_name='" . $name . "',
        brand_id='" . $brand . "',
        unit_id='" . $unit . "' ,
        product_saleprice='" . $saleprice . "',
        product_reorder='".$repoint."',
        product_detail='".$detail."'
        WHERE product_id = '" . $idproduct . "'";
        // echo $sql1;
        if ($conn->query($sql1)==true) {

            $sql = "UPDATE categorycolor SET color_id='" . $color . "',mt_id='" . $material . "',cc_volume='" . $volume . "' WHERE product_id = '" . $idproduct . "'";
            mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
            
            echo "<script>";
            echo "alert('Update Success!');";
            echo "window.location.href='cgr_color_show.php';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('กรุณากรอกใหม่!');";
            echo "window.location.href='cgr_color_edit.php?p_id=$idproduct;";
            echo "</script>";
        }
    }elseif($name == ''|| $color == '' || $material == '' || $volume == '' || $detail == '' || $saleprice == '' || $unit == '' || $brand == '' || $repoint == ''){
        echo "<script>";
        echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
        echo "window.location.href='cgr_color_edit.php?p_id=$idproduct';";
        echo "</script>";
    } else {
        echo "<script>";
        echo "alert('สินค้าซ้ำในระบบกรุณากรอกใหม่อีกครั้ง !!');";
        echo "window.location.href='cgr_color_edit.php?p_id=$idproduct';";
        echo "</script>";
    }
}
?>