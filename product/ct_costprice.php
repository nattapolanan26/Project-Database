<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); ?>
</head>

<body>
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
                        <h4>เพิ่มราคาทุนสินค้าและบริษัทคู่ค้า</h4>
                        <a href="ct_show.php" class="btn-danger btn-sm"><i class='fa fa-undo' aria-hidden='true'></i> ย้อนกลับ</a>
                        <form action="#" name="addcostprice" id="addcostprice" method="post" enctype="multipart/form-data">
                            <p></p>
                            <?php
                            $search = isset($_GET['search']) ? $_GET['search'] : '';
                            $productid = isset($_GET['p_id']) ? $_GET['p_id'] : '';

                            $sql = "SELECT product.*,craftmantool.*,brand.brand_name,unit.unit_name,color_name,mt_name
                            FROM product
                            INNER JOIN craftmantool ON craftmantool.product_id = product.product_id
                            INNER JOIN unit ON unit.unit_id = product.unit_id 
                            INNER JOIN brand ON brand.brand_id = product.brand_id
                            INNER JOIN color ON color.color_id = craftmantool.color_id
                            INNER JOIN material ON material.mt_id = craftmantool.mt_id
                            WHERE craftmantool.product_id = '$productid'
                            ";
                            $result = mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
                            while ($row = mysqli_fetch_array($result)) {
                                $productname = $row['product_name'];
                                $brandname = $row['brand_name'];
                                $size = $row['ct_size'];
                                $color = $row['color_name'];
                                $saleprice = $row['product_saleprice'];
                            }
                            ?>

                            <div class="form-row">
                                <div class="col-md-2 mb-3">
                                    <label for="validationDefault01">รหัสสินค้า</label>
                                    <input class="form-control" type="text" value="<?php echo $productid; ?>" disabled>
                                    <input class="form-control" type="hidden" name="product" id="product" value="<?php echo $productid; ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="validationDefault02">ชื่อสินค้า</label>
                                    <input class="form-control" type="text" style="width:500px" value="<?php echo $productname . " " . $brandname . " " . "สี" . $color . " " . "ขนาด" . " (" . $size . ") " . "ราคา" . " " . number_format($saleprice, 2); ?>" disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="validationDefault03">บริษัทคู่ค้า</label>
                                    <select name="company" id="company" onchange="document.addcostprice.submit();" class="custom-select" required>
                                        <option>Please select a company . . .</option>
                                        <?php
                                        $sql = "SELECT * FROM company";
                                        $result = mysqli_query($conn, $sql);

                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <option value="<?php echo $row['cpn_id']; ?>" <?php if (isset($_POST['company'])) {
                                                                                                if ($_POST['company'] == $row['cpn_id']) {
                                                                                                    echo 'selected';
                                                                                                }
                                                                                            }
                                                                                            ?>>
                                                <?php echo $row["cpn_name"]; ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-2 mb-3">
                                    <label for="validationDefault04">ราคาทุน</label>
                                    <input type="text" name="costprice" class="form-control" value="<?php if (isset($_POST['costprice'])) echo $_POST['costprice']; ?>">
                                    <small id="costpriceHelp" class="form-text text-muted" style="color:blue;"><a style="color:red;">*กรุณาใส่ราคาทุนสินค้า*</a></small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-2 mb-3">
                                    <input class="w3-button w3-red w3-round-xlarge" type="submit" name="addcostprice" value="บันทึก" style="width: 250px">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
    if (isset($_POST['addcostprice'])) {
        $idcompany = $_POST['company'];
        $idproduct = $_POST['product'];
        $costprice = $_POST['costprice'];

        $Sqlsaleprice = "SELECT product_saleprice 
                FROM product 
                INNER JOIN craftmantool ON craftmantool.product_id = product.product_id 
                WHERE craftmantool.product_id = '$idproduct'";
        $resultsp = mysqli_query($conn, $Sqlsaleprice) or die('Error query: ' . mysqli_error($conn));
        $rowsp = mysqli_fetch_array($resultsp);
        $saleprice = $rowsp['product_saleprice'];

        if ($_POST['company'] && $_POST['costprice'] != '') {
            if ($_POST['costprice'] <  0) {
                echo "<script>"; //คำสั่งสคิป
                echo "alert('กรุณากรอกราคาทุนตั้งแต่ 0 ขึ้นไป.');"; //แสดงหน้าต่างเตือน
                echo "window.location.href='ct_costprice.php?p_id=$productid'"; //แสดงหน้าก่อนนี้
                echo "</script>";
            } elseif ($saleprice >= $costprice) {
                $sql = "INSERT INTO costprice (cpn_id,product_id,costprice) VALUES ('$idcompany','$idproduct','$costprice')";
                // echo  $sql;
                if ($conn->query($sql) == true) {
                    echo "<script>"; //คำสั่งสคิป
                    echo "window.location.href='ct_show.php';"; //แสดงหน้าก่อนนี้
                    echo "</script>";
                } else {
                    echo "<script>"; //คำสั่งสคิป
                    echo "alert('This list has been added to the database already.');"; //แสดงหน้าต่างเตือน
                    echo "window.location.href='ct_costprice.php?p_id=$productid'"; //แสดงหน้าก่อนนี้
                    echo "</script>";
                }
            } else {
                echo "<script>"; //คำสั่งสคิป
                echo "alert('กรุณากรอกราคาทุนให้น้อยกว่าราคาขาย !');"; //แสดงหน้าต่างเตือน
                echo "window.location.href='ct_costprice.php?p_id=$productid'"; //แสดงหน้าก่อนนี้
                echo "</script>";
            }
        } else {
            echo "<script>"; //คำสั่งสคิป
            echo "alert('กรุณากรอกข้อมูลให้ครบ !');"; //แสดงหน้าต่างเตือน
            echo "window.location.href='ct_costprice.php?p_id=$productid'"; //แสดงหน้าก่อนนี้
            echo "</script>";
        }
    }
    ?>
</body>

</html>