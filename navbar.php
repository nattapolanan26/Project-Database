<?php if(!isset($_SESSION)) { session_start(); } ?>
<link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_o5hd5vvqpoqiwwmi.css">
</style>
<?php include('connectdb.php');
$sqlemp="SELECT * FROM employee INNER JOIN position ON employee.pos_id = position.pos_id WHERE position.pos_id = '".$_SESSION['posid']."'";
$resultemp=mysqli_query($conn, $sqlemp);
$rowposemp = mysqli_fetch_array($resultemp);  

?>
<!DOCTYPE html>
<html>
<head>
<? include('h.php'); ?>
<nav class="navbar navbar-expand-md navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="#" ><a style="color:#ff9933;font-weight:bold;font-size:large;">ร้านไทยเจริญก่อสร้าง &#8482;</a></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="../index.php"><i class="fa fa-home" aria-hidden="true"></i> หน้าหลัก <span class="sr-only">(current)</span></a>
      </li>
      <?php if($substr = substr($rowposemp['pos_status'], 6, 1)) { ?>
      <li class="nav-item">
        <a class="nav-link" href="/company/cpn_show.php">
        <i class="fa fa-building-o" aria-hidden="true"></i>
        บริษัทคู่ค้า</a>
      </li>
        <?php } ?>

      <?php if($substr = substr($rowposemp['pos_status'], 3, 1)) { ?>
      <li class="nav-item">
        <a class="nav-link" href="/employee/emp_show.php"><i class="fa fa-user-o" aria-hidden="true"></i>
        พนักงาน</a>
      </li>
      <?php } ?>

      <?php if($substr = substr($rowposemp['pos_status'], 0, 1)) { ?>
      <li class="nav-item">
        <a class="nav-link" href="/customer/cs_show.php"><i class="fa fa-user-o" aria-hidden="true"></i>
        สมาชิก</a>
      </li>
      <?php } ?>   

      <?php if(isset($substrproduct) != substr($rowposemp['pos_status'], 1, 1) || isset($substrbrand) != substr($rowposemp['pos_status'], 4, 1) || isset($substrunit) != substr($rowposemp['pos_status'], 7, 1) || isset($substrcolor) != substr($rowposemp['pos_status'], 10, 1) || isset($substrsize) != substr($rowposemp['pos_status'], 11, 1) || isset($substrclass) != substr($rowposemp['pos_status'], 12, 1) || isset($substrclass) != substr($rowposemp['pos_status'], 13, 1)) { ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-alt" aria-hidden="true"></i>
        ข้อมูลสินค้า
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php if($substrproduct = substr($rowposemp['pos_status'], 1, 1)) { ?>
            <a class="dropdown-item" href="/product/product_show.php">รายการสินค้า</a>
            <?php } ?>
            <?php if($substrbrand = substr($rowposemp['pos_status'], 4, 1)) { ?>
            <a class="dropdown-item" href="/brand/brand_show.php">ยี่ห้อ</a>
            <?php } ?>
            <?php if($substrcolor = substr($rowposemp['pos_status'], 10, 1)) { ?>
            <a class="dropdown-item" href="/color/color_show.php">สี</a>
            <?php } ?>
            <?php if($substrsize = substr($rowposemp['pos_status'], 11, 1)) { ?>
            <a class="dropdown-item" href="/material/m_show.php">วัสดุ</a>
            <?php } ?>
            <?php if($substrunit = substr($rowposemp['pos_status'], 7, 1)) { ?>
            <a class="dropdown-item" href="/unit/unit_show.php">หน่วย</a>
            <?php } ?>
        </div>
      </li>
      <?php } ?>

      <?php if(isset($substrquo) !=  substr($rowposemp['pos_status'], 2, 1) || isset($substrorder) != substr($rowposemp['pos_status'], 5, 1) || isset($substrreceive) != substr($rowposemp['pos_status'], 8, 1)){ ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-cart-plus" aria-hidden="true"></i>
        สั่งซื้อสินค้า
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php if($substrquo = substr($rowposemp['pos_status'], 2, 1)) { ?>
            <a class="dropdown-item" href="/transaction/quo_form.php">ออกใบเสนอซื้อ</a>
            <?php } ?>
            <?php if($substrorder = substr($rowposemp['pos_status'], 5, 1)) { ?>
            <a class="dropdown-item" href="/transaction/order_show.php">ใบสั่งซื้อสินค้า</a>
            <?php } ?>
            <?php if($substrreceive = substr($rowposemp['pos_status'], 8, 1)) { ?>
            <a class="dropdown-item" href="/transaction/receive_show.php">รับสินค้า & ล็อต</a>
            <?php } ?>
            <!-- <a class="dropdown-item" href="/product/lot_product.php">ล็อตสินค้าหลังร้าน</a> -->
        </div>
      </li>
      <?php } ?>
      <?php if($substrquo = substr($rowposemp['pos_status'], 13, 1)) { ?>
      <li class="nav-item">
        <a class="nav-link" href="/sale_product/sale_product.php">
        <i class="fa fa-credit-card" aria-hidden="true"></i>
        การขายสินค้า
        </a>
      </li>
      <?php } ?>
      
    </ul>
    <form class="form-inline my-2 my-lg-0">
    <?php
    $date=date('Y-m-d');
    list($y,$m,$d)=explode('-',$date); 

    // Change the line below to your timezone!
    date_default_timezone_set('Asia/Bangkok');
    $date = date('d/m/Y');
    ?>
      <a class="nav-link" style="color:white;">วันที่ <?= $date?></a>
    </form>
  </div>
</nav>
</head>

<body>

<?php 
include('connectdb.php');
$username = $_SESSION['user'];
?>
<input type="hidden" name="date" value="<?= $date=date('Y-m-d') ?>"/>
</body>
</html>
