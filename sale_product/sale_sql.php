<?php session_start();
include('../connectdb.php');
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");

if (isset($_POST['item_product'])) {

    $product = $_POST['item_product'];

    $sql = mysqli_query($conn, "SELECT concat('S',LPAD(ifnull(SUBSTR(max(s_id),2,7),'0')+1,6,'0')) as S_ID FROM sales_slip");
    $row = mysqli_fetch_array($sql);
    $id = $row['S_ID'];

    if ($product != '') {

        $data_sale_slip = array(':date' => $_POST['date'], ':total_price' => $_POST['total_price'], ':discount' => $_POST['discount'], ':vat' => $_POST['vat'], ':result_total' => $_POST['total'], ':customer' => $_POST['customer']);
        //insert headreceive
        $sql = "INSERT INTO sales_slip (s_id, s_date, emp_id, cus_id, s_sum, s_discount, s_vat, s_total) VALUES ('$id', :date, '" . $_SESSION['empid'] . "', :customer, :total_price, :discount, :vat, :result_total)";

        $statement = $connect->prepare($sql);

        $check_ss = $statement->execute($data_sale_slip);

        if ($check_ss) {

            for ($i = 0; $i < count($_POST['item_product']); $i++) {

                $data_sl = array(':no' => $_POST['item_no'][$i], ':product' => $_POST['item_product'][$i], ':lot' => $_POST['item_lot'][$i], ':number' => $_POST['item_number'][$i], ':total' => $_POST['item_sum'][$i], ':discount' => $_POST['item_discount'][$i]);

                $sql = "INSERT INTO sale_items (s_id, s_no, product_id, lot_order, s_amount, s_total, s_discount) VALUES ('$id', :no, :product, :lot, :number, :total, :discount)";

                $statement = $connect->prepare($sql);

                $check_sl = $statement->execute($data_sl);

                if ($check_sl) {
                    $data = array(
                        ':product' => $_POST['item_product'][$i],
                        ':lot' => $_POST['item_lot'][$i]
                    ); //จำนวนขายต่อรายการ

                    $lot_balance = $_POST['lot_balance'][$i];
                    $number = $_POST['item_number'][$i];

                    while ($number > 0) {

                        $balance = $lot_balance - $number; //คงเหลือที่วนเช็ครอบ1

                        if ($balance < 0) {
                            //check 1
                            $statement = $connect->prepare("UPDATE lot SET lot.lot_balance = '0' WHERE product_id = :product AND lot_order = :lot");
                            $statement->execute($data);
                            // echo "<script>console.log('Debug if: " . $lot_balance . " จำนวน " . $number . " คงเหลือ " . $balance . "' );</script>";
                            $number -= $lot_balance;
                        } else {
                            //check 2
                            $data = array(':product' => $_POST['item_product'][$i]); //จำนวนขายต่อรายการ
                            $querylot = $connect->prepare("SELECT product.*,min(lot.lot_order) AS lot_order,lot.lot_number,lot.lot_balance
                                                            FROM product
                                                            INNER JOIN lot ON lot.product_id = product.product_id
                                                            WHERE product.product_id = :product AND lot.lot_balance > 0 
                                                            ORDER BY lot.lot_order ASC");
                            $querylot->execute($data);
                            $row = $querylot->fetch(PDO::FETCH_ASSOC);

                            $product = $row['product_id'];
                            $lot_order = $row['lot_order'];
                            $lot_balance = $row['lot_balance'];
                            $pd_stock = $row['product_stock'];
                            $balance = $lot_balance - $number; //คงเหลือที่วนเช็ครอบ2
                            $cutstock = $pd_stock - $_POST['item_number'][$i]; //จำนวนตัด

                            //update lot
                            $query = $connect->prepare("UPDATE lot SET lot.lot_balance='$balance' WHERE product_id = '$product' AND lot_order = '$lot_order'");
                            $query->execute();
                            // echo "<script>console.log('Debug else: " . $lot_balance . " จำนวน " . $number . " คงเหลือ " . $balance . "' );</script>";
                            $number = 0; //clear

                            //update stock
                            $stm = $connect->prepare("UPDATE product SET product_stock='$cutstock' WHERE product_id='$product'");
                            $stm->execute();
                        }
                    }
                }
            }
            if ($check_ss && $check_sl) {
                echo "อัพเดทข้อมูลการขายสำเร็จ";
            } else {
                echo "ข้อมูลผิดพลาด";
            }
        }
    }
} else {
    echo "ไม่มีรายการสินค้า";
    exit();
}
