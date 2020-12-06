<?php session_start() ?>
<?php include('../h.php'); ?>

<!DOCTYPE html>
<html>

<head>
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
                        <h5 class="card-header"><i class="fa fa-list-alt" aria-hidden="true"></i> ใบเสนอซื้อ</h5>
                        <p></p>
                        <div class="form-group">
                            <a href="#" class="btn-primary btn-sm add_data" name="add_data" data-toggle='modal' data-target='#add'><i class='fa fa-plus' aria-hidden='true'></i>
                                เพิ่มใบเสนอซื้อ</a>
                        </div>
                        <div class="table-responsive">
                            <table class="content-table" id="show_quo" width="100%">
                                <thead align="center">
                                    <th>รหัสใบเสนอซื้อ</th>
                                    <th>วันที่ออก</th>
                                    <th>ผู้ออก</th>
                                    <th>สถานะ</th>
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

<!-- ---------------------------------------- หน้าเพิ่ม/แสดงตารางรายการใบเสนอสั่งซื้อ ---------------------------------------- -->
<div class="modal fade bd-example-modal-xl" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มใบเสนอสั่งซื้อ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="add_list">
                <div class="shadow-sm p-3 mb-5 bg-white rounded">
                    <span id="message" class="text-success"></span>
                    <form method="post" id="myform">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label style="font-weight:bold">วันที่ออก :</label>
                                <input class="form-control" type="date" id="date" name="date">
                                <span id="error_date" class="text-danger"></span>
                            </div>
                            <div class="col-md-6 mb-3"></div>
                            <div class="col-md-6 mb-3">
                                <label style="font-weight:bold">รายการสินค้า :</label>
                                <select name="product" id="product" class="selectpicker form-control" data-live-search="true" title="ค้นหารายการสินค้า . . ."></select>
                                <span id="error_product" class="text-danger"></span>
                            </div>
                            <div class="col-md-6 mb-3"></div>
                            <div class="col-md-6 mb-3">
                                <label style="font-weight:bold">จำนวน :</label>
                                <input type="number" id="number" name="number" class="form-control number" min="1" max="99">
                                <span id="error_number" class="text-danger"></span>
                            </div>
                            <div class="col-md-6 mb-3"></div>
                            <input type="hidden" id="quo_id" name="quo_id">
                            <div class="col-md-12 mb-3">
                                <label id="lb_cpn" style="font-weight:bold">บริษัทคู่ค้า :</label>
                                <div class="table-responsive">
                                    <table class="content-table" id="pd_quo_list">
                                        <thead align="center">
                                            <th width="5%">เพิ่ม</th>
                                            <th width="25%">รหัสสินค้า</th>
                                            <th>รายการสินค้า</th>
                                            <th>ราคาทุน</th>
                                        </thead>
                                        <tbody id="quo_tb"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label id="lb_list" style="font-weight:bold">รายการที่เลือก :</label>
                                <div class="table-responsive">
                                    <table class="content-table" id="quo_list_select">
                                        <thead align="center">
                                            <th width="10%">รายการที่</th>
                                            <th>รายการสินค้า</th>
                                            <th>บริษัทคู่ค้า</th>
                                            <th>จำนวนเสนอ</th>
                                            <th>ราคาทุน</th>
                                            <th>ราคารวม</th>
                                            <th>ลบ</th>
                                        </thead>
                                        <tbody id="quo_list"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-7 mb-3"></div>
                            <div class="col-md-5 mb-3">
                                <div class="table-responsive">
                                    <table class="content-table">
                                        <tbody>
                                            <tr class="active-row">
                                                <td colspan="3" align="right">ราคารวม</td>
                                                <td colspan="1"><input class="in-sum-price form-control" type="text" style="text-align:right;border: none;border-color: transparent;" disabled="disabled"></td>
                                                <td colspan="1"> <input class="cal-sum-price" name="sum_price" type="hidden"></td>
                                            </tr>
                                            <tr class="active-row">
                                                <td colspan="3" align="right">ภาษีมูลค่าเพิ่ม7%</td>
                                                <td colspan="1"><input class="in-vat form-control" type="text" style="text-align:right;border: none;border-color: transparent;" disabled="disabled"></td>
                                                <td colspan="1"><input class="cal-vat" name="vat" type="hidden"></td>
                                            </tr>
                                            <tr class="active-row">
                                                <td colspan="3" align="right">จำนวนเงินทั้งสิ้น</td>
                                                <td colspan="1"><input class="in-total form-control" type="text" style="text-align:right;border: none;border-color: transparent;" disabled="disabled"></td>
                                                <td colspan="1"><input class="cal-total" name="total" type="hidden"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                <button type="submit" id="btn_submit" name="submit" class="btn btn-success"><i class="fa fa-save" aria-hidden="true"></i> อนุมัติใบเสนอ</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- ---------------------------------------- หน้าแสดง/แสดงรายการใบเสนอสั่งซื้อ ---------------------------------------- -->
