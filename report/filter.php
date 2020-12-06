<?php include('../connectdb.php');
if (isset($_POST["from_date"])) { ?>
    <html>

    <head>
        <script type="text/javascript">
            // Load google charts
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            // Draw the chart and set the chart values
            function drawChart($si_sum, $si_disc, $si_vat, $rc_sum, $rc_vat) {
                $s = parseInt($si_sum);
                $d = parseInt($si_disc);
                $v = parseInt($si_vat);
                $r = parseInt($rc_sum);
                $v2 = parseInt($rc_vat);
                console.log($s, $d, $v, $r, $v2);
                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['ยอดขายรวม', $s],
                    ['ภาษีมูลค่าเพิ่ม 7% - ยอดขาย', $v],
                    ['ส่วนลดโปรโมชั่น', $d],
                    ['รายจ่ายรวม', $r],
                    ['ภาษีมูลค่าเพิ่ม 7% - รายรับ', $v2],
                ]);

                // Optional; add a title and set the width and height of the chart
                var options = {
                    'title': 'ผลสรุปรายรับ - รายจ่าย',
                    'width': 1000,
                    'height': 600
                };

                // Display the chart inside the <div> element with id="piechart"
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }
        </script>
    </head>

    <body>
        <?php
        //filter.php
        $output = '';
        $pie = '';
        $si_sum = 0;
        $si_disc = 0;
        $si_vat = 0;
        $rc_sum = 0;
        $rc_vat = 0;
        echo $query = "
        SELECT COALESCE(SUM(si.s_total),0) AS si_sum,COALESCE(SUM(si.s_discount),0) AS si_disc,COALESCE(SUM(si.s_total*(7/100)),0) AS si_vat,COALESCE(SUM(si.s_total)+SUM(si.s_discount),0) AS si_total,COALESCE(SUM(drp.rp_sumprice),0) AS rc_sum,COALESCE(SUM(drp.rp_sumprice*(7/100)),0) AS rc_vat,COALESCE(SUM(drp.rp_sumprice)+SUM(drp.rp_sumprice*(7/100))-SUM(si.s_discount),0) AS rc_total
        FROM detailreceivepro drp 
        LEFT JOIN receiveproduct rp ON rp.rp_id = drp.rp_id
        LEFT JOIN lot ON lot.rp_id = drp.rp_id AND lot.rp_no = drp.rp_no AND lot.product_id = drp.product_id 
        LEFT JOIN sale_items si ON si.product_id = lot.product_id AND si.lot_order = lot.lot_order 
        LEFT JOIN sales_slip ss ON ss.s_id = si.s_id 
        WHERE (rp.date BETWEEN '" . $_POST['from_date'] . "') OR (ss.s_date BETWEEN '" . $_POST['from_date'] . "')
        HAVING rc_total != 0
        ";
        $result = mysqli_query($conn, $query);
        $output .= '
    <table class="content-table" id="tb_report" width="70%">
        <thead align="center">
            <tr>
                <th colspan="4" scope="colgroup">สรุปรายรับ/ยอดขาย</th>
                <th colspan="3" scope="colgroup">สรุปรายจ่าย/สั่งซื้อ</th>
                <th scope="colgroup">ส่วนต่างรายได้</th>
            </tr>
        </thead>
    ';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $si_sum = $row['si_sum'];
                $si_disc = $row['si_disc'];
                $si_vat = $row['si_vat'];
                $si_total = $row['si_total'];
                $rc_sum = $row['rc_sum'];
                $rc_vat = $row['rc_vat'];
                $rc_total = $row['rc_total'];
                $output .= '
            <tbody>
                <tr align="center">
                    <td>ยอดขายรวม</td>
                    <td>ส่วนลด</td>
                    <td>ภาษีมูลค่าเพิ่ม 7%</td>
                    <td>ยอดขายสุทธิ</td>
                    <td>รายจ่ายรวม</td>
                    <td>ภาษีมูลค่าเพิ่ม 7%</td>
                    <td>รายจ่ายสุทธิ</td>
                    <td>จำนวน/บาท</td>
                </tr>
                <tr align="center">
                    <td>' . number_format($si_sum, 2) . '</td>
                    <td>' . number_format($si_disc, 2) . '</td>
                    <td>' . number_format($si_vat, 2) . '</td>
                    <td>' . number_format($si_total, 2) . '</td>
                    <td>' . number_format($rc_sum, 2) . '</td>
                    <td>' . number_format($rc_vat, 2) . '</td>
                    <td>' . number_format($rc_total, 2) . '</td>
                    <td>' . number_format($si_total - $rc_total, 2) . '</td>
                </tr>
            </tbody>
            ';
            }
        } else {
            $output .= '
            <tr>
                <td colspan="8" align="center">ไม่มีข้อมูล</td>
            </tr>
        ';
        }
        $output .= '</table><br><br>';
        echo $output;
        // กด icons เพื่อแสดงกราฟ
        echo '<i class="fas fa-chart-pie fa-4x" onClick="drawChart(\'' . $si_sum . '\',\'' . $si_disc . '\',\'' . $si_vat . '\',\'' . $rc_sum . '\',\'' . $rc_vat . '\')"></i>&ensp;แสดงกราฟเป็น %';
        $pie .= '<center><div id="piechart" style="height: 470px;"></div></center>';
        echo $pie;
        ?>
    </body>

    </html>
