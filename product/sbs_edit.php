<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); ?>
    </script>
</head>

<body>
<?php
    include('../connectdb.php');
    $idproduct = isset($_GET['p_id']) ? $_GET['p_id'] : '';

    $sql = "SELECT * FROM product WHERE product_id='" . $idproduct . "' ORDER BY product_id ASC";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    extract($row);
    ?>
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
                        <h4>แก้ไขสินค้าทั่วไป</h4>
                        <form action="#" name="editform" id="editform" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <p></p>
                            <div class="form-row">
                                <div class="col-sm-2">
                                    <label for="validationDefault01">รหัสสินค้า</label>
                                    <input type="hidden" name="idproduct" class="form-control" value="<?php echo $idproduct; ?>">
                                    <input type="text" class="form-control" value="<?php echo $idproduct; ?>" disabled>
                                </div>


                                <div class="col-sm-4 mb-3">
                                    <label for="validationDefault02">ชื่อสินค้า</label>
                                    <input class="form-control" type="text" name="name" value="<?php if (isset($_POST['name'])) {
                                                                                                    echo $_POST['name'];
                                                                                                } else {
                                                                                                    echo $product_name;
                                                                                                } ?>">
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-sm-4 mb-3">
                                    <label for="validationDefault03">ยี่ห้อ</label>
                                    <select name="brand" id="brand" class="form-control" onchange="document.editform.submit();" required>
                                        <?php
                                        $strSQL = "SELECT * FROM brand";
                                        $result = mysqli_query($conn, $strSQL);
                                        while ($row = mysqli_fetch_array($result)) {
                                            if (isset($_POST["brand"])) {
                                        ?>
                                                <option value="<?php echo $row["brand_id"]; ?>" <?php
                                                                                                if ($_POST["brand"] == $row["brand_id"]) {
                                                                                                    echo "selected";
                                                                                                } else {
                                                                                                    echo "unselected";
                                                                                                } ?>>
                                                    <?php echo $row['brand_name']; ?></option>
                                            <?php
                                            } else { ?>
                                                <option value="<?php echo $row["brand_id"]; ?>" <?php
                                                                                                if ($row["brand_id"] == @$brand_id) {
                                                                                                    echo "selected";
                                                                                                } else {
                                                                                                    echo "unselected";
                                                                                                } ?>>
                                                    <?php echo $row["brand_name"]; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-4 mb-3">
                                    <label for="validationDefault04">หน่วย</label>
                                    <select name="unit" id="unit" class="form-control" onchange="document.editform.submit();" required>
                                        <?php
                                        $strSQL = "SELECT * FROM unit";
                                        $result = mysqli_query($conn, $strSQL);
                                        while ($row = mysqli_fetch_array($result)) {
                                            if (isset($_POST["unit"])) {
                                        ?>
                                                <option value="<?php echo $row["unit_id"]; ?>" <?php
                                                                                                if ($_POST["unit"] == $row["unit_id"]) {
                                                                                                    echo "selected";
                                                                                                } else {
                                                                                                    echo "unselected";
                                                                                                } ?>>
                                                    <?php echo $row['unit_name']; ?></option>
                                            <?php
                                            } else { ?>
                                                <option value="<?php echo $row["unit_id"]; ?>" <?php
                                                                                                if ($row["unit_id"] == @$unit_id) {
                                                                                                    echo "selected";
                                                                                                } else {
                                                                                                    echo "unselected";
                                                                                                } ?>>
                                                    <?php echo $row["unit_name"]; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-6 mb-3 control-label">
                                    <label for="validationDefault05">รายละเอียด</label><br>
                                    <textarea name="detail" rows="4" cols="51"><?php if (isset($_POST['detail'])) {
                                                                                    echo $_POST['detail'];
                                                                                } else {
                                                                                    echo $product_detail;
                                                                                } ?></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-2 mb-3">
                                    <label for="validationDefault06">ราคาขาย</label>
                                    <input class="form-control" type="text" name="saleprice" value="<?php if (isset($_POST['saleprice'])) {
                                                                                                        echo $_POST['saleprice'];
                                                                                                    } else {
                                                                                                        echo $product_saleprice;
                                                                                                    } ?>">
                                    <small id="salepriceHelp" class="form-text text-muted"><a style="color:red;">*กรุณาใส่ราคาขายสินค้า*</a></small>
                                </div>

                                <div class="col-sm-2 mb-3 control-label">
                                    <label for="validationDefault07">จุดสั่งซื้อ</label>
                                    <input class="form-control" type="text" name="reorderpoint" value="<?php if (isset($_POST['reorderpoint'])) {
                                                                                                            echo $_POST['reorderpoint'];
                                                                                                        } else {
                                                                                                            echo $product_reorder;
                                                                                                        } ?>">
                                    <small id="salepriceHelp" class="form-text text-muted"><a style="color:red;">*กรุณาใส่จุดสั่งซื้อสินค้า*</a></small>
                                </div>
                            </div>



                            <div class="form-row">
                                <div class="col-sm-3">
                                    <div>
                                        <input class="w3-button w3-black w3-round-xlarge" type="submit" name="sbsback" value="ย้อนกลับ" style="width: 100px" onclick="document.editform.action='idprocheck.php';">
                                        <input class="w3-button w3-red w3-round-xlarge" type="submit" name="update" value="บันทึก" style="width: 100px" onclick="document.editform.action='sbs_update.php';">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>