<?php if (!isset($_SESSION)) {
     session_start();
} ?>
<?php
include('../connectdb.php');

$sqlemp = "SELECT * FROM employee INNER JOIN position ON employee.pos_id = position.pos_id WHERE position.pos_id = '" . $_SESSION['posid'] . "'";
$resultemp = mysqli_query($conn, $sqlemp);
$rowposemp = mysqli_fetch_array($resultemp);

$request = $_REQUEST;
$col = array(
     0   =>  'quo_id',
     1   =>  'emp_name',
     2   =>  'emp_lname',
     3   =>  'date',
);  //create column like table in database

$sql = "SELECT * FROM quotation
     INNER JOIN employee ON employee.emp_id = quotation.emp_id 
     WHERE 1=1";
$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

//Search
$sql = "SELECT quo_id,date,emp_name,emp_lname,status FROM quotation
     INNER JOIN employee ON employee.emp_id = quotation.emp_id 
     WHERE 1=1";
if (!empty($request['search']['value'])) {
     $sql .= " AND (quo_id Like '" . $request['search']['value'] . "%' ";
     $sql .= " OR date Like '" . $request['search']['value'] . "%' ";
     $sql .= " OR emp_name Like '" . $request['search']['value'] . "%' ";
     $sql .= " OR emp_lname Like '" . $request['search']['value'] . "%' )";
}
$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);

//Order
$sql .= " ORDER BY " . $col[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'] . "  LIMIT " .
     $request['start'] . "  ," . $request['length'] . "  ";

$query = mysqli_query($conn, $sql);

$data = array();

while ($row = mysqli_fetch_array($query)) {
     //ตัดวันที่ : เวลา

     $date = date('d/m/Y', strtotime($row[1]));

     $subdata = array();

     $subdata[] = $row[0];
     $subdata[] = $date;
     $subdata[] = $row[2] . " " . $row[3];
     if ($row[4] == '0') { 
          $subdata[] = "<div class='mx-auto bg-warning' style='font-size:12px;width:100px;border-radius:6px;'>รอการอนุมัติ</div>";
     } else if ($row[4] == '1') {
          $subdata[] = "<div class='mx-auto bg-warning' style='font-size:12px;width:100px;border-radius:6px;color:green;'><i style='color:green;' class='fa fa-check-circle' aria-hidden='true'></i> อนุมัติเสร็จสิ้น</div>";
     } else if ($row[4] == '2') {
          $subdata[] = "<div class='mx-auto bg-danger' style='font-size:12px;width:100px;border-radius:6px;color:white;'><i style='color:green;' class='fa fa-times-circle' aria-hidden='true'></i> ไม่อนุมัติ</div>";
     }
     $subdata[] = '<button type="button" id="show_data" class="btn btn-info btn-xs show_data" data-toggle="modal" data-target="#show-modal" data-id="' . $row[0] . '"><i class="fa fa-eye" aria-hidden="true"></i></button>
     <button type="button" id="approve" class="btn btn-success btn-xs approve" data-toggle="modal" data-target="#approve-modal" data-id="' . $row[0] . '"><i class="fas fa-check-square"></i></i></i></button>
     <a href="../mpdf/quo_mpdf.php?quo_id='.$row[0].'" class="btn btn-primary btn-xs"><i class="fa fa-print" aria-hidden="true"></i></a>';

     $data[] = $subdata;
}

$json_data = array(
     "draw"              =>  intval($request['draw']),
     "recordsTotal"      =>  intval($totalData),
     "recordsFiltered"   =>  intval($totalFilter),
     "data"              =>  $data
);

echo json_encode($json_data);