<?php
}
if (isset($_POST["from_date_sale"])) { ?>
    <html>

    <head>
    <script type="text/javascript">
            // Load google charts
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            // Draw the chart and set the chart values
            function drawChart($total, $discount, $vat) {
                $t = parseInt($total);
                $d = parseInt($discount);
                $v = parseInt($vat);
      
                console.log($t, $d, $v);
                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['ยอดรวมทั้งสิ้น', $t],
                    ['ส่วนลดโปรโมชั่น', $d],
                    ['ภาษีมูลค่าเพิ่ม 7%', $v],
                ]);

                // Optional; add a title and set the width and height of the chart
                var options = {
                    'title': 'ผลสรุปยอดขายตามช่วงเวลา',
                    'width': 1000,
                    'height': 600
                };

                // Display the chart inside the <div> element with id="piechart"
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }
        </script>
    </head>

    <body>
        <?php
        //filter.php
        $output = '';
        $pie= '';
        $total = 0;
        $discount = 0;
        $vat = 0;
        $profit = 0;
        $query = "
        SELECT s_date,si.product_id,si.amount,cp.costprice,si.disc,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,pb.class
        FROM sales_slip ss
        INNER JOIN
        (SELECT s_id,product_id,SUM(s_amount) AS amount,SUM(s_discount) AS disc FROM sale_items GROUP BY product_id) si ON si.s_id = ss.s_id
        INNER JOIN costprice cp ON cp.product_id = si.product_id
        INNER JOIN product pd ON pd.product_id = cp.product_id
        INNER JOIN brand b ON b.brand_id = pd.brand_id
        INNER JOIN unit u ON u.unit_id = pd.unit_id
        LEFT JOIN cement cm ON cm.product_id = pd.product_id
        LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
        LEFT JOIN toilet tl ON tl.product_id = pd.product_id
        LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
        LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
        LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
        LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
        WHERE ss.s_date BETWEEN '" . $_POST['from_date_sale'] . "'
        GROUP BY si.product_id
        ";
        $result = mysqli_query($conn, $query);
        $output .= '
    <table class="content-table" id="tb_report" width="80%">
        <thead align="center">
            <tr>
                <th>รหัสสินค้า</th>
                <th>รายการสินค้า</th>
                <th>จำนวนที่ขายได้</th>
                <th>ราคา/หน่วย</th>
                <th>ส่วนลด/บาท</th>
                <th>ราคารวม/บาท</th>
            </tr>
        </thead>
    ';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $p_id = $row['product_id'];
                $brand_name = $row['brand_name'];
                $amount = $row['amount'];
                $costprice = $row['costprice'];
                $sum_price = $row['costprice'] * $row['amount'];
                $disc = $row['disc'];
                $total = $total + $sum_price;
                $discount = $discount + $disc;
                $vat = $total * (7 / 100);
                $profit = $total - $discount - $vat;
                $output .= '
            <tbody>
                <tr>
                    <td align="center">' . $p_id . '</td>
                    ';
                if ($row['product_name'] != '') {
                    $output .= "<td>" . $row['product_name'] . " ";
                }
                if ($row['brand_name'] != '') {
                    $output .= $row['brand_name'];
                }
                if ($row['color_name'] != '') {
                    $output .= " สี" . $row['color_name'];
                }
                if ($row['class'] != '') {
                    $output .= " ชั้น " . $row['class'];
                }
                if ($row['tl_size'] != '') {
                    $output .= " ขนาด (" . $row['tl_size'] . ")";
                }
                if ($row['pb_size'] != '') {
                    $output .= " ขนาด (" . $row['pb_size'] . ")";
                }
                if ($row['ct_size'] != '') {
                    $output .= " ขนาด (" . $row['ct_size'] . ")";
                }
                if ($row['pb_thick'] != '') {
                    $output .= " หนา " . $row['pb_thick'];
                }
                if ($row['cc_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cc_volume'];
                }
                if ($row['cs_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cs_volume'];
                }
                if ($row['cm_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cm_volume'] . "</td> ";
                };
                $output .= ' 
                    <td align="center">' . $amount . '</td>
                    <td align="right">' . number_format($costprice, 2) . '</td>
                    <td align="right">' . number_format($disc, 2) . '</td>
                    <td align="right">' . number_format($sum_price, 2) . '</td>
                </tr>
            </tbody>
            ';
            }
        } else {
            $output .= '
            <tr>
                <td colspan="8" align="center">ไม่มีข้อมูล</td>
            </tr>
        ';
        }
        $output .= '<tr><td colspan="5" align="right">ยอดรวมทั้งสิ้น : </td><td align="right">' . number_format($total, 2) . '</td></tr>';
        $output .= '<tr><td colspan="5" align="right">ส่วนลดโปรโมชั่น : </td><td align="right">' . number_format($discount, 2) . '</td></tr>';
        $output .= '<tr><td colspan="5" align="right">ภาษีมูลค่าเพิ่ม 7% : </td><td align="right">' . number_format($vat, 2) . '</td></tr>';
        $output .= '<tr><td colspan="5" align="right">รายได้สุทธิ : </td><td align="right"><u>' . number_format($profit, 2) . '</u></td></tr>';
        $output .= '</table><br><br>';
        echo $output;
        // กด icons เพื่อแสดงกราฟ
        echo '<i class="fas fa-chart-pie fa-4x" onClick="drawChart(\'' . $total . '\',\'' . $discount . '\',\'' . $vat . '\')"></i>&ensp;แสดงกราฟเป็น %';
        $pie .= '<center><div id="piechart" style="height: 600px;"></div></center>';
        echo $pie;
        ?>
    </body>

    </html>
