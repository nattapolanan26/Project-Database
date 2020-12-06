<?php session_start();
include('../connectdb.php');
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");
date_default_timezone_set('Asia/Bangkok');
$today_date = date('Y-m-d H:i:s');


//quotation_insert.php
if (isset($_POST['quo_insert'])) {
    if (isset($_POST["item_product"])) {
        echo 
        $result = mysqli_query($conn, "SELECT concat('Q',LPAD(ifnull(SUBSTR(max(quo_id),2,7),'0')+1,6,'0')) as Q_ID FROM quotation");
        $row = mysqli_fetch_array($result);
        $id = $row['Q_ID'];

        for ($count = 0; $count < count($_POST["item_product"]); $count++) { //วนเช็ค ให้รับค่าตามรายการสินค้า

            $sql = "INSERT INTO quotation (quo_id, date, status, emp_id) VALUES ('$id', '$today_date', '0', '" . $_SESSION['empid'] . "')";
            mysqli_query($conn, $sql);

            $data = array(
                ':quo_id'   => $id,
                ':no_count'  => $_POST["item_no"][$count],
                ':item_product_id'  => $_POST["item_product"][$count],
                ':item_company_id' => $_POST["item_company"],
                ':number'  => $_POST["item_number"][$count]
            );


            $query = "INSERT INTO detailquotation (quo_id, quo_order, product_id, cpn_id, number, num_approve) VALUES (:quo_id, :no_count, :item_product_id, :item_company_id, :number, '0')";

            $statement = $connect->prepare($query);

            $statement->execute($data);

            print_r($data);
        }

        echo 'success';

        exit();
    }
}
