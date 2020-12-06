<?php session_start();
      include('../connectdb.php');
      include('../h.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</style>
</head>
<body>
<form action="#" name="confirmorder" id="confirmorder" method="post">
<input type="hidden" name="date" value="<?=$date=date('Y-m-d H:i:s')?>"/>
<?php
    $id=isset($_GET['order_id']) ? $_GET['order_id'] : '';

    $sqlorderproduct="SELECT * FROM orderproduct op, detailorderpro dop WHERE op.order_id = '".$id."' AND dop.order_id = '".$id."'";
    $result=mysqli_query($conn, $sqlorderproduct);

    $idsql = "SELECT concat('REC',LPAD(ifnull(SUBSTR(max(rp_id),4,7),'0')+1,4,'0')) as REC_ID FROM receiveproduct";
    $resultid = mysqli_query($conn, $idsql);
    $row = mysqli_fetch_array($resultid);
    $rpid = $row['REC_ID'];

    $sqlemp="SELECT * FROM employee INNER JOIN position ON employee.pos_id = position.pos_id WHERE position.pos_id = '".$_SESSION['posid']."'";
    $resultemp=mysqli_query($conn, $sqlemp);
    $rowposemp = mysqli_fetch_array($resultemp);  

    while ($row = mysqli_fetch_array($result)) {
        $orderid=$row['order_id'];
        $order = $row['order_no'];
        $number = $row['number'];
        $price = $row['price'];
        $status=$row['status_order'];
        if (substr($rowposemp['pos_status'], 14, 1)) {
            if ($status == '0') {
                $updatesql = "UPDATE orderproduct SET status_order='1' WHERE order_id='".$id."'";
                mysqli_query($conn, $updatesql);

                $sqlreceivepro = "INSERT INTO receiveproduct (rp_id,date,rp_status,rp_number,emp_id) VALUES ('$rpid','$date','0','0000000000','".$_SESSION['empid']."')";
                mysqli_query($conn, $sqlreceivepro);

                $sqlredetaireceivepro = "INSERT INTO detailreceivepro (rp_id,drp_order,order_id,drp_number,drp_balance) VALUES ('$rpid','$order','$orderid','0','$number')";
                mysqli_query($conn, $sqlredetaireceivepro);
                // echo  $sqlredetaireceivepro;
                echo "<script>"; //คำสั่งสคิป
                echo "alert('ยืนยันใบสั่งซื้อเสร็จสิ้น');"; //แสดงหน้าต่างเตือน
                echo "window.location.href='order_show.php'"; //แสดงหน้าก่อนนี้
                echo "</script>";
            } elseif ($row['status_order'] == '1') {
                echo "<script>"; //คำสั่งสคิป
                echo "alert('ใบสั่งซื้อนี้ได้ทำการยืนยันไปแล้ว!');"; //แสดงหน้าต่างเตือน
                echo "window.location.href='order_show.php'"; //แสดงหน้าก่อนนี้
                echo "</script>";
            }
        }else{
            echo "<script>"; //คำสั่งสคิป
            echo "alert('ตำแหน่งของคุณไม่สามารถอนุมัติใบสั่งซื้อได้!!');"; //แสดงหน้าต่างเตือน
            echo "window.location.href='order_show.php'"; //แสดงหน้าก่อนนี้
            echo "</script>";
        }
    }
    
?>

</form>
</body>
</html>