<?php session_start(); 
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");

function po_select($connect)
{
    $query = "SELECT op.order_id FROM orderproduct op ORDER BY op.order_id ASC";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();

    $output = '';
    foreach ($result as $row) {
        $id = $row['order_id'];

        $output .= '<option value="' . $id . '">' . $id . '</option>';
    }
    return $output;
}

?>
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
                        <h5 class="card-header"><i class="fa fa-list-alt" aria-hidden="true"></i> ใบรับสินค้า</h5>
                        <p></p>
                        <div class="form-group">
                            <a href="#" class="btn-primary btn-sm add_data" name="add_data" data-toggle='modal' data-target='#modalAdd'><i class='fa fa-plus' aria-hidden='true'></i>
                                เพิ่มใบรับสินค้า</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table display" id="show_receive" width="100%">
                                <thead bgcolor='#E3F1C0' align="center">
                                    <th>รหัสใบรับสินค้า</th>
                                    <th>วันที่รับ</th>
                                    <th>พนักงานรับ</th>
                                    <th>รหัสใบสั่งซื้อ</th>
                                </thead>
                                <tbody align="center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<!-- ---------------------------------------- หน้าเพิ่ม/แสดงตารางรายการรับสินค้า---------------------------------------- -->
<div class="modal fade bd-example-modal-xl" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus" aria-hidden="true"></i>
                    เพิ่มใบรับสินค้า</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="add_list">
                <div class="shadow-sm p-3 mb-5 bg-white rounded">
                    <form method="post" id="receive_form">
                        <span id="message" class="text-success"></span>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="validationTooltip01" style="font-weight:bold">ค้นหาใบสั่งซื้อ :</label>
                                <select name="p_order" id="p_order" class="form-control selectpicker p_order">
                                    <option value='0'>ค้นหาใบสั่งซื้อ . . .</option>
                                    <?php echo po_select($connect); ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationTooltip02" style="font-weight:bold">วันที่รับ :</label>
                                <input class="form-control" type="datetime-local" id="date" name="date">
                                <span id="error_date" class="text-danger"></span>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label id="lb_s_list" style="font-weight:bold">รายการใบสั่งซื้อ :</label>&emsp;&emsp;
                                <div class="table-responsive">
                                    <span id="error_edit"></span>
                                    <p></p>
                                    <table class="table table-bordered" id="receive_table">
                                        <thead bgcolor='#E3F1C0' align="center">
                                            <th width="5%">เลือก</th>
                                            <th width="7%">รายการที่</th>
                                            <th>รายการสินค้า</th>
                                            <th>บริษัทคู่ค้า</th>
                                            <th>ราคาทุน</th>
                                            <th>จำนวนสั่งซื้อ</th>
                                            <th>จำนวนรับแล้ว</th>
                                            <th width="10%">จำนวนรับเข้า</th>
                                            <th width="15%">วันหมดอายุสินค้า</th>
                                        </thead>
                                        <tbody id="list_table"></tbody>
                                    </table>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="item_radio[]" id="c1" value="share" class="form-check-input item_radio" checked="checked">
                                        <label class="form-check-label" id="lb_check1" for="inlineRadio2">ทยอยรับ</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="item_radio[]" id="c2" value="quite" class="form-check-input item_radio">
                                        <label class="form-check-label" id="lb_check2" for="inlineRadio2">รับหมด</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                    <button type="submit" name="btn_submit" class="btn btn-primary btn_submit"><i class="fa fa-save" aria-hidden="true"></i> บันทึกข้อมูล</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ---------------------------------------- หน้าแสดง/แสดงรายการรับสินค้า ---------------------------------------- -->
<div class="modal fade bd-example-modal-xl" id="modalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    ข้อมูลใบรับสินค้า</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="myModal">
                <h6><span>รหัสใบรับสินค้า : </span><span id="po_id" style="background-color:#FCDAA8;"></span></h6>
                <p></p>
                <table class="table table-bordered" id="receive_show">
                    <thead bgcolor='#E3F1C0' align="center">
                        <th width="20%">รหัสสินค้า</th>
                        <th width="10%">รายการที่</th>
                        <th>รายการสินค้า</th>
                        <th>จำนวนรับ</th>
                    </thead>
                    <tbody id="list_show"></tbody>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="../js/ajax_receive.js"></script>