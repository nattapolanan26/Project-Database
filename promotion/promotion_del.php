<?php
include('../connectdb.php');

if (isset($_POST['promotion_id'])) {

    $id_pmt = $_POST['promotion_id'];

    if ($id_pmt != '') {  //check ว่ามีข้อมูลที่เพิ่มในรายการไหม

        //delete สินค้าร่วมรายการ
        $sql = "DELETE FROM product_promotion WHERE promotion_id ='$id_pmt'";

        if ($result1 = mysqli_query($conn, $sql)) {
            //delete โปรโมชั่น
            $sql = "DELETE FROM promotion WHERE promotion_id ='$id_pmt'";

            $result2 = mysqli_query($conn, $sql);
        }
        if ($result1 && $result2) {
            echo ('ลบข้อมูลสำเร็จ');
        } else if(!$result1){
            echo ('ข้อมูลสินค้าร่วมรายการผิดพลาด');
        } else if(!$result2){
            echo ('ข้อมูลโปรโมชั่นผิดพลาด');
        } else {
            echo ('ข้อมูลผิดพลาด');
        }
    }
}else{
    echo "ไม่พบรหัสโปรโมชั่น";
}
