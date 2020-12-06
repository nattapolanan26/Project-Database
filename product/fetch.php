<?php

include('../connectdb.php');

if (isset($_POST['product'])) {
     $product=$_POST['product'];
     $query = "SELECT pd.product_id,pd.product_name,pd.product_saleprice,pd.product_stock,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,CURDATE() AS C_DATE
     FROM product pd
     INNER JOIN brand ON brand.brand_id = pd.brand_id
     INNER JOIN unit ON unit.unit_id = pd.unit_id
     LEFT JOIN cement ON cement.product_id = pd.product_id
     LEFT JOIN categorycolor ON categorycolor.product_id = pd.product_id
     LEFT JOIN toilet ON toilet.product_id = pd.product_id
     LEFT JOIN chemicalsolution ON chemicalsolution.product_id = pd.product_id
     LEFT JOIN craftmantool ON craftmantool.product_id = pd.product_id
     LEFT JOIN plumbling ON plumbling.product_id = pd.product_id
      
     LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
     WHERE pd.product_id = '$product'
     ORDER BY pd.product_id";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          $row = $statement->fetch(PDO::FETCH_ASSOC);

          $data = $row;
          
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}


if (isset($_POST['quo_id'])) {

     $query = "SELECT detailquotation.*,costprice.product_id,product.product_name,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,cpn_name
     FROM detailquotation
     INNER JOIN costprice ON costprice.cpn_id = detailquotation.cpn_id AND costprice.product_id = detailquotation.product_id
     INNER JOIN product ON product.product_id = costprice.product_id
     INNER JOIN company ON company.cpn_id = costprice.cpn_id
     INNER JOIN brand ON brand.brand_id = product.brand_id
     INNER JOIN unit ON unit.unit_id = product.unit_id
     LEFT JOIN cement ON cement.product_id = product.product_id
     LEFT JOIN categorycolor ON categorycolor.product_id = product.product_id
     LEFT JOIN toilet ON toilet.product_id = product.product_id
     LEFT JOIN chemicalsolution ON chemicalsolution.product_id = product.product_id
     LEFT JOIN craftmantool ON craftmantool.product_id = product.product_id
     LEFT JOIN plumbling ON plumbling.product_id = product.product_id
      
     LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
     WHERE detailquotation.quo_id = '" . $_POST['quo_id'] . "'
     ORDER BY quo_order ASC";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          echo json_encode($data);
     }
}

if (isset($_POST['quo_head'])) {

     $q_id = $_POST['quo_head'];

     $query = "SELECT q.status FROM quotation q WHERE q.quo_id = '$q_id'";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          $data = $statement->fetch(PDO::FETCH_ASSOC);

          echo json_encode($data);
     }
}

