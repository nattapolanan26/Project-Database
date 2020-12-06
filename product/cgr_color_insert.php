<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); ?>
</head>

<?php include('../connectdb.php'); ?>
<?php include('../navbar.php'); ?>
<div class="container-fluid">
    <p></p>
    <div class="row">
        <div class="col-md-2">
            <!-- Left side column. contains the logo and sidebar -->
            <div class="color-login">
                <h6><i class="fas fa-user-circle"></i>&ensp;<a style="font-weight:bold;"><?php echo "ผู้ใช้"; ?></a><a style="color:#c92828;font-weight:bold;"><?php echo " : " . $_SESSION['user']; ?></a></h6>
                <h6><i class="fas fa-check-square"></i></i></i>&ensp;<a style="font-weight:bold;"><?php echo "ตำแหน่ง"; ?></a><a style="color:#1d4891;font-weight:bold;"><?php echo " : " . $_SESSION['posname']; ?></a></h6>
            </div>
            <?php include('../menu_left.php'); ?>
            <!-- Content Wrapper. Contains page content -->
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <h4>เพิ่มสีทาอาคาร</h4>
                    <form action="#" id="myformAdd" name="myformAdd" method="post">
                        <p></p>

                        <div class="form-row">
                            <div class="col-sm-2">
                                <label for="validationDefault01">รหัสสินค้า</label>
                                <input type="hidden" name="idproduct" value="<?php $idsql = "SELECT concat('PRO',LPAD(ifnull(SUBSTR(max(product_id),4,10),'0')+1,7,'0')) as PRO_ID FROM product";
                                                                                $resultid = mysqli_query($conn, $idsql);
                                                                                $row = mysqli_fetch_array($resultid);
                                                                                $idproduct = $row['PRO_ID']; ?><?php echo $idproduct; ?>">
                                <input type="text" class="form-control" value="<?php echo $idproduct; ?>" disabled>
                            </div>
                            <div class="col-sm-4 mb-3">
                                <label for="validationDefault02">ชื่อสินค้า</label>
                                <input class="form-control" type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="col-sm-4 mb-3">
                                <label for="validationDefault03">ยี่ห้อ</label>
                                <select name="brand" id="brand" onchange="document.myformAdd.submit();" class="form-control" required>
                                    <option value=''>Please select a brand . . .</option>
                                    <?php
                                    $strSQL = "SELECT * FROM brand";
                                    $result = mysqli_query($conn, $strSQL);
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <option value="<?php echo $row["brand_id"]; ?>" <?php if (isset($_POST["brand"])) {
                                                                                            if ($_POST["brand"] == $row["brand_id"]) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>><?php echo $row["brand_name"]; ?>
                                        </option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-4 mb-3">
                                <label for="validationDefault04">สี</label>
                                <select name="color" id="color" onchange="document.myformAdd.submit();" class="form-control" required>
                                    <option value=''>Please select a color . . .</option>
                                    <?php
                                    $strSQL = "SELECT * FROM color";
                                    $result = mysqli_query($conn, $strSQL);
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <option value="<?php echo $row["color_id"]; ?>" <?php if (isset($_POST["color"])) {
                                                                                            if ($_POST["color"] == $row["color_id"]) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>><?php echo $row["color_name"]; ?>
                                        </option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-4 mb-3">
                                <label for="validationDefault05">วัสดุ</label>
                                <select name="material" id="material" onchange="document.myformAdd.submit();" class="form-control" required>
                                    <option value=''>Please select a material . . .</option>
                                    <?php
                                    $strSQL = "SELECT * FROM material";
                                    $result = mysqli_query($conn, $strSQL);
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <option value="<?php echo $row["mt_id"]; ?>" <?php if (isset($_POST["material"])) {
                                                                                            if ($_POST["material"] == $row["mt_id"]) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>><?php echo $row["mt_name"]; ?>
                                        </option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-4 mb-3">
                                <label for="validationDefault06">หน่วย</label>
                                <select name="unit" id="unit" onchange="document.myformAdd.submit();" class="form-control" required>
                                    <option value=''>Please select a unit . . .</option>
                                    <?php
                                    $strSQL = "SELECT * FROM unit";
                                    $result = mysqli_query($conn, $strSQL);
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <option value="<?php echo $row["unit_id"]; ?>" <?php if (isset($_POST["unit"])) {
                                                                                            if ($_POST['unit'] == $row["unit_id"]) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>><?php echo $row["unit_name"]; ?>
                                        </option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-6 mb-3 control-label">
                                <label for="validationDefault07">รายละเอียด</label><br>
                                <textarea name="detail" rows="4" cols="51"><?php if (isset($_POST['detail'])) echo $_POST['detail']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-2 mb-3 control-label">
                                <label for="validationDefault08">ปริมาณ</label>
                                <input class="form-control" type="text" name="volume" value="<?php if (isset($_POST['volume'])) echo $_POST['volume']; ?>">
                                <small id="salepriceHelp" class="form-text text-muted"><a style="color:red;">*กรุณาใส่ปริมาณสินค้า*</a></small>
                            </div>
                            <div class="col-sm-2 mb-3 control-label">
                                <label for="validationDefault09">ราคาขาย</label>
                                <input class="form-control" type="text" name="saleprice" value="<?php if (isset($_POST['saleprice'])) echo $_POST['saleprice']; ?>">
                                <small id="salepriceHelp" class="form-text text-muted"><a style="color:red;">*กรุณาใส่ราคาขายสินค้า*</a></small>
                            </div>
                            <div class="col-sm-2 mb-3 control-label">
                                <label for="validationDefault10">จุดสั่งซื้อ</label>
                                <input class="form-control" type="text" name="reorderpoint" value="<?php if (isset($_POST['reorderpoint'])) echo $_POST['reorderpoint']; ?>">
                                <small id="salepriceHelp" class="form-text text-muted"><a style="color:red;">*กรุณาใส่จุดสั่งซื้อสินค้า*</a></small>
                            </div>
                        </div>

                        <div>
                            <input class="w3-button w3-black w3-round-xlarge" type="submit" name="cgrcolorback" value="ย้อนกลับ" style="width: 100px" onclick="document.myformAdd.action='idprocheck.php'">
                            <input class="w3-button w3-red w3-round-xlarge" type="submit" name="submitcolor" value="บันทึก" style="width: 100px">
                        </div>
                        <p></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
if (isset($_POST['submitcolor'])) {
    $idproduct = $_POST['idproduct'];
    $name = $_POST['name'];
    $saleprice = $_POST['saleprice'];
    $unit = $_POST['unit'];
    $brand = $_POST['brand'];
    $color = $_POST['color'];
    $material = $_POST['material'];
    $detail = $_POST['detail'];
    $repoint = $_POST['reorderpoint'];
    $volume = $_POST['volume'];

    $sql = "SELECT product.product_id,count(product_name) AS p_name,count(color_id) AS c_color,count(brand_id) AS b_brand,count(categorycolor.mt_id) AS m_material,count(cc_volume) AS v_volume 
        FROM product
        INNER JOIN categorycolor ON categorycolor.product_id = product.product_id 
        WHERE product.product_name = '$name' AND categorycolor.product_id != '$idproduct' AND brand_id = '$brand' AND color_id = '$color' AND categorycolor.mt_id = '$material' AND categorycolor.cc_volume = '$volume'";
    // echo $sql;
    $query = mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
    $row  = mysqli_fetch_array($query);
    $p_name = $row['p_name'];
    $c_color = $row['c_color'];
    $b_brand = $row['b_brand'];
    $m_mateial = $row['m_material'];
    $v_volume = $row['v_volume'];

    if ($p_name == '0' && $c_color == '0' && $b_brand == '0' && $m_mateial == '0' && $v_volume == '0' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $color != '' && $repoint != '' && $volume != '' && $material != '') {
        $sql = "INSERT INTO product (product_id,product_name,brand_id,unit_id,product_saleprice,product_reorder,product_stock,product_detail,product_status) VALUES ('$idproduct','$name','$brand','$unit','$saleprice','$repoint','0','$detail','4')";
        // echo $sql;
        if (mysqli_query($conn, $sql) == true) {
            $sql1 = "INSERT INTO categorycolor (product_id,color_id,mt_id,cc_volume) VALUES ('$idproduct','$color','$material','$volume')";
            mysqli_query($conn, $sql1);
            // echo $sql1;
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='cgr_color_show.php';"; //แสดงหน้าก่อนนี้
            echo "</script>";
        } else {
            echo "<script>"; //คำสั่งสคิป
            echo "alert('ข้อมูลผิดพลาด กรุณาตรวจสอบใหม่อีกครั้ง!!');"; //แสดงหน้าต่างเตือน
            echo "window.location.href='cgr_color_insert.php';"; //แสดงหน้าก่อนนี้
            echo "</script>";
        }
    } elseif ($name == '' || $saleprice == '' || $unit == '' || $brand == '' || $repoint == '' || $material == '' || $detail == '' || $volume == '' || $color == '') {
        echo "<script>";
        echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
        echo "window.location.href='cgr_color_insert.php';";
        echo "</script>";
    } else {
        echo "<script>";
        echo "alert('สินค้าซ้ำในระบบกรุณากรอกใหม่อีกครั้ง !!');";
        echo "window.location.href='cgr_color_insert.php';";
        echo "</script>";
    }
}

?>
</body>

</html>