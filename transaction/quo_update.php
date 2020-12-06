<?php session_start();
include('../connectdb.php');
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");
date_default_timezone_set('Asia/Bangkok');
$date = date('Y-m-d');


if (isset($_POST['fetch_product'])) {
    $result = mysqli_query($conn, "SELECT concat('PO',LPAD(ifnull(SUBSTR(max(order_id),3,8),'0')+1,6,'0')) as PO_ID FROM orderproduct");
    $row = mysqli_fetch_assoc($result);
    $or_id = $row['PO_ID'];
    //update

    // insert table ใบสั่งซื้อ
    $sql_h = "INSERT INTO orderproduct (order_id, date, emp_id,status_receive) VALUES ('$or_id', '$date', '" . $_SESSION['empid'] . "','0')";
    $orderproduct = mysqli_query($conn, $sql_h);

    for ($i = 0; $i < count($_POST['fetch_no']); $i++) {
        $approve = array(
            ':quo_id'  => $_POST["q_id"],
            ':quo_no'  => $_POST["fetch_no"][$i],
            ':product'  => $_POST["fetch_product"][$i],
            ':num_approve'  => $_POST["num_approve"][$i],
        );

        $sql = "UPDATE detailquotation SET num_approve=:num_approve WHERE quo_id = :quo_id AND quo_order = :quo_no AND product_id = :product";

        $statement = $connect->prepare($sql);

        $result = $statement->execute($approve);

        // print_r($approve);

        if ($result) {

            $sql = "UPDATE quotation SET status='1' WHERE quo_id = '" . $_POST["q_id"] . "'";
            $status = mysqli_query($conn, $sql);

            if ($status) {
                $data = array(
                    ':no_order'  => $_POST["fetch_no"][$i],
                    ':product_order'  => $_POST["fetch_product"][$i],
                    ':num_approve_order'  => $_POST["num_approve"][$i],
                    ':company_order' => $_POST['fetch_company'][$i],
                );

                // insert table รายการใบสั่งซื้อ
                $sql_d = "INSERT INTO detailorderpro (order_id, order_no, product_id, cpn_id, number, sum_price, status_receive) VALUES ('$or_id', :no_order, :product_order , :company_order, :num_approve_order,'0', '0')";
                //ราคารวมยังไม่ขึ้น
                $statement_d = $connect->prepare($sql_d);

                $detail_order = $statement_d->execute($data);
                // print_r($data);
            }
        }
    }
    if ($result && $status && $orderproduct && $detail_order) {
        echo ('อนุมัติสำเร็จ');
    } else if (!$result) {
        echo ('ข้อมูลอัพเดทรายการผิดพลาด');
    } else if (!$status) {
        echo ('อัพเดทสถานะผิดพลาด');
    } else if (!$orderproduct) {
        echo ('เพิ่มใบสั่งซื้อผิดพลาด');
    } else if (!$detail_order) {
        echo ('การเพิ่มรายการใบสั่งซื้อผิดพลาด');
    } else {
        echo ('ข้อมูลผิดพลาด');
    }
} else {
    echo "ไม่พบข้อมูล";
}
