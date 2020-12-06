<?php session_start();
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");

function po_select($connect)
{
  $output = '';
  $query = "SELECT pd.product_id,pd.product_name,pd.product_saleprice,pd.product_stock,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume
    FROM product pd
    INNER JOIN brand ON brand.brand_id = pd.brand_id
    INNER JOIN unit ON unit.unit_id = pd.unit_id
    LEFT JOIN cement ON cement.product_id = pd.product_id
    LEFT JOIN categorycolor ON categorycolor.product_id = pd.product_id
    LEFT JOIN toilet ON toilet.product_id = pd.product_id
    LEFT JOIN chemicalsolution ON chemicalsolution.product_id = pd.product_id
    LEFT JOIN craftmantool ON craftmantool.product_id = pd.product_id
    LEFT JOIN plumbling ON plumbling.product_id = pd.product_id
     
    LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
    ORDER BY pd.product_id";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();

  foreach ($result as $row) {
    $id = $row['product_id'];
    $name = $row['product_name'];
    $brand = $row['brand_name'];
    $class = $row['class'];
    $color = $row['color_name'];
    $size_tl = $row['tl_size'];
    $size_pb = $row['pb_size'];
    $size_ct = $row['ct_size'];
    $thick_pb = $row['pb_thick'];
    $cc = $row['cc_volume'];
    $cs = $row['cs_volume'];
    $cm = $row['cm_volume'];

    if ($name != '') {
      $output .= '<option value="' . $id . '">' . $name . '';
    }
    if ($brand != '') {
      $output .= " " . $brand;
    }
    if ($class != '') {
      $output .= " ชั้น " . $class;
    }
    if ($color != '') {
      $output .= " สี" . $color;
    }
    if ($size_tl != '') {
      $output .= " ขนาด (" . $size_tl . ")";
    }
    if ($size_pb != '') {
      $output .= " ขนาด (" . $size_pb . ")";
    }
    if ($size_ct != '') {
      $output .= " ขนาด (" . $size_ct . ")";
    }
    if ($thick_pb != '') {
      $output .= " หนา " . $thick_pb;
    }
    if ($cc != '') {
      $output .= " ปริมาณ " . $cc;
    }
    if ($cs != '') {
      $output .= " ปริมาณ " . $cs;
    }
    if ($cm != '') {
      $output .= " ปริมาณ " . $cm;
    }
    $output .= '</option>';
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
            <h5 class="card-header"><i class="fa fa-tags" aria-hidden="true"></i> โปรโมชั่น</h5>
            <p></p>
            <!-- เพิ่มขอมูล -->
            <div class="form-group">
              <a href="#" class="btn-primary btn-sm add" id="add" data-toggle='modal' data-target='#modalAdd'><i class='fa fa-plus' aria-hidden='true'></i> เพิ่มโปรโมชั่น</a>
            </div>
            <div class="table-responsive">
              <table class="table display" id="example">
                <thead>
                  <tr bgcolor="#AED6F1" align="center">
                    <th>รหัสโปรโมชั่น</th>
                    <th>ชื่อโปรโมชั่น</th>
                    <th>ส่วนลด</th>
                    <th>วันที่เริ่ม</th>
                    <th>วันที่สิ้นสุด</th>
                    <th>จัดการ/สินค้าร่วมรายการ</th>
                  </tr>
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
<script type="text/javascript" src="/js/ajax_promotion.js"></script>
<!-- ---------------------------------------- หน้าเพิ่ม/โปรโมชั่น---------------------------------------- -->
<div class="modal fade bd-example-modal-xxl" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xxl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          <i class="fa fa-tags" aria-hidden="true"></i> เพิ่มโปรโมชั่น</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="add_list">
        <div class="shadow-sm p-3 mb-5 bg-white rounded">
          <span id="message" class="text-success"></span>
          <form method="post" id="myform">
            <span id="error"></span>
            <div class="form-row">
              <div class="col-md-12 mb-3">
                <label style="font-weight:bold">ชื่อโปรโมชั่น :</label>
                <input type="text" class="form-control" id="pmt_name" name="pmt_name" maxlength="50">
                <span id="error_name" class="text-danger"></span>
              </div>

              <div class="col-md-6 mb-3">
                <label style="font-weight:bold">วันที่เริ่ม :</label>
                <input class="form-control date_start" type="date" id="date_start" name="date_start">
                <span id="error_date_s" class="text-danger"></span>
              </div>
              <div class="col-md-6 mb-3">
                <label style="font-weight:bold">วันสิ้นสุด :</label>
                <input class="form-control date_end" type="date" id="date_end" name="date_end">
                <span id="error_date_e" class="text-danger"></span>
              </div>

              <div class="col-md-12 mb-3">
                <label style="font-weight:bold">ส่วนลด % :</label>
                <input type="text" class="form-control" id="discount" name="discount" value="<?php echo $_POST['discount']; ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="2">
                <span id="error_discount" class="text-danger"></span>
              </div>

              <div class="col-md-12 mb-3">
                <label style="font-weight:bold">สินค้าร่วมรายการ :</label>

                <select name="product" id="product" class="form-control selectpicker" data-live-search="true" title="ค้นหารายการสินค้า . . .">
                  <option value="">กรุณาเลือกรายการสินค้า . . .</option>
                  <?php echo po_select($connect); ?>
                </select>
                <span id="error_product" class="text-danger"></span>
                <input type="hidden" name="id_pmt" id="id_pmt" />
                <p></p>
                <button type="button" id="add_product" class="btn btn-danger form-control">เพิ่มสินค้าร่วมโปรโมชั่น</button>
                <div class="table-responsive">
                  <span id="error_list"></span>
                  <p></p>

                  <table class="table table-bordered" id="pmt_table">
                    <thead bgcolor='#A7E1FA' align="center">
                      <th>รายการสินค้า</th>
                      <th width="20%">ลบ</th>
                    </thead>
                    <tbody id="list_table">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fa fa-close' aria-hidden='true'></i> ปิด</button>
        <button type="submit" name="btn_submit" id="btn_submit" class="btn btn-primary btn_submit">บันทึกข้อมูล</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- ---------------------------------------- หน้าแสดง/แสดงสินค้าร่วมรายการ ---------------------------------------- -->
<div class="modal fade" id="modalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xxl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> <i class="fa fa-tags" aria-hidden="true"></i> ข้อมูลสินค้าร่วมรายการ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="myModal">
        <h6><span>รหัสโปรโมชั่น : </span><span id="pmt_id" style="background-color:#FCDAA8;"></span></h6>
        <p></p>
        <table class="table table-bordered" id="table_list">
          <thead bgcolor='#E3F1C0' align="center">
            <th width="30%">รหัสสินค้า</th>
            <th>รายการสินค้า</th>
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
<script type="text/javascript" src="../js/ajax_receive.js">
</script>