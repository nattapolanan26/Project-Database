<?php session_start();
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");

function customer_select($connect)
{
    $query = "SELECT cus_id,cus_name FROM customer ctm";
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
                        <h5 class="card-header"><i class="fa fa-list-alt" aria-hidden="true"></i> การขายสินค้า</h5>
                        <p></p>
                        <div class="form-group">
                            <a href="#" class="btn-primary btn-sm add_data" id="st_modal" data-toggle='modal' data-target='#modalAdd'><i class='fa fa-plus' aria-hidden='true'></i>
                                เพิ่มใบขายสินค้า</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table display" id="show_saleslip" width="100%">
                                <thead bgcolor='#F9B8B8' align="center">
                                    <th>รหัสการขาย</th>
                                    <th>วันที่ขาย</th>
                                    <th>พนักงานขาย</th>
                                    <th>รวมเงินทั้งสิ้น</th>
                                    <th width="15%">แสดงรายการ</th>
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

<script type="text/javascript" src="/js/ajax_sale.js"></script>
<!-- ---------------------------------------- หน้าเพิ่ม/การขายสินค้า---------------------------------------- -->
<div class="modal fade bd-example-modal-xl" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus" aria-hidden="true"></i>
                    เพิ่มใบการขาย</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="add_list">
                <div class="shadow-sm p-3 mb-5 bg-white rounded">
                    <span id="message" class="text-success"></span>
                    <form method="post" id="sale_form">
                        <span id="error"></span>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label style="font-weight:bold">วันที่ขาย :</label>
                                <input class="form-control " type="datetime-local" id="date" name="date">
                                <span id="error_date" class="text-danger"></span>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label style="font-weight:bold">ลูกค้า :</label>
                                <select name="customer" id="customer" class="form-control customer">
                                    <option value=''>กรุณาเลือกรายชื่อลูกค้า . . .</option>
                                    <?php echo customer_select($connect); ?>
                                </select>
                                <span id="error_customer" class="text-danger"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div>
                                    <input type="hidden" name="row_id" id="hidden_row_id" />
                                    <a href="#" id="add-list" role="button" class="btn btn-primary" data-toggle="modal" data-target='#modalAdditem'><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการ</a>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label id="lb_salelist" style="font-weight:bold">รายการขายสินค้า :</label>&emsp;&emsp;
                                <div class="table-responsive">
                                    <p></p>
                                    <table class="table table-hover" id="sale_table">
                                        <thead bgcolor='#F9B8B8' align="center">
                                            <th width="8%">รายการที่</th>
                                            <th>รายการสินค้า</th>
                                            <th>จำนวนขาย</th>
                                            <th>ราคาขาย</th>
                                            <th>ราคารวม</th>
                                            <th width="17%">ส่วนลดโปรโมชั่น</th>
                                            <th width="10%">ลบ</th>
                                        </thead>
                                        <tbody id="list_table">

                                        </tbody>
                                        <tr>
                                            <td colspan="5" align="right">ราคารวมก่อนภาษีมูลค่าเพิ่ม</td>
                                            <td colspan="1"><input class="in-amount-sum form-control" type="text" style="text-align:right;border: none;border-color: transparent;" readonly>
                                            <td colspan="2"> <input class="cal-amount-sum" name="total_price" type="hidden"></td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" align="right">ส่วนลดโปรโมชั่น</td>
                                            <td colspan="1"><input class="in-discount form-control" type="text" style="text-align:right;border: none;border-color: transparent;" readonly>
                                            <td colspan="2"><input class="cal-discount" name="discount" type="hidden"></td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" align="right">ภาษีมูลค่าเพิ่ม 7%</td>
                                            <td colspan="1"><input class="in-vat form-control" type="text" style="text-align:right;border: none;border-color: transparent;" readonly>
                                            <td colspan="2"> <input class="cal-vat" name="vat" type="hidden"></td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" align="right">จำนวนเงินทั้งสิ้น</td>
                                            <td colspan="1"><input class="in-showtotal form-control" type="text" style="text-align:right;border: none;border-color: transparent;" readonly></td>
                                            <td colspan="2"><input class="in-total" name="total" type="hidden" readonly></td>

                                        </tr>
                                    </table>
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

<!-- ---------------------------------------- หน้าเพิ่ม/การขายสินค้า---------------------------------------- -->
<div class="modal fade bd-example-modal-xxl" id="modalAdditem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    รายการสินค้า</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="add_list">
                <div class="shadow-sm p-3 mb-5 bg-white rounded">
                    <form method="post" id="add_form">
                        <span id="error"></span>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label style="font-weight:bold;">เลือกโปรโมชั่น :</label>
                                <input type=radio name="rd1" id="have_pmt" class="have_pmt"> มีโปรโมชั่น
                                <input type=radio name="rd1" id="nohave_pmt" class="nohave_pmt"> ไม่มีโปรโมชั่น
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="lb_pmt" style="font-weight:bold;display: none">โปรโมชั่น :</label> <span id="error_promotion" class="text-danger"></span>
                                <select name="promotion" id="promotion" class="form-control selectpicker promotion" style="display: none" data-live-search="true" title="เลือกโปรโมชั่น . . ."></select>


                                <label class="lb_pd_pmt" style="font-weight:bold;display: none">รายการสินค้า :</label>
                                <span id="error_product_pmt" class="text-danger"></span>
                                <select name="product_pmt" id="product_pmt" class="form-control selectpicker product_pmt" style="display: none" data-live-search="true" title="เลือกสินค้าร่วมรายการ . . ."></select>
                                <small class="form-text text-muted"><a id="alert_pd" style="color:red;">*จะแสดงเฉพาะสินค้าที่อยู่ในช่วงเวลา และ มีในสต็อกเท่านั้น*</a></small> 

                                <label class="lb_pd" style="font-weight:bold;display: none">รายการสินค้า :</label> <span id="error_product" class="text-danger"></span>
                                <select name="product" id="product" class="form-control selectpicker product" style="display: none" data-live-search="true" title="เลือกรายการสินค้า . . ."></select>


                                <label class="lb_number" style="font-weight:bold;display:none">จำนวน :</label> <span id="error_number" class="text-danger"></span>
                                <input style="display:none" class="form-control number" type="number" id="number" name="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="5">

                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                <button type="button" id="add_data" name="add_data" class="btn btn-danger">เพิ่มรายการ</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- ---------------------------------------- หน้าแสดง/รายการขาย ---------------------------------------- -->
<div class="modal fade bd-example-modal-xl" id="modalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    ข้อมูลรายการขาย</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="myModal">
                <h6><span>รหัสใบการขาย : </span><span id="sale_id" style="background-color:#FCDAA8;"></span></h6>
                <p></p>
                <div class="table-responsive">
                    <table class="table" id="sale_show">
                        <thead bgcolor='#F9B8B8' align="center">
                            <th width="20%">รหัสสินค้า</th>
                            <th width="10%">รายการที่</th>
                            <th>รายการสินค้า</th>
                            <th>จำนวนที่ขาย</th>
                            <th width="15%">ราคารวม</th>
                            <th width="15%">ส่วนลด</th>
                        </thead>
                        <tbody id="list_show"></tbody>
                        <tr>
                            <td colspan="5" align="right">ราคารวมก่อนภาษีมูลค่าเพิ่ม</td>
                            <td colspan="1"><input class="total form-control" type="text" style="text-align:right;border: none;border-color: transparent;" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right">ส่วนลดโปรโมชั่น</td>
                            <td colspan="1"><input class="disc form-control" type="text" style="text-align:right;border: none;border-color: transparent;" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right">ภาษีมูลค่าเพิ่ม 7%</td>
                            <td colspan="1"><input class="vat form-control" type="text" style="text-align:right;border: none;border-color: transparent;" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right">ราคารวมทั้งสิ้น</td>
                            <td colspan="1"><input class="result-total form-control" type="text" style="text-align:right;border: none;border-color: transparent;" readonly></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>