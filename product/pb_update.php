<?php 
    include('../connectdb.php');
    if (isset($_POST['update'])) {
		$name = $_POST['name'];
		$idproduct = $_POST['idproduct'];
		$saleprice = $_POST['saleprice'];
		$unit = $_POST['unit'];
		$brand = $_POST['brand'];
		$class = isset($_POST['class']) ? $_POST['class'] : '';
		$color = $_POST['color'];
		$repoint = $_POST['reorderpoint'];
		$material = $_POST['material'];
		$detail = $_POST['detail'];
		$size = $_POST['size'];
		$thick = $_POST['thick'];


        $sql = "SELECT plumbling.product_id,count(product_name) AS p_name,count(brand_id) AS b_brand,count(class) AS c_class,count(pb_size) AS s_size,count(color_id) AS c_color ,count(pb_thick) AS pb_thick
		FROM product 
		INNER JOIN plumbling ON product.product_id = plumbling.product_id 
		
		WHERE product_name = '$name' AND brand_id = '$brand' AND pb_size = '$size' AND pb_thick = '$thick' AND class = '$class' AND color_id = '$color' AND plumbling.product_id != '$idproduct'";
		$query = mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
		$row  = mysqli_fetch_array($query);
		$p_name = $row['p_name'];
        $b_brand = $row['b_brand'];
        $c_class = $row['c_class'];
		$s_size = $row['s_size'];
		$c_color = $row['c_color'];
		$pb_thick = $row['pb_thick'];

        if ($p_name == '0' && $b_brand == '0' && $c_class == '0' && $s_size == '0' && $c_color == '0' && $pb_thick == '0' && $color != '' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $class != '' && $size != '' && $repoint != '') {
            $sql1 = "UPDATE product SET product_name='".$name."',brand_id='".$brand."', unit_id='".$unit."' ,product_saleprice='".$saleprice."',product_reorder='".$repoint."',product_detail='".$detail."' WHERE product_id='".$idproduct."'";
            if (mysqli_query($conn, $sql1)==true) {
                $sql2 = "UPDATE plumbling SET mt_id='".$material."',color_id='".$color."', pb_size='".$size."' ,pb_thick='".$thick."' WHERE product_id='".$idproduct."'";
                // echo $sql2;
                if(mysqli_query($conn, $sql2) ==true){
//เช็คเมื่อมีการรับค่า class เข้า
                    $sql3 = "UPDATE pvc SET class='".$class."' WHERE product_id='".$idproduct."'";
                    // echo $sql3;
                    mysqli_query($conn, $sql3);
                    $sql4 = "INSERT INTO pvc (product_id,class) VALUES ('$idproduct','$class')";
                    mysqli_query($conn, $sql4);
                    
                    echo "<script>";
                    echo "alert('Update Success!');";
                    echo "window.location.href='pb_show.php';";
                    echo "</script>";
                }
            } else {
                echo "<script>";
                echo "alert('ERROR!');";
                echo "window.location.href='pb_edit.php?p_id=$idproduct';";
                echo "</script>";
            }
        }elseif ($class == '' && $p_name == '0' && $b_brand == '0' && $s_size == '0' && $c_color == '0' && $pb_thick == '0' && $color != '' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $size != '' && $repoint != '') {
            $sql1 = "UPDATE product SET product_name='".$name."',brand_id='".$brand."', unit_id='".$unit."' ,product_saleprice='".$saleprice."',product_reorder='".$repoint."',product_detail='".$detail."' WHERE product_id='".$idproduct."'";
            if (mysqli_query($conn, $sql1)==true) {

                $sql2 = "UPDATE plumbling SET mt_id='".$material."',color_id='".$color."', pb_size='".$size."' ,pb_thick='".$thick."' WHERE product_id='".$idproduct."'";
                if(mysqli_query($conn, $sql2) ==true){
//ถ้าไม่มีการรับ class เข้าให้ลบ class อันเก่าออก
                    $sql3="DELETE FROM pvc WHERE product_id = '$idproduct'";
                    mysqli_query($conn, $sql3);
                    echo "<script>";
                    echo "alert('Update Success!');";
                    echo "window.location.href='pb_show.php';";
                    echo "</script>";
                }
            } else {
                echo "<script>";
                echo "alert('ERROR!');";
                echo "window.location.href='pb_edit.php?p_id=$idproduct';";
                echo "</script>";
            }
        }
        elseif($name == '' || $saleprice == '' || $unit == '' || $brand == '' || $class == '' || $size == '' || $repoint == '' || $color == '' || $thick == '' || $detail == '' || $material == ''){
            echo "<script>";
            echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
            echo "window.location.href='pb_edit.php?p_id=$idproduct';;";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('มีสินค้านี้ในระบบแล้วกรุณากรอกใหม่อีกครั้ง!');";
            echo "window.location.href='pb_edit.php?p_id=$idproduct';";
            echo "</script>";
        }
    }
