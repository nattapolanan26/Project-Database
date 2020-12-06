<?php session_start();
include('../connectdb.php');
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");
date_default_timezone_set('Asia/Bangkok');
$date = date('Y-m-d H:i:s');

if (isset($_POST["approve_id"])) {

    $result = mysqli_query($conn, "SELECT concat('PO',LPAD(ifnull(SUBSTR(max(order_id),3,8),'0')+1,6,'0')) as PO_ID FROM orderproduct");
    $row = mysqli_fetch_assoc($result);
    $new_id = $row['PO_ID'];

    // $check = array();  // สร้าง Array

    for ($i = 0; $i < count($_POST["approve_no"]); $i++) {
        //Array ในชุดหัวใบเสนอ
        $id = array(':id' => $_POST['approve_id'][$i]);
        //Array ในชุดใบเสนอ
        $data = array(
            ':item_id'   => $_POST['approve_id'][$i],
            ':item_no' => $_POST['approve_no'][$i],
            ':item_numapprove' => $_POST['num_approve'][$i],
        );
        //Array ในชุดใบสั่งซื้อ
        $data2 = array(
            ':item_no2' => $_POST['approve_no'][$i],
            ':item_numapprove2' => $_POST['num_approve'][$i],
            ':item_product' => $_POST['approve_product'][$i],
            ':item_company' => $_POST['approve_company'][$i],
        );

        if ($data != '') {
            $sql1 = "UPDATE detailquotation 
            SET num_approve = :item_numapprove
            WHERE quo_id = :item_id AND quo_order = :item_no";
            $statement1 = $connect->prepare($sql1);

            if ($statement1->execute($data)) {
                // update table สถานะใบสั่งซื้อ
                $sql2 = "UPDATE quotation 
                SET status = '1' 
                WHERE quotation.quo_id = :id";

                $statement2 = $connect->prepare($sql2);

                if ($statement2->execute($id)) {
                    // insert table ใบสั่งซื้อ
                    $sql3 = "INSERT INTO orderproduct (order_id, date, emp_id,status_receive) VALUES ('$new_id', '$date', '" . $_SESSION['empid'] . "','0')";

                    $statement3 = $connect->prepare($sql3);

                    $statement3->execute($data);

                    // insert table รายการใบสั่งซื้อ
                    $sql4 = "INSERT INTO detailorderpro (order_id, order_no, product_id, cpn_id, number, status_receive) VALUES ('$new_id', :item_no2, :item_product , :item_company, :item_numapprove2,'0')";

                    $statement4 = $connect->prepare($sql4);

                    if($statement4->execute($data2)){
                        echo "ทำรายการทั้งหมดสำเร็จ"; 
                    }
                    // $sql4;
                    print_r($data2);
                }
            }
        }
    }
}
