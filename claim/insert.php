<?php

use Mpdf\Tag\IndexEntry;

session_start();
include('../connectdb.php');

if (isset($_POST['item_product'])) {

    $sql = mysqli_query($conn, "SELECT concat('CL',LPAD(ifnull(SUBSTR(max(cl_id),3,6),'0')+1,4,'0')) as CL_ID FROM claim_ctm_h");
    $row = mysqli_fetch_array($sql);
    $id = $row['CL_ID'];
    if ($_POST['sale_slip'] != '') {

        if ($_POST['item_radio'] == "shop") { // เคลมกับทางร้าน

            $data_h = array(':date' => $_POST['date'], ':customer' => $_POST['customer']);

            $sql = "INSERT INTO claim_ctm_h (cl_id, cl_date, emp_id, cus_id, cl_status, choice_status) VALUES ('$id', :date, '" . $_SESSION['empid'] . "', :customer, '4','shop')";

            $statement = $connect->prepare($sql);

            $check1 = $statement->execute($data_h);
            // print_r($data_h);

            if ($check1) {

                for ($i = 0; $i < count($_POST['item_no']); $i++) {
                    $lot_balance = $_POST['lot_balance'][$i];
                    $amount = $_POST['amount'][$i];
                    $product = $_POST['item_product'][$i];
                    $lot = $_POST['lot_order'][$i];

                    $data_l = array(':no' => $_POST['item_no'][$i], ':product' => $_POST['item_product'][$i], ':amount' => $_POST['amount'][$i], ':price' => $_POST['price'][$i], ':cause' => $_POST['cause'][$i]);

                    $sql = "INSERT INTO claim_ctm_list (cl_id, cl_no, product_id, cl_amount, cl_price, cl_cause,s_id) VALUES ('$id', :no, :product, :amount, :price, :cause,'" . $_POST['sale_slip'] . "')";

                    $statement = $connect->prepare($sql);

                    $check2 = $statement->execute($data_l);
                    $balance = $lot_balance - $amount;
                    // print_r($balance);
                    while ($amount > 0) {

                        $balance = $lot_balance - $amount; //คงเหลือ
                        // print_r($balance);
                        if ($balance < 0) {

                            $statement = $connect->prepare("UPDATE lot SET lot_balance='0' WHERE product_id = '$product' AND lot_order = '$lot'");
                            $lot1 = $statement->execute();
                            $amount -= $lot_balance;
                            // print_r($statement);
                        } else {
                            //check 2
                            $data = array(':product' => $_POST['item_product'][$i]); //จำนวนขายต่อรายการ
                            $querylot = $connect->prepare("SELECT product.*,min(lot.lot_order) AS lot_order,lot.lot_number,lot.lot_balance
                                                                FROM product
                                                                INNER JOIN lot ON lot.product_id = product.product_id
                                                                WHERE product.product_id = '$product' AND lot.lot_balance > 0
                                                                ORDER BY lot.lot_order ASC");
                            $querylot->execute();
                            $row = $querylot->fetch(PDO::FETCH_ASSOC);
                            // print_r($querylot);
                            $lot_order = $row['lot_order'];
                            $lot_balance = $row['lot_balance'];
                            $pd_stock = $row['product_stock'];
                            $balance = $lot_balance - $amount; //คงเหลือที่วนเช็ครอบ2
                            $cutstock = $pd_stock - $amount; //จำนวนตัด

                            //update lot
                            $stm1 = $connect->prepare("UPDATE lot SET lot.lot_balance='$balance' WHERE product_id = '$product' AND lot_order = '$lot_order'");
                            $lot2 = $stm1->execute();
                            $amount = 0;
                            // print_r($stm1);

                            //update stock
                            $stm2 = $connect->prepare("UPDATE product SET product_stock='$cutstock' WHERE product_id='$product'");
                            $stock = $stm2->execute();
                            // print_r($stm2);
                        }
                    }
                }
                if ($check1 && $check2 || $lot1 || $lot2 || $stock) {
                    echo "เพิ่มข้อมูลสำเร็จ";
                } else {
                    echo "ข้อมูลผิดพลาด";
                }
            }
        } else if ($_POST['item_radio'] == "company") { // เคลมกับทางบริษัทคู่ค้า

            $data_h = array(':date' => $_POST['date'], ':customer' => $_POST['customer']);

            $sql = "INSERT INTO claim_ctm_h (cl_id, cl_date, emp_id, cus_id, cl_status, choice_status) VALUES ('$id', :date, '" . $_SESSION['empid'] . "', :customer, '0', 'cpn')";

            $statement = $connect->prepare($sql);

            $check1 = $statement->execute($data_h);

            if ($check1) {

                for ($i = 0; $i < count($_POST['item_no']); $i++) {

                    $data_l = array(':no' => $_POST['item_no'][$i], ':product' => $_POST['item_product'][$i], ':amount' => $_POST['amount'][$i], ':price' => $_POST['price'][$i], ':cause' => $_POST['cause'][$i]);

                    $sql = "INSERT INTO claim_ctm_list (cl_id, cl_no, product_id, cl_amount, cl_price, cl_cause,s_id) VALUES ('$id', :no, :product, :amount, :price, :cause,'" . $_POST['sale_slip'] . "')";

                    $statement = $connect->prepare($sql);

                    $check2 = $statement->execute($data_l);
                    // print_r($data_l);
                }
                if ($check1 && $check2) {
                    echo "เพิ่มข้อมูลสำเร็จ";
                } else {
                    echo "ข้อมูลผิดพลาด";
                }
            }
        }
    } else {
        exit();
        mysqli_close($conn);
    }
}

if (isset($_POST['item_pd'])) {

    if ($_POST['item_no'] != '' && $_POST['item_pd'] != '' && $_POST['item_company'] != '' && $_POST['cl_id'] != '') {

        $sql = mysqli_query($conn, "SELECT concat('CCP',LPAD(ifnull(SUBSTR(max(ccp_id),4,7),'0')+1,4,'0')) as CCP_ID FROM claim_cpn_h");
        $row = mysqli_fetch_array($sql);
        $id = $row['CCP_ID'];

        $date = date('Y-m-d');

        $sql = "INSERT INTO claim_cpn_h (ccp_id, ccp_date, emp_id, ccp_status) VALUES ('$id', '$date', '" . $_SESSION['empid'] . "', '0')";

        $statement = $connect->prepare($sql);

        $check1 = $statement->execute();

        // echo ($check);

        if ($check1) {
            for ($i = 0; $i < count($_POST['item_no']); $i++) {

                $data_l = array(':ccp_no' => $_POST['item_no'][$i], ':cl_id' => $_POST['cl_id'], ':company' => $_POST['item_company'][$i]);

                $sql = "INSERT INTO claim_cpn_list (ccp_id, cl_id, ccp_no ,cpn_id,ccp_status) VALUES ('$id', :cl_id, :ccp_no, :company,'0')";

                $statement = $connect->prepare($sql);

                $check2 = $statement->execute($data_l);

                // print_r($data_l);
            }
            if ($check2) {

                $sql = "UPDATE claim_ctm_h SET cl_status='1' WHERE cl_id = '" . $_POST['cl_id'] . "'";

                $statement = $connect->prepare($sql);

                $check3 = $statement->execute();
                // print_r($data_id);
            }
        }
        if ($check1 && $check2 && $check3) {
            echo "อัพเดทการส่งเคลมกับบริษัทคู่ค้าสำเร็จ";
        } else if (!$check1) {
            echo "ข้อมูลใบเคลมผิดพลาด";
        } else if (!$check2) {
            echo "ข้อมูลรายการใบเคลมผิดพลาด";
        } else if (!$check3) {
            echo "ข้อมูลอัพเดทสถานะใบเคลมลูกค้าผิดพลาด";
        }
    } else {
        echo "ไม่มีรายการ";
    }
}

if (isset($_POST['item_pd_c'])) {
    $sql = mysqli_query($conn, "SELECT concat('CR',LPAD(ifnull(SUBSTR(max(cr_id),3,6),'0')+1,4,'0')) as CR_ID FROM claim_receive_h");
    $row = mysqli_fetch_array($sql);
    $id = $row['CR_ID'];

    if ($_POST['ccp_id'] != '' && $_POST['item_no'] != '' && $_POST['item_amount'] != '' && $_POST['item_pd_c'] != '') {
        // insert ใบการรับ
        $sql = "INSERT INTO claim_receive_h (cr_id, cr_date, emp_id, cr_status) VALUES ('$id', '" . $_POST['date_rc'] . "', '" . $_SESSION['empid'] . "', '0')";
        $statement = $connect->prepare($sql);
        $check1 = $statement->execute();

        if ($check1) {

            for ($i = 0; $i < count($_POST['item_no']); $i++) {
                // insert รายการรับ
                $data_l = array(':no' => $_POST['item_no'][$i], ':amount' => $_POST['item_amount'][$i], ':ccp_id' => $_POST['hidden_id']);
                $sql = "INSERT INTO claim_receive_list (cr_id, cr_no, cr_amount, ccp_id) VALUES ('$id', :no, :amount , :ccp_id)";
                $statement = $connect->prepare($sql);
                $check2 = $statement->execute($data_l);

                if ($check2) {
                    // update สต็อก
                    $data_s = array(':product_id' => $_POST['item_pd_c'][$i], ':amount' => $_POST['item_amount'][$i]);
                    $query = "UPDATE product SET product_stock=product_stock+:amount WHERE product_id = :product_id";
                    $statement = $connect->prepare($query);
                    $check3 = $statement->execute($data_s);

                    if ($check3) {
                        // update ล็อต
                        $data_lot = array(':product_id' => $_POST['item_pd_c'][$i], ':amount' => $_POST['item_amount'][$i], ':lot_order' => $_POST['lot_order'][$i]);
                        $query = "UPDATE lot SET lot_number=lot_number+:amount WHERE product_id = :product_id AND lot_order = :lot_order";
                        $statement = $connect->prepare($query);
                        $check4 = $statement->execute($data_lot);

                        if ($check4) {

                            $data = array(':ccp_id' => $_POST['hidden_id']);
                            // select จากจำนวนที่ส่ง และ จำนวนรับ เพื่อ อัพเดทสถานะรายการ
                            $query = "
                            SELECT totals , cl_amount
                            FROM claim_cpn_h c1
                            INNER JOIN claim_cpn_list c2 ON c2.ccp_id = c1.ccp_id
                            LEFT JOIN
                                (
                                SELECT claim_receive_list.ccp_id,claim_receive_list.cr_no,SUM(claim_receive_list.cr_amount) AS totals
                                FROM claim_receive_list
                                WHERE claim_receive_list.ccp_id = :ccp_id
                                GROUP BY claim_receive_list.cr_no
                                ) c3 ON c3.ccp_id = c2.ccp_id AND c3.cr_no = c2.ccp_no
                            INNER JOIN claim_ctm_list c5 ON c5.cl_id = c2.cl_id AND c5.cl_no = c2.ccp_no
                            INNER JOIN claim_ctm_h c6 ON c6.cl_id = c5.cl_id
                            WHERE c1.ccp_id = :ccp_id AND c2.ccp_status = 0";
                            $statement = $connect->prepare($query);
                            $statement->execute($data);

                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

                                if ($row['totals'] >= $row['cl_amount']) {
                                    // update สถานะรายการ
                                    $data = array(':ccp_id' => $_POST['hidden_id'], ':item_no' => $_POST['item_no'][$i]);
                                    $query = "UPDATE claim_cpn_list SET ccp_status='1' WHERE ccp_id = :ccp_id AND ccp_no = :item_no";
                                    $statement = $connect->prepare($query);
                                    $check5 = $statement->execute($data);

                                    // select สถานะรายการ เช็คว่าครบไหม
                                    if ($check5) {
                                        $data = array(':ccp_id' => $_POST['hidden_id']);
                                        $cl_id = array(':cl_id' => $_POST['cl_id']);
                                        $cr_id = array(':cr_id' => $_POST['cr_id']);
                                        $query = "
                                        SELECT c3.ID,count(c2.ccp_status) AS STATUS
                                        FROM claim_cpn_h c1
                                        INNER JOIN claim_cpn_list c2 ON c2.ccp_id = c1.ccp_id
                                        INNER JOIN
                                        (
                                            SELECT claim_cpn_list.ccp_id,count(claim_cpn_list.ccp_id) AS ID
                                            FROM claim_cpn_list
                                            WHERE claim_cpn_list.ccp_id = :ccp_id
                                        ) c3 ON c3.ccp_id = c2.ccp_id
                                        WHERE c1.ccp_id = :ccp_id AND c2.ccp_status = 1";

                                        $statement = $connect->prepare($query);
                                        $statement->execute($data);
                                        $row = $statement->fetch(PDO::FETCH_ASSOC);

                                        if ($row['ID'] === $row['STATUS']) { // check count

                                            $sql = "
                                            SELECT c3.cr_id
                                            FROM claim_cpn_list c1
                                            LEFT JOIN claim_ctm_list c2 ON c2.cl_id = c1.cl_id AND c2.cl_no = c1.ccp_no
                                            LEFT JOIN claim_receive_list c3 ON c3.cr_no = c1.ccp_no AND c3.ccp_id = c1.ccp_id
                                            WHERE c1.ccp_id = :ccp_id
                                            GROUP BY c1.ccp_no AND c3.cr_id";
                                            $statement = $connect->prepare($sql);
                                            $statement->execute($data);
                                            while ($row_cr = $statement->fetch(PDO::FETCH_ASSOC)) {
                                                $cr_id = $row_cr['cr_id'];
                                                $query = "UPDATE claim_receive_h SET cr_status='1' WHERE cr_id = '$cr_id'";
                                                $statement = $connect->prepare($query);
                                                $statement->execute();
                                            }

                                            $query = "UPDATE claim_cpn_h SET ccp_status='1' WHERE ccp_id = :ccp_id";
                                            $statement = $connect->prepare($query);
                                            $statement->execute($data);

                                            $query = "UPDATE claim_ctm_h SET cl_status='2' WHERE cl_id = :cl_id";
                                            $statement = $connect->prepare($query);
                                            $statement->execute($cl_id);

                                            // echo "<script>console.log('Debug Rows: " . $row2['ID'] .":". $row2['STATUS'] . "' );</script>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($check1 && $check2 && $check3 && $check4) {
                echo "อัพเดทรายการเคลมสำเร็จ";
            } else {
                echo "ข้อมูลผิดพลาด";
            }
        }
    }
}

if (isset($_POST['pd_lot'])) {
    // print_r($_POST['pd_lot']);
    // print_r($_POST['no_list']);
    // print_r($_POST['lot_balance']);
    // print_r($_POST['receive_number']);
    // print_r($_POST['lot']);
    if ($_POST['pd_lot'] != '' && $_POST['no_list'] != '' && $_POST['lot_balance'] != '' && $_POST['receive_number'] != '' && $_POST['lot'] != '') {

        for ($i = 0; $i < count($_POST['pd_lot']); $i++) {
            $cl_id = $_POST['cl_id'];
            $lot_balance = $_POST['lot_balance'][$i];
            $receive_number = $_POST['receive_number'][$i];
            $product = $_POST['pd_lot'][$i];
            $lot = $_POST['lot'][$i];

            // print_r($lot_balance);
            // print_r($receive_number);
            while ($receive_number > 0) {

                $balance = $lot_balance - $receive_number; //คงเหลือที่วนเช็ครอบ1

                // print_r($balance);
                if ($balance < 0) {
                    //check 1
                    $statement = $connect->prepare("UPDATE lot SET lot.lot_balance = '0' WHERE product_id = '$product' AND lot_order = '$lot'");
                    $lot1 = $statement->execute();
                    // print_r($statement);
                    $receive_number -= $lot_balance;
                } else {
                    //check 2
                    $querylot = $connect->prepare("SELECT product.*,min(lot.lot_order) AS lot_order,lot.lot_number,lot.lot_balance
                                                    FROM product
                                                    INNER JOIN lot ON lot.product_id = product.product_id
                                                    WHERE product.product_id = '$product' AND lot.lot_balance > 0
                                                    ORDER BY lot.lot_order ASC");
                    $querylot->execute();
                    $row = $querylot->fetch(PDO::FETCH_ASSOC);
                    // print_r($querylot);
                    $lot_order = $row['lot_order'];
                    $lot_balance = $row['lot_balance'];
                    $pd_stock = $row['product_stock'];
                    $balance = $lot_balance - $receive_number; //คงเหลือที่วนเช็ครอบ2
                    $cutstock = $pd_stock - $receive_number; //จำนวนตัด

                    //update lot
                    $stm1 = $connect->prepare("UPDATE lot SET lot.lot_balance='$balance' WHERE product_id = '$product' AND lot_order = '$lot_order'");
                    $lot2 = $stm1->execute();
                    $receive_number = 0; //clear

                    //update stock
                    $stm2 = $connect->prepare("UPDATE product SET product_stock='$cutstock' WHERE product_id='$product'");
                    $stock = $stm2->execute();

                    //update stock
                    $stm3 = $connect->prepare("UPDATE claim_ctm_h SET cl_status='3' WHERE cl_id='$cl_id'");
                    $status = $stm3->execute();
                    // print_r($stm);
                }
            }
        }
        if ($stock && $status) {
            echo "คืนสินค้าสำเร็จ";
        } else {
            echo "ข้อมูลผิดพลาด";
        }
    }
}
