<?php 
    include('../connectdb.php');
    if (isset($_POST['update'])) {
        $idcategory=$_POST['idcategory'];
        $name=$_POST['name'];
        $idproduct=$_POST['idproduct'];
        $saleprice=$_POST['saleprice'];
        $unit=$_POST['unit'];
        $brand=$_POST['brand'];
        $size=$_POST['size'];
        $repoint=$_POST['reorderpoint'];

        $sql = "SELECT att_id,product_id,count(size_id) AS s_size,count(product_name) AS p_name,count(brand_id) AS b_brand FROM attachment WHERE product_name = '$name' AND size_id = '$size' AND att_id != '$idcategory' AND brand_id = '$brand'";
        $query = mysqli_query($conn, $sql);
        $row  = mysqli_fetch_array($query);
        $s_size=$row['s_size'];
        $p_name=$row['p_name'];
        $b_brand=$row['b_brand'];
                
        if ($s_size == '0' && $p_name == '0' && $b_brand == '0' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $repoint != '') {
            $sql = "UPDATE attachment SET product_name='".$name."',brand_id='".$brand."',size_id='".$size."',unit_id='".$unit."', saleprice='".$saleprice."',reorderpoint='".$repoint."' WHERE att_id='".$idcategory."' AND product_id = '".$idproduct."'";
            if ($conn->query($sql)==true) {
                echo "<script>";
                echo "alert('Update Success!');";
                echo "window.location.href='att_show.php';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('ERROR!');";
                echo "window.location.href='att_edit.php?att_id=$idcategory&p_id=$idproduct';";
                echo "</script>";
            }
        }elseif($name == '' || $saleprice == '' || $unit == '' || $brand == '' || $repoint == ''){
            echo "<script>";
            echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
            echo "window.location.href='att_edit.php?att_id=$idcategory&p_id=$idproduct';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('สินค้าซ้ำในระบบกรุณากรอกใหม่อีกครั้ง !!');";
            echo "window.location.href='att_edit.php?att_id=$idcategory&p_id=$idproduct';";
            echo "</script>";
        }
    }
?>