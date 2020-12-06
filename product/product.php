<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <?php include('../h.php');
  error_reporting(error_reporting() & ~E_NOTICE); ?>

</head>

<body>
  <?php include('../connectdb.php'); ?>
  <?php include('../navbar.php'); ?>
  <div class="container-fluid">
    <p></p>
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
          <h5 class="card-header"><i class="fab fa-product-hunt"></i> หมวดหมู่สินค้า</h5>
          <p></p>
            <?php
            $act = $_GET['act'];
            if ($act == 'add') {
              include('product_insert.php');
            } else {
              include('product_show.php');
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>
</body>

</html>