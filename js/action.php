<?php
include('../connectdb.php');
date_default_timezone_set('Asia/Bangkok');
$today_date = date('Y-m-d H:i:s');

$result = isset($_GET['result']) ? $_GET['result'] : '';
$result_select = isset($_GET['result_id']) ? $_GET['result_id'] : '';
$result_name = isset($_GET['result_name']) ? $_GET['result_name'] : '';
$resultlot = isset($_GET['resultlot']) ? $_GET['resultlot'] : '';
$result_check = isset($_GET['result_check']) ? $_GET['result_check'] : '';

if ($result != '') {
    if ($result == 'amphur') {
        echo "<option value=''>เลือกอำเภอ . . .</option>";
        $sql = "SELECT * FROM tbl_amphures WHERE province_id ='" . $_GET['select_id'] . "' ORDER BY amphur_id ASC";
        $rstTemp1 = mysqli_query($conn, $sql);
        while ($arr_1 = mysqli_fetch_array($rstTemp1)) {
            ?>

            <option value="<?php echo $arr_1['amphur_id'] ?>" <?php if ($arr_1['amphur_id'] == $_GET['point_id']) {
                echo "selฺected";
            }
            ?>>
                <?php echo $arr_1['amphur_name']; ?></option>
    <?php }
    }?>

    <?php if ($result == 'district') {?>
        <select name='district' id='district'>
            <?php
                echo "<option value=''>เลือกตำบล . . .</option>";
        $sql = "SELECT * FROM tbl_districts WHERE amphur_id ='" . $_GET['select_id'] . "' ORDER BY district_id ASC";
        $rstTemp2 = mysqli_query($conn, $sql);
        while ($arr_2 = mysqli_fetch_array($rstTemp2)) {
            ?>
                <option value="<?php echo $arr_2['district_id']; ?>" <?php if ($arr_2['district_id'] == $_GET['point_id']) {
                echo "selected";
            }
            ?>><?php echo $arr_2['district_name']; ?>
                </option>
            <?php }?>
        </select>
    <?php }?>

    <?php if ($result == 'zipcode') {?>
        <select name='zipcode' id='zipcode'>
            <?php
echo "<option value=''>เลือกรหัสไปษณีย์ . . .</option>";
        $sql = "SELECT * FROM tbl_zipcodes WHERE district_id = '" . $_GET['select_id'] . "' ORDER BY zipcode_id ASC";

        $rstTemp3 = mysqli_query($conn, $sql);
        while ($arr_3 = mysqli_fetch_array($rstTemp3)) {
            ?>
                <option value="<?php echo $arr_3['zipcode_id']; ?>" <?php if ($arr_3['zipcode_id'] == $_GET['point_id']) {
                echo "selected";
            }
            ?>><?php echo $arr_3['zipcode']; ?></option>
            <?php }?>
        </select>
<?php }
}?>

<?php if ($result == 'company') {
    ?>
    <select name="item_company[]" id="company" class="form-control item_company">
        <?php
echo "<option value=''>เลือกบริษัทคู่ค้าที่ต้องการเสนอสั่งซื้อ . . .</option>";
    $strSQL = "SELECT * FROM company
    INNER JOIN costprice ON company.cpn_id = costprice.cpn_id
    INNER JOIN product ON product.product_id = costprice.product_id
    WHERE costprice.product_id = '" . $_GET['select_id'] . "'
    GROUP BY company.cpn_id,costprice.cpn_id,costprice.product_id";
    $result = mysqli_query($conn, $strSQL);
    while ($row = mysqli_fetch_array($result)) {
        ?>
            <option value="<?php echo $row['cpn_id']; ?>" <?php if ($row['cpn_id'] == $_GET['point_id']) {
            echo 'selected';
        }
        ?>>
                <?php echo $row['cpn_name'] . " " . number_format($row['costprice'], 2) . " บาท."; ?>
            </option>
        <?php }?>
    </select>
<?php }
//คิวรี่ Search ใบการขาย เพื่อตรวจสอบสินค้า
if (isset($_POST['query'])) {
    $inpText = $_POST['query'];
    $query = "SELECT sell_id FROM sell_product WHERE sell_id LIKE '%$inpText%'";

    $result = mysqli_query($conn, $query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<a href='#' class='list-group-item list-group-item-action'>" . $row['sell_id'] . "</a>";
        }
    } else {
        echo "<p class='list-group-item'>ไม่มีข้อมูล...</p>";
    }
}

