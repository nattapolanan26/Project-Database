<?php include('../connectdb.php'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<body>
<?php
    if (isset($_POST['addproduct'])) {
                $id = $_POST['idpro'];
                $order = $_POST['orderproduct'];
                $color = $_POST['color'];
                $size = $_POST['size'];
                $loworder = $_POST['loworder'];
                $saleprice = $_POST['saleprice'];

                
        
                $sql1 = "INSERT INTO detailproduct (product_order,product_id,loworder,saleprice) VALUES ('$order','$id','$loworder','$saleprice')";
                mysqli_query($conn, $sql1);
                $sql2 = "INSERT INTO color_product (product_id,product_order,color_name) VALUES ('$id','$order','$color')";
                mysqli_query($conn, $sql2);
                $sql3 = "INSERT INTO size_product (product_id,product_order,size_name) VALUES ('$id','$order','$size')";
                if(mysqli_query($conn, $sql3)){
                    echo "<script>"; //คำสั่งสคิป
                    echo "alert('บันทึกสำเร็จ!');"; //แสดงหน้าต่างเตือน
                    echo "window.location.href='show.php';"; //แสดงหน้าก่อนนี้
                    echo "</script>";
                } else {
                    echo "<script>"; //คำสั่งสคิป
                    echo "alert('ผิดพลาด กรุณากรอกข้อมูลใหม่!');"; //แสดงหน้าต่างเตือน
                    echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
                    echo "</script>";
                }
            }
        
?>
</body>
</html>