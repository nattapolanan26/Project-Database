<?php include('../connectdb.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html" ; charset="utf-8">
</head>

<body>
    <?php
    if (isset($_POST['addcpn'])) {
        $name = $_POST['name'];
        $province = $_POST['province'];
        $amphur = $_POST['amphur'];
        $district = $_POST['district'];
        $email = $_POST['email'];
        $zipcode = $_POST['zipcode'];
        $address = $_POST['address'];



        $sqlcheck = "SELECT cpn_name FROM company WHERE cpn_name = '" . $_POST['name'] . "'";
        $resultcheck = mysqli_query($conn, $sqlcheck);
        $namerow = mysqli_fetch_array($resultcheck);
        if($name || $province || $amphur || $district || $email || $zipcode || $address != ''){
        if ($namerow > 0) {
            echo "<script>"; //คำสั่งสคิป
            echo "alert('ชื่อบริษัทซ้ำ กรุณากรอกใหม่อีกครั้ง!');"; //แสดงหน้าต่างเตือน
            echo "window.location.href='cpn_form_insert.php';"; //แสดงหน้าก่อนนี้
            echo "</script>";
        } else {
            $idSQL = "SELECT concat('CPN-',LPAD(ifnull(SUBSTR(max(cpn_id),5,7),'0')+1,3,'0')) as CPN_ID FROM company";
            if ($conn->query($idSQL) == true) {
                $resultid = mysqli_query($conn, $idSQL);
                $row = mysqli_fetch_array($resultid);
                $id = $row['CPN_ID'];
            }
            $sql = "INSERT INTO company (cpn_id,cpn_name,cpn_email,province_id,amphur_id,district_id,zipcode_id,cpn_address) 
            VALUES ('$id','$name','$email','$province','$amphur','$district','$zipcode','$address')";
            // echo $sql;
            if (mysqli_query($conn, $sql)) {
                if (isset($_POST['tel_list'])) {
                    foreach ($_POST['tel_list'] as $rowtel) {
                        $sqltel = "INSERT INTO tel_company (cpn_id,cpn_tel) VALUES ('$id','$rowtel')";
                        mysqli_query($conn, $sqltel);
                        // echo $sqltel;
                    }
                }
                echo "<script>"; //คำสั่งสคิป
                echo "window.location.href='cpn_show.php';"; //แสดงหน้าก่อนนี้
                echo "</script>";
            }
            }
        }else{
            echo "<script>"; //คำสั่งสคิป
            echo "alert('กรุณากรอกข้อมูลให้ครบถ้วน!');"; //แสดงหน้าต่างเตือน
            echo "window.location.href='cpn_form_insert.php';"; //แสดงหน้าก่อนนี้
            echo "</script>";
        }
    }
    ?>
</body>

</html>