<?php
}
if (isset($_POST["from_date_sale_y"])) { ?>
    <html>

    <head>
    <script type="text/javascript">
            // Load google charts
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            // Draw the chart and set the chart values
            function drawChart($total, $discount, $vat) {
                $t = parseInt($total);
                $d = parseInt($discount);
                $v = parseInt($vat);
         
                console.log($t, $d, $v);
                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['รายได้สุทธิ', $t],
                    ['ส่วนลดโปรโมชั่น', $d],
                    ['ภาษีมูลค่าเพิ่ม 7%', $v],
                ]);

                // Optional; add a title and set the width and height of the chart
                var options = {
                    'title': 'ผลสรุปยอดขายประจำปี',
                    'width': 1000,
                    'height': 600
                };

                // Display the chart inside the <div> element with id="piechart"
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }
        </script>
    </head>

    <body>
        <?php
        //filter.php
        $output = '';
        $pie = '';
        $pd1 = 0;
        $pd2 = 0;
        $pd3 = 0;
        $pd4 = 0;
        $pd5 = 0;
        $pd6 = 0;
        $pd7 = 0;
        $pd8 = 0;
        $pd9 = 0;
        $pd10 = 0;
        $pd11 = 0;
        $pd12 = 0;
        $priceTo1 = 0;
        $priceTotalN = 0;
        $total = 0;
        $discount = 0;
        $vat = 0;

        $year = $_POST["from_date_sale_y"];
        $query = "
        SELECT si.product_id,COUNT(si.product_id) AS p_num,SUM(si.s_amount) AS amount,SUM(si.s_total) AS total,sum(si.s_discount) AS disc,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,pb.class
        FROM sales_slip ss 
        INNER JOIN sale_items si ON si.s_id = ss.s_id
        INNER JOIN product pd ON pd.product_id = si.product_id 
        INNER JOIN brand b ON b.brand_id = pd.brand_id 
        INNER JOIN unit u ON u.unit_id = pd.unit_id 
        LEFT JOIN cement cm ON cm.product_id = pd.product_id 
        LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id 
        LEFT JOIN toilet tl ON tl.product_id = pd.product_id 
        LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id 
        LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id 
        LEFT JOIN plumbling pb ON pb.product_id = pd.product_id 
        LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
        WHERE ss.s_date LIKE '" . $year . "%'
        GROUP BY si.product_id
        ";
        $result = mysqli_query($conn, $query);
        $output .= '
    <table class="content-table" id="tb_report" width="150%">
        <thead align="center">
            <tr>
                <th>รหัสสินค้า</th>
                <th>รายการสินค้า</th>
                <th>จำนวนที่ขายได้</th>
                <th>มกราคม</th>
                <th>กุมภาพันธ์</th>
                <th>มีนาคม</th>
                <th>เมษายน</th>
                <th>พฤษภาคม</th>
                <th>มิถุนายน</th>
                <th>กรกฎาคม</th>
                <th>สิงหาคม</th>
                <th>กันยายน</th>
                <th>ตุลาคม</th>
                <th>พฤศจิกายน</th>
                <th>ธันวาคม</th>
                <th>ส่วนลด/บาท</th>
                <th>ราคารวม/บาท</th>
            </tr>
        </thead>
    ';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $p_id = $row['product_id'];
                $brand_name = $row['brand_name'];
                $amount = $row['amount'];
                // $costprice = $row['costprice'];
                $disc = $row['disc'];
                $discount = $discount + $disc;
                $priceTotal = $row['total'] * $row['amount']; //ราคารวม

                // ------------ เดือน มกราคม ------------
                $m1 = $year . '-01';
                $price_buy1 = 0;

                $txtSQL1 = "
                            SELECT si.s_total 
                            FROM sales_slip ss 
                            INNER JOIN sale_items si ON si.s_id = ss.s_id 
                            WHERE ss.s_date LIKE '" . $m1 . "%' AND si.product_id = '$p_id'
                        ";
                $query1 = mysqli_query($conn, $txtSQL1);

                while ($row1 = mysqli_fetch_assoc($query1)) {

                    $price_buy1 += $row1['s_total'];
                }
                $p1 = $price_buy1 * $amount;

                // ------------ เดือน กุมภาพันธ์ ------------
                $m2 = $year . '-02';
                $price_buy2 = 0;

                $txtSQL2 = "
                            SELECT si.s_total 
                            FROM sales_slip ss 
                            INNER JOIN sale_items si ON si.s_id = ss.s_id 
                            WHERE ss.s_date LIKE '" . $m2 . "%' AND si.product_id = '$p_id'
                        ";
                $query2 = mysqli_query($conn, $txtSQL2);

                while ($row2 = mysqli_fetch_assoc($query2)) {

                    $price_buy2 += $row2['s_total'];
                }
                $p2 = $price_buy2 * $amount;

                // ------------ เดือน มีนาคม ------------
                $m3 = $year . '-03';
                $price_buy3 = 0;

                $txtSQL3 = "
                            SELECT si.s_total 
                            FROM sales_slip ss 
                            INNER JOIN sale_items si ON si.s_id = ss.s_id 
                            WHERE ss.s_date LIKE '" . $m3 . "%' AND si.product_id = '$p_id'
                        ";
                $query3 = mysqli_query($conn, $txtSQL3);

                while ($row3 = mysqli_fetch_assoc($query3)) {

                    $price_buy3 += $row3['s_total'];
                }
                $p3 = $price_buy3 * $amount;

                // ------------ เดือน เมษยน------------
                $m4 = $year . '-04';
                $price_buy4 = 0;

                $txtSQL4 = "
                            SELECT si.s_total 
                            FROM sales_slip ss 
                            INNER JOIN sale_items si ON si.s_id = ss.s_id 
                            WHERE ss.s_date LIKE '" . $m4 . "%' AND si.product_id = '$p_id'
                        ";
                $query4 = mysqli_query($conn, $txtSQL4);

                while ($row4 = mysqli_fetch_assoc($query4)) {

                    $price_buy4 += $row3['s_total'];
                }
                $p4 = $price_buy4 * $amount;

                // ------------ เดือน พฤษภาคม------------
                $m5 = $year . '-05';
                $price_buy5 = 0;

                $txtSQL5 = "
                            SELECT si.s_total 
                            FROM sales_slip ss 
                            INNER JOIN sale_items si ON si.s_id = ss.s_id 
                            WHERE ss.s_date LIKE '" . $m5 . "%' AND si.product_id = '$p_id'
                            ";
                $query5 = mysqli_query($conn, $txtSQL5);

                while ($row5 = mysqli_fetch_assoc($query5)) {

                    $price_buy5 += $row5['s_total'];
                }
                $p5 = $price_buy5 * $amount;

                // ------------ เดือน มิถุนายน------------
                $m6 = $year . '-06';
                $price_buy6 = 0;

                $txtSQL6 = "
                            SELECT si.s_total 
                            FROM sales_slip ss 
                            INNER JOIN sale_items si ON si.s_id = ss.s_id 
                            WHERE ss.s_date LIKE '" . $m6 . "%' AND si.product_id = '$p_id'
                            ";
                $query6 = mysqli_query($conn, $txtSQL6);

                while ($row6 = mysqli_fetch_assoc($query6)) {

                    $price_buy6 += $row6['s_total'];
                }
                $p6 = $price_buy6 * $amount;

                // ------------ เดือน กรกฎาคม------------
                $m7 = $year . '-07';
                $price_buy7 = 0;

                $txtSQL7 = "
                             SELECT si.s_total 
                             FROM sales_slip ss 
                             INNER JOIN sale_items si ON si.s_id = ss.s_id 
                             WHERE ss.s_date LIKE '" . $m7 . "%' AND si.product_id = '$p_id'
                             ";
                $query7 = mysqli_query($conn, $txtSQL7);

                while ($row7 = mysqli_fetch_assoc($query7)) {

                    $price_buy7 += $row7['s_total'];
                }
                $p7 = $price_buy7 * $amount;


                // ------------ เดือน สิงหาคม------------
                $m8 = $year . '-08';
                $price_buy8 = 0;

                $txtSQL8 = "
                            SELECT si.s_total 
                            FROM sales_slip ss 
                            INNER JOIN sale_items si ON si.s_id = ss.s_id 
                            WHERE ss.s_date LIKE '" . $m8 . "%' AND si.product_id = '$p_id'
                            ";
                $query8 = mysqli_query($conn, $txtSQL8);

                while ($row8 = mysqli_fetch_assoc($query8)) {

                    $price_buy8 += $row8['s_total'];
                }
                $p8 = $price_buy8 * $amount;

                // ------------ เดือน กันยายน------------
                $m9 = $year . '-09';
                $price_buy9 = 0;

                $txtSQL9 = "
                             SELECT si.s_total 
                             FROM sales_slip ss 
                             INNER JOIN sale_items si ON si.s_id = ss.s_id 
                             WHERE ss.s_date LIKE '" . $m9 . "%' AND si.product_id = '$p_id'
                             ";
                $query9 = mysqli_query($conn, $txtSQL9);

                while ($row9 = mysqli_fetch_assoc($query9)) {

                    $price_buy9 += $row9['s_total'];
                }
                $p9 = $price_buy9 * $amount;

                // ------------ เดือน ตุลาคม------------
                $m10 = $year . '-10';
                $price_buy10 = 0;

                $txtSQL10 = "
                              SELECT si.s_total 
                              FROM sales_slip ss 
                              INNER JOIN sale_items si ON si.s_id = ss.s_id 
                              WHERE ss.s_date LIKE '" . $m10 . "%' AND si.product_id = '$p_id'
                              ";
                $query10 = mysqli_query($conn, $txtSQL10);

                while ($row10 = mysqli_fetch_assoc($query10)) {

                    $price_buy10 += $row10['s_total'];
                }
                $p10 = $price_buy10 * $amount;

                // ------------ เดือน พฤศจิกายน------------
                $m11 = $year . '-11';
                $price_buy11 = 0;

                $txtSQL11 = "
                                SELECT si.s_total 
                                FROM sales_slip ss 
                                INNER JOIN sale_items si ON si.s_id = ss.s_id 
                                WHERE ss.s_date LIKE '" . $m11 . "%' AND si.product_id = '$p_id'
                                ";
                $query11 = mysqli_query($conn, $txtSQL11);

                while ($row11 = mysqli_fetch_assoc($query11)) {

                    $price_buy11 += $row11['s_total'];
                }
                $p11 = $price_buy11 * $amount;


                // ------------ เดือน พฤศจิกายน------------
                $m12 = $year . '-12';
                $price_buy12 = 0;

                $txtSQL12 = "
                                SELECT si.s_total 
                                FROM sales_slip ss 
                                INNER JOIN sale_items si ON si.s_id = ss.s_id 
                                WHERE ss.s_date LIKE '" . $m12 . "%' AND si.product_id = '$p_id'
                                ";
                $query12 = mysqli_query($conn, $txtSQL12);

                while ($row12 = mysqli_fetch_assoc($query12)) {

                    $price_buy12 += $row12['s_total'];
                }
                $p12 = $price_buy12 * $amount;

                $output .= '
            <tbody>
                <tr>
                    <td align="center">' . $p_id . '</td>
                    ';
                if ($row['product_name'] != '') {
                    $output .= "<td>" . $row['product_name'] . " ";
                }
                if ($row['brand_name'] != '') {
                    $output .= $row['brand_name'];
                }
                if ($row['color_name'] != '') {
                    $output .= " สี" . $row['color_name'];
                }
                if ($row['class'] != '') {
                    $output .= " ชั้น " . $row['class'];
                }
                if ($row['tl_size'] != '') {
                    $output .= " ขนาด (" . $row['tl_size'] . ")";
                }
                if ($row['pb_size'] != '') {
                    $output .= " ขนาด (" . $row['pb_size'] . ")";
                }
                if ($row['ct_size'] != '') {
                    $output .= " ขนาด (" . $row['ct_size'] . ")";
                }
                if ($row['pb_thick'] != '') {
                    $output .= " หนา " . $row['pb_thick'];
                }
                if ($row['cc_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cc_volume'];
                }
                if ($row['cs_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cs_volume'];
                }
                if ($row['cm_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cm_volume'] . "</td> ";
                };

                $output .= ' 
                    <td align="center">' . $amount . '</td>
                    <td align="right">' . number_format($p1, 2) . '</td>
                    <td align="right">' . number_format($p2, 2) . '</td>
                    <td align="right">' . number_format($p3, 2) . '</td>
                    <td align="right">' . number_format($p4, 2) . '</td>
                    <td align="right">' . number_format($p5, 2) . '</td>
                    <td align="right">' . number_format($p6, 2) . '</td>
                    <td align="right">' . number_format($p7, 2) . '</td>
                    <td align="right">' . number_format($p8, 2) . '</td>
                    <td align="right">' . number_format($p9, 2) . '</td>
                    <td align="right">' . number_format($p10, 2) . '</td>
                    <td align="right">' . number_format($p11, 2) . '</td>
                    <td align="right">' . number_format($p12, 2) . '</td>
                    <td align="right">' . number_format($disc, 2) . '</td>
                    <td align="right">' . number_format($priceTotal, 2) . '</td>
                </tr>
            </tbody>
            ';
                $pd1 += $p1;
                $pd2 += $p2;
                $pd3 += $p3;
                $pd4 += $p4;
                $pd5 += $p5;
                $pd6 += $p6;
                $pd7 += $p7;
                $pd8 += $p8;
                $pd9 += $p9;
                $pd10 += $p10;
                $pd11 += $p11;
                $pd12 += $p12;
                $priceTo1 += $priceTotal;
            }

            $output .= '
             <tr align="right" style="font-weight:bold;background-color:#F7F7F9">
                <td style="font-weight:bold" colspan="3" align="center">ราคารวม</td>
                <td>' . number_format($pd1, 2) . '</td> 
                <td>' . number_format($pd2, 2) . '</td> 
                <td>' . number_format($pd3, 2) . '</td> 
                <td>' . number_format($pd4, 2) . '</td> 
                <td>' . number_format($pd5, 2) . '</td> 
                <td>' . number_format($pd6, 2) . '</td> 
                <td>' . number_format($pd7, 2) . '</td> 
                <td>' . number_format($pd8, 2) . '</td> 
                <td>' . number_format($pd9, 2) . '</td> 
                <td>' . number_format($pd10, 2) . '</td> 
                <td>' . number_format($pd11, 2) . '</td> 
                <td>' . number_format($pd12, 2) . '</td> 
                <td>' . number_format($discount, 2) . '</td> 
                <td>' . number_format($priceTo1, 2) . '</td> 

            </tr>
            ';
        } else {
            $output .= '
            <tr>
                <td colspan="16" align="center">ไม่มีข้อมูล</td>
            </tr>
        ';
        }
        $vat = $priceTo1 * (7 / 100);
        $total = $priceTo1 - $discount - $vat;
        $output .= '<tr><td colspan="16" align="right">ยอดรวมทั้งสิ้น : </td><td align="right">' . number_format($priceTo1, 2) . '</td></tr>';
        $output .= '<tr><td colspan="16" align="right">ส่วนลดโปรโมชั่น : </td><td align="right">' . number_format($discount, 2) . '</td></tr>';
        $output .= '<tr><td colspan="16" align="right">ภาษีมูลค่าเพิ่ม 7% : </td><td align="right">' . number_format($vat, 2) . '</td></tr>';
        $output .= '<tr><td colspan="16" align="right">กำไรทั้งสิ้น : </td><td align="right"><u>' . number_format($total, 2) . '</u></td></tr>';
        $output .= '</table><br><br>';
        echo $output;
        // กด icons เพื่อแสดงกราฟ
        echo '<i class="fas fa-chart-pie fa-4x" onClick="drawChart(\'' . $total . '\',\'' . $discount . '\',\'' . $vat . '\')"></i>&ensp;แสดงกราฟเป็น %';
        $pie .= '<center><div id="piechart" style="height: 600px;"></div></center>';
        echo $pie;
        ?>
    </body>

    </html>
