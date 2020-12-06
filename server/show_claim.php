<?php
     include('../connectdb.php');

     $request=$_REQUEST;
     $col =array(
          0   =>  'cl_id',
          1   =>  'cl_date',
          2   =>  'emp_name',
          3   =>  'emp_lname',
          4   =>  's_id',
          5   =>  'cl_status',

     );  //create column like table in database

     $sql ="
     SELECT c1.cl_id,c1.cl_date,s1.s_id,c1.cl_status,emp.emp_name,emp.emp_lname,c4.ccp_status,c6.cr_status,c4.ccp_id
     FROM claim_ctm_h c1
     LEFT JOIN claim_ctm_list c2 ON c2.cl_id = c1.cl_id
     LEFT JOIN sale_items s1 ON s1.s_id = c2.s_id
     LEFT JOIN claim_cpn_list c3 ON c3.cl_id = c2.cl_id AND c3.ccp_no = c2.cl_no
     LEFT JOIN claim_cpn_h c4 ON c4.ccp_id = c3.ccp_id
     LEFT JOIN claim_receive_list c5 ON c5.ccp_id = c3.ccp_id
     LEFT JOIN claim_receive_h c6 ON c6.cr_id = c5.cr_id
     INNER JOIN employee emp ON emp.emp_id = c1.emp_id
     WHERE 1=1
     GROUP BY c1.cl_id";
     $query=mysqli_query($conn,$sql);
     $totalData=mysqli_num_rows($query);
     $totalFilter=$totalData;

     //Search
     $sql ="
     SELECT c1.cl_id,c1.cl_date,s1.s_id,c1.cl_status,emp.emp_name,emp.emp_lname,c4.ccp_status,c6.cr_status,c4.ccp_id,c1.choice_status
     FROM claim_ctm_h c1
     LEFT JOIN claim_ctm_list c2 ON c2.cl_id = c1.cl_id
     LEFT JOIN sale_items s1 ON s1.s_id = c2.s_id
     LEFT JOIN claim_cpn_list c3 ON c3.cl_id = c2.cl_id AND c3.ccp_no = c2.cl_no
     LEFT JOIN claim_cpn_h c4 ON c4.ccp_id = c3.ccp_id
     LEFT JOIN claim_receive_list c5 ON c5.ccp_id = c3.ccp_id
     LEFT JOIN claim_receive_h c6 ON c6.cr_id = c5.cr_id
     INNER JOIN employee emp ON emp.emp_id = c1.emp_id
     WHERE 1=1
     GROUP BY c1.cl_id";
     if(!empty($request['search']['value'])){
          $sql .=" AND (cl_id Like '".$request['search']['value']."%' ";
          $sql .=" OR cl_date Like '".$request['search']['value']."%' ";
          $sql .=" OR emp_name Like '".$request['search']['value']."%' ";
          $sql .=" OR emp_lname Like '".$request['search']['value']."%' ";
          $sql .=" OR s_id Like '".$request['search']['value']."%' ";
          $sql .=" OR cl_status Like '".$request['search']['value']."%' )";
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
          $subdata[]=$date; 
          $subdata[]=$row[4] ." ". $row[5]; 
          $subdata[]=$row[2]; 
          if($row[3] == '0'){
               $subdata[]="<button type='submit' style='color:green' id='show_data' class='btn btn-light show_data' data-toggle='modal' data-target='#modalShow' data-id_claim='$row[0]' data-id_sale='$row[2]'><i class='fas fa-check-circle'></i>&ensp;ยืนยันส่งเคลม</i></button>";
          }else if($row[3] == '1'){
               $subdata[]="<div class='mx-auto bg-warning' style='font-size:12px;width:140px;border-radius:6px;color:#4440BB'><i class='fas fa-shipping-fast'></i>&ensp;ส่งไปเคลมบริษัทคู่ค้า </div>";
          }else if($row[3] == '2'){
               $subdata[]="<div class='mx-auto bg-success' style='font-size:12px;width:140px;border-radius:6px;color:white;'><i class='far fa-check-circle'></i>&ensp;การเคลมสำเร็จ </div>";
          }else if($row[3] == '3'){
               $subdata[]="<div class='mx-auto bg-success' style='font-size:12px;width:140px;border-radius:6px;color:white;'><i class='far fa-check-circle'></i>&ensp;คืนสินค้าให้ลูกค้าสำเร็จ </div>";
          }else if($row[3] == '4'){
               $subdata[]="<div class='mx-auto' style='font-size:12px;width:140px;border-radius:6px;color:white;background-color:#EA1A1A;'><i class='far fa-check-circle'></i>&ensp;เคลมกับทางร้านสำเร็จ </div>";
          }

          if($row[6] == NULL){
               $subdata[]="";
          }else if($row[6] == '0'){
               $subdata[]="<div class='mx-auto' style='font-size:12px;width:140px;border-radius:6px;color:#4440BB;'><i class='fa fa-spinner fa-spin fa-fw'></i><span class='sr-only'>Loading...</span>&ensp;กำลังเคลมสินค้า </div>";
          }else if($row[6] == '1'){
               $subdata[]="<div class='mx-auto bg-success' style='font-size:12px;width:140px;border-radius:6px;color:white;'><i class='far fa-check-circle'></i>&ensp;ส่งคืนสินค้าสำเร็จ </div>";
          }

          if($row[7] == NULL){
               $subdata[]="";
          }else if($row[7] == '0'){
               $subdata[]="<div class='mx-auto' style='font-size:12px;width:140px;border-radius:6px;color:red;'>&ensp;รับสินค้าไม่ครบ</div>";
          }else if($row[7] == '1'){
               $subdata[]="<div class='mx-auto bg-success' style='font-size:12px;width:140px;border-radius:6px;color:white;'><i class='far fa-check-circle'></i>&ensp; รับสินค้าครบ</div>";
          }else if($row[7] == '2'){
               $subdata[]="ยกเลิกการเคลม";
          }
          $subdata[] = '<button type="button" id="show_data_print" class="btn btn-xs show_data_print" data-toggle="modal" data-target="#modalClaim" data-id="' . $row[0] . '"><i class="far fa-file-pdf fa-2x"></i></button>';
          if($row[9] == 'cpn' && $row[3] != '0'){
               $subdata[] = '<button type="button" id="show_data_print" class="btn btn-xs show_data_print" data-toggle="modal" data-target="#modalClaim" data-id="' . $row[0] . '"><i class="far fa-file-pdf fa-2x"></i></button>';
          }else{
               $subdata[] = '';
          }
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