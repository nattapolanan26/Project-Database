<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); ?>
    <script type="text/javascript">
        function submitupdate() {
            document.editform.action = "pvc_update.php";
        }

        function backSubmit() {
            document.editform.action = "idprocheck.php"
        }
    </script>
</head>

<body>
    <?php
    include('../connectdb.php');

    $idproduct = isset($_GET['p_id']) ? $_GET['p_id'] : '';

    $sql = "SELECT * FROM product 
    LEFT JOIN plumbling ON plumbling.product_id = product.product_id 
     
    WHERE plumbling.product_id = '$idproduct'
    ORDER BY plumbling.product_id ASC";
    // echo $sql;
    $result = mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
    $row = mysqli_fetch_array($result);

    extract($row);
    // ----------------------------------------------------------------------------- //
    $submit = $_GET['submit'];

    if ($submit == 'Edit') {
        if ($idproduct != '') { // ถ้า id ไม่เท่ากับ ค่าว่าง
            $sql = "SELECT * FROM plumbling WHERE product_id='" . $idproduct . "'";
            $result = mysqli_query($conn, $sql); //คิวรี่ คำสั่งsql เก็บใน ตัวแปร result
            $row = mysqli_fetch_array($result) or die("Error description: " . mysqli_error($conn));
            $material = $row['mt_id'];
        }
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
                            <h4>แก้ไขงานประปา</h4>
                            <form action="#" name="editform" id="editform" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <p></p>
                                <div class="form-row">
                                    <div class="col-sm-2">
                                        <label for="validationDefault01">รหัสสินค้า</label>
                                        <input type="hidden" name="idproduct" class="form-control" value="<?php echo $idproduct; ?>">
                                        <input type="text" class="form-control" value="<?php echo $idproduct; ?>" disabled>
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label for="validationDefault03">ชื่อสินค้า</label>
                                        <input class="form-control" type="text" name="name" value="<?php if (isset($_POST['name'])) {
                                                                                                        echo $_POST['name'];
                                                                                                    } else {
                                                                                                        echo $product_name;
                                                                                                    } ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-sm-4 mb-3">
                                        <label for="validationDefault04">ยี่ห้อ</label>
                                        <select name="brand" id="brand" class="form-control" onchange="document.editform.submit();" required>
                                            <option>Please select a brand . . .</option>
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
                                        <label for="validationDefault05">วัสดุ</label>
                                        <select name="material" id="material" class="form-control" onchange="document.editform.submit();">
                                            <option>Please select a material . . .</option>
                                            <?php
                                            $strSQL = "SELECT * FROM material";
                                            $result = mysqli_query($conn, $strSQL);
                                            while ($row = mysqli_fetch_array($result)) {
                                                if (isset($_POST["material"])) {
                                            ?>
                                                    <option value="<?php echo $row["mt_id"]; ?>" <?php
                                                                                                    if ($_POST["material"] == $row["mt_id"]) {
                                                                                                        echo "selected";
                                                                                                    } else {
                                                                                                        echo "unselected";
                                                                                                    } ?>>
                                                        <?php echo $row['mt_name']; ?></option>
                                                <?php
                                                } else { ?>
                                                    <option value="<?php echo $row["mt_id"]; ?>" <?php
                                                                                                    if ($row["mt_id"] == @$mt_id) {
                                                                                                        echo "selected";
                                                                                                    } else {
                                                                                                        echo "unselected";
                                                                                                    } ?>>
                                                        <?php echo $row["mt_name"]; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-sm-4 mb-3">
                                        <label for="validationDefault05">ชั้นพีวีซี</label>
                                        <select name="class" id="class" class="form-control" onchange="document.editform.submit();">
                                            <option value=''>Please select a class . . .</option>
                                            <option value='5' <?php if (isset($_POST["class"])) if ($_POST["class"] == '5') echo 'selected'; ?>>5</option>
                                            <option value='8.5' <?php if (isset($_POST["class"])) if ($_POST["class"] == '8.5') echo 'selected'; ?>>8.5</option>
                                            <option value='13.5' <?php if (isset($_POST["class"])) if ($_POST["class"] == '13.5') echo 'selected'; ?>>13.5</option>
                                            //* Edit ยังไม่ได้ทำ
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-sm-4 mb-3">
                                        <label for="validationDefault05">สี</label>
                                        <select name="color" id="color" class="form-control" onchange="document.editform.submit();" required>
                                            <option>Please select a color . . .</option>
                                            <?php
                                            $strSQL = "SELECT * FROM color";
                                            $result = mysqli_query($conn, $strSQL);
                                            while ($row = mysqli_fetch_array($result)) {
                                                if (isset($_POST["color"])) {
                                            ?>
                                                    <option value="<?php echo $row["color_id"]; ?>" <?php
                                                                                                    if ($_POST["color"] == $row["color_id"]) {
                                                                                                        echo "selected";
                                                                                                    } else {
                                                                                                        echo "unselected";
                                                                                                    } ?>>
                                                        <?php echo $row['color_name']; ?></option>
                                                <?php
                                                } else { ?>
                                                    <option value="<?php echo $row["color_id"]; ?>" <?php
                                                                                                    if ($row["color_id"] == @$color_id) {
                                                                                                        echo "selected";
                                                                                                    } else {
                                                                                                        echo "unselected";
                                                                                                    } ?>>
                                                        <?php echo $row["color_name"]; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-sm-2 mb-3 control-label">
                                        <label for="validationDefault05">ขนาด<a style="color:red;"> (กว้าง*ลึก*สูง)</a></label>
                                        <input class="form-control" type="text" name="size" value="<?php if (isset($_POST['size'])) {
                                                                                                        echo $_POST['size'];
                                                                                                    } else {
                                                                                                        echo $pb_size;
                                                                                                    } ?>">
                                    </div>
                                    <div class="col-sm-2 mb-3 control-label">
                                        <label for="validationDefault05">ความหนา</label>
                                        <input class="form-control" type="text" name="thick" value="<?php if (isset($_POST['thick'])) {
                                                                                                        echo $_POST['thick'];
                                                                                                    } else {
                                                                                                        echo $pb_thick;
                                                                                                    } ?>">
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="col-sm-4 mb-3">
                                        <label for="validationDefault07">หน่วย</label>
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
                                        <label for="validationDefault06">รายละเอียด</label><br>
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
                                </div>

                                <div class="form-row">
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

                                <div>
                                    <input class="w3-button w3-black w3-round-xlarge mb-3" type="submit" name="pvcback" value="ย้อนกลับ" style="width: 100px" onclick="document.editform.action='pb_show.php'">
                                    <input class="w3-button w3-red w3-round-xlarge mb-3" type="submit" name="update" value="บันทึก" style="width: 100px" onclick="document.editform.action='pb_update.php'">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</body>

</html>