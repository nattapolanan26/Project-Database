<?php session_start();
if (isset($_SESSION['empid']) == true) {
?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php include('../h.php'); ?>
        <style>
            .content-table {
                border-collapse: collapse;
                margin: 0px 0;
                font-size: 0.9em;
                width: 100%;
                border-radius: 5px 5px 0 0;
                overflow: hidden;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }

            .content-table thead tr {
                background-color: #009879;
                color: #ffffff;
                text-align: center;
                font-weight: bold;
            }

            .content-table th,
            .content-table td {
                padding: 12px 15px;
            }

            .content-table tbody tr {
                border-bottom: 1px solid #dddddd;
            }

            .content-table tbody tr:nth-of-type(even) {
                background-color: #f3f3f3;
            }

            .content-table tbody tr:last-of-type {
                border-bottom: 2px solid #009879;
            }

            .content-table tbody tr.active-row {
                font-weight: bold;
                color: #009879;
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
                            <h5 class="card-header"><i class="fa fa-list-alt" aria-hidden="true"></i> ใบสั่งซื้อ</h5>
                            <p></p>
                            <div class="table-responsive">
                                <table class="content-table" id="show_quo" width="100%">
                                    <thead bgcolor='#AED6F1' align="center">
                                        <th>รหัสใบเสนอซื้อ</th>
                                        <th>วันที่ออก</th>
                                        <th>ผู้ออก</th>
                                        <th>สถานะการรับ</th>
                                        <th>จัดการ</th>
                                    </thead>
                                    <tbody align="center"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </body>

    </html>

    <!-- ---------------------------------------- หน้าแสดงบริษัทคู่ค้าในรายการใบสั่งซื้อ ---------------------------------------- -->
    <div class="modal fade bd-example-modal-xl show-modal" id="show-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-list-alt"></i> รายการใบสั่งซื้อ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="show_list">
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <h6><span style="font-weight:bold">รหัสใบสั่งซื้อ : </span><span id="show_id"></span></h6>
                            <div class="table-responsive">
                                <table class="content-table" id="table_list">
                                    <thead bgcolor='#009879' align="center">
                                        <th>รหัสบริษัทคู่ค้า</th>
                                        <th>ชื่อบริษัทคู่ค้า</th>
                                        <th>พิมพ์</th>
                                    </thead>
                                    <tbody id="list_show"></tbody>
                                </table>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="../js/ajax_order.js"></script>
        </script>
    <?php } else {
    header("Location: ../../login_form.php");
} ?>