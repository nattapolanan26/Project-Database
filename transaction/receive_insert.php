<?php session_start();
include('../connectdb.php');
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");
error_reporting(E_ALL ^ E_NOTICE);
if (isset($_POST['hidden_id'])) {

    $po_id = $_POST['hidden_id'];
    $item_no = $_POST['item_no'];
    $item_number = $_POST['txt_number'];
    $item_product = $_POST['item_product'];
    $item_company = $_POST['item_company'];
    $costprice = $_POST['costprice'];
    $sql = mysqli_query($conn, "SELECT concat('RP',LPAD(ifnull(SUBSTR(max(rp_id),3,7),'0')+1,5,'0')) as RP_ID FROM receiveproduct");
    $row = mysqli_fetch_array($sql);
    $rp_id = $row['RP_ID'];

    if ($po_id != '') {

        $date = array(':date' => $_POST['date']);
        //เพิ่มหัวใบรับสินค้า
        $sql = "INSERT INTO receiveproduct (rp_id, date, emp_id) VALUES ('$rp_id', :date, '" . $_SESSION['empid'] . "')";

        $statement = $connect->prepare($sql);

        $rc = $statement->execute($date);

        if ($rc) {

            for ($i = 0; $i < count($_POST['item_no']); $i++) {
                //เพิ่มรายการใบรับสินค้า
                $data = array(':item_no' => $item_no[$i], ':item_product' => $item_product[$i], ':item_number' => $item_number[$i], ':item_po_id' => $po_id, ':sum_price' => $costprice[$i] * $item_number[$i]);
                $query = "INSERT INTO detailreceivepro (rp_id, rp_no, product_id, rp_number,order_id, rp_sumprice) VALUES ('$rp_id', :item_no, :item_product, :item_number,:item_po_id ,:sum_price)";
                $statement = $connect->prepare($query);
                $detail_rc = $statement->execute($data);
                // print_r($data);
                if ($detail_rc) {
                    //อัพเดทสต็อกสินค้า
                    $data_stock = array(':item_product' => $item_product[$i], ':item_number' => $item_number[$i]);
                    $query = "UPDATE product SET product_stock=product_stock+:item_number WHERE product_id = :item_product";
                    $statement = $connect->prepare($query);
                    $stock = $statement->execute($data_stock);

                    if ($stock) {
                        //เพิ่มล็อต
                        $gen_lot = array(':item_product' => $item_product[$i]);
                        $data_lot = array(':item_no' => $item_no[$i], ':item_product' => $item_product[$i], ':item_number' => $item_number[$i]);

                        //+1 จากล็อตที่มากที่สุด
                        $sth = $connect->prepare("SELECT ifnull(max(lot_order),'0')+1 as LOT_T FROM lot WHERE lot.product_id = :item_product");
                        $sth->execute($gen_lot);
                        $row = $sth->fetch(PDO::FETCH_ASSOC);
                        $lot = $row['LOT_T'];

                        $query = "INSERT INTO lot (rp_id, rp_no, product_id, lot_order, lot_number, lot_balance, lot_status_exp) VALUES ('$rp_id', :item_no, :item_product, '$lot', :item_number, :item_number, '0')";
                        $statement = $connect->prepare($query);
                        $lots = $statement->execute($data_lot);
                        //เพิ่มวันหมดอายุของล็อต (สี)
                        $new_date = strtotime($_POST['item_date_exp'][$i] . ' year'); //รับค่าปี
                        $d_exp = date('Y-m-d', $new_date);
                        $todate = date('Y-m-d');
                        if ($d_exp > $todate) {
                            $data_exp = array(':item_product' => $_POST['item_product'][$i], ':item_date_exp' => $d_exp);
                            $query = "INSERT INTO lot_exp (product_id, lot_order, exp_date) VALUES (:item_product, '$lot', :item_date_exp)";
                            $statement = $connect->prepare($query);
                            $exp = $statement->execute($data_exp);

                            // print_r($data_exp);
                        }


                        if ($lots) {

                            $p_id = array(':po_id' => $po_id);
                            // select จากจำนวนที่สั่ง และ จำนวนรับ เพื่อ อัพเดทสถานะรายการ
                            $query = "
                            SELECT d1.product_id,d1.number,totals,d1.status_receive
                            FROM orderproduct o1
                            INNER JOIN detailorderpro d1 ON o1.order_id = d1.order_id
                            LEFT JOIN
                            (  
                                SELECT product_id,order_id,rp_no AS rp_no,SUM(rp_number) AS totals
                                FROM detailreceivepro 
                                WHERE order_id = :po_id
                                GROUP BY product_id
                            ) d2 ON d2.order_id = d1.order_id AND d2.product_id = d1.product_id
                            WHERE o1.order_id = :po_id AND d1.status_receive = 0 
                            GROUP BY d1.order_no";
                            $statement = $connect->prepare($query);
                            $sl_num = $statement->execute($p_id);
                            while ($row1 = $statement->fetch(PDO::FETCH_ASSOC)) {

                                if ($row1['number'] == $row1['totals']) {
                                    //  update สถานะรายการ
                                    $data = array(':po_id' => $po_id, ':item_no' => $_POST['item_no'][$i]);
                                    $query = "UPDATE detailorderpro SET status_receive='1' WHERE order_id = :po_id AND order_no = :item_no";
                                    $statement = $connect->prepare($query);
                                    $status = $statement->execute($data);

                                    if ($status) {
                                        $query = "
                                        SELECT d2.ID,COUNT(d1.status_receive) AS STATUS
                                        FROM orderproduct o1
                                        INNER JOIN detailorderpro d1 ON d1.order_id = o1.order_id
                                        LEFT JOIN
                                        (  
                                            SELECT order_id,count(detailorderpro.order_id) AS ID
                                            FROM detailorderpro
                                            WHERE detailorderpro.order_id = :po_id
                                        ) d2 ON d2.order_id = d1.order_id
                                        WHERE o1.order_id = :po_id AND d1.status_receive = 1";

                                        $statement = $connect->prepare($query);
                                        $statement->execute($p_id);
                                        $row2 = $statement->fetch(PDO::FETCH_ASSOC);

                                        if ($row2['ID'] === $row2['STATUS']) { // check count
                                            $query = "UPDATE orderproduct SET status_receive='1' WHERE order_id = :po_id";
                                            $statement = $connect->prepare($query);
                                            $statement->execute($p_id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($rc && $detail_rc && $stock && $lots) {
                echo "เพิ่มข้อมูลสำเร็จ";
            } else {
                echo "ข้อมูลผิดพลาด";
            }
        }
        // print_r($data_exp);
        // echo $query;
    } else {
        echo "ไม่มีรายการรับสินค้า";
    }

    mysqli_close($conn);
    exit();
}
