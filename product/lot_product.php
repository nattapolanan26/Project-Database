<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); error_reporting( error_reporting() & ~E_NOTICE ); ?>
</head>

<bod>
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
                        $rp_id = isset($_GET['rp_id']) ? $_GET['rp_id'] : '';
                        $search = isset($_POST['search']) ? $_POST['search'] : '';
                        $sql = "SELECT product.*,detailreceivepro.*,lotproduct.*,
                        brand.brand_name,unit.unit_name,color.color_name,class,
                        expproduct.product_id AS exp_pro_id,expproduct.lot_order AS exp_order,expproduct.exp_status,expproduct.exp_date,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume
                        FROM lotproduct 
                        INNER JOIN product ON product.product_id = lotproduct.product_id
                        INNER JOIN detailreceivepro ON detailreceivepro.rp_id = lotproduct.rp_id
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
                        WHERE lotproduct.rp_id = '$rp_id'
                        GROUP BY lotproduct.product_id
                        ORDER BY detailreceivepro.drp_order";
                        // echo $sql;
                        $result = $conn->query($sql);
                        $row = mysqli_fetch_array($result);
                        
                        ?>

                        <script>
                            $(document).ready(function() {
                                $('#example').DataTable({
                                "pageLength" : 10,
                                "order": [[ 0, 'asc' ]]
                                });
                            } );
					    </script>
                        <?php
                        
                        echo '<table class="display table table-bordered" id="example">';
                        //หัวข้อตาราง
                        echo "
                        <thead>
                        <tr bgcolor='#AED6F1' align='center' style='font-weight:bold' class='info'>
                        <th width='25%'>รหัสรับสินค้า</th>
                        <th>สินค้า</th>
                        <th width='15%'>ล็อต & วันหมดอายุ</th>
                        </tr>
                        </thead>";
                        do {
                            if($row['product_id'] != ''){
                            echo "<tr>";
                            echo "<td align='center'>" . $row['rp_id'] . "</td> ";
                            if($row['product_name'] != ''){echo "<td>" . $row['product_name'];} ?>&ensp;<?php
							if($row['brand_name'] != ''){echo $row['brand_name']; ?>&ensp;<?php } 
							if($row['color_name'] != ''){echo $row['color_name']; ?>&ensp;<?php } 
							if($row['class'] != ''){echo "ชั้น " .  $row['class']; ?>&ensp;<?php } 
							if($row['tl_size'] != ''){echo "ขนาด"." ".$row['tl_size'];} 
							if($row['pb_size'] != ''){echo "ขนาด"." ".$row['pb_size'];} 
							if($row['ct_size'] != ''){echo "ขนาด"." ".$row['ct_size']; ?>&ensp;<?php } 
							if($row['pb_thick'] != ''){echo " "."หนา"." ".$row['pb_thick']; ?>&ensp;<?php } 
							if($row['cc_volume'] != ''){echo $row['cc_volume'];}
							if($row['cs_volume'] != ''){echo $row['cs_volume'];} 
							if($row['cm_volume'] != ''){echo $row['cm_volume']. "</td> "; ?>&ensp;<?php } 
                            echo "<td align='center' ><a href='lot_product_check.php?p_id=$row[product_id]&rp_id=$row[rp_id]' class='btn btn-warning btn-xs'><i class='fa fa fa-calendar' aria-hidden='true'></i> วันหมดอายุ</a></td> ";
                            echo "</tr>";
                        }else{
                            echo "<script>";
                            echo "alert('ไม่มีสินค้าที่รับมา !!');";
                            echo "window.location.href='receive_show.php';";
                            echo "</script>";
                        } } while ($row =  mysqli_fetch_array($result)); echo '</table>' ?>
                       
                    </form>
                </div>
            </div>
        </div>
</body>

</html>