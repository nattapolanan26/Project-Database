<?php
     include('../connectdb.php');

     $request=$_REQUEST;
     $col =array(
          0   =>  'rp_id',
          1   =>  'date',
          2   =>  'emp_name',
          3   =>  'emp_lname',
     );  //create column like table in database

     $sql ="SELECT DISTINCT rp.rp_id,date,emp.emp_name,emp.emp_lname
     FROM receiveproduct rp
     INNER JOIN employee emp ON rp.emp_id = emp.emp_id
     WHERE 1=1";
     $query=mysqli_query($conn,$sql);
     $totalData=mysqli_num_rows($query);
     $totalFilter=$totalData;

     //Search
     $sql ="SELECT DISTINCT rp.rp_id,date,emp.emp_name,emp.emp_lname
     FROM receiveproduct rp
     INNER JOIN employee emp ON rp.emp_id = emp.emp_id
     WHERE 1=1";
     if(!empty($request['search']['value'])){
          $sql .=" AND (rp_id Like '".$request['search']['value']."%' ";
          $sql .=" OR date Like '".$request['search']['value']."%' ";
          $sql .=" OR emp_name Like '".$request['search']['value']."%' ";
          $sql .=" OR emp_lname Like '".$request['search']['value']."%' )";

     }
     $query=mysqli_query($conn,$sql);
     $totalData=mysqli_num_rows($query);

     //Order
     $sql.=" ORDER BY ".$col[$request['order'][0]['column']]."   ".$request['order'][0]['dir']."  LIMIT ".
     $request['start']."  ,".$request['length']."  ";

     $query=mysqli_query($conn,$sql);

     $data=array();

     while($row=mysqli_fetch_array($query)){
          //ตัดวันที่ : เวลา
          $date = $row[1];
          list($date, $time) = explode(' ', $date); // แยกวันที่ กับ เวลาออกจากกัน
          $overdate = date('d/m/Y', strtotime($date));

          $subdata=array();

          $subdata[]=$row[0]; //rp_id
          $subdata[]=$overdate ." - ". $time; //date
          $subdata[]=$row[2] ." ". $row[3]; //name & lname
          $subdata[]='<button type="button" id="show_data" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalShow" data-id="'.$row[0].'"><i class="fa fa-eye" aria-hidden="true"></i></button>';
          $data[]=$subdata;
     }

     $json_data=array(
          "draw"              =>  intval($request['draw']),
          "recordsTotal"      =>  intval($totalData),
          "recordsFiltered"   =>  intval($totalFilter),
          "data"              =>  $data
     );

     echo json_encode($json_data);
?>