<?php
}
if (isset($_POST["from_claim_date"])) { ?>
    <html>

    <head>
    <script type="text/javascript">
            // Load google charts
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            // Draw the chart and set the chart values
            function drawChart($shop_amount, $cpn_amount, $rc_amount) {
                $shop = parseInt($shop_amount);
                $cpn = parseInt($cpn_amount);
                $rc_cpn = parseInt($rc_amount);
         
                console.log($shop, $cpn, $rc_cpn);
                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['จำนวนที่เคลมกับร้าน', $shop],
                    ['จำนวนที่เคลมกับบริษัทคู่ค้า', $cpn],
                    ['จำนวนที่รับเคลมจากบริษัทคู่ค้า', $rc_cpn],
                ]);

                // Optional; add a title and set the width and height of the chart
                var options = {
                    'title': 'ผลสรุปสินค้าเคลม',
                    'width': 1000,
                    'height': 600
                };

                // Display the chart inside the <div> element with id="piechart"
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }
        </script>
    </head>

    <body>
        <?php
        //filter.php
        $output = '';
        $pie = '';
        $shop_amount = 0;
        $cpn_amount = 0;
        $rc_amount = 0;
        $sum_price = 0;
        $total = 0;
       $query = "
        SELECT c1.cl_date,c1.cl_status,c1.choice_status,c2.product_id,count(c2.product_id) AS p_id,sum(c2.cl_amount) AS amount,SUM(c2.cl_price) AS price,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,pb.class
        FROM claim_ctm_h c1
        INNER JOIN claim_ctm_list c2 ON c2.cl_id = c1.cl_id
        INNER JOIN product pd ON pd.product_id = c2.product_id
        INNER JOIN brand b ON b.brand_id = pd.brand_id 
        INNER JOIN unit u ON u.unit_id = pd.unit_id 
        LEFT JOIN cement cm ON cm.product_id = pd.product_id 
        LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id 
        LEFT JOIN toilet tl ON tl.product_id = pd.product_id 
        LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id 
        LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id 
        LEFT JOIN plumbling pb ON pb.product_id = pd.product_id 
        LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
        WHERE c1.cl_date  BETWEEN '" . $_POST['from_claim_date'] . "'
        GROUP BY c2.product_id
        ORDER BY c2.product_id ASC
        ";
        $result = mysqli_query($conn, $query);
        $output .= '
        รายการส่งเคลมกับทางร้าน
        <p></p>
        <table class="content-table" id="tb_report" width="80%">
            <thead align="center">
                <tr>
                    <th>รหัสสินค้า</th>
                    <th>รายการสินค้า</th>
                    <th>จำนวนที่เคลม</th>
                </tr>
            </thead>
        ';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                // $id_claim = $row['cl_id'];
                $p_id = $row['product_id'];
                $shop_amount = $row['amount'];
                $price = $row['price'];
                $sum_price = $sum_price + $price;
                $output .= '
            <tbody>
                <tr>
                    <td align="center">' . $p_id . '</td>
                    ';
                if ($row['product_name'] != '') {
                    $output .= "<td>" . $row['product_name'] . " ";
                }
                if ($row['brand_name'] != '') {
                    $output .= $row['brand_name'];
                }
                if ($row['color_name'] != '') {
                    $output .= " สี" . $row['color_name'];
                }
                if ($row['class'] != '') {
                    $output .= " ชั้น " . $row['class'];
                }
                if ($row['tl_size'] != '') {
                    $output .= " ขนาด (" . $row['tl_size'] . ")";
                }
                if ($row['pb_size'] != '') {
                    $output .= " ขนาด (" . $row['pb_size'] . ")";
                }
                if ($row['ct_size'] != '') {
                    $output .= " ขนาด (" . $row['ct_size'] . ")";
                }
                if ($row['pb_thick'] != '') {
                    $output .= " หนา " . $row['pb_thick'];
                }
                if ($row['cc_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cc_volume'];
                }
                if ($row['cs_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cs_volume'];
                }
                if ($row['cm_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cm_volume'] . "</td> ";
                }
                $output .= ' 
                    <td align="center">' . $shop_amount . '</td>
                ';
            }
        } else {
            $output .= '
            <tr>
                <td colspan="3" align="center">ไม่มีข้อมูล</td>
            </tr>
        ';
        }
        $output .= '</table>';

        $query = "
        SELECT c3.cpn_id,cpn.cpn_name,c1.cl_date,c1.cl_status,c1.choice_status,c2.product_id,count(c2.product_id) AS p_id,sum(c2.cl_amount) AS amount,SUM(c2.cl_price) AS price,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,pb.class
        FROM claim_ctm_h c1
        INNER JOIN claim_ctm_list c2 ON c2.cl_id = c1.cl_id
        INNER JOIN claim_cpn_list c3 ON c3.cl_id = c2.cl_id AND c3.ccp_no = c2.cl_no
        INNER JOIN company cpn ON cpn.cpn_id = c3.cpn_id
        INNER JOIN product pd ON pd.product_id = c2.product_id
        INNER JOIN brand b ON b.brand_id = pd.brand_id 
        INNER JOIN unit u ON u.unit_id = pd.unit_id 
        LEFT JOIN cement cm ON cm.product_id = pd.product_id 
        LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id 
        LEFT JOIN toilet tl ON tl.product_id = pd.product_id 
        LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id 
        LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id 
        LEFT JOIN plumbling pb ON pb.product_id = pd.product_id 
        LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
        WHERE c1.cl_date  BETWEEN '" . $_POST['from_claim_date'] . "' AND c1.choice_status = '2'
        GROUP BY c2.product_id
        ORDER BY c2.product_id ASC
        ";
        $result = mysqli_query($conn, $query);
        $output .= '
        <p></p>
        รายการส่งเคลมกับทางบริษัทคู่ค้า
        <p></p>
        <table class="content-table" id="tb_report" width="80%">
            <thead align="center">
                <tr>
                    <th>รหัสสินค้า</th>
                    <th>รายการสินค้า</th>
                    <th>บริษัทคู่ค้า</th>
                    <th>จำนวนที่เคลม</th>
                </tr>
            </thead>
        ';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                // $id_claim = $row['cl_id'];
                $p_id = $row['product_id'];
                $cpn_name = $row['cpn_name'];
                $cpn_amount = $row['amount'];

                $output .= '
            <tbody>
                <tr>
                    <td align="center">' . $p_id . '</td>
                    ';
                if ($row['product_name'] != '') {
                    $output .= "<td>" . $row['product_name'] . " ";
                }
                if ($row['brand_name'] != '') {
                    $output .= $row['brand_name'];
                }
                if ($row['color_name'] != '') {
                    $output .= " สี" . $row['color_name'];
                }
                if ($row['class'] != '') {
                    $output .= " ชั้น " . $row['class'];
                }
                if ($row['tl_size'] != '') {
                    $output .= " ขนาด (" . $row['tl_size'] . ")";
                }
                if ($row['pb_size'] != '') {
                    $output .= " ขนาด (" . $row['pb_size'] . ")";
                }
                if ($row['ct_size'] != '') {
                    $output .= " ขนาด (" . $row['ct_size'] . ")";
                }
                if ($row['pb_thick'] != '') {
                    $output .= " หนา " . $row['pb_thick'];
                }
                if ($row['cc_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cc_volume'];
                }
                if ($row['cs_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cs_volume'];
                }
                if ($row['cm_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cm_volume'] . "</td> ";
                }
                $output .= ' 
                    <td align="center">' . $cpn_name . '</td>
                    <td align="center">' . $cpn_amount . '</td>
                ';
            }
        } else {
            $output .= '
            <tr>
                <td colspan="4" align="center">ไม่มีข้อมูล</td>
            </tr>
        ';
        }
        $output .= '</table>';


        $query = "
        SELECT c3.cpn_id,cpn.cpn_name,c2.cr_date,c4.product_id,count(c4.product_id) AS p_id,SUM(c1.cr_amount) AS rc_amount,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,class,c5.choice_status
        FROM claim_receive_list c1
        INNER JOIN claim_receive_h c2 ON c1.cr_id = c2.cr_id
        INNER JOIN claim_cpn_list c3 ON c3.ccp_id = c1.ccp_id AND c3.ccp_no = c1.cr_no
        INNER JOIN claim_ctm_list c4 ON c4.cl_id = c3.cl_id AND c4.cl_no = c3.ccp_no
        INNER JOIN claim_ctm_h c5 ON c5.cl_id = c4.cl_id
        INNER JOIN company cpn ON cpn.cpn_id = c3.cpn_id
        INNER JOIN product pd ON pd.product_id = c4.product_id
        INNER JOIN brand b ON b.brand_id = pd.brand_id 
        INNER JOIN unit u ON u.unit_id = pd.unit_id 
        LEFT JOIN cement cm ON cm.product_id = pd.product_id 
        LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id 
        LEFT JOIN toilet tl ON tl.product_id = pd.product_id 
        LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id 
        LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id 
        LEFT JOIN plumbling pb ON pb.product_id = pd.product_id 
          
        LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
        WHERE c2.cr_date BETWEEN '" . $_POST['from_claim_date'] . "' AND c5.choice_status = '2'
        GROUP BY c4.product_id
        ORDER BY c4.product_id ASC
        ";
        $result = mysqli_query($conn, $query);
        $output .= '
        <p></p>
        รายการรับสินค้าเคลมกับบริษัทคู่ค้า
        <p></p>
        <table class="content-table" id="tb_report" width="80%">
            <thead align="center">
                <tr>
                    <th>รหัสสินค้า</th>
                    <th>รายการสินค้า</th>
                    <th>บริษัทคู่ค้า</th>
                    <th>จำนวนที่รับ</th>
                </tr>
            </thead>
        ';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                // $id_claim = $row['cl_id'];
                $p_id = $row['product_id'];
                $cpn_name = $row['cpn_name'];
                $rc_amount = $row['rc_amount'];
                $output .= '
            <tbody>
                <tr>
                    <td align="center">' . $p_id . '</td>
                    ';
                if ($row['product_name'] != '') {
                    $output .= "<td>" . $row['product_name'] . " ";
                }
                if ($row['brand_name'] != '') {
                    $output .= $row['brand_name'];
                }
                if ($row['color_name'] != '') {
                    $output .= " สี" . $row['color_name'];
                }
                if ($row['class'] != '') {
                    $output .= " ชั้น " . $row['class'];
                }
                if ($row['tl_size'] != '') {
                    $output .= " ขนาด (" . $row['tl_size'] . ")";
                }
                if ($row['pb_size'] != '') {
                    $output .= " ขนาด (" . $row['pb_size'] . ")";
                }
                if ($row['ct_size'] != '') {
                    $output .= " ขนาด (" . $row['ct_size'] . ")";
                }
                if ($row['pb_thick'] != '') {
                    $output .= " หนา " . $row['pb_thick'];
                }
                if ($row['cc_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cc_volume'];
                }
                if ($row['cs_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cs_volume'];
                }
                if ($row['cm_volume'] != '') {
                    $output .= " ปริมาณ " . $row['cm_volume'] . "</td> ";
                }
                $output .= ' 
                    <td align="center">' . $cpn_name . '</td>
                    <td align="center">' . $rc_amount . '</td>
                ';
            }
        } else {
            $output .= '
            <tr>
                <td colspan="4" align="center">ไม่มีข้อมูล</td>
            </tr>
        ';
        }
        $output .= '</table><br><br>';
        echo $output;
        // กด icons เพื่อแสดงกราฟ
        echo '<i class="fas fa-chart-pie fa-4x" onClick="drawChart(\'' . $shop_amount . '\',\'' . $cpn_amount . '\',\'' . $rc_amount . '\')"></i>&ensp;แสดงกราฟเป็น %';
        $pie .= '<center><div id="piechart" style="height: 600px;"></div></center>';
        echo $pie;
        ?>
    </body>

    </html>
<?php
}
