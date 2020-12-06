<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); ?>
    <script type="text/javascript">
        function submitheadcolor1() {

            document.insert.submit();
        }

        function submitForm() {

            document.getElementById("insert").action = "m_insert.php";
        }
    </script>
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
                        <form action="#" name="insert" id="insert" method="post">
                            <h4>เพิ่มวัสดุ</h4>
                            <table class="table">
                                <tr>
                                    <td align="right">&emsp;&emsp;&emsp;&emsp;&emsp;ชื่อวัสดุ :</td>
                                    <td><input class="form-control mb-3" style="width:220px" type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"></td>
                                </tr>
                                <tr>
                                    <td>
                                    <td><input class="w3-button w3-red w3-round-xlarge" type="submit" name="submitadd" value="บันทึก" onclick="submitForm();" style="width:220px;"></td>
                                    </td>
                                </tr>
                            </table>
                            <?php
                            if (isset($_POST['submitadd'])) {
                                $name = $_POST['name'];

                                $idsql = "SELECT concat('MT',LPAD(ifnull(SUBSTR(max(mt_id),3,7),'0')+1,5,'0')) as MT_ID FROM material";
                                $conn->query($idsql) == true;
                                $resultid = mysqli_query($conn, $idsql);
                                $row = mysqli_fetch_array($resultid);
                                $id = $row['MT_ID'];


                                $sqlname = "SELECT mt_name FROM material WHERE mt_name = '$name'";
                                $result = mysqli_query($conn, $sqlname);
                                $namerows = mysqli_num_rows($result);
                                if ($name != '') {
                                    if ($namerows > 0) {
                                        echo "<script>"; //คำสั่งสคิป
                                        echo "alert('Material ซ้ำ กรุณากรอกใหม่!');"; //แสดงหน้าต่างเตือน
                                        echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
                                        echo "</script>";
                                    } else {
                                        $sql = "INSERT INTO material (mt_id,mt_name) VALUES ('$id','$name')";

                                        if ($conn->query($sql) == true) {
                                            echo "<script>"; //คำสั่งสคิป
                                            echo "alert('insert Success!');"; //แสดงหน้าต่างเตือน
                                            echo "window.location.href='m_show.php';"; //แสดงหน้าก่อนนี้
                                            echo "</script>";
                                        } else {
                                            echo "<script>"; //คำสั่งสคิป
                                            echo "alert('ผิดพลาด กรุณากรอกข้อมูลใหม่!');"; //แสดงหน้าต่างเตือน
                                            echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
                                            echo "</script>";
                                        }
                                    }
                                } else {
                                    echo "<script>"; //คำสั่งสคิป
                                    echo "alert('กรุณากรอกข้อมูลให้ครบถ้วน!');"; //แสดงหน้าต่างเตือน
                                    echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
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