<?php
include('../connectdb.php');
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");
if (isset($_POST['product'])) {

    $id_pmt = $_POST['id_pmt'];
    $name = $_POST['pmt_name'];
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];
    $discount = $_POST['discount'];

    if ($id_pmt != '' && $name != '' && $date_start != '' && $date_end != '' && $discount != '' && $_POST['product'] != '') {  //check ว่ามีข้อมูลที่เพิ่มในรายการไหม

        $sql = "UPDATE promotion SET promotion_name='$name',date_start='$date_start',date_end='" . $date_end . "',promotion_discount='$discount' WHERE promotion_id='" . $id_pmt . "'";

        if ($result = mysqli_query($conn, $sql)) {
            //delete promotion
            $del_query = "DELETE FROM product_promotion WHERE promotion_id ='$id_pmt'";

            $statement = $connect->prepare($del_query);

            $check_del = $statement->execute();
            //เพิ่มรายการร่วมโปรโมชั่น (ส่วนที่ดึงฐานข้อมูล)
            for ($j = 0; $j < count($_POST['fetch_product']); $j++) {

                $data = array(':product' => $_POST['fetch_product'][$j]);

                $ins_query = "INSERT INTO product_promotion (product_id,promotion_id) VALUES (:product,'$id_pmt')";

                $statement = $connect->prepare($ins_query);

                $check_insert1 = $statement->execute($data);
            }
            //เพิ่มรายการร่วมโปรโมชั่น (ส่วนเพิ่มใหม่)
            for ($i = 0; $i < count($_POST['product']); $i++) {

                $data = array(':product' => $_POST['product'][$i]);

                $ins_query = "INSERT INTO product_promotion (product_id,promotion_id) VALUES (:product,'$id_pmt')";

                $statement = $connect->prepare($ins_query);

                $check_insert2 = $statement->execute($data);
            }

            if ($result) {
                echo ('อัพเดทโปรโมชั่นสำเร็จ');
            } else if (!$result) {
                echo ('ข้อมูลโปรโมชั่นผิดพลาด');
            } else if (!$check_insert1) {
                echo ('รายการเพิ่มสินค้าร่วมรายการผิดพลาด');
            } else if (!$check_insert2) {
                echo ('รายการเพิ่มสินค้าร่วมรายการผิดพลาด');
            } else {
                echo ('ข้อมูลผิดพลาด');
            }
        }
    } else if ($id_pmt != '' && $name != '' && $date_start != '' && $date_end != '' && $discount != '' && $_POST['product'] == '') { //check ข้อมูลที่ไม่มีการเพิ่มในรายการ

        $sql = "UPDATE promotion SET promotion_name='$name',date_start='$date_start',date_end='" . $date_end . "',promotion_discount='$discount' WHERE promotion_id='" . $id_pmt . "'";

        if ($result = mysqli_query($conn, $sql)) {

            $del_query = "DELETE FROM product_promotion WHERE promotion_id ='$id_pmt'";

            $statement = $connect->prepare($del_query);

            $check_del = $statement->execute();

            for ($j = 0; $j < count($_POST['fetch_product']); $j++) {

                $data = array(':product' => $_POST['fetch_product'][$j]);

                $ins_query = "INSERT INTO product_promotion (product_id,promotion_id) VALUES (:product,'$id_pmt')";

                $statement = $connect->prepare($ins_query);

                $check_insert = $statement->execute($data);
            }

            if ($result) {
                echo ('อัพเดทโปรโมชั่นสำเร็จ');
            } else if (!$result) {
                echo ('ข้อมูลโปรโมชั่นผิดพลาด');
            } else if (!$check_del) {
                echo ('การลบผิดพลาด');
            } else if (!$check_insert) {
                echo ('การเพิ่มรากการสินค้าผิดพลาด');
            }  else {
                echo ('ข้อมูลผิดพลาด');
            }
        }
    }
}else{
    echo "ไม่พบข้อมูล";
}