//เช็คบริษัทคู่ค้าที่ทำการสั่งซื้อ
if ($result_select == 'cl_company') {?>
    <select name='cl_company' id='cl_company'>
        <?php
$sql = "SELECT company.cpn_name,detailquotation.cpn_id
        FROM company
        INNER JOIN costprice ON company.cpn_id = costprice.cpn_id
        INNER JOIN product ON product.product_id = costprice.product_id
        INNER JOIN lotproduct ON lotproduct.product_id = product.product_id
        INNER JOIN detailreceivepro ON detailreceivepro.rp_id = lotproduct.rp_id
        INNER JOIN detailorderpro ON detailorderpro.order_id = detailreceivepro.order_id
        INNER JOIN detailquotation ON detailquotation.quo_id = detailorderpro.quo_id AND detailquotation.cpn_id = company.cpn_id AND detailquotation.product_id = product.product_id
        INNER JOIN sell_listproduct ON sell_listproduct.lot_order = lotproduct.lot_order
        WHERE lotproduct.product_id = '" . $_GET['select_id'] . "'
        GROUP BY lotproduct.product_id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        ?>
            <option value="<?php echo $row['cpn_id']; ?>" <?php if ($row['cpn_id'] == $_GET['point_id']) {
            echo 'selected';
        }
        ?>>
                <?php echo $row['cpn_name']; ?></option>
        <?php }?>
    </select>
<?php }

//เช็คบริษัทคู่ค้าที่ทำการสั่งซื้อ
if ($result_name == 'company_name') {?>
    <select name='company_name' id='company_name'>
        <?php
$sql = "SELECT company.cpn_name,detailquotation.cpn_id
        FROM company
        INNER JOIN costprice ON company.cpn_id = costprice.cpn_id
        INNER JOIN product ON product.product_id = costprice.product_id
        INNER JOIN lotproduct ON lotproduct.product_id = product.product_id
        INNER JOIN detailreceivepro ON detailreceivepro.rp_id = lotproduct.rp_id
        INNER JOIN detailorderpro ON detailorderpro.order_id = detailreceivepro.order_id
        INNER JOIN detailquotation ON detailquotation.quo_id = detailorderpro.quo_id AND detailquotation.cpn_id = company.cpn_id AND detailquotation.product_id = product.product_id
        INNER JOIN sell_listproduct ON sell_listproduct.lot_order = lotproduct.lot_order
        WHERE lotproduct.product_id = '" . $_GET['select_id'] . "'
        GROUP BY lotproduct.product_id";
    $result = mysqli_query($conn, $sql);
    var_dump($sql);
    while ($row = mysqli_fetch_array($result)) {
        ?>
            <option value="<?php echo $row['cpn_name']; ?>" <?php echo 'selected'; ?>><?php echo $row['cpn_name']; ?></option>
        <?php }?>
    </select>
<?php }

//เช็คบริษัทคู่ค้าที่ทำการสั่งซื้อ
if ($resultlot == 'lotproduct') {?>
    <select name='lotproduct' id='lotproduct'>
        <?php
$sql = "SELECT lotproduct.lot_order,lotproduct.product_id
        FROM lotproduct
        INNER JOIN sell_listproduct ON sell_listproduct.lot_order = lotproduct.lot_order AND sell_listproduct.product_id = lotproduct.product_id
        WHERE lotproduct.product_id = '" . $_GET['select_id'] . "' AND sell_listproduct.lot_order = lotproduct.lot_order AND sell_listproduct.sell_id = '" . $_GET['sale_id'] . "'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        ?>
            <option value="<?php echo $row['lot_order']; ?>" <?php if ($row['product_id'] == $_GET['point_id']) {
            echo 'selected';
        }
        ?>><?php echo $row['lot_order']; ?></option>
        <?php }?>
    </select>
<?php }

