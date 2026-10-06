<?php
 
include_once('function_query.php'); 


if ($_POST['action'] == 'Due_Collection_Type'){


        if($_POST['Type'] == 'NOGOD-GROHON' ){


            $type_text = 'নগদ গ্রহণ';
            $type_content = '<input type="number"  class="form-control text-success" id="receive_now" value="0.00" />
            <input type="hidden" id="new_client_id" value="" />';


        }else{
            $type_text = 'ভাড়াটিয়া';
            $type_content = '<select class="form-control select"  id="new_client_id" name="new_client_id">';
            $type_content .= '<option value="">Select One</option>';
            $qry_c = DB::conn()->prepare("SELECT * FROM `setup_client`  ");
            $qry_c->execute();
            $fetch_list_c = $qry_c->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetch_list_c as  $value) {
                $type_content .= ' <option  value ="'.$value['id'].'" > '.$value['client_name'].'  </option>';
        
            }
            $type_content .= ' </select><input type="number" id="receive_now" value="0.00" />';

        }

    
        print json_encode(array (
      
            'type_text' => $type_text,
            'type_content' => $type_content
    
        ));

        


}else if ($_POST['action'] == 'search_pending_invoice'){



   if($_POST['client_id'] == 'All' ){
    $QUERY = " " ;
   }else{

    $QUERY = "  AND A.client_id  = '".$_POST['client_id']."' " ;
   }

   
    
   
$content = '<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-body" id="load_table">
   
    <div class="table-responsive">
    <table class="table datatable" id="MSalary">
    <thead>
    
    <th>নং</th>
    <th>ভাড়াটিয়ার নাম</th>
    <th>যোগাযোগ</th>
    <th>কোন মাসের ভাড়া ?</th>
    <th>একশন</th>

    </thead>
        <tbody>'; 
$sl =1;
$qry = DB::conn()->prepare("SELECT A.date,A.id,A.client_id,A.rent_month,A.rent_year, GROUP_CONCAT(A.id) as collection_id,GROUP_CONCAT(CONCAT(E.vobon_name, '-', D.unit_name) SEPARATOR '@') AS unit_vobon_names,B.client_name,B.mobile,date_format(A.date, '%d-%m-%Y') AS `invoice_date`
FROM `collect_rent` A
JOIN setup_client B ON (A.client_id = B.id)
JOIN assign_unit C ON (A.assign_unit_id = C.id)
JOIN setup_unit D ON (C.unit_id = D.id)  
JOIN setup_vobon E ON (D.vobon_id = E.id)  
WHERE A.pay_later = 1     $QUERY 
GROUP BY A.client_id,A.rent_year,A.rent_month  
ORDER BY A.client_id ASC");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach($fetch_list AS $fetch) {
    
    $data_value = $fetch['client_id']  . "@" . $fetch['rent_year']. "@" . $fetch['rent_month'] . "@" . $fetch['collection_id'] . "@" . $fetch['date']; 

    $image_name = "generate/" . $fetch['client_id'] . "_client_phone_call_qr_code.png" ;

    $content .= '<tr><input type="" style="display:none;" id="id_'.$fetch['id'].'" value="'.$fetch['id'].'" >
    <input type="" style="display:none;" id="dataArray_'.$fetch['id'].'" value="'.$data_value.'" >

    <td>'.$sl++.'</td>';

    $content .= '<td><b class="text-danger">'.$fetch['client_name'].' '.$fetch['mobile'].' </b><br><b >ভবন/ইউনিট:</b><br>' ; 
    
    $unit_vobon_names = explode('@', $fetch['unit_vobon_names']);
 
    foreach ($unit_vobon_names as $value) {
        $content .= '<p style="color:blue">' . $value . '<br></p>'; // Add line break after each value
    }

    
    $content .= '</td>'; 

    $content .= '

    <td>
    <a class="text-danger" href="tel:'.$fetch['mobile'].'"><img style="height:100px" src="'.$image_name.'"> </a>
    </td>
    <td>'.$fetch['rent_month'].' '.$fetch['rent_year'].' <br> ইনভেস তৈরি : '.$fetch['invoice_date'].' </td>

    <td>
    
    <a id="#exampleModalCenter" class="btn btn-success btn-rounded btn-sm" onclick="modal_wihout_refresh(\'receive_rent\',\'dataArray_'.$fetch['id'].'\',\'ভাড়া কালেকশন \');">রিসিভ করুন </a>
    

    
    </td>
   </tr>';
}


            
              
$content .= '</tbody> 
  </table>
  </div>
  </div>     
</div>

</div>
</div>';
   
   print $content; 



}else if ($_POST['action'] == 'find_vobon_wise_unit'){

    $unit_id = '';
    $content ='<select class="form-control select" id="unit_id" name = "unit_id"  data-live-search="true" onchange="UnitWiseRent(this.value)">
<option value="">Select One</option>';
$qry = DB::conn()->prepare("SELECT * FROM `setup_unit` where vobon_id = '".$_POST['id']."'  ");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) { 

    $find_status =  FIND::RECENT_STATUS($fetch['id']);


    if($find_status == 'red'){
        $content .= '<option ';
        if($unit_id == $fetch['id']){
            $content .= ' selected="selected"';
        }else{
        }
        $content .= 'value="'.$fetch['id'].'">' .$fetch['unit_name']. '</option>';
    }
    

}
$content .='</select>';

