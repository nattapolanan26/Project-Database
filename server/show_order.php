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
     0   =>  'order_id',
     1   =>  'emp_name',
     2   =>  'emp_lname',
     3   =>  'date',
     4   =>  'status_receive',
);  //create column like table in database

$sql = "
SELECT * FROM orderproduct
INNER JOIN employee ON employee.emp_id = orderproduct.emp_id 
WHERE 1=1 AND status_receive = '0'";
$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);
$totalFilter = $totalData;

//Search
$sql = "
SELECT * FROM orderproduct
INNER JOIN employee ON employee.emp_id = orderproduct.emp_id 
WHERE 1=1 AND status_receive = '0'";
if (!empty($request['search']['value'])) {
     $sql .= " AND (order_id Like '" . $request['search']['value'] . "%' ";
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
     $subdata[] = $row[5] . " " . $row[6];
     if ($row[3] == '0') { 
          $subdata[] = "<div class='mx-auto bg-warning' style='font-size:12px;width:100px;border-radius:6px;'>รออัพเดท</div>";
     } else if ($row[3] == '1') {
          $subdata[] = "<div class='mx-auto bg-warning' style='font-size:12px;width:100px;border-radius:6px;color:green;'><i style='color:green;' class='fa fa-check-circle' aria-hidden='true'></i> การรับเสร็จสิ้น</div>";
     }
     $subdata[] = '<button type="button" id="show_data" class="btn btn-primary btn-xs show_data" data-toggle="modal" data-target="#show-modal" data-id="' . $row[0] . '"><i class="fa fa-eye" aria-hidden="true"></i></button>';

     $data[] = $subdata;
}

$json_data = array(
     "draw"              =>  intval($request['draw']),
     "recordsTotal"      =>  intval($totalData),
     "recordsFiltered"   =>  intval($totalFilter),
     "data"              =>  $data
);

echo json_encode($json_data);