//เช็ครหัสสินค้า เพื่อดึงค่ารายชื่อสินค้า
if ($result_check == 'listname') {?>
    <select name='listname' id='listname'>
        <?php
$sql = "SELECT product.*,brand_name,unit_name,cm_volume,cc_volume,tl_size,cs_volume,ct_size,pb_size,pb_thick,class,color_name
        FROM product
        INNER JOIN brand ON brand.brand_id = product.brand_id
        INNER JOIN unit ON unit.unit_id = product.unit_id
        LEFT JOIN cement ON cement.product_id = product.product_id
        LEFT JOIN categorycolor ON categorycolor.product_id = product.product_id
        LEFT JOIN toilet ON toilet.product_id = product.product_id
        LEFT JOIN chemicalsolution ON chemicalsolution.product_id = product.product_id
        LEFT JOIN craftmantool ON craftmantool.product_id = product.product_id
        LEFT JOIN plumbling ON plumbling.product_id = product.product_id

        LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
        WHERE product.product_id = '" . $_GET['select_id'] . "'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['product_name'];
        $brand = $row['brand_name'];
        $class = $row['class'];
        $color = $row['color_name'];
        $size_tl = $row['tl_size'];
        $size_pb = $row['pb_size'];
        $size_ct = $row['ct_size'];
        $thick_pb = $row['pb_thick'];
        $cc = $row['cc_volume'];
        $cs = $row['cs_volume'];
        $cm = $row['cm_volume'];
        ?>
            <option value="<?php echo $name;
        if ($brand != '') {
            echo " " . $brand;
        }
        if ($color != '') {
            echo " " . $color;
        }
        if ($class != '') {
            echo "ชั้น " . $class;
        }
        if ($size_tl != '') {
            echo "ขนาด " . $size_tl;
        }
        if ($size_pb != '') {
            echo "ขนาด " . $size_pb;
        }
        if ($size_ct != '') {
            echo "ขนาด " . $size_ct;
        }
        if ($thick_pb != '') {
            echo " หนา " . $thick_pb;
        }
        if ($cc != '') {
            echo " ปริมาณ " . $cc;
        }
        if ($cs != '') {
            echo " ปริมาณ " . $cs;
        }
        if ($cm != '') {
            echo " ปริมาณ " . $cm;
        } ?>" <?php if ($row['product_id'] == $_GET['point_id']) {
            echo 'selected';
        }
        ?>><?php echo $name;
        if ($brand != '') {
            echo " " . $brand;
        }
        if ($color != '') {
            echo " " . $color;
        }
        if ($class != '') {
            echo "ชั้น " . $class;
        }
        if ($size_tl != '') {
            echo "ขนาด " . $size_tl;
        }
        if ($size_pb != '') {
            echo "ขนาด " . $size_pb;
        }
        if ($size_ct != '') {
            echo "ขนาด " . $size_ct;
        }
        if ($thick_pb != '') {
            echo " หนา " . $thick_pb;
        }
        if ($cc != '') {
            echo " ปริมาณ " . $cc;
        }
        if ($cs != '') {
            echo " ปริมาณ " . $cs;
        }
        if ($cm != '') {
            echo " ปริมาณ " . $cm;
        }
        ?>
            </option>
        <?php }?>
    </select>
<?php }

//บันทึกข้อมูลใบเคลมสินค้า
$dbh = new PDO('mysql:host=localhost;dbname=dbcons', 'root', '1234');

