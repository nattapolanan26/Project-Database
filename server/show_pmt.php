<?php
     include('../connectdb.php');

     $request=$_REQUEST;
     $col =array(
          0   =>  'promotion_id',
          1   =>  'promotion_name',
          2   =>  'promotion_discount',
          3   =>  'date_start',
          4   =>  'date_end',
     );  //create column like table in database

     $sql ="SELECT * FROM promotion WHERE 1=1 AND date_end > CURDATE()";
     $query=mysqli_query($conn,$sql);
     $totalData=mysqli_num_rows($query);
     $totalFilter=$totalData;

     //Search
     $sql ="SELECT * FROM promotion WHERE 1=1 AND date_end > CURDATE()";
     if(!empty($request['search']['value'])){
          $sql .=" AND (promotion_id Like '".$request['search']['value']."%' ";
          $sql .=" OR promotion_name Like '".$request['search']['value']."%' ";
          $sql .=" OR promotion_discount Like '".$request['search']['value']."%' ";
          $sql .=" OR date_start Like '".$request['search']['value']."%' ";
          $sql .=" OR date_end Like '".$request['search']['value']."%' )";

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
          
          $date_s = date('d/m/Y', strtotime($row[2]));
          $date_e = date('d/m/Y', strtotime($row[3]));

          $subdata=array();

          $subdata[]=$row[0];
          $subdata[]=$row[1];
          $subdata[]=$row[4]."%";
          $subdata[]=$date_s;
          $subdata[]=$date_e;
          $subdata[]='<button type="button" id="show_pmt" class="btn btn-primary btn-xs show_pmt" data-toggle="modal" data-target="#modalShow" data-id="'.$row[0].'"><i class="fa fa-eye" aria-hidden="true"></i></button>
          <button type="button" id="edit_pmt" class="btn btn-warning btn-xs edit_pmt" data-toggle="modal" data-target="#modalAdd" data-id="'.$row[0].'"><i class="far fa-edit"></i></button>
          <button type="button" id="del_pmt" class="btn btn-danger btn-xs del_pmt" data-id="'.$row[0].'"><i class="fas fa-trash-alt"></i></button>';
          $data[]=$subdata;
     }

     $json_data=array(
          "draw"              =>  intval($request['draw']),
          "recordsTotal"      =>  intval($totalData),
          "recordsFiltered"   =>  intval($totalFilter),
          "data"              =>  $data
     );

     echo json_encode($json_data);
