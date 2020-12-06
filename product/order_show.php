<?php
if (isset($_SESSION['empid']) == true) {
?>
  <!DOCTYPE html>
  <html>

  <head>
    <?php include('../h.php'); ?>
  </head>

  <body>
    <?php include('../connectdb.php'); ?>
    <?php include('../navbar.php'); ?>
    <div class="container-fluid">>
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
              <form action="#" name="order_pro_show" id="order_pro_show" method="post">
                <h5 class="card-header"><i class="fa fa-list-alt" aria-hidden="true"></i> ใบสั่งซื้อ</h5>
                <p></p>
                <input type="hidden" name="date" value="<? $date=date('Y-m-d H:i:s') ?>" />
                <div class="table-responsive">
                  <?php
                  $sql = "SELECT * FROM orderproduct INNER JOIN employee ON orderproduct.emp_id = employee.emp_id";
                  $result = $conn->query($sql);
                  $row =  mysqli_fetch_array($result);
                  ?>

                  <script>
                    $(document).ready(function() {
                      $('#example').dataTable({
                        "oLanguage": {
                          "sLengthMenu": "แสดง _MENU_ แถว",
                          "sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
                          "sInfo": "แสดง _START_ - _END_ ทั้งหมด _TOTAL_ แถว",
                          "sInfoEmpty": "แสดง 0 - 0 ของ 0 แถว",
                          "sInfoFiltered": "(จากแถวทั้งหมด _MAX_ แถว)",
                          "sSearch": "ค้นหา :",
                          "aaSorting": [
                            [0, 'desc']
                          ],
                          "oPaginate": {
                            "sFirst": "หน้าแรก",
                            "sPrevious": "ก่อนหน้า",
                            "sNext": "ถัดไป",
                            "sLast": "หน้าสุดท้าย"
                          },
                          "pageLength": 10,
                          "order": [
                            [0, 'asc']
                          ]
                        }
                      });
                    });
                  </script>

                  <?php
                  echo '<table border="2" class="display table table-bordered" id="example" align="center">';
                  //หัวข้อตาราง
                  echo " <thead>
                <tr bgcolor='#AED6F1' align='center' style='font-weight:bold'>
                <th width='15%'>รหัสใบสั่งซื้อ</th>
                <th width='20%'>วันที่ออกใบสั่งซื้อ</th>
                <th>ผู้อนุมัติ</th>
                <th width='15%'>สถานะการรับ</th>
                <th width='12%'>พิมพ์ใบสั่งซื้อ</th>
                </tr>
                </thead>";
                  do {
                    if ($row["order_id"] != '') {
                      $quo_date = $row["date"];
                      list($date, $time) = explode(' ', $quo_date); // แยกวันที่ กับ เวลาออกจากกัน
                      $overdate = date('d/m/Y', strtotime($date));

                      echo "<tr>";
                      echo "<td align='center'>" . $row["order_id"] . "</td> ";
                      echo "<td align='center'>" . $overdate . ' : ' . $time . "</td> ";
                      echo "<td align='center'>" . $row["emp_name"] . " " . $row["emp_lname"] . "</td> ";
                      if ($row['status_receive'] == '0') {
                        echo "<td align='center'>" . "<div class='mx-auto bg-warning' style='font-size:12px;width:150px;border-radius:6px;color:dark;font-weight:bold;'>รอทำรายการ</div>" . "</td>";
                      } else if ($row['status_receive'] == '1') {
                        echo "<td align='center'>" .  "<div class='mx-auto bg-warning' style='font-size:12px;width:150px;border-radius:6px;color:green;font-weight:bold;'><i class='fa fa-check-circle' aria-hidden='true'></i> ทำรายการเสร็จสิ้น</div>";
                      }
                      echo "<td align='center'><a href='../mpdf/order_mpdf.php?order_id=$row[order_id]' class='btn btn-primary btn-xs'><i class='fa fa-print' aria-hidden='true'></i></a></td>";
                    }
                  } while ($row =  mysqli_fetch_array($result));
                  echo '</table>'
                  ?>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>

  </html>
<?php } else {
  header("Location: ../../login_form.php");
} ?>