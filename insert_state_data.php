<?php
//insert.php;
 global $db;
 $record_id = $_POST['record_id'];
 //$record_id ='55915921-7959-57af-dcc2-5da56027da0a';
//$record_id;

 
//------------Get document id from record id Start ------------------------------------------------//
$query = $db->query("SELECT * FROM c_certificate_c_certificate_document_1_c WHERE
c_certificate_c_certificate_document_1c_certificate_ida='".$record_id."'");
//$query = $db->query("SELECT * FROM `c_certificate_c_certificate_document_1_c` WHERE`c_certificate_c_certificate_document_1c_certificate_ida`='55915921-7959-57af-dcc2-5da56027da0a'");
$row = $db->fetchByAssoc($query);
//print_r($row);exit();
$doc_id = $row['c_certificate_c_certificate_document_1c_certificate_document_idb'];
//echo $doc_id;
//echo"<pre>";print_r($doc_id);exit();

//------------Get document id from record id End ------------------------------------------------//



 
//------------Get Account id from record id start ------------------------------------------------// 
$query1 =$db->query("SELECT accounts_c_certificate_1accounts_ida FROM accounts_c_certificate_1_c WHERE 
accounts_c_certificate_1c_certificate_idb='".$record_id."'");
$row = $db->fetchByAssoc($query1);
$acc_id = $row['accounts_c_certificate_1accounts_ida'];

//------------Get Account id from record id end ------------------------------------------------//




// ------------Get Certificate value and store in variable start---------------------------------//


 $query1 =$db->query("SELECT * FROM c_certificate WHERE id='".$record_id."'");
while ($row = $db->fetchByAssoc($query1)){
$date_entered = $row['date_entered'];
$date_modified =$row['date_modified'];
$modified_user_id =$row['modified_user_id'];
$created_by = $row['created_by'];
$entity_code_c =$row['entity_code_c'];
$cust_state_c =$row['cust_state_c'];
$cust_country_c =$row['cust_country_c'];

}
// ------------Get Certificate value and store in variable end---------------------------------//


// ------------Get Certificate_cstm value and store in variable start---------------------------------//

$query1 =$db->query("SELECT * FROM c_certificate_cstm WHERE id_c='".$record_id."'");
$row = $db->fetchByAssoc($query1);
$certificate_auto_id_c = $row['certificate_auto_id_c'];
$customer_id_c =$row['customer_id_c'];
$single_purchase_c =$row['single_purchase_c'];
$po_invoice_id_c = $row['po_invoice_id_c'];
$contact_name_c =$row['contact_name_c'];
$contact_phone_c =$row['contact_phone_c'];
$contact_email_c =$row['contact_email_c'];
$customer_street_c =$row['customer_street_c'];
$customer_city_c = $row['customer_city_c'];
$contact_zip_code_c =$row['contact_zip_code_c'];
$notes_2_c =$row['notes_2_c'];

// ------------Get Certificate_cstm value and store in variable end---------------------------------//


// ------------Get Certificate_cstm value and store in variable end---------------------------------//
//for($count = 0; $count < count($_POST["state_name"]); $count++){
$all_ok =true;
 $error_cmsg='';
 
$i = 0;
// print_r($_POST);exit();
foreach ($_POST['state_name'] as $val) {
    $state_name = $_POST['state_name'][$i];
    $reason = $_POST['reason'][$i];
	$certificate = $_POST['certificate'][$i];
	$begin_date = $_POST['begin_date'][$i];
    $end_date = $_POST['end_date'][$i];
	$notes = $_POST['notes'][$i];
	
	
	$query = "SELECT count(*) as count FROM c_certificate c INNER JOIN c_certificate_cstm cm ON c.id=cm.id_c and  cm.customer_id_c = '".$customer_id_c."' AND c.reason_c = '".$reason."' AND c.state_c = '".$state_name."' AND cm.single_purchase_c = '".$single_purchase_c."' AND cm.po_invoice_id_c ='".$po_invoice_id_c."' AND c.deleted=0";
	$result = $db->query($query);
                $row = $db->fetchByAssoc($result);
                $count = $row['count'];
	if($count != 0)
                {
			$all_ok=false;
			$error_cmsg = $error_cmsg . "<br>Customer ID, State, Reason, Single Purchase and PO/Invoice #  already exists @".$i." - " . $state_name ." - " . $reason  ;
				}
	else{			

    // ---- Generate uuid for certificate entry ---
	$uuid = $db->query("SELECT UUID() as uid");
    $uid = $db->fetchByAssoc($uuid);
    $new_certid = $uid['uid'];
	
	// ----- Insert into C_Certificate
	$query2= "INSERT INTO c_certificate(id, name, date_entered,date_modified,modified_user_id,
        created_by,entity_code_c,state_c,reason_c,cust_state_c,cust_country_c)VALUES('".$new_certid."','".$certificate."',NOW(),NOW(),1,'Manasi Mayekar','".$entity_code_c."','".$state_name."','".$reason."','".$cust_state_c."','".$cust_country_c."')";
	 $result = $db->query($query2);	
	
	// ----- Insert into C_Certificate_cstm
	$query3= "INSERT INTO c_certificate_cstm(id_c,certificate_auto_id_c,customer_id_c,begin_exemption_c,end_exemption_c,single_purchase_c,po_invoice_id_c,contact_name_c,contact_phone_c,contact_email_c,customer_street_c,customer_city_c,contact_zip_code_c,notes_c,notes_2_c)VALUES('".$new_certid."','".$certificate_auto_id_c."','".$customer_id_c."','".$begin_date."','".$end_date."','".$single_purchase_c."','".$po_invoice_id_c."','".$contact_name_c."','".$contact_phone_c."','".$contact_email_c."','".$customer_street_c."','".$customer_city_c."','".$contact_zip_code_c."','".$notes_c."','".$notes_2_c."')";
	$result = $db->query($query3);	
	
	
	// ----- Update auto_incremented_name	
	$query4= "UPDATE c_certificate_cstm SET certificate_auto_id_c= CONCAT('C',RIGHT(CONCAT('0000000',auto_number_c),7))
              WHERE id_c= '".$new_certid."'";
	$result = $db->query($query4);	
	
	// ---- Create relationship with Account
    $query5= "Insert into accounts_c_certificate_1_c(id,date_modified,accounts_c_certificate_1accounts_ida,accounts_c_certificate_1c_certificate_idb) Values(UUID(),now(),'".$acc_id."','".$new_certid."')";
	$result = $db->query($query5);	
	
	// Generate uuid for document entry
	$uuid1 = $db->query("SELECT UUID() as uid1");
    $uid1 = $db->fetchByAssoc($uuid1);
	$new_docid = $uid1['uid1'];
	
	// Insert into certificate document 
	$query6= "Insert into c_certificate_document(id,date_entered,date_modified,modified_user_id,created_by,document_name,filename,file_ext,file_mime_type,active_date) select '".$new_docid."',date_entered,date_modified,modified_user_id,created_by,document_name,filename,file_ext,file_mime_type,active_date from c_certificate_document where id='".$doc_id."'";
	$result = $db->query($query6);	
	
	// Insert into certificate document_cstm 
	$query7= "Insert into c_certificate_document_cstm(id_c) Values('".$new_docid."')";
	$result = $db->query($query7);	
	
	// ---- Create relationship with document
	
	$query8= "Insert into c_certificate_c_certificate_document_1_c(id,date_modified,c_certificate_c_certificate_document_1c_certificate_ida,c_certificate_c_certificate_document_1c_certificate_document_idb) Values(UUID(),now(),'".$new_certid."','".$new_docid."')";
	$result = $db->query($query8);	
	
	//----- Copy Physical file in upload folder. sourcefile= $doc_id destination= $new_docid
	
	$path = $_SERVER["DOCUMENT_ROOT"];
	//echo $source = $_SERVER['HTTP_HOST']'/upload/'.$doc_id;
	 $source = $path.'/upload/'.$doc_id;
	 $destination = $path.'/upload/'.$new_docid;
	// $destination = '/var/www/html/testecsm.suchimsapps.com/upload/'.$new_docid;
	
	//concat(related($source,$destination),"");
	copy($source, $destination);
	
	
	
	
   }
	$i++;	
  } 
  if($all_ok){
	  echo "ok";
	  
	  }
	  else{
		  echo $error_cmsg;
		  }
  
	//echo "ok";	
		
		/* $query2= "INSERT INTO c_certificate('id', 'name', 'date_entered','date_modified','modified_user_id',
        'created_by','entity_code_c','state_c','reason_c','cust_state_c','cust_country_c','deleted')VALUES('".$new_certid."','".$certificate."',NOW(),NOW(),1,'Manasi Mayekar','".$entity_code_c."','".$state_name."','".$reason."','".$cust_state_c."','".$cust_country_c."')";
		$i++; */
		//$row = $db->fetchByAssoc($query2);
 
//}//


?>