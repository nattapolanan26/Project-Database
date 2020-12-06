<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<body>
<?php
    include('../connectdb.php');

    if (isset($_POST['updateunit'])) {
        $id=$_POST['id'];
        $name=$_POST['name']; 


        $sqlname = "SELECT unit_id,count(unit_name) AS cs_name FROM unit WHERE unit_name = '$name' AND unit_id != '$id'";
        $query = mysqli_query($conn, $sqlname);
        $row = mysqli_fetch_array($query);
        
        $namerows=$row['cs_name'];

        if ($namerows == '0') {
            $sql = "UPDATE unit SET unit_id='" . $id . "', unit_name='" . $name . "' WHERE unit_id='" . $id . "'";
            if ($result=mysqli_query($conn, $sql)==true) {
                echo "<script>";
                echo "alert('Update Success!');";
                echo "window.location.href='unit_show.php';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('Update Unsuccess!');";
                echo "window.location.href='javascript:history.back(1)';";
                echo "</script>";
            }
        }else{
            echo "<script>";
            echo "alert('มีชื่อยี่ห้อนี้ในระบบแล้ว กรุณาเปลี่ยนใหม่!');";
            echo "window.location.href='javascript:history.back(1)';";
            echo "</script>";
        }
    }
?>
</body>
</html>