if (isset($_POST['hidden_product'])) {
    $result = mysqli_query($conn, "SELECT CONCAT('CL',LPAD(ifnull(SUBSTR(max(cl_id),3,6),0)+1,4,0)) AS CL_ID FROM claim_product");
    $row = mysqli_fetch_array($result);
    $cl_id = $row['CL_ID'];
    ?>
    <input class="form-control" type="text" name="cl_id" id="cl_id" value="<?php echo $cl_id; ?>" hidden>

<?php
$i = 0;
    for ($count = 0; $count < count($_POST['hidden_product']); $count++) {
        $id = $_POST['cl_id'][$count];
        $claim_date = $_POST['hidden_cl_date'][$count];
        $cpn = $_POST['hidden_company'][$count];
        $no = $_POST['hidden_no'][$count];
        $product = $_POST['hidden_product'][$count];
        $lot = $_POST['hidden_lot'][$count];
        $num = $_POST['hidden_num'][$count];
        $price = $_POST['hidden_price'][$count];
        $cause = $_POST['hidden_cause'][$count];

        if ($cpn == 1) {
            $query_main = $dbh->prepare("INSERT INTO claim_product (cl_id,cl_date,cpn_id,emp_id,cl_status) VALUES (:cl_id,:cl_date,:cpn_id,'" . $_SESSION['empid'] . "','0')");
            $query_main->execute(array(
                ':cl_id' => $id,
                ':cl_date' => $claim_date,
                ':cpn_id' => $cpn,
            ));

            $query_sec = $dbh->prepare("INSERT INTO claim_listproduct (cl_id,cm_id,product_id,lot_order,cm_num,cm_price,cm_cause) VALUES (:cl_id,:cl_no,:cl_product,:lot,:cl_num,:cl_price,:cl_cause)");
            $query_sec->execute(array(
                ':cl_id' => $id,
                ':cl_no' => $no,
                ':cl_product' => $product,
                ':lot' => $lot,
                ':cl_num' => $num,
                ':cl_price' => $price,
                ':cl_cause' => $cause,
            ));
        } else if ($cpn >= 2) {
            $query_main = $dbh->prepare("INSERT INTO claim_product (cl_id,cl_date,cpn_id,emp_id,cl_status) VALUES (:cl_id,:cl_date,:cpn_id,'" . $_SESSION['empid'] . "','0')");
            $query_main->execute(array(
                ':cl_id' => $id,
                ':cl_date' => $claim_date,
                ':cpn_id' => $cpn,
            ));
            $query_sec = $dbh->prepare("INSERT INTO claim_listproduct (cl_id,cm_id,product_id,lot_order,cm_num,cm_price,cm_cause) VALUES (:cl_id,:cl_no,:cl_product,:lot,:cl_num,:cl_price,:cl_cause)");
            $query_sec->execute(array(
                ':cl_id' => $id,
                ':cl_no' => $no,
                ':cl_product' => $product,
                ':lot' => $lot,
                ':cl_num' => $num,
                ':cl_price' => $price,
                ':cl_cause' => $cause,
            ));
        }

        // var_dump($query_sec);
        // print_r($query_sec);
    }
}

//เพิ่่มใบเสนอสั่งซื้อสินค้า
if (isset($_POST['quo_product'])) {

    $i = 0;
    for ($count = 0; $count < count($_POST['quo_product']); $count++) {
        // $product=$_POST['quo_product'][$count];
        // $company=$_POST['quo_company'][$count];
        // $number=$_POST['quo_number'][$count];
        $result = mysqli_query($conn, "SELECT concat('QUO',LPAD(ifnull(SUBSTR(max(quo_id),4,10),'0')+1,7,'0')) as Q_ID FROM quotation");
        $row = mysqli_fetch_array($result);
        $id = $row['Q_ID'];

        $query_quotation = $dbh->prepare("INSERT INTO quotation (quo_id,date,status,emp_id) VALUES (:id,:today_date,'0','" . $_SESSION['empid'] . "')");
        $query_quotation->execute(array(
            ':id' => $id,
            ':today_date' => $today_date,
        ));
    }
}
?>



<?php

if (isset($_POST["type_pmt"])) {
    if ($_POST["type_pmt"] == "pmt_data") {
        $query = "
        SELECT * FROM promotion pmt
        WHERE pmt.date_start <= CURDATE() AND pmt.date_end >= CURDATE()
        ORDER BY promotion_id ASC
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();
        foreach ($data as $row) {
            $datestart = date('d/m/Y', strtotime($row["date_start"]));
            $dateend = date('d/m/Y', strtotime($row["date_end"]));
            $output[] = array(
                'id' => $row["promotion_id"],
                'name' => $row["promotion_name"] . " " . $datestart . " - " . $dateend,
            );
        }
        echo json_encode($output);
    } else {
        $query = "
        SELECT pd.product_id,product_stock,pd.product_name,b.brand_name,u.unit_name,c.color_name,class,tl.tl_size,pb.pb_size,pb.pb_thick,cc.cc_volume,ct.ct_size,cs.cs_volume,cm.cm_volume,lot.*,pmt.*
        FROM product pd
        LEFT JOIN lot ON lot.product_id = pd.product_id
        LEFT JOIN product_promotion pp ON pp.product_id = pd.product_id
        LEFT JOIN promotion pmt ON pmt.promotion_id = pp.promotion_id
        INNER JOIN brand b ON b.brand_id = pd.brand_id
        INNER JOIN unit u ON u.unit_id = pd.unit_id
        LEFT JOIN cement cm ON cm.product_id = pd.product_id
        LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
        LEFT JOIN toilet tl ON tl.product_id = pd.product_id
        LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
        LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
        LEFT JOIN plumbling pb ON pb.product_id = pd.product_id

        LEFT JOIN color c ON c.color_id = cc.color_id OR c.color_id = ct.color_id OR c.color_id = tl.color_id
        WHERE pp.promotion_id = '" . $_POST['promotion_id'] . "' AND lot.lot_balance > 0 AND pd.product_stock != 0 AND pmt.date_start <= CURDATE() AND pmt.date_end >= CURDATE()
        GROUP BY pd.product_id
        ORDER BY pd.product_id ASC
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();
        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["product_id"],
                'name' => $row["product_name"] . " " . $row["brand_name"] . " " . $row["color_name"] . " " . $row["class"] . " " . $row["tl_size"] . " " . $row["pb_size"] . " " . $row["pb_thick"] . " " . $row["cc_volume"] . " " . $row["ct_size"] . " " . $row["cs_volume"] . " " . $row["cm_volume"] . " | ล็อตที่ " . $row['lot_order'] . " สต็อก " . $row['product_stock'],
            );
        }
        if ($data == null) {
            echo "ไม่มีรายการสินค้า";
        } else {
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
        }
    }
}

