<?php session_start(); ?>
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
                    <h6><i class="fas fa-user-circle"></i>&ensp;<a style="font-weight:bold;"><?php echo "ผู้ใช้"; ?></a><a style="color:#c92828;font-weight:bold;"><?php echo " : " . $_SESSION['user']; ?></a></h6>
                    <h6><i class="fas fa-check-square"></i></i></i>&ensp;<a style="font-weight:bold;"><?php echo "ตำแหน่ง"; ?></a><a style="color:#1d4891;font-weight:bold;"><?php echo " : " . $_SESSION['posname']; ?></a></h6>
                </div>
                <?php include('../menu_left.php'); ?>
                <!-- Content Wrapper. Contains page content -->
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <form action="m_edit.php" name="edit" method="post">
                            <h4>แก้ไขวัสดุ</h4>
                            <table class="table">
                                <?php
                                $id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';   //getรับค่าจาก show เป็น url

                                if ($id != '') { // ถ้า id ไม่เท่ากับ ค่าว่าง
                                    $sql = "SELECT * FROM material WHERE mt_id='" . $id . "'";
                                    $result = mysqli_query($conn, $sql); //คิวรี่ คำสั่งsql เก็บใน ตัวแปร result
                                    $row = mysqli_fetch_array($result);
                                    extract($row);
                                ?>
                                    <tr hidden>
                                        <td align="right">Material_id : </td>
                                        <td><input class="form-control mb-3" type="text" name="id" value="<?= $mt_id; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td align="right">&emsp;&emsp;&emsp;&emsp;&emsp;ชื่อวัสดุ :</td>
                                        <td><input class="form-control mb-3" style="width:220px" type="text" name="name" value="<?= $mt_name; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input class="w3-button w3-red w3-round-xlarge" style="width:220px" type="submit" name="updatebrand" value="บันทึก"></td>
                                    </tr>

                                <?php } ?>
                            </table>
                            <?php

                            if (isset($_POST['updatebrand'])) {
                                $id = $_POST['id'];
                                $name = $_POST['name'];

                                $sqlname = "SELECT mt_id,count(mt_name) AS mt_name FROM material WHERE mt_name = '$name' AND mt_id != '$id'";
                                $query = mysqli_query($conn, $sqlname);
                                $row = mysqli_fetch_array($query);

                                $namerows = $row['mt_name'];

                                if ($namerows == '0') {
                                    $sql = "UPDATE material SET mt_name = '$name' WHERE mt_id = '$id'";
                                    if ($result = mysqli_query($conn, $sql) == true) {
                                        echo "<script>";
                                        echo "alert('Update Success!');";
                                        echo "window.location.href='m_show.php';";
                                        echo "</script>";
                                    } else {
                                        echo "<script>";
                                        echo "alert('Update Unsuccess!');";
                                        echo "window.location.href='javascript:history.back(1)';";
                                        echo "</script>";
                                    }
                                } else {
                                    echo "<script>";
                                    echo "alert('มีชื่อยี่ห้อนี้ในระบบแล้ว กรุณาเปลี่ยนใหม่!');";
                                    echo "window.location.href='javascript:history.back(1)';";
                                    echo "</script>";
                                }
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>