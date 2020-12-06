<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php') ?>
    <script type="text/javascript">
        function submitheadproduct1() {

            document.costpriceedit.submit();

        }

        function submitcolorproduct() {

            document.costpriceedit.submit();

        }

        function submitsizeproduct() {

            document.costpriceedit.submit();

        }

        function submitheadproduct2() {

            document.getElementById("costpriceedit").action = "cpn_insert_product.php";
        }
    </script>
</head>

<body>
    <?php include('../connectdb.php'); ?>
    <div class="container-fluid">
        <?php include('../navbar.php'); ?>
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
                        <h4>แก้ไขราคาทุนสินค้าและบริษัทคู่ค้า</h4>
                        <?php
                        $productid = isset($_GET['p_id']) ? $_GET['p_id'] : '';
                        $cpnid = isset($_GET['cpn_id']) ? $_GET['cpn_id'] : '';
                        ?>
                        <a href="tl_costprice_show.php?p_id=<?= $productid; ?>" class="btn-danger btn-sm">ย้อนกลับ</a>
                        <form action="#" name="editcostprice" id="editcostprice" method="post" enctype="multipart/form-data">
                            <p></p>
                            <?php
                            $sql = "SELECT product.*,toilet.*,brand.brand_name,unit.unit_name,costprice.costprice,color_name
                    FROM product
                    INNER JOIN toilet ON toilet.product_id = product.product_id
                    INNER JOIN unit ON product.unit_id= unit.unit_id 
                    INNER JOIN brand ON brand.brand_id = product.brand_id
                    INNER JOIN color ON color.color_id = toilet.color_id
                    INNER JOIN costprice ON costprice.product_id = product.product_id
                    INNER JOIN company ON company.cpn_id = costprice.cpn_id
                    WHERE product.product_id = '$productid' AND costprice.cpn_id = '$cpnid'
                    ";
                            // echo $sql;
                            $result = mysqli_query($conn, $sql) or die('Error query: ' . mysqli_error($conn));
                            while ($row = mysqli_fetch_array($result)) {
                                $productname = $row['product_name'];
                                $brandname = $row['brand_name'];
                                $colorname = $row['color_name'];
                                $costprice = $row['costprice'];
                                $size = $row['tl_size'];
                                $saleprice = $row['product_saleprice'];
                            }

                            ?>
                            <div class="form-row">
                                <div class="col-sm-2 mb-3">
                                    <label for="validationDefault01">รหัสสินค้า</label>
                                    <input class="form-control" type="text" value="<?php echo $productid; ?>" disabled>
                                    <input class="form-control" type="hidden" name="product" id="product" value="<?php echo $productid; ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-5 mb-3">
                                    <label for="validationDefault02">ชื่อสินค้า</label>
                                    <input class="form-control" type="text" value="<?php echo $productname . " " . $brandname . " " . "สี" . $colorname . " " . "ขนาด" . " " .  $size . " " . "ราคา" . " " . number_format($saleprice, 2); ?>" disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-4 mb-3">
                                    <label for="validationDefault03">บริษัทคู่ค้า</label>
                                    <select name="company" id="company" onchange="document.editcostprice.submit();" class="custom-select" required>
                                        <option>--------------------โปรดเลือก---------------------</option>
                                        <?php
                                        $sql = "SELECT * FROM company";
                                        $result = mysqli_query($conn, $sql);

                                        while ($row = mysqli_fetch_array($result)) {
                                            if (isset($_POST["company"])) {
                                        ?>
                                                <option value="<?php echo $row["cpn_id"]; ?>" <?php
                                                                                                if ($_POST["company"] == $row["cpn_id"]) {
                                                                                                    echo "selected";
                                                                                                } else {
                                                                                                    echo "unselected";
                                                                                                } ?>>
                                                    <?php echo $row['cpn_name']; ?></option>
                                            <?php
                                            } else { ?>
                                                <option value="<?php echo $row["cpn_id"]; ?>" <?php
                                                                                                if ($row["cpn_id"] == @$cpnid) {
                                                                                                    echo "selected";
                                                                                                } else {
                                                                                                    echo "unselected";
                                                                                                } ?>>
                                                    <?php echo $row["cpn_name"]; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-2 mb-3">
                                    <label for="validationDefault04">ราคาทุน</label>
                                    <input type="text" name="costprice" class="form-control" value="<?php if (isset($_POST['costprice'])) {
                                                                                                        echo $_POST['costprice'];
                                                                                                    } else {
                                                                                                        echo $costprice;
                                                                                                    } ?>">
                                    <small id="costpriceHelp" class="form-text text-muted" style="color:blue;">*กรุณาใส่ราคาทุนที่จะแก้ไข*</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-2 control-label">
                                    <input class="w3-button w3-red w3-round-xlarge" type="submit" name="editcostprice" value="บันทึก" style="width: 250px">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['editcostprice'])) {
        $costprice = $_POST['costprice'];
        $productid = $_POST['product'];
        $company = $_POST['company'];

        $sql = "UPDATE costprice SET cpn_id='" . $company . "',costprice='" . $costprice . "' WHERE product_id = '" . $productid . "' AND cpn_id = '" . $_GET['cpn_id'] . "'";
        if ($result = mysqli_query($conn, $sql)) {
            echo "<script>";
            echo "alert('Update Success!');";
            echo "window.location.href='tl_costprice_show.php?p_id=$productid';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('This list has been added to the database already!');";
            echo "window.location.href='tl_costprice_show.php?p_id=$productid';";
            echo "</script>";
        }
    }
    ?>

</body>

</html>