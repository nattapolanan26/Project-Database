<?php session_start();
include('../home.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <script type="text/javascript">
        function submitheadproduct1() {
            document.editproduct.submit();
        }

        function submiteditproduct() {
            document.getElementById("editproduct").action = "update_product.php";
        }
    </script>

    <style type="text/css">
        @font-face {
            font-family: PrintAble4U;
            src: url(font\sPrintAble4U.ttf) format("truetype");
        }

        body {
            font-family: 'PrintAble4U', verdana, helvetica, sans-serif;
        }

        body {
            font-family: 'lato', sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            /* text-align: left; */
            padding: 5px;
        }

        /* ปรับสี background ใน table */
        tbody {
            background: #e8edff;
        }

        /* hover cursor เป็นสีฟ้า */
        tbody tr:hover td {
            color: #339;
            background: #d0dafd;
        }

        th {
            background-color: #20B2AA;
            color: white;


        }
    </style>
</head>
<title>Product</title>
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
                    <form action="#" name="editproduct" id="editproduct" method="post">
                        <table>
                            <?php
                            $editid = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
                            $sqlproduct = "SELECT * FROM product WHERE product_id = '" . $editid . "'";
                            $result = mysqli_query($conn, $sqlproduct);
                            $rowproduct = mysqli_fetch_array($result);
                            ?>
                            <h3>Add Product</h3>
                            <tr>
                                <td align="right">รหัสสินค้า : </td>
                                <td>
                                    <div>
                                        &emsp;&ensp;<input type="hidden" name="id" value="<?php echo $editid; ?>"><?php echo $editid; ?>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td align="right">ชื่อสินค้า : </td>
                                <td>
                                    <div>
                                        &emsp;&ensp;<input type="text" name="nameproduct" value="<?php echo $rowproduct['product_name']; ?>">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td align="right">ประเภทสินค้า :</td>
                                <td>
                                    <div>
                                        &emsp;&ensp;<select onchange="submitheadproduct1();" name="cgr" id="cgr">
                                            <option value="0">-----------------โปรดเลือก-----------------</option>
                                            <?php
                                            $strSQL = "SELECT cgr_id,cgr_name FROM category";
                                            $result = mysqli_query($conn, $strSQL);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?php echo $row['cgr_id']; ?>" <?php if (isset($_POST['cgr'])) {
                                                                                                    if ($_POST['cgr'] == $row['cgr_id']) {
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                } ?>>
                                                    <?php echo $row['cgr_name']; ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td align="right">ประเภทย่อยสินค้า :</td>
                                <td>
                                    <div>
                                        &emsp;&ensp;<select onchange="submitheadproduct1();" name="cgr_sub" id="cgr_sub">
                                            <option value="0">-----------------โปรดเลือก-----------------</option>
                                            <?php
                                            if (isset($_POST['cgr'])) {
                                                $strSQL = "SELECT  cgrs_id,cgrs_name FROM category_sub WHERE cgr_id = '" . $_POST['cgr'] . "'";
                                                $result = mysqli_query($conn, $strSQL);
                                                while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                    <option value="<?php echo $row['cgrs_id']; ?>" <?php if (isset($_POST['cgr_sub'])) {
                                                                                                        if ($_POST['cgr_sub'] == $row['cgrs_id']) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                                    } ?>>
                                                        <?php echo $row['cgrs_name']; ?></option>
                                            <?php
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td align="right">ยี่ห้อสินค้า :</td>
                                <td>
                                    <div>
                                        &emsp;&ensp;<select onchange="submitheadproduct1();" name="brand" id="brand">
                                            <option value="0">-----------------โปรดเลือก-----------------</option>
                                            <?php
                                            if (isset($_POST['cgr_sub'])) {
                                                $strSQL = "SELECT brand_id,brand_name FROM brand";
                                                $result = mysqli_query($conn, $strSQL);
                                                while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                    <option value="<?php echo $row['brand_id']; ?>" <?php if (isset($_POST['brand'])) {
                                                                                                        if ($_POST['brand'] == $row['brand_id']) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                                    } ?>>
                                                        <?php echo $row['brand_name']; ?></option>
                                            <?php
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <center><input type="submit" name="editproduct" value="แก้ไขสินค้า" onclick="submiteditproduct();"></center><br>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>