if (isset($_POST["type_pd"])) {
    if ($_POST["type_pd"] == "product") {
        $query = "
        SELECT lot.*,pd.product_name,pd.product_saleprice,pd.product_stock,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume
        FROM product pd
        INNER JOIN lot ON lot.product_id = pd.product_id
        INNER JOIN brand ON brand.brand_id = pd.brand_id
        INNER JOIN unit ON unit.unit_id = pd.unit_id
        LEFT JOIN cement ON cement.product_id = pd.product_id
        LEFT JOIN categorycolor ON categorycolor.product_id = pd.product_id
        LEFT JOIN toilet ON toilet.product_id = pd.product_id
        LEFT JOIN chemicalsolution ON chemicalsolution.product_id = pd.product_id
        LEFT JOIN craftmantool ON craftmantool.product_id = pd.product_id
        LEFT JOIN plumbling ON plumbling.product_id = pd.product_id
        LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
        WHERE lot.lot_balance > 0 AND pd.product_stock != 0
        GROUP BY pd.product_id
        ORDER BY pd.product_name
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();
        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["product_id"],
                'name' => $row["product_name"] . " " . $row["brand_name"] . " " . $row["color_name"] . " " . $row["class"] . " " . $row["tl_size"] . " " . $row["pb_size"] . " " . $row["pb_thick"] . " " . $row["cc_volume"] . " " . $row["ct_size"] . " " . $row["cs_volume"] . " " . $row["cm_volume"] . " | ล็อตที่ " . $row['lot_order'] . " สต็อก " . $row['product_stock'],
            );
        }
        echo json_encode($output);
    }
}

if (isset($_POST["type_sale"])) {
    if ($_POST["type_sale"] == "sale_id") {
        $query = "
        SELECT s_id,s_date
        FROM sales_slip
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();

        foreach ($data as $row) {
            $date = $row["s_date"];
            $date = date('d/m/Y', strtotime($date));

            $output[] = array(
                'id' => $row["s_id"],
                'name' => "",
                'date' => $row["s_date"],
            );
        }
        echo json_encode($output);
    } else {
        $query = "
        SELECT si.s_id AS S_ID,pd.product_id,pd.product_name,b.brand_name,u.unit_name,c.color_name,class,tl.tl_size,pb.pb_size,pb.pb_thick,cc.cc_volume,ct.ct_size,cs.cs_volume,cm.cm_volume
        FROM product pd
        INNER JOIN brand b ON b.brand_id = pd.brand_id
        INNER JOIN unit u ON u.unit_id = pd.unit_id
        LEFT JOIN cement cm ON cm.product_id = pd.product_id
        LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
        LEFT JOIN toilet tl ON tl.product_id = pd.product_id
        LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
        LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
        LEFT JOIN plumbling pb ON pb.product_id = pd.product_id

        LEFT JOIN color c ON c.color_id = cc.color_id OR c.color_id = ct.color_id OR c.color_id = tl.color_id
        LEFT JOIN sale_items si ON si.product_id = pd.product_id
        LEFT JOIN sales_slip ss ON ss.s_id = si.s_id
        WHERE si.s_id = '" . $_POST['sale_id'] . "'
        GROUP BY pd.product_id
        ORDER BY pd.product_name ASC
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();
        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["product_id"],
                'name' => $row["product_name"] . " " . $row["brand_name"] . " " . $row["color_name"] . " " . $row["class"] . " " . $row["tl_size"] . " " . $row["pb_size"] . " " . $row["pb_thick"] . " " . $row["cc_volume"] . " " . $row["ct_size"] . " " . $row["cs_volume"] . " " . $row["cm_volume"],
            );
        }
        echo json_encode($output);
    }
}

