<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php');
    error_reporting(error_reporting() & ~E_NOTICE); ?>
    <script>
        function bootboxAlert() {
            bootbox.prompt("This is the default prompt!", function(result) {
                console.log(result);
            });
        }
    </script>
</head>

<body>
    <form action="#" name="receiveshow" id="receiveshow" method="post">
        <?php include('../connectdb.php'); ?>
        <?php include('../navbar.php'); ?>
        <div class="container-fluidid">
            <p></p>
            <div class="row">
                <div class="col-md-3">
                    <!-- Left side column. contains the logo and sidebar -->
                    <div class="color-login">
                        <h6><i class="fas fa-user-circle"></i>&ensp;<a style="font-weight:bold;"><?php echo "ผู้ใช้"; ?></a><a style="color:#c92828;font-weight:bold;"><?php echo " : " . $_SESSION['user']; ?></a></h6>
                        <h6><i class="fas fa-check-square"></i></i></i>&ensp;<a style="font-weight:bold;"><?php echo "ตำแหน่ง"; ?></a><a style="color:#1d4891;font-weight:bold;"><?php echo " : " . $_SESSION['posname']; ?></a></h6>
                    </div>
                    <?php include('../menu_left.php'); ?>
                    <!-- Content Wrapper. Contains page content -->
                </div>
                <div class="col-md-9">
                    <h4>สินค้าที่มีวันหมดอายุในแต่ละล็อต</h4>
                    <form action="#" name="myformshow" id="myformshow" method="post">
                        <?php
                        $search = isset($_POST['search']) ? $_POST['search'] : '';
                        $sql = "SELECT lotproduct.*,product_name,brand.brand_name,unit.unit_name,color.color_name,expproduct.*
                        FROM lotproduct
                        INNER JOIN product ON lotproduct.product_id = product.product_id
                        INNER JOIN brand ON brand.brand_id = product.brand_id 
                        INNER JOIN unit ON unit.unit_id = product.unit_id
                        LEFT JOIN expproduct ON expproduct.product_id = lotproduct.product_id AND expproduct.lot_order = lotproduct.lot_order
                        LEFT JOIN cement ON cement.product_id = product.product_id
                        LEFT JOIN categorycolor ON categorycolor.product_id = product.product_id
                        LEFT JOIN toilet ON toilet.product_id = product.product_id
                        LEFT JOIN chemicalsolution ON chemicalsolution.product_id = product.product_id
                        LEFT JOIN craftmantool ON craftmantool.product_id = product.product_id
                        LEFT JOIN plumbling ON plumbling.product_id = product.product_id
                         
                        LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
                        WHERE expproduct.product_id IS NOT NULL
                        ORDER BY expproduct.product_id,expproduct.lot_order";
                        $result = $conn->query($sql);
                        $row = mysqli_fetch_array($result);

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

                        echo '<table class="display table table-bordered" id="example">';
                        //หัวข้อตาราง
                        echo "
                        <thead>
                        <tr bgcolor='#AED6F1' align='center' style='font-weight:bold' class='info'>
                        <th width='15%'>รหัสสินค้า</th>
                        <th width='5%'>#</td>
                        <th>ชื่อสินค้า</th>
                        <th width='10%'>จำนวนที่รับเข้าล็อต</td>
                        <th>วันหมดอายุ</td>
                        <th>สถานะ</td>
                        </tr>
                        </thead>";
                        do {
                            $exp_status = $row['exp_status'];
                            //จัดเรียง วัน/เดือน/ปี
                            $expdate = $row['exp_date'];
                            list($y, $m, $d) = explode('-', $expdate);

                            if ($row['product_id'] != '') {
                                echo "<tr>";
                                echo "<td align='center'>" . $row['product_id'] . "</td> ";
                                echo "<td align='center'>" . $row['lot_order'] . "</td> ";
                                echo "<td>" . $row['product_name'] . $row["sbs_name"] . $row["mortar_name"] . $row["cc_name"] . $row["att_name"] . $row["cmc_name"] . $row["ct_name"] . $row["pvc_name"] . " " . $row["brand_name"] . " " . $row["color_name"] . " " . $row["class_name"] . " " . $row["size_name"] . "</td> ";
                                echo "<td align='right' style='color:blue;'>" . $row['lot_number'] . " " . $row['unit_name'] .  "</td> ";
                                echo "<td align='center' style='color:red'>" . $d . '/' . $m . '/' . $y . "</td> ";
                                if ($exp_status == '1') {
                                    echo "<td align='center' style='color:red;font-weight:bold;'>" . "Expired product" . "</td> ";
                                } elseif ($exp_status == '0') {
                                    echo "<td align='center' style='color:green;font-weight:bold;'>" . "Ready for sale" . "</td> ";
                                } else {
                                    echo "<td align='center' style='color:green;font-weight:bold;'>" . "Ready for sale" . "</td> ";
                                }
                                echo "</tr>";
                            }
                        } while ($row =  mysqli_fetch_array($result));
                        echo '</table>' ?>

                    </form>
                </div>
            </div>
        </div>
</body>

</html>