<?php
     include('../connectdb.php');

     $request=$_REQUEST;
     $col =array(
          0   =>  's_id',
          1   =>  's_date',
          2   =>  'emp_name',
          3   =>  'emp_lname',
          4   =>  's_total',
     );  //create column like table in database

     $sql ="
     SELECT ss.s_id,ss.s_date,ss.s_total,emp.emp_name,emp.emp_lname
     FROM sales_slip ss
     INNER JOIN employee emp ON emp.emp_id = ss.emp_id
     WHERE 1=1";
     $query=mysqli_query($conn,$sql);
     $totalData=mysqli_num_rows($query);
     $totalFilter=$totalData;

     //Search
     $sql ="
     SELECT ss.s_id,ss.s_date,ss.s_total,emp.emp_name,emp.emp_lname
     FROM sales_slip ss
     INNER JOIN employee emp ON emp.emp_id = ss.emp_id
     WHERE 1=1";
     if(!empty($request['search']['value'])){
          $sql .=" AND (s_id Like '".$request['search']['value']."%' ";
          $sql .=" OR s_date Like '".$request['search']['value']."%' ";
          $sql .=" OR emp_name Like '".$request['search']['value']."%' ";
          $sql .=" OR emp_lname Like '".$request['search']['value']."%' ";
          $sql .=" OR s_total Like '".$request['search']['value']."%' )";

     }
     $query=mysqli_query($conn,$sql);
     $totalData=mysqli_num_rows($query);

     //Order
     $sql.=" ORDER BY ".$col[$request['order'][0]['column']]."   ".$request['order'][0]['dir']."  LIMIT ".
     $request['start']."  ,".$request['length']."  ";

     $query=mysqli_query($conn,$sql);

     $data=array();

     while($row=mysqli_fetch_array($query)){

          $date = $row[1];
          $date = date('d/m/Y', strtotime($date));

          $subdata=array();

          $subdata[]=$row[0];
          $subdata[]=$date; //date
          $subdata[]=$row[3] ." ". $row[4];
          $subdata[]=number_format($row[2],2);
          $subdata[]='<button type="button" id="show_data" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modalShow" data-id="'.$row[0].'"><i class="fa fa-eye" aria-hidden="true"></i></button>
          <a href="../mpdf/sale_mpdf.php?sale_id='.$row[0].'" class="btn btn-primary btn-xs"><i class="fa fa-print" aria-hidden="true"></i></a>';
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