if (isset($_POST['quo_list'])) {

     $q_id = $_POST['quo_list'];
     $output = '';
     $query = "
     SELECT dq.quo_order,dq.product_id,dq.number,dq.price,dq.cpn_id,q.sum_price,q.vat,q.total_price,pd.product_name,b.brand_name,u.unit_name,c.color_name,class,cp.costprice,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,cpn_name,q.status
     FROM detailquotation dq
     INNER JOIN quotation q ON q.quo_id = dq.quo_id 
     LEFT JOIN product pd ON dq.product_id = pd.product_id 
     LEFT JOIN cement cm ON cm.product_id = pd.product_id 
     LEFT JOIN categorycolor cgc ON cgc.product_id = pd.product_id 
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id 
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id 
     LEFT JOIN craftmantool cmt ON cmt.product_id = pd.product_id 
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id 
       
     LEFT JOIN color c ON c.color_id = cgc.color_id OR c.color_id = cmt.color_id OR c.color_id = tl.color_id 
     LEFT JOIN costprice cp ON cp.product_id = pd.product_id AND cp.cpn_id = dq.cpn_id 
     LEFT JOIN company cpn ON cpn.cpn_id = cp.cpn_id 
     INNER JOIN brand b ON b.brand_id = pd.brand_id 
     INNER JOIN unit u ON u.unit_id = pd.unit_id 
     WHERE dq.quo_id = '$q_id'";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['order_list'])) {

     $id = $_POST['order_list'];
     $output = '';
     $query = "
     SELECT op.order_id,dop.cpn_id,cpn.cpn_name
     FROM detailorderpro dop
     INNER JOIN orderproduct op ON dop.order_id = op.order_id
     INNER JOIN company cpn ON cpn.cpn_id = dop.cpn_id
     WHERE dop.order_id = '$id'
     GROUP BY dop.cpn_id";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['po_id'])) {

     $po_id = $_POST['po_id'];

     $query = "
     SELECT dop.order_id,dop.order_no,dop.product_id,pd.product_status,dop.cpn_id,dop.number,drp.total,pd.product_name,cpn.cpn_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,class,pd.product_stock,cp.costprice
     FROM detailorderpro dop
     INNER JOIN orderproduct op ON op.order_id = dop.order_id
     INNER JOIN costprice cp ON cp.cpn_id = dop.cpn_id AND cp.product_id = dop.product_id
     INNER JOIN company cpn ON cpn.cpn_id = cp.cpn_id
     INNER JOIN product pd ON pd.product_id = cp.product_id
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
      
     LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
     LEFT JOIN
     (SELECT product_id,SUM(rp_number) AS total 
          FROM detailreceivepro
          INNER JOIN receiveproduct rp ON rp.rp_id = detailreceivepro.rp_id
          WHERE detailreceivepro.order_id = '$po_id'
          GROUP BY product_id) drp ON dop.product_id = drp.product_id
     WHERE dop.order_id = '$po_id'
     GROUP BY dop.product_id
     HAVING dop.number != drp.total IS NULL OR dop.number != drp.total
     ORDER BY dop.order_no";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}


if (isset($_POST['show_receive'])) {

     $id = $_POST['show_receive'];

     $query = "SELECT DISTINCT drp.*,pd.product_id,cpn.cpn_name,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,pb.class
     FROM detailreceivepro drp
     INNER JOIN receiveproduct rp ON rp.rp_id = drp.rp_id
     INNER JOIN detailorderpro dop ON dop.order_no = drp.rp_no AND dop.order_id = drp.order_id
     INNER JOIN orderproduct op ON op.order_id = dop.order_id
     INNER JOIN detailquotation dq ON dq.quo_order = dop.order_no AND dq.product_id = dop.product_id AND dq.cpn_id = dop.cpn_id
     INNER JOIN quotation q ON q.quo_id = dq.quo_id
     INNER JOIN costprice cp ON cp.cpn_id = dq.cpn_id AND cp.product_id = dq.product_id
     INNER JOIN company cpn ON cpn.cpn_id = cp.cpn_id
     INNER JOIN product pd ON pd.product_id = cp.product_id
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
     LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
     WHERE drp.rp_id = '$id'";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}


if (isset($_POST['sale_product_pmt'])) {
     $id=$_POST['sale_product_pmt'];
     $query = "SELECT pd.product_id,pd.product_name,pd.product_saleprice,pd.product_stock,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,pmt.*,lot_order,lot_balance
     FROM product pd
     INNER JOIN lot ON lot.product_id = pd.product_id
     INNER JOIN product_promotion pp ON pp.product_id = pd.product_id
     INNER JOIN promotion pmt ON pmt.promotion_id = pp.promotion_id
     INNER JOIN brand ON brand.brand_id = pd.brand_id
     INNER JOIN unit ON unit.unit_id = pd.unit_id
     LEFT JOIN cement ON cement.product_id = pd.product_id
     LEFT JOIN categorycolor ON categorycolor.product_id = pd.product_id
     LEFT JOIN toilet ON toilet.product_id = pd.product_id
     LEFT JOIN chemicalsolution ON chemicalsolution.product_id = pd.product_id
     LEFT JOIN craftmantool ON craftmantool.product_id = pd.product_id
     LEFT JOIN plumbling ON plumbling.product_id = pd.product_id
      
     LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
     WHERE pd.product_id = '".$id."' AND lot.lot_balance > 0 AND pd.product_stock != 0";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          $row = $statement->fetch(PDO::FETCH_ASSOC);

          $data = $row;
          
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['sale_product_notpmt'])) {
     $id=$_POST['sale_product_notpmt'];
     $query = "SELECT pd.product_id,pd.product_name,pd.product_saleprice,pd.product_stock,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,lot_order,lot_balance
     FROM product pd
     INNER JOIN lot ON lot.product_id = pd.product_id
     INNER JOIN brand ON brand.brand_id = pd.brand_id
     INNER JOIN unit ON unit.unit_id = pd.unit_id
     LEFT JOIN cement ON cement.product_id = pd.product_id
     LEFT JOIN categorycolor ON categorycolor.product_id = pd.product_id
     LEFT JOIN toilet ON toilet.product_id = pd.product_id
     LEFT JOIN chemicalsolution ON chemicalsolution.product_id = pd.product_id
     LEFT JOIN craftmantool ON craftmantool.product_id = pd.product_id
     LEFT JOIN plumbling ON plumbling.product_id = pd.product_id
      
     LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
     WHERE pd.product_id = '$id' AND lot.lot_balance > 0 AND pd.product_stock != 0
     GROUP BY pd.product_id";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          $row = $statement->fetch(PDO::FETCH_ASSOC);

          $data = $row;
          
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}


if (isset($_POST['show_promotion'])) {
     $id=$_POST['show_promotion'];
     $query = "SELECT pmt.*,pp.product_id,pp.promotion_id,pd.product_name,b.brand_name,u.unit_name,c.color_name,class,tl.tl_size,pb.pb_size,pb.pb_thick,cc.cc_volume,ct.ct_size,cs.cs_volume,cm.cm_volume
     FROM product_promotion pp
     INNER JOIN promotion pmt ON pmt.promotion_id = pp.promotion_id
     INNER JOIN product pd ON pd.product_id = pp.product_id
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
      
     LEFT JOIN color c ON c.color_id = cc.color_id OR c.color_id = ct.color_id OR c.color_id = tl.color_id
     WHERE pmt.promotion_id = '$id'";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}


if (isset($_POST['show_sale'])) {

     $id = $_POST['show_sale'];

     $query = "SELECT si.*,pd.product_name,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,ss.s_sum AS total_sum,si.s_discount AS dis_list,ss.s_discount AS discount,ss.s_vat AS vat,ss.s_total AS total_list
     FROM sale_items si
     INNER JOIN sales_slip ss ON ss.s_id = si.s_id
     INNER JOIN lot ON lot.product_id = si.product_id AND lot.lot_order = si.lot_order
     INNER JOIN product pd ON pd.product_id = lot.product_id
     INNER JOIN brand ON brand.brand_id = pd.brand_id
     INNER JOIN unit ON unit.unit_id = pd.unit_id
     LEFT JOIN cement ON cement.product_id = pd.product_id
     LEFT JOIN categorycolor ON categorycolor.product_id = pd.product_id
     LEFT JOIN toilet ON toilet.product_id = pd.product_id
     LEFT JOIN chemicalsolution ON chemicalsolution.product_id = pd.product_id
     LEFT JOIN craftmantool ON craftmantool.product_id = pd.product_id
     LEFT JOIN plumbling ON plumbling.product_id = pd.product_id
      
     LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
     WHERE ss.s_id = '$id'";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['sale_id'])) {

     $sale_id = $_POST['sale_id'];

     $query = "
     SELECT si.s_id,si.s_no,si.s_amount,pd.product_id,pd.product_name,lot.lot_order,exp_date,pd.product_stock,pd.product_reorder,pd.product_status,b.brand_name,u.unit_name,c.color_name,class,tl.tl_size,pb.pb_size,pb.pb_thick,cc.cc_volume,ct.ct_size,cs.cs_volume,cm.cm_volume,ss.s_date,CURDATE(),emp_name,emp_lname
     FROM product pd
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
      
     LEFT JOIN color c ON c.color_id = cc.color_id OR c.color_id = ct.color_id OR c.color_id = tl.color_id
     LEFT JOIN sale_items si ON si.product_id = pd.product_id
     LEFT JOIN sales_slip ss ON ss.s_id = si.s_id
     LEFT JOIN lot ON lot.product_id = pd.product_id
     LEFT JOIN lot_exp ON lot_exp.product_id = lot.product_id AND lot_exp.lot_order = lot.lot_order
     INNER JOIN employee emp ON emp.emp_id = ss.emp_id
     WHERE si.s_id = '".$sale_id."'
     GROUP BY si.s_no
     ORDER BY si.s_no ASC";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}
if (isset($_POST['claim_product'])) {
     $product=$_POST['claim_product'];
     $lot=$_POST['claim_lot'];
     $query = "
     SELECT pd.product_id,lot.lot_order,lot.lot_balance,exp_date,pd.product_name,pd.product_saleprice,pd.product_stock,pd.product_reorder,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,CURDATE() AS C_DATE
     FROM product pd
     INNER JOIN brand ON brand.brand_id = pd.brand_id
     INNER JOIN unit ON unit.unit_id = pd.unit_id
     LEFT JOIN cement ON cement.product_id = pd.product_id
     LEFT JOIN categorycolor ON categorycolor.product_id = pd.product_id
     LEFT JOIN toilet ON toilet.product_id = pd.product_id
     LEFT JOIN chemicalsolution ON chemicalsolution.product_id = pd.product_id
     LEFT JOIN craftmantool ON craftmantool.product_id = pd.product_id
     LEFT JOIN plumbling ON plumbling.product_id = pd.product_id
      
     LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
     LEFT JOIN lot ON lot.product_id = pd.product_id
     LEFT JOIN lot_exp ON lot_exp.product_id = lot.product_id AND lot_exp.lot_order = lot.lot_order
     WHERE pd.product_id = '$product' AND lot.lot_order = '$lot'
     ORDER BY pd.product_id";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          $row = $statement->fetch(PDO::FETCH_ASSOC);

          $data = $row;
          
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['claim_show'])) {

     $id=$_POST['claim_show'];

     $query = "
     SELECT c1.*,s1.s_id,lot.rp_id,lot.lot_order,drp.rp_id,drp.rp_no,pd.product_name,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,class,pd.product_stock,cpn.cpn_id,cpn.cpn_name
     FROM claim_ctm_list c1
     INNER JOIN product pd ON pd.product_id = c1.product_id
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
      
     LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
     LEFT JOIN sale_items s1 ON s1.s_id = c1.s_id AND s1.product_id = c1.product_id
     LEFT JOIN lot ON lot.product_id = s1.product_id AND lot.lot_order = s1.lot_order AND lot.product_id = pd.product_id
     LEFT JOIN detailreceivepro drp ON drp.product_id = lot.product_id AND drp.rp_no = lot.rp_no
     LEFT JOIN detailorderpro dop ON dop.product_id = s1.product_id AND dop.product_id = pd.product_id
     INNER JOIN company cpn ON cpn.cpn_id = dop.cpn_id
     WHERE c1.cl_id = '$id'
     GROUP BY c1.cl_no
     ORDER BY c1.cl_no";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          
          }
          if (isset($data)) {
              
               echo json_encode($data);

          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['ccp_id'])) {

     $ccp_id = $_POST['ccp_id'];

     $query = "
     SELECT c1.ccp_id,c1.ccp_no,c1.ccp_status,c2.*,c3.cr_id,c3.cr_amount,SUM(c3.cr_amount) AS total,pd.product_name,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,class,pd.product_stock
     FROM claim_cpn_list c1
     LEFT JOIN claim_ctm_list c2 ON c2.cl_id = c1.cl_id AND c2.cl_no = c1.ccp_no
     LEFT JOIN claim_receive_list c3 ON c3.cr_no = c1.ccp_no AND c3.ccp_id = c1.ccp_id
     INNER JOIN product pd ON pd.product_id = c2.product_id
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
      
     LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
     WHERE c1.ccp_id = '$ccp_id'
     GROUP BY c1.ccp_no
     ORDER BY c1.ccp_no";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['row_id'])) {

     $pmt_id = $_POST['row_id'];

     $query = "
     SELECT * FROM promotion WHERE promotion_id = '$pmt_id'
     ";

     $statement = $connect->prepare($query);
     if ($statement->execute()) {
          $data = $statement->fetch(PDO::FETCH_ASSOC);
               echo json_encode($data);
          
     }
}

if (isset($_POST['pmt_product'])) {

     $pmt_product = $_POST['pmt_product'];

     $query = "
     SELECT p1.*,pp.*,pd.product_name,pd.product_stock,pd.product_status,b.brand_name,u.unit_name,c.color_name,class,tl.tl_size,pb.pb_size,pb.pb_thick,cc.cc_volume,ct.ct_size,cs.cs_volume,cm.cm_volume
     FROM promotion p1
     INNER JOIN product_promotion pp ON pp.promotion_id = p1.promotion_id
     INNER JOIN product pd ON pp.product_id = pd.product_id
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
      
     LEFT JOIN color c ON c.color_id = cc.color_id OR c.color_id = ct.color_id OR c.color_id = tl.color_id
     WHERE p1.promotion_id = '$pmt_product'
     ";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['pmt_check'])) {

     $pmt_product = $_POST['pmt_check'];

     $query = "
     SELECT p1.*,pp.*,pd.product_name,pd.product_stock,pd.product_status,b.brand_name,u.unit_name,c.color_name,class,tl.tl_size,pb.pb_size,pb.pb_thick,cc.cc_volume,ct.ct_size,cs.cs_volume,cm.cm_volume
     FROM promotion p1
     INNER JOIN product_promotion pp ON pp.promotion_id = p1.promotion_id
     INNER JOIN product pd ON pp.product_id = pd.product_id
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
      
     LEFT JOIN color c ON c.color_id = cc.color_id OR c.color_id = ct.color_id OR c.color_id = tl.color_id
     WHERE p1.promotion_id = '$pmt_product'
     ";

     $statement = $connect->prepare($query);
     if ($statement->execute()) {
          $data = $statement->fetch(PDO::FETCH_ASSOC);
               echo json_encode($data);
          
     }
}

if (isset($_POST['quotation'])) {

     $quotation = $_POST['quotation'];

     $query = "
     SELECT q.*
     FROM quotation q
     WHERE q.quo_id = '$quotation'
     ";

     $statement = $connect->prepare($query);

     $statement = $connect->prepare($query);
     if ($statement->execute()) {
          $data = $statement->fetch(PDO::FETCH_ASSOC);
               echo json_encode($data);
          
     }
}


if (isset($_POST['pd_id'])) {

     $pd_id = $_POST['pd_id'];

     $query = "
     SELECT cpn.cpn_id,cpn.cpn_name,cp.product_id,cp.costprice
     FROM company cpn
     INNER JOIN costprice cp ON cp.cpn_id = cpn.cpn_id
     WHERE cp.product_id = '$pd_id'";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['quo_list_product'])) {
     $quo_list_product=$_POST['quo_list_product'];
     $quo_list_company=$_POST['quo_list_company'];
     $query = "
     SELECT pd.product_id,pd.product_name,pd.product_saleprice,pd.product_stock,brand.brand_name,unit.unit_name,color.color_name,class,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,cpn.*,cp.costprice
     FROM product pd
     INNER JOIN brand ON brand.brand_id = pd.brand_id
     INNER JOIN unit ON unit.unit_id = pd.unit_id
     LEFT JOIN cement ON cement.product_id = pd.product_id
     LEFT JOIN categorycolor ON categorycolor.product_id = pd.product_id
     LEFT JOIN toilet ON toilet.product_id = pd.product_id
     LEFT JOIN chemicalsolution ON chemicalsolution.product_id = pd.product_id
     LEFT JOIN craftmantool ON craftmantool.product_id = pd.product_id
     LEFT JOIN plumbling ON plumbling.product_id = pd.product_id
      
     LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
     INNER JOIN costprice cp ON cp.product_id = pd.product_id
     INNER JOIN company cpn ON cpn.cpn_id = cp.cpn_id
     WHERE pd.product_id = '$quo_list_product' AND cpn.cpn_id = '$quo_list_company' 
     ORDER BY pd.product_id";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          $row = $statement->fetch(PDO::FETCH_ASSOC);

          $data = $row;
          
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['claim_id'])) {
     $claim_id=$_POST['claim_id'];
     $query = "
     SELECT c1.*,SUM(c5.cr_amount) AS receive,c2.cl_date,c4.ccp_date,pd.product_id,lot.*,pd.product_name,b.brand_name,u.unit_name,cm.cm_volume,cc.cc_volume,tl.tl_size,cs.cs_volume,ct.ct_size,pb.pb_size,pb.pb_thick,cl.color_name,class,product_stock,product_reorder
     FROM claim_ctm_list c1
     INNER JOIN claim_ctm_h c2 ON c2.cl_id = c1.cl_id
     INNER JOIN product pd ON pd.product_id = c1.product_id
     INNER JOIN brand b ON b.brand_id = pd.brand_id
     INNER JOIN unit u ON u.unit_id = pd.unit_id
     LEFT JOIN cement cm ON cm.product_id = pd.product_id
     LEFT JOIN categorycolor cc ON cc.product_id = pd.product_id
     LEFT JOIN toilet tl ON tl.product_id = pd.product_id
     LEFT JOIN chemicalsolution cs ON cs.product_id = pd.product_id
     LEFT JOIN craftmantool ct ON ct.product_id = pd.product_id
     LEFT JOIN plumbling pb ON pb.product_id = pd.product_id
      
     LEFT JOIN color cl ON cl.color_id = cc.color_id OR cl.color_id = ct.color_id OR cl.color_id = tl.color_id
     LEFT JOIN claim_cpn_list c3 ON c3.cl_id = c1.cl_id AND c3.ccp_no = c1.cl_no
     LEFT JOIN claim_cpn_h c4 ON c4.ccp_id = c3.ccp_id
     LEFT JOIN claim_receive_list c5 ON c5.ccp_id = c3.ccp_id AND c5.cr_no = c3.ccp_no
     LEFT JOIN sale_items s1 ON s1.product_id = c1.product_id AND s1.s_id = c1.s_id
     LEFT JOIN lot ON lot.product_id = s1.product_id AND lot.lot_order = s1.lot_order
     WHERE c1.cl_id = '$claim_id'
     GROUP BY c1.product_id
     ORDER BY c1.cl_no";
     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}

if (isset($_POST['claim_list'])) {

     $id = $_POST['claim_list'];
     $output = '';
     $query = "
     SELECT c1.cpn_id,cpn.cpn_name,c1.ccp_id,c3.*
     FROM claim_cpn_list c1
     INNER JOIN claim_cpn_h c2 ON c1.ccp_id = c2.ccp_id
     INNER JOIN claim_ctm_list c3 ON c3.cl_id = c1.cl_id AND c3.cl_no = c1.ccp_no
     INNER JOIN claim_ctm_h c4 ON c4.cl_id = c3.cl_id
     INNER JOIN company cpn ON cpn.cpn_id = c1.cpn_id
     WHERE c1.cl_id = '$id'
     GROUP BY c1.cpn_id";

     $statement = $connect->prepare($query);

     if ($statement->execute()) {
          while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

               $data[] = $row;
          }
          if (isset($data)) {
               echo json_encode($data);
          } else {
               echo "ไม่มีข้อมูล";
               return false;
          }
     }
}