<div class="modal fade bd-example-modal-xl show-modal" id="show-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-list-alt"></i> รายการใบเสนอสั่งซื้อ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="show_list">
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <h6><span style="font-weight:bold">รหัสใบเสนอซื้อ : </span><span id="show_id"></span></h6>
                        <div class="table-responsive">
                            <table class="content-table" id="table_list">
                                <thead bgcolor='#009879' align="center">
                                    <th>รายการที่</th>
                                    <th>รหัสสินค้า</th>
                                    <th>รายการสินค้า</th>
                                    <th>จำนวนเสนอ</th>
                                    <th>ราคารวม</th>
                                </thead>
                                <tbody id="list_show"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7 mb-3"></div>
                    <div class="col-md-5 mb-3">
                        <div class="table-responsive">
                            <table class="content-table">
                                <tbody>
                                    <tr>
                                        <td colspan="3" align="right">ราคารวม</td>
                                        <td colspan="1"><input class="in-sum-price form-control" type="text" style="text-align:right;border: none;border-color: transparent;" disabled="disabled"></td>
                                        <td colspan="1"> <input class="cal-sum-price" name="total_price" type="hidden"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">ภาษีมูลค่าเพิ่ม7%</td>
                                        <td colspan="1"><input class="in-vat form-control" type="text" style="text-align:right;border: none;border-color: transparent;" disabled="disabled"></td>
                                        <td colspan="1"><input class="cal-vat" name="vat" type="hidden"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">จำนวนเงินทั้งสิ้น</td>
                                        <td colspan="1"><input class="in-total form-control" type="text" style="text-align:right;border: none;border-color: transparent;" disabled="disabled"></td>
                                        <td colspan="1"><input class="cal-total" name="total" type="hidden"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ---------------------------------------- หน้าเพิ่ม/แสดงตารางรายการใบเสนอสั่งซื้อ ---------------------------------------- -->
<div class="modal fade bd-example-modal-xl" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-check"></i> อนุมัติใบเสนอซื้อ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="add_list">
                <div class="shadow-sm p-3 mb-5 bg-white rounded">
                    <span id="messageBox" class="text-success"></span>
                    <form method="post" id="myformApprove">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <h6><span style="font-weight:bold">รหัสใบเสนอซื้อ : </span><span id="app_quo_id"></span></h6>
                                <input type="hidden" id="q_id" name="q_id">
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="table-responsive">
                                    <table class="content-table" id="approve_tb">
                                        <thead>
                                            <th>รายการที่</th>
                                            <th>รายการสินค้า</th>
                                            <th>บริษัทคู่ค้า</th>
                                            <th>จำนวนเสนอ</th>
                                            <th>ราคารวมเสนอ</th>
                                            <th id='num_approve'>จำนวนอนุมัติ</th>
                                        </thead>
                                        <tbody id="list_approve"></tbody>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" class="sum_price" name="sum_price">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                    <button type="submit" id="btn_approve" name="submit" class="btn btn-success"><i class="fas fa-check"></i> อนุมัติเป็นใบสั่งซื้อ</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="../js/ajax_quotation.js">
</script>