print json_encode(array (
    
    'content' => $content

));

}else if ($_POST['action'] == 'find_unit_wise_rent'){

    $qry = DB::conn()->prepare("SELECT * FROM `setup_unit` where id = '".$_POST['id']."'  ");
    $qry->execute();
    $fetch_list = $qry->fetch(PDO::FETCH_ASSOC);

    print json_encode(array (
    
        'content' => $fetch_list['rent']
    
    ));



}else if($_POST['action'] == 'add_transection_row'){

    $prepaire_table = SETUP::SETUP_CODE_INSERT_DATA('account_transection');
    $id = $prepaire_table['last_id'];
    $invoice_no =  "PX-TR-$prepaire_table[related_code]";
    $code=  "$prepaire_table[related_code]";
    $transection_type=  "INCOME";
    $ledger_id=  "2";
    $transection_head_id=  "66";
    $transection_to = 'Customer';
    $transection_to_id = $_POST['client_id'];


	$data = [
		'transection_type' => $transection_type,
		'ledger_id' => $ledger_id,
        'invoice_no' => $invoice_no,
		'transection_head_id' => $transection_head_id,
		'transection_to' => $transection_to,
		'transection_to_id' => $transection_to_id
	  ];

	


      $update_query =   CRUD::updateData(DB::conn(), 'account_transection',$id,$data); 

    $query = DB::conn()->prepare("SELECT `transection_ids` FROM `slot_booking` WHERE `id` = '".$_POST['slot_booking_id']."' ");
    $query->execute();
    $existingData = json_decode($query->fetchColumn(), true);

    $existingData[] = [  'transection_id' => $id,  'promise_date' => ''];
    $newData = json_encode($existingData);
      
    $query2 = DB::conn()->prepare("UPDATE `slot_booking` SET `transection_ids` = '".$newData."' WHERE  `id` = '".$_POST['slot_booking_id']."'  ");
    $query2->execute();

   print  "Transection Created" ;


}else if($_POST['action'] == 'transfer_type'){

    $content = '<table class="table">';

    
    if ($_POST['type'] == 'Cash_To_Bank'){

        $content   .= '<input type="hidden" class="form-control" id="transection_by_from" value="Cash" >';
        $content   .= '<input type="hidden" class="form-control" id="transection_by_to" value="Bank" >';


        $content   .= '<tr>';
        $content   .= '<th>From</th>';
        $content   .= '<th><input type="text" id="tr_from" value="Cash" class="form-control text-success" readonly></th>';
        $content   .= '</tr>';
        $content   .= '<tr>';
        $content   .= '<th>To Bank</th>';
        $content   .= '<td>
        <select class="form-control select" id="tr_to" name = "tr_to"  data-live-search="true">
<option value="">Select One</option>';
$qry =DB::conn()->prepare("SELECT * FROM `setup_bank` where `status`  = 'Active'   ORDER BY `id` ASC");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) { 
    $bank_details = "$fetch[account_number] :: $fetch[brunch_name] :: $fetch[bank_name]";
    $content .= '<option  value="'.$fetch['id'].'">' .$bank_details. '</option>';
}
$content .='</select></td>';
$content   .= '</tr>';
       

}else if ($_POST['type'] == 'Bank_To_Cash'){


    $content   .= '<input type="hidden" class="form-control" id="transection_by_from" value="Bank" >';
    $content   .= '<input type="hidden" class="form-control" id="transection_by_to" value="Cash" >';


    $content   .= '<tr>';
    $content   .= '<th>From Bank</th>';
    $content   .= '<td>
    <select class="form-control select" id="tr_from" name = "tr_from"  data-live-search="true">
<option value="">Select One</option>';
$qry =DB::conn()->prepare("SELECT * FROM `setup_bank` where `status`  = 'Active'   ORDER BY `id` ASC");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) { 
$bank_details = "$fetch[account_number] :: $fetch[brunch_name] :: $fetch[bank_name]";
$content .= '<option  value="'.$fetch['id'].'">' .$bank_details. '</option>';
}
$content .='</select></td>';
$content   .= '</tr>';

