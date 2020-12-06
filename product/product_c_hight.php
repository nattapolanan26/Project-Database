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
                        <form action="#" name="myformAdd" method="get">
                            <!-- ค้นหา -->
                            <div class="form-inline">
                                <div class="form-group">
                                    <h4 class="mr-sm-2">สินค้า</h4>
                                    <h5 class="mr-sm-4" style="color:red;">(สูงกว่าจุดสั่งซื้อ)</h5>
                                </div>
                            </div>
                            <div class="form-inline mb-2">
                                <a href="product_show.php" class="btn-danger btn-sm">Back</a>
                            </div>
                            <?php
                            $search = isset($_GET['search']) ? $_GET['search'] : '';

                            $sql = "SELECT product.*,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,brand_name,color_name,class
                FROM  product
                INNER JOIN brand ON brand.brand_id = product.brand_id
                INNER JOIN unit ON unit.unit_id = product.unit_id
                LEFT JOIN cement ON cement.product_id = product.product_id
                LEFT JOIN categorycolor ON categorycolor.product_id = product.product_id
                LEFT JOIN toilet ON toilet.product_id = product.product_id
                LEFT JOIN chemicalsolution ON chemicalsolution.product_id = product.product_id
                LEFT JOIN craftmantool ON craftmantool.product_id = product.product_id
                LEFT JOIN plumbling ON plumbling.product_id = product.product_id
                 
                LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
                ORDER BY product.product_id";

                            $queryresult = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_array($queryresult);
                            ?>

                            <script>
                                $(document).ready(function() {
                                    $('#example').DataTable({
                                        "pageLength": 10,
                                        "order": [
                                            [0, 'asc']
                                        ]
                                    });
                                });
                            </script>

                            <?php
                            echo ' <table border="2" class="display table table-bordered" id="example" align="center">';
                            //หัวข้อตาราง
                            echo "
                            <thead>
                                <tr bgcolor='#AED6F1' align='center' style='font-weight:bold'>
                                    <th width='30%'>รหัสสินค้า</th>
                                    <th>ชื่อสินค้า</th>
                                    <th width='12%'>จุดสั่งซื้อ</th>
                                    <th width='13%'>จำนวนสต๊อก</th>
                                </tr>
                            </thead>";
                            do {
                                if ($row["product_id"] != '') {
                                    if ($row['product_stock'] > $row['product_reorder']) {
                                        echo "<tr>";
                                        echo "<td align='center'>" . $row["product_id"] . "</td> ";
                                        if ($row['product_name'] != '') {
                                            echo "<td>" . $row['product_name'];
                                        } ?>&ensp;<?php
                                                    if ($row['brand_name'] != '') {
                                                        echo $row['brand_name']; ?>&ensp;<?php }
                                                                                                                                                if ($row['color_name'] != '') {
                                                                                                                                                    echo "สี" . $row['color_name']; ?>&ensp;<?php }
                                                                                                                                                    if ($row['class'] != '') {
                                                                                                                                                        echo "ชั้น" . " " . $row['class']; ?>&ensp;<?php }
                                                                                                                                                        if ($row['tl_size'] != '') {
                                                                                                                                                            echo "ขนาด" . " " . $row['tl_size'];
                                                                                                                                                        }
                                                                                                                                                        if ($row['pb_size'] != '') {
                                                                                                                                                            echo "ขนาด" . " " . $row['pb_size'];
                                                                                                                                                        }
                                                                                                                                                        if ($row['ct_size'] != '') {
                                                                                                                                                            echo "ขนาด" . " " . $row['ct_size']; ?>&ensp;<?php }
                                                                                                                                                            if ($row['pb_thick'] != '') {
                                                                                                                                                                echo " " . "หนา" . " " . $row['pb_thick']; ?>&ensp;<?php }
                                                                                                                                                                if ($row['cc_volume'] != '') {
                                                                                                                                                                    echo $row['cc_volume'];
                                                                                                                                                                }
                                                                                                                                                                if ($row['cs_volume'] != '') {
                                                                                                                                                                    echo $row['cs_volume'];
                                                                                                                                                                }
                                                                                                                                                                if ($row['cm_volume'] != '') {
                                                                                                                                                                    echo $row['cm_volume'] . "</td> "; ?>&ensp;<?php }
                                                                                                                                                                echo "<td align='center' style='color:red;'>" . $row["product_reorder"] . "</td> ";
                                                                                                                                                                echo "<td align='center' style='color:blue;'>" . $row["product_stock"] . "</td> ";
                                                                                                                                                                // echo "<td align='center'>" . $row["sbs_price"] . $row["mortar_price"] . $row["cc_price"] . $row["att_price"] . $row["cmc_price"] . $row["ct_price"] . $row["pvc_price"] . " " . "บาท." . "</td> ";
                                                                                                                                                                echo "</tr>";
                                                                                                                                                            }
                                                                                                                                                        }
                                                                                                                                                    } while ($row = mysqli_fetch_array($queryresult));
                                                                                                                                                    echo "</table>";
                                                                                                                                                    //5. close connection
                                                                                                                                                    mysqli_close($conn);
                                                                                                                                                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>