<?php session_start();
include('../connectdb.php');
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");
date_default_timezone_set('Asia/Bangkok');
$today_date = date('Y-m-d H:i:s');


//quotation_insert.php
if (isset($_POST["product"])) {
   
    $result = mysqli_query($conn, "SELECT concat('Q',LPAD(ifnull(SUBSTR(max(quo_id),2,7),'0')+1,6,'0')) as Q_ID FROM quotation");
    $row = mysqli_fetch_array($result);
    $id = $row['Q_ID'];

    $sql = "INSERT INTO quotation (quo_id, date, status, emp_id,sum_price,vat,total_price) VALUES ('$id', '$today_date', '0', '" . $_SESSION['empid'] . "','" . $_POST['sum_price'] . "','" . $_POST['vat'] . "','" . $_POST['total'] . "')";
    $query1 = mysqli_query($conn, $sql);

    if ($query1 == TRUE) {
        for ($i = 0; $i < count($_POST["item_no"]); $i++) { //วนเช็ค ให้รับค่าตามรายการสินค้า

            $data = array(
                ':id'   => $id,
                ':no'  => $_POST["item_no"][$i],
                ':product'  => $_POST["item_product"][$i],
                ':company'  => $_POST["item_company"][$i],
                ':number'  => $_POST["number"][$i],
                ':sum_price'  => $_POST["sum"][$i]
            );

            $query = "INSERT INTO detailquotation (quo_id, quo_order, product_id, cpn_id, number, num_approve,price) VALUES (:id, :no, :product, :company, :number, '0',:sum_price)";

            $statement = $connect->prepare($query);

            $query2 = $statement->execute($data);

            // print_r($data);
        }
    }
    if($query1 && $query2)
        echo 'เพิ่มข้อมูลสำเร็จ';
    else if(!$query1)
        echo 'ข้อมูลใบเสนอซื้อผิดพลาด';
    else if(!$query2)
        echo 'ข้อมูลรายการใบเสนอซื้อผิดพลาด';
    exit();
}
