<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php');
    error_reporting(error_reporting() & ~E_NOTICE); ?>
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
                <h4>ล็อตสินค้า</h4>
                <form action="#" name="myformshow" id="myformshow" method="post">
                    <input type="hidden" name="date" value="<? $date = date('Y-m-d') ?>" />
                    <?php
                    $p_id = isset($_GET['p_id']) ? $_GET['p_id'] : '';
                    $rp_id = isset($_GET['rp_id']) ? $_GET['rp_id'] : '';

                    $sqllot = "SELECT product.*,lotproduct.product_id AS lot_pro_id,lotproduct.lot_order AS lot_order,lotproduct.lot_number,lotproduct.rp_id,lotproduct.lot_date,
                        brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,
                        expproduct.product_id AS exp_pro_id,expproduct.lot_order AS exp_order,expproduct.exp_status,expproduct.exp_date
                        FROM lotproduct 
                        LEFT JOIN product ON product.product_id = lotproduct.product_id
                        LEFT JOIN detailreceivepro ON detailreceivepro.rp_id = lotproduct.rp_id AND detailreceivepro.drp_order = lotproduct.lot_order
                        INNER JOIN brand ON brand.brand_id = product.brand_id
                        INNER JOIN unit ON unit.unit_id = product.unit_id
                        LEFT JOIN cement ON cement.product_id = product.product_id
                        LEFT JOIN categorycolor ON categorycolor.product_id = product.product_id
                        LEFT JOIN toilet ON toilet.product_id = product.product_id
                        LEFT JOIN chemicalsolution ON chemicalsolution.product_id = product.product_id
                        LEFT JOIN craftmantool ON craftmantool.product_id = product.product_id
                        LEFT JOIN plumbling ON plumbling.product_id = product.product_id
                         
                        LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
                        LEFT JOIN expproduct ON expproduct.product_id = lotproduct.product_id AND expproduct.lot_order = lotproduct.lot_order
                        WHERE lotproduct.product_id = '" . $p_id . "' AND lotproduct.rp_id = '" . $rp_id . "'
                        ORDER BY lotproduct.lot_order,lotproduct.lot_date";
                    $result = $conn->query($sqllot);
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
                        <th width='15%'>รหัสการรับ</th>
                        <th>ลำดับ</td>
                        <th>สินค้า</th>
                        <th>จำนวนที่รับ</td>
                        <th>วันที่รับ</td>
                        <th>วันที่หมดอายุ</td>
                        <th width='15%'>สถานะการขาย</th>
                        </tr>
                        </thead>";
                    do {
                        //จัดเรียง วัน/เดือน/ปี
                        $lot_date = $row['lot_date'];
                        $exp_date = $row['exp_date'];
                        $lot_id = $row['lot_pro_id'];
                        $exp_id = $row['exp_pro_id'];
                        $lot_order = $row['lot_order'];
                        $exp_order = $row['exp_order'];
                        $exp_status = $row['exp_status'];

                        if ($lot_id != '') {
                            list($date, $time) = explode(' ', $lot_date); // แยกวันที่ กับ เวลาออกจากกัน
                            list($exp_y, $exp_m, $exp_d) = explode('-', $exp_date);

                            echo "<tr>";
                            echo "<td align='center'>" . $row['rp_id'] . "</td> ";
                            echo "<td align='center''>" . $row['lot_order'] . "</td> ";
                            if ($row['product_name'] != '') {
                                echo "<td>" . $row['product_name'];
                            } ?>&ensp;<?php
                                                                                                        if ($row['brand_name'] != '') {
                                                                                                            echo $row['brand_name']; ?>&ensp;<?php }
                                                                                                        if ($row['color_name'] != '') {
                                                                                                            echo $row['color_name']; ?>&ensp;<?php }
                                                                                                        if ($row['class'] != '') {
                                                                                                            echo "ชั้น " . $row['class']; ?>&ensp;<?php }
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
                                                                                                        echo "<td align='right' style='color:green;'>" . $row['lot_number'] . "</td> ";
                                                                                                        echo "<td align='center' style='color:blue;'>" . $date . '/' . $time . "</td> ";

                                                                                                        //แสดงวันหมดอายุ
                                                                                                        if ($lot_id == $exp_id && $lot_order == $exp_order) {
                                                                                                            echo "<td align='center' style='color:red;'>" . $exp_d . '/' . $exp_m . '/' . $exp_y . "</a></td> ";
                                                                                                        } else {
                                                                                                            echo "<td align='center' style='color:red;'>" . "ไม่มีวันหมดอายุ" . "</td> ";
                                                                                                        }
                                                                                                        //สถานะสินค้าหมดอายุ
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