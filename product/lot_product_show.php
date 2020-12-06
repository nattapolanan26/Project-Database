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
                <h4>สินค้าที่ไม่มีวันหมดอายุในแต่ละล็อต</h4>
                <form action="#" name="myformshow" id="myformshow" method="post">
                    <input type="hidden" name="date" value="<? $date = date('Y-m-d') ?>" />
                    <?php
                    $search = isset($_POST['search']) ? $_POST['search'] : '';
                    $sql = "SELECT lotproduct.*,product_name,brand.brand_name,unit.unit_name,color.color_name
                        FROM lotproduct
                        INNER JOIN product ON lotproduct.product_id = product.product_id
                        INNER JOIN brand ON brand.brand_id = product.brand_id 
                        INNER JOIN unit ON unit.unit_id = product.unit_id
                        LEFT JOIN cement ON cement.product_id = product.product_id
                        LEFT JOIN categorycolor ON categorycolor.product_id = product.product_id
                        LEFT JOIN toilet ON toilet.product_id = product.product_id
                        LEFT JOIN chemicalsolution ON chemicalsolution.product_id = product.product_id
                        LEFT JOIN craftmantool ON craftmantool.product_id = product.product_id
                        LEFT JOIN plumbling ON plumbling.product_id = product.product_id
                         
                        LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
                        ORDER BY product.product_id,lotproduct.lot_order";
                    $result = $conn->query($sql);
                    $row = mysqli_fetch_array($result);

                    ?>

                    <script>
                        $(document).ready(function() {
                            $('#example1').DataTable({
                                "product_id,lot_order": [
                                    [0, 'ASC']
                                ],
                                //"lengthMenu":[[20,50, 100, -1], [20,50, 100,"All"]]
                            });
                        });
                    </script>
                    <?php

                    echo '<table class="display table table-bordered" id="example1">';
                    //หัวข้อตาราง
                    echo "
                        <thead>
                        <tr bgcolor='#AED6F1' align='center' style='font-weight:bold' class='info'>
                        <th width='15%'>รหัสสินค้า</th>
                        <th width='5%'>#</td>
                        <th>ชื่อสินค้า</th>
                        <th width='10%'>จำนวน</td>
                        <th>วันที่รับเข้าล็อต</td>
                        <th>วันหมดอายุ</td>
                        </tr>
                        </thead>";
                    do {
                        if ($row['product_id'] != '') {
                            $lotdate = $row['lot_date'];
                            $exp_status = $row["exp_status"];
                            list($date, $time) = explode(' ', $lotdate); // แยกวันที่ กับ เวลาออกจากกัน
                            echo "<tr>";
                            echo "<td align='center'>" . $row['product_id'] . "</td> ";
                            echo "<td align='center'>" . $row['lot_order'] . "</td> ";
                            if ($row['product_name'] != '') {
                                echo "<td>" . $row['product_name'];
                            } ?>&ensp;<?php
                                                                                                        if ($row['brand_name'] != '') {
                                                                                                            echo $row['brand_name']; ?>&ensp;<?php }
                                                                                                        if ($row['color_name'] != '') {
                                                                                                            echo $row['color_name']; ?>&ensp;<?php }
                                                                                                        if ($row['class_name'] != '') {
                                                                                                            echo $row['class_name']; ?>&ensp;<?php }
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
                                                                                                        echo "<td align='right' style='color:blue;'>" . $row['lot_number'] . " " . $row['unit_name'] . "</td> ";
                                                                                                        echo "<td align='center'>" . $date . '/' . $time . "</td> ";
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