$content   .= '<tr>';
$content   .= '<th>To</th>';
$content   .= '<th><input type="text" value="Cash" id="tr_to" name = "tr_to" class="form-control text-success" readonly></th>';
$content   .= '</tr>';

}else{
    $content   = '<input type="hidden" value="nothing" id="tr_from"><input type="hidden" id="tr_to" value="nothing">';
    $content   .= '<input type="hidden" class="form-control" id="transection_by_to" value="" >';
    $content   .= '<input type="hidden" class="form-control" id="transection_by_from" value="" >';

}

print json_encode(array ('content' => $content));


}else if ($_POST['action'] == 'find_transection_by_details'){




    $content   ='';
    $content2   ='';
    if($_POST['TYPE'] == 'Bank'){

        $content   .= '<tr>
        <th >Bank Details</th>
        <td colspan="4">
        <select class="form-control select" id="transection_by_id" name = "transection_by_id"  data-live-search="true">
        <option value="">Select One</option>';
        $qry =DB::conn()->prepare("SELECT * FROM `setup_bank` where `status`  = 'Active'   ORDER BY `id` ASC");
        $qry->execute();
        $fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
        foreach ($fetch_list as $fetch) { 
            $bank_details = "$fetch[account_number] :: $fetch[brunch_name] :: $fetch[bank_name]";
            $content .= '<option ';

            if($_POST['transection_by_id'] == $fetch['id']){
                $content .= ' selected="selected"';
            }else{

            }
            $content .= 'value="'.$fetch['id'].'"><b style="font-color:blue">qq' .$bank_details. '</b></option>';

        }
    $content .='</select>

            </select>
        </td>
       


   </tr>';

   $content2   .= '<tr>
   <th>Cheque/Transaction No</th>
   <td><input type="text" class="form-control" id="check_number" value="'.$_POST['check_number'].'" ></td>
  

   <th >Cheque/Transaction Date</th>
   <td><input type="date" class="form-control" id="check_date" value="'.$_POST['check_date'].'" ></td> 

</tr>';

    }else if ($_POST['TYPE'] == 'Mobile-Banking'){
 
        $content   .= '<tr>
        <th >Number</th>
        <td colspan="4">
        <select class="form-control select" id="transection_by_id" name = "transection_by_id"  data-live-search="true">
        <option value="">Select One</option>';
        $qry =DB::conn()->prepare("SELECT * FROM `setup_mobile_banking` where `status`  = 'Active'   ORDER BY `id` ASC");
        $qry->execute();
        $fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
        foreach ($fetch_list as $fetch) { 
            $bank_details = "$fetch[mobile_number] :: $fetch[mobile_bank_name] ";
            $content .= '<option ';
            
            if($_POST['transection_by_id'] == $fetch['id']){
                $content .= ' selected="selected"';
            }else{

            }
            $content .=' value="'.$fetch['id'].'"><b style="font-color:blue">' .$bank_details. '</b></option>';

        }
    $content .='</select>

            </select>
        </td>
       
        <input type="hidden" class="form-control" id="check_number" value="" >
        <input type="hidden" class="form-control" id="check_date" value="" >
   </tr>';


    }else if($_POST['TYPE'] == 'Cash'){
        $content   .= '
        <input type="hidden" class="form-control" id="check_number" value="" >
        <input type="hidden" class="form-control" id="check_date" value="" >
        <input type="hidden" class="form-control" id="transection_by_id" value="" >';

    }else{
        $content   .= '';
    }


    print json_encode(array ('content' => $content,'content2' => $content2));


}else if ($_POST['action'] == 'ledger_wise_accounts'){

    

    $content   = '<select name="transection_head_id" id="transection_head_id" class="form-control select"  data-live-search="true"><option value="">Select One</option>';

$qry =DB::conn()->prepare("SELECT * FROM `setup_ac_head` where ( `account_type`  = '".$_POST['type']."' OR  `account_type`  = 'BOTH' )  AND  `ledger_id`  = '".$_POST['ledger_id']."' ");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) {

 $content   .= ' <option value="'.$fetch['id'].'">'.$fetch['account_head'].'</option>';

} 
$content   .= '</select>';

print $content ; 



}else if($_POST['action'] == 'delete_table_row'){


        $query = DB::conn()->prepare("DELETE FROM `{$_POST['TABLENAME']}` WHERE `id` = '".$_POST['ID']."' "); 
        $query->execute();

        print "Delete Success";




}else{



}


