<?php session_start();
if (isset($_SESSION['empid']) == true) {

    $connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");

    function customer_select($connect)
    {
        $query = "SELECT cus_id,cus_name FROM customer";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        $output = '';
        foreach ($result as $row) {
            $id = $row['cus_id'];
            $name = $row['cus_name'];
            $output .= '<option value="' . $id . '">' . $name . '</option>';
        }
        return $output;
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php include('../h.php'); ?>
        <style>
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

            .content-table {
                border-collapse: collapse;
                margin: 25px 0;
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
        <?php include '../connectdb.php'; ?>
        <?php include '../navbar.php'; ?>
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
                    <?php include '../menu_left.php'; ?>
                    <!-- Content Wrapper. Contains page content -->
                </div>
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-header"><i class="fa fa-wrench" aria-hidden="true"></i> เคลมสินค้า</h5>
                            <p></p>
                            <div class="form-group">
                                <a href="#" class="btn-primary btn-sm add_data" name="add_data" id="add_data" data-toggle='modal' data-target='#modalAdd'><i class='fa fa-plus' aria-hidden='true'></i>
                                    เพิ่มรายการเคลม</a>
                                <a href="#" class="btn-danger btn-sm rc_data" name="rc_data" id="rc_data" data-toggle='modal' data-target='#modalReceive'><i class="fas fa-receipt"></i>
                                    รับสินค้าเคลม</a>
                                <a href="#" class="btn-success btn-sm return_data" name="return_data" id="return_data" data-toggle='modal' data-target='#modalReturn'><i class='fa fa-sign-language' aria-hidden='true'></i>
                                    คืนสินค้าฝากเคลม</a>
                            </div>
                            <div class="table-responsive">
                                <table class="content-table" id="claim_table" width="100%">
                                    <thead align="center">
                                        <th>รหัสเคลมสินค้า</th>
                                        <th>วันที่ส่งเคลม</th>
                                        <th>ผู้ออกใบเคลม</th>
                                        <th>รหัสใบเสร็จ</th>
                                        <th>สถานะเคลมลูกค้า</th>
                                        <th>สถานะเคลมบริษัทคู่ค้า</th>
                                        <th>สถานะการรับ</th>
                                        <th width="8%">ใบเสร็จลูกค้า</th>
                                        <th width="9%">ใบเสร็จบริษัทคู่ค้า</th>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>

    <script type="text/javascript" src="/js/ajax_claim.js"></script>

    <!-- ---------------------------------------- หน้าเพิ่ม/เคลมกับลูกค้า---------------------------------------- -->
    <div class="modal fade bd-example-modal-xl" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus" aria-hidden="true"></i>
                        เพิ่มการเคลมสินค้า</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="add_list">
                    <div class="shadow-sm p-3 mb-5 bg-white rounded">
                        <span id="message" class="text-success"></span>
                        <form method="post" id="claim_form">
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label style="font-weight:bold;">รหัสใบเสร็จ :</label>
                                    <span id="error_slip" class="text-danger"></span>
                                    <select name="sale_slip" id="sale_slip" class="form-control selectpicker sale_slip" data-live-search="true" title="ตัวอย่างเลขที่ใบเสร็จ S0000XX . . ."></select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label style="font-weight:bold">วันที่ส่งเคลม :</label>
                                    <input class="form-control" type="datetime-local" id="date" name="date">
                                    <span id="error_date" class="text-danger"></span>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label style="font-weight:bold">ลูกค้า :</label>
                                    <select name="customer" id="customer" class="form-control customer">
                                        <option value="">กรุณาเลือกรายชื่อลูกค้า . . .</option>
                                        <?php echo customer_select($connect); ?>
                                    </select>
                                    <span id="error_customer" class="text-danger"></span>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <hr>
                                    <label style="font-weight:bold">เลือกการเคลม :</label> &ensp;
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="item_radio" id="claim_shop" value="shop" class="form-check-input item_radio" checked="checked">
                                        <label class="form-check-label" id="lb_check1" for="inlineRadio2">เคลมกับทางร้าน</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="item_radio" id="claim_company" value="company" class="form-check-input item_radio">
                                        <label class="form-check-label" id="lb_check2" for="inlineRadio2">ฝากเคลมกับบริษัทคู่ค้า</label>
                                    </div>
                                    <hr>
                                        <label id="lb_emp_sale" style="font-weight:bold">ผู้ออกใบเสร็จ :</label>&emsp;
                                        <span id="emp_sale" style="color:red;"></span>&emsp;
                                        <label id="lb_date_sale" style="font-weight:bold">วันที่ขาย :</label>&emsp;
                                        <span id="date_sale" style="color:red;"></span>
                                    <hr>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label id="lb_sale" style="font-weight:bold">รายการใบเสร็จ :</label>
                                    <div class="table-responsive">
                                        <p></p>
                                        <table class="table" id="sale_table" width="100%">
                                            <thead align="center">
                                                <th width="5%">เพิ่ม</th>
                                                <th width="8%">รายการที่</th>
                                                <th>รายการสินค้า</th>
                                                <th>จำนวนขาย</th>
                                                <th>คลังสต็อก</th>
                                                <th>หน่วย</th>
                                            </thead>
                                            <tbody id="sale_list"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <hr>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label id="lb_claim" style="font-weight:bold;">รายการเคลม :</label>
                                    <div class="table-responsive">
                                        <p></p>
                                        <table class="table" id="claim_list_tb" width="100%">
                                            <thead align="center">
                                                <th width="8%">รายการที่</th>
                                                <th>รายการสินค้า</th>
                                                <th width="10%">จำนวนเคลม</th>
                                                <th width="15%">ราคาโดยประมาณ</th>
                                                <th width="15%">สาเหตุ</th>
                                            </thead>
                                            <tbody id="claim_list">

                                            </tbody>
                                            <tr>
                                                <td colspan="3" align="right">ราคารวม</td>
                                                <td colspan="1"><input class="in-amount-sum form-control" type="text" style="text-align:right;" readonly><input class="cal-amount-sum" name="total_price" type="hidden"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align="right">จำนวนเงินทั้งสิ้น</td>
                                                <td colspan="1"><input class="in-showtotal form-control" type="text" style="text-align:right;" readonly><input class="in-total" name="total" type="hidden" readonly></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                        <button type="submit" name="btn_submit" class="btn btn-primary btn_submit"><i class="fa fa-save" aria-hidden="true"></i> บันทึกการเคลมสินค้า</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------------------------------------- หน้าแสดง/รายการเคลม ---------------------------------------- -->
    <div class="modal fade bd-example-modal-xl" id="modalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-list-alt" aria-hidden="true"></i>
                        ข้อมูลรายการเคลมสินค้าของลูกค้า</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="myModal">
                    <span id="alert_message" class="text-success"></span>
                    <form method="post" id="claim_data">
                        <h6><span>รหัสใบเคลม : </span><span id="id_claim" style="background-color:#FCDAA8;"></span>&emsp;<span>รหัสใบเสร็จ : </span><span id="id_sale" style="background-color:#FCDAA8;"></span></h6>
                        <div class="table-responsive">
                            <table class="content-table" id="claim_show">
                                <thead align="center">
                                    <th width="20%">รหัสสินค้า</th>
                                    <th width="10%">รายการที่</th>
                                    <th>รายการสินค้า</th>
                                    <th>จำนวนเคลม</th>
                                    <th>ราคาโดยประมาณ</th>
                                    <th>สาเหตุ</th>
                                </thead>
                                <tbody id="list_show"></tbody>
                            </table>
                            <center><button type="button" id="btn_update" name="btn_update" value="" class="btn btn-success btn_update" style="width:200px"><i class="fa fa-check-circle" aria-hidden="true"></i> ยืนยันส่งเคลม</button>
                                <p></p>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------------------------------------- หน้ารับสินค้าเคลม ---------------------------------------- -->
    <div class="modal fade bd-example-modal-xl" id="modalReceive" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-receipt"></i>
                        รับสินค้าเคลมกับบริษัทคู่ค้า</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="add_list">
                    <div class="shadow-sm p-3 mb-5 bg-white rounded">
                        <span id="message_receive" class="text-success"></span>
                        <form method="post" id="claim_form_rc">
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="validationTooltip01" style="font-weight:bold">ค้นหาใบเคลมสินค้า :</label>
                                    <div></div>
                                    <select name="ccp_id" id="ccp_id" class="form-control selectpicker ccp_id" data-live-search="true" title="ตัวอย่างเลขที่ใบเคลม CCPXXXX . . .">
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="validationTooltip02" style="font-weight:bold">วันที่รับ :</label>
                                    <input class="form-control" type="datetime-local" id="date_rc" name="date_rc">
                                    <span id="error_date_rc" class="text-danger"></span>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label id="lb_rc_list" style="font-weight:bold">รายการใบสั่งซื้อ :</label>&emsp;&emsp;
                                    <div class="table-responsive">
                                        <span id="error_edit"></span>
                                        <p></p>
                                        <table class="table" id="receive_claim_tb">
                                            <thead align="center">
                                                <th width="5%">เลือก</th>
                                                <th width="7%">รายการที่</th>
                                                <th>รายการสินค้า</th>
                                                <th>จำนวนที่ต้องรับ</th>
                                                <th>จำนวนรับเข้า</th>
                                            </thead>
                                            <tbody id="list_table"></tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                        <button type="submit" name="btn_submit" id="btn_submit" class="btn btn-primary btn_submit"><i class="fa fa-save" aria-hidden="true"></i> บันทึกข้อมูล</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------------------------------------- หน้ารับสินค้าเคลม ---------------------------------------- -->
    <div class="modal fade bd-example-modal-xl" id="modalReturn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class='fa fa-sign-language' aria-hidden='true'></i>
                        คืนสินค้าฝากเคลม</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="add_list">
                    <div class="shadow-sm p-3 mb-5 bg-white rounded">
                        <span id="message_return" class="text-success"></span>
                        <form method="post" id="claim_form_return">
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="validationTooltip01" style="font-weight:bold">ค้นหาใบเสร็จเคลม :</label>
                                    <div></div>
                                    <select name="claim_slip" id="claim_slip" class="form-control selectpicker claim_slip" data-live-search="true" title="ตัวอย่างเลขที่ใบเสร็จ CLXXXXX . . ."></select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label id="lb_rt_date" style="font-weight:bold">วันที่ฝากเคลม :</label>&emsp;<span style="color:red;" id="date_claim"></span> &emsp;
                                    <label id="lb_cpn_date" style="font-weight:bold">วันที่ส่งเคลมกับบริษัทคู่ค้า :</label>&emsp;<span style="color:red;" id="date_cpn"></span>
                                    <br>
                                    <label id="lb_rt_list" style="font-weight:bold">รายการเคลมสินค้า :</label>
                                    <div class="table-responsive">
                                        <p></p>
                                        <table class="table" id="return_claim_tb">
                                            <thead align="center">
                                                <th width="7%">รายการที่</th>
                                                <th>รายการสินค้า</th>
                                                <th>สต็อก</th>
                                                <th>จำนวนเคลม</th>
                                                <th>จำนวนที่รับ</th>
                                                <th>หน่วย</th>
                                                <th>สถานะ</th>
                                            </thead>
                                            <tbody id="list_return_tb"></tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                        <button type="submit" name="btn_return" id="btn_return" class="btn btn-success btn_return"><i class="fa fa-check" aria-hidden="true"></i> ยืนยันการคืน</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ---------------------------------------- หน้าแสดง/บริษัทคู่ค้า ---------------------------------------- -->
    <div class="modal fade bd-example-modal-xl" id="modalClaim" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-warehouse"></i>
                        ข้อมูลบริษัทคู่ค้าในใบเคลม</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="myModal">
                    <span id="alert_message" class="text-success"></span>
                    <form method="post" id="claim_data">
                        <h6><span>รหัสใบเคลมสินค้า : </span><span id="show_id" style="background-color:#FCDAA8;"></span></h6>
                        <div class="table-responsive">
                            <table class="content-table" id="claim_show">
                                <thead align="center">
                                    <th>รหัสบริษัทคู่ค้า</th>
                                    <th>ชื่อบริษัทคู่ค้า</th>
                                    <th>พิมพ์</th>
                                </thead>
                                <tbody id="cpn_list"></tbody>
                            </table>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    header("Location: ../../login_form.php");
} ?>