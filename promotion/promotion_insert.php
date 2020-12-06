<?php
include('../connectdb.php');
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");
if (isset($_POST['hidden_product'])) {

    $sql = mysqli_query($conn, "SELECT concat('PMT',LPAD(ifnull(SUBSTR(max(promotion_id),4,7),'0')+1,4,'0')) as ID FROM promotion");
    $row = mysqli_fetch_array($sql);
    $id_pmt = $row['ID'];

    $name=$_POST['pmt_name'];
    $date_start=$_POST['date_start'];
    $date_end=$_POST['date_end'];
    $discount=$_POST['discount'];

    if($id_pmt != '' && $name != '' && $date_start != '' && $date_end != '' && $discount != ''){

        $sql = "INSERT INTO promotion (promotion_id,promotion_name,date_start,date_end,promotion_discount,promotion_status) VALUES ('$id_pmt','$name','$date_start','$date_end','$discount','0')";

        if($result=mysqli_query($conn, $sql)){

            for($i = 0; $i < count($_POST['hidden_product']) ; $i++){

                $product_arr = array(':hidden_product' => $_POST['hidden_product'][$i]);
    
                $query = "INSERT INTO product_promotion (product_id,promotion_id) VALUES (:hidden_product,'$id_pmt')";
    
                $statement = $connect->prepare($query);
                    
                $check=$statement->execute($product_arr);
            }

            if($check){
                echo('บันทึกโปรโมชั่นสำเร็จ');
            }else{
                echo('ข้อมูลผิดพลาด');
            }

        }
    }else{
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    }   
}else{
    echo "กรุณากรอกข้อมูลให้ครบถ้วน";
}
?>