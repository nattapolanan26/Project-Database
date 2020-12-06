<?php session_start() ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); ?>
    <style>
        table,
        th,
        td {
            border: 1px solid #1B66A3;
        }

        .content-table {
            border-collapse: collapse;
            margin: 0px 0;
            font-size: 1.1em;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .content-table thead tr {
            background-color: #1B66A3;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
        }

        .content-table th,
        .content-table td {
            padding: 12px 15px;
        }

        .content-table tbody .td_h td {
            background-color: #E5E7F6;
        }

        .content-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .content-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .content-table tbody tr:last-of-type {
            border-bottom: 2px solid #1B66A3;
        }

        .content-table tbody tr.active-row {
            font-weight: bold;
            color: #1B66A3;
        }

        .btn-circle.btn-xl {
            width: 70px;
            height: 70px;
            padding: 10px 16px;
            border-radius: 35px;
            font-size: 24px;
            line-height: 1.33;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            text-align: center;
            font-size: 12px;
            line-height: 1.42857;
        }
    </style>
    <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['สรุปรายรับ', 8],
                ['สรุปรายจ่าย', 2],
                ['สรุปยอดขายตามช่วงเวลา', 2],
                ['สรุปยอดขายประจำปี', 2],
                ['สรุปสินค้าเคลม', 2],
                ['อื่นๆ', 8]
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = {
                'title': 'ตัวอย่างกราฟสรุปผล',
                'width': 1000,
                'height': 600
            };

            // Display the chart inside the <div> element with id="piechart"
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript" src="../js/ajax_report.js"></script>
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
                    <h6><i class="fas fa-user-circle"></i>&ensp;<a style="font-weight:bold;"><?php echo "ผู้ใช้"; ?></a><a style="color:#c92828;font-weight:bold;"><?php echo " : " . $_SESSION['user']; ?></a>
                    </h6>
                    <h6><i class="fas fa-check-square"></i></i></i>&ensp;<a style="font-weight:bold;"><?php echo "ตำแหน่ง"; ?></a>
                        <a style="color:#1d4891;font-weight:bold;"><?php echo " : " . $_SESSION['posname']; ?></a>
                    </h6>
                </div>
                <?php include('../menu_left.php'); ?>
                <!-- Content Wrapper. Contains page content -->
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-header"><i class="far fa-file-alt"></i> รายงานรีพอร์ต</h5>
                        <p></p>
                        <form>
                            <div class="table-responsive" align="center">
                                <table class="content-table" width="70%">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-funnel-dollar fa-lg"></i>&ensp;<a href="report1.php" style="color:white;">สรุปรายรับ/รายจ่าย</a></th>
                                            <th><i class="fas fa-dollar-sign fa-lg"></i>&ensp;<a href="report2.php" style="color:white;">สรุปยอดขายตามช่วงเวลา</a></th>
                                            <th><i class="fas fa-file-invoice-dollar fa-lg"></i>&ensp;<a href="report3.php" style="color:white;">สรุปยอดขายประจำปี</a></th>
                                            <th><i class="fas fa-wrench fa-lg"></i>&ensp;<a href="report4.php" style="color:white;">สรุปสินค้าเคลม</a></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                        <div id="piechart" align="center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>