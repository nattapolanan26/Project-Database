<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); ?>
    <script type="text/javascript">
        function submitheadcolor1() {

            document.unitform.submit();
        }

        function submitForm() {

            document.getElementById("unitform").action = "unit_insert.php";
        }
    </script>
</head>

<body>
    <?php include('../connectdb.php'); ?>
    <div class="container-fluid">
        <?php include('../navbar.php'); ?>
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
                        <form action="#" name="unitform" id="unitform" method="post">
                            <h4>เพิ่มหน่วย</h4>
                            <table class="table">
                                <tr>
                                    <td align="right">&emsp;&emsp;&emsp;&emsp;&emsp;ชื่อหน่วย :</td>
                                    <td><input class="form-control mb-3" style="width:220px" type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input class="w3-button w3-red w3-round-xlarge" style="width:220px" type="submit" name="unitadd" value="บันทึก" onclick="submitForm();" style="width: 100px;"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>