if (isset($_POST["type_claim"])) {
    if ($_POST["type_claim"] == "claim_id") {
        $query = "
        SELECT ccp_id,ccp_status FROM claim_cpn_h WHERE ccp_status = 0 ORDER BY ccp_id ASC
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();
        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["ccp_id"],
            );
        }
        echo json_encode($output);
    }
}

if (isset($_POST["type_product"])) {
    if ($_POST["type_product"] == "product_data") {
        $query = "
        SELECT pd.product_id,pd.product_name,b.brand_name,u.unit_name,c.color_name,class,tl.tl_size,pb.pb_size,pb.pb_thick,cc.cc_volume,ct.ct_size,cs.cs_volume,cm.cm_volume,pd.product_stock
        FROM product pd
        INNER JOIN brand b ON b.brand_id = pd.brand_id
        INNER JOIN unit u ON u.unit_id = pd.unit_id
        LEFT JOIN cement cm ON cm.product_id = pd.product_id
        LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
        LEFT JOIN toilet tl ON tl.product_id = pd.product_id
        LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
        LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
        LEFT JOIN plumbling pb ON pb.product_id = pd.product_id

        LEFT JOIN color c ON c.color_id = cc.color_id OR c.color_id = ct.color_id OR c.color_id = tl.color_id
        ORDER BY pd.product_id ASC
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();

        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["product_id"],
                'name' => $row["product_id"] . " " . $row["product_name"] . " " . $row["brand_name"] . " " . $row["color_name"] . " " . $row["class"] . " " . $row["tl_size"] . " " . $row["pb_size"] . " " . $row["pb_thick"] . " " . $row["cc_volume"] . " " . $row["ct_size"] . " " . $row["cs_volume"] . " " . $row["cm_volume"] . " คงเหลือ " . $row["product_stock"],
            );
        }
        echo json_encode($output);
    } else {
        $query = "
        SELECT cpn.cpn_id,cpn.cpn_name,cp.product_id
        FROM company cpn
        INNER JOIN costprice cp ON cp.cpn_id = cpn.cpn_id
        WHERE cp.product_id = '" . $_POST['product_id'] . "'
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();
        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["cpn_id"],
                'name' => $row["cpn_name"],
            );
        }
        echo json_encode($output);
    }
}

if (isset($_POST["type_company"])) {
    if ($_POST["type_company"] == "company_data") {
        $query = "
        SELECT *
        FROM detailquotation dq
        INNER JOIN company cpn ON cpn.cpn_id = dq.cpn_id
        WHERE dq.quo_id = '" . $_POST['quo_id'] . "'
        GROUP BY dq.cpn_id
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();

        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["cpn_id"],
                'name' => $row["cpn_name"],
            );
        }
        echo json_encode($output);
    } else {
        $query = "
        SELECT cpn.cpn_id,cpn.cpn_name,cp.product_id
        FROM company cpn
        INNER JOIN costprice cp ON cp.cpn_id = cpn.cpn_id
        WHERE cp.product_id = '" . $_POST['product_id'] . "'
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();
        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["cpn_id"],
                'name' => $row["cpn_name"],
            );
        }
        echo json_encode($output);
    }
}

if (isset($_POST["type_claim_slip"])) {
    if ($_POST["type_claim_slip"] == "claim_id") {
        $query = "
        SELECT * FROM claim_ctm_h WHERE cl_status = 2 ORDER BY cl_id ASC
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();

        foreach ($data as $row) {
            $output[] = array(
                'id' => $row["cl_id"],
            );
        }
        echo json_encode($output);
    }
}

?>