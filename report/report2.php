<?php session_start(); ?>
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
      font-size: 0.9em;
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
            <h5 class="card-header"><i class="far fa-file-alt"></i> สรุปยอดขายตามช่วงเวลา</h5>
            <p></p>
            <form>
              <div class="form-row align-items-center d-flex justify-content-center">
                <div class="col-auto">
                  <input type="text" name="datefilter_sale" id="datefilter_sale" value="" class="form-control mb-2" style="width:300px" placeholder="เริ่มต้นวันที่    -    สิ้นสุดวันที่" />
                </div>
                <div class="col-auto">
                  <button type="button" name="filter_sale" id="filter_sale" value="Filter" class="btn btn-info mb-2 btn-block" style="width:50px"><i class="fas fa-search"></i></button>
                </div>
              </div>

              <div class="table-responsive">
                <input type="hidden" id="ip_date">
                <center><span style="color:red;" id="show_date"></span></center>
                <p></p>
                <p></p>
                <div id="tb_report" align="center">
                  <!-- ข้อมูล รายรับ รายจ่าย กำไร ขาดทุน -->
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</body>

</html>