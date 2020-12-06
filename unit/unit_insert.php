<?php include('../connectdb.php'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<body>
<?php
    if(isset($_POST['unitadd'])) {
        $name=$_POST['name'];
       
        $idsql = "SELECT concat('U',LPAD(ifnull(SUBSTR(max(unit_id),2,4),'0')+1,3,'0')) as UNIT_ID FROM unit";
        $resultid=mysqli_query($conn, $idsql);
        $row=mysqli_fetch_array($resultid);
        $id=$row['UNIT_ID'];

        $sqlname="SELECT unit_name FROM unit WHERE unit_name = '$name'";
        $result=mysqli_query($conn,$sqlname);
        $namerows=mysqli_num_rows($result);

        if ($name != '') {
            if ($namerows > 0) {
                echo "<script>"; //คำสั่งสคิป
                echo "alert('Unit ซ้ำ กรุณากรอกใหม่!');"; //แสดงหน้าต่างเตือน
                echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
                echo "</script>";
            }else{
                $sql = "INSERT INTO unit (unit_id,unit_name) VALUES ('$id','$name')";
                if($conn->query($sql)==true) {
                    $result=mysqli_query($conn,$sql);
                    echo "<script>"; //คำสั่งสคิป
                    echo "alert('เพิ่ม Unit สำเร็จ!');"; //แสดงหน้าต่างเตือน
                    echo "window.location.href='unit_show.php';"; //แสดงหน้าก่อนนี้
                    echo "</script>";
                } else {
                    echo "<script>"; //คำสั่งสคิป
                    echo "alert('ผิดพลาด กรุณากรอกข้อมูลใหม่!');"; //แสดงหน้าต่างเตือน
                    echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
                    echo "</script>";
                }
            }
        }else {
            echo "<script>"; //คำสั่งสคิป
            echo "alert('กรุณากรอกข้อมูลให้ครบถ้วน!');"; //แสดงหน้าต่างเตือน
            echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
            echo "</script>";
        }
    }
?>
</body>
</html>