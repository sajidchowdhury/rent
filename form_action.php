<?php
include_once("auth.php");
include_once("clean.php");
include_once('function_query.php'); 



if($_POST['action'] == 'save_ac_head' ){


	$account_head= clean($_POST['account_head']);
	$account_type= clean($_POST['account_type']);
	$description= clean($_POST['description']);
	$status= clean($_POST['status']);
    $ladger_head= clean($_POST['ladger_head']);
	$parent_id= clean($_POST['parent_id']);

	$data1 = [
		'ledger_id' => $parent_id,
		'account_head' => $account_head,
		'account_type' => $account_type,
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . ' Date: ' . date("d-M-Y") . ' IP ' . $_SERVER['REMOTE_ADDR']
	  ];

	  $data2 = [
		'name' => $ladger_head,
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . ' Date: ' . date("d-M-Y") . ' IP ' . $_SERVER['REMOTE_ADDR']
	  ];




    if($parent_id == 'New_Parent'){

           
    $lc_fetch =DB::conn()->prepare("SELECT * FROM `setup_ladger_head` WHERE `name` = '".$ladger_head."' ");
	$lc_fetch->execute();
	if ($lc_fetch->rowCount() > 0){

      $mess = 'Duplicate Ladger Head Found';


    }else{

		$query =   CRUD::insert_data(DB::conn(), 'setup_ladger_head', $data2);

		$mess = $query['mess'] ;
		  
    }


    }else{

		if($_POST['related_id'] == 'new_id' ){

		$query =   CRUD::insert_data(DB::conn(), 'setup_ac_head', $data1);

		$mess = $query['mess'] ;

		}else{

		$query =   CRUD::updateData(DB::conn(),'setup_ac_head',$_POST['related_id'],$data1);
		$mess = $query['mess'] ;

		}


		print $mess;

    }

}else if ($_POST['action'] == 'assain_client'){



	$aggrement_start = date("Y-m-d", strtotime($_POST['aggrement_start']));
	$aggrement_ends = date("Y-m-d", strtotime($_POST['aggrement_ends']));

	$advance_payment_date = NULL;

	if (!empty($_POST['advance_payment_date'])) {
		$timestamp = strtotime($_POST['advance_payment_date']);
		if ($timestamp !== false) {
			$advance_payment_date = date("Y-m-d", $timestamp);
			
		} else {
			// Handle invalid date format error
			// For example, you can set an error message or log the error
			$advance_payment_date = NULL;
		}
	}else{
		$advance_payment_date = NULL;

	}



    $data = [
		'client_id' => clean($_POST['client_id']),
        'vobon_id' => clean($_POST['vobon_id']),
        'unit_id' => clean($_POST['unit_id']),
        'aggrement_start' => clean($aggrement_start),
		'aggrement_ends' => clean($aggrement_ends),
		'actual_rent' => clean($_POST['actual_rent']),
        'rent' => clean($_POST['rent']),
        'advance_payment' => clean($_POST['advance_payment']),
        'advance_payment_date' => $advance_payment_date,
        'gus_bill' => clean($_POST['gus_bill']),
        'moylar_bill' => clean($_POST['moylar_bill']),
        'service_charge' => clean($_POST['service_charge']),
		'other_bill' => clean($_POST['other_bill']),
		'client_status' => clean($_POST['client_status']),
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => time()
	  ];


      if($_POST['related_id'] == 'New' ){

		$query =   CRUD::insert_data(DB::conn(), 'assign_unit', $data);

		}else{

		$query =   CRUD::updateData(DB::conn(), 'assign_unit',$_POST['related_id'],$data);

		}

		print  $query['mess'] ;
		die();


}else if ($_POST['action'] == 'save_client'){
	include_once("generate_vard.php");

	//saved_mobile

    $data = [
		'client_name' => clean($_POST['client_name']),
		'mobile' => clean($_POST['mobile']),
        'nid' => clean($_POST['nid']),
        'family_member' => clean($_POST['family_member']),
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => time()
	  ];




      if($_POST['related_id'] == 'New' ){

        $prepaire_table = SETUP::SETUP_CODE_INSERT_DATA('setup_client');
		$query =   CRUD::updateData(DB::conn(), 'setup_client',$prepaire_table['last_id'],$data);
		$id  = $prepaire_table['last_id'];

		QRCodeGenerator::generatePhoneQRCode($id, $_POST['mobile']); 



		}else{      

		$query =   CRUD::updateData(DB::conn(), 'setup_client',$_POST['related_id'],$data);
		$id  = $_POST['related_id'];

		$imagePath = "generate/" . $id . "_client_phone_call_qr_code.png" ;

			if (file_exists($imagePath)) {
				if (unlink($imagePath)) {
					QRCodeGenerator::generatePhoneQRCode($id, $_POST['mobile']); 
				} 
			} else{
				QRCodeGenerator::generatePhoneQRCode($id, $_POST['mobile']); 

			}



		}




print  $query['mess'] ;
die();

}else if($_POST['action'] == 'remove_recipt' ){



$explode = explode("@",$_POST['data_value']);
$collection_ids = $explode[3]; 
$collection_id_array = explode(',', $collection_ids);
$collection_id = implode(',', $collection_id_array);   

	  $qry2 = DB::conn()->prepare("SELECT A.id FROM `collect_rent`  A  WHERE A.id IN  ({$collection_id})");
	  $qry2->execute();
	  $fetch_list2 = $qry2->fetchAll(PDO::FETCH_ASSOC);
	  foreach($fetch_list2 AS $fetch2) {

		$query = DB::conn()->prepare("DELETE FROM collect_rent WHERE `id` = '".$fetch2['id']."' "); 
		$query->execute();
	}

     print "সফল হয়েছে";



}else if($_POST['action'] == 'cant_pay_now' ){

$explode = explode("@",$_POST['data_value']);
$collection_ids = $explode[3]; 
$collection_id_array = explode(',', $collection_ids);
$collection_id = implode(',', $collection_id_array);   

	  $qry2 = DB::conn()->prepare("SELECT A.id FROM `collect_rent`  A   WHERE A.id IN  ({$collection_id}) ");
	  $qry2->execute();
	  $fetch_list2 = $qry2->fetchAll(PDO::FETCH_ASSOC);
	  foreach($fetch_list2 AS $fetch2) {

		$data1 = [
			'pay_later' => 1,
			'collection_status' => 'Pending'
		  ];

		$query1 =   CRUD::updateData(DB::conn(), 'collect_rent',$fetch2['id'],$data1);

	
	}

     print "সফল হয়েছে";


}else if($_POST['action'] == 'create_receipt' ){

	$receive_date = date("Y-m-d", strtotime($_POST['receive_date']));


	$my_array = json_decode($_POST['today_data'], true);



	if($_POST['receive_now'] > 0 ){

		$data2 = [
			'transection_type' => 'INCOME',
			'ledger_id' => 1,
			'transection_head_id' => 1,
			'transection_to' => 'Customer',
			'transection_to_id' =>  $_POST['client_id'],
			'data_inserted_from' => 'RENT COLLECTION',
			'transection_by' => 'Cash',
			'brunch_id' => 1,
			'transection_by_id' => 0,
			'transection_date' =>  $receive_date,
			'in_amount'	 => 	clean($_POST['receive_now']),
			'date' => date("Y-m-d"),
			'time' =>date("h:i:s a"),
			'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
			'lastupdate' => time()
		  ];
	
		  $TRANSECTIONE = SETUP::SETUP_CODE_INSERT_DATA('account_transection');
		  $transection_id = $TRANSECTIONE['last_id'];
		  $query2 =   CRUD::updateData(DB::conn(), 'account_transection',$transection_id,$data2);
	}else{
		$transection_id = NULL ; 
	}
	print 'x' ; 
	for ($x = 0; $x < $_POST['total_item']; $x++) {
	
			if($my_array[$x]['element']['advance_adjustment'] > 0 ){
			
				$TRANSECTIONE = SETUP::SETUP_CODE_INSERT_DATA('account_transection');
				$advance_transection_id = $TRANSECTIONE['last_id'];


				$data2 = [
					'transection_type' => 'EXPENSE',
					'ledger_id' => 4,
					'transection_head_id' => 4,
					'transection_to' => 'Customer',
					'transection_to_id' =>  $_POST['client_id'],
					'data_inserted_from' => 'ADVANCE ADJUSTMENT',
					'transection_by' => 'Cash',
					'brunch_id' => 1,
					'transection_by_id' => 0,
					'transection_date' =>  $receive_date,
					'out_amount'	 => 	$my_array[$x]['element']['advance_adjustment'],
					'date' => date("Y-m-d"),
					'time' =>date("h:i:s a"),
					'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
					'lastupdate' => time()
				  ];


				  
		        $query2 =   CRUD::updateData(DB::conn(), 'account_transection',$advance_transection_id,$data2);

			
			}else{
				$advance_transection_id = NULL; 
			}



			$data1 = [
				'receive_date' => $receive_date,
				'pay_later' => 0,
				'transection_id' => $transection_id,
				'advance_adj_id' => $advance_transection_id,
				'collection_status' => 'Done',
				];


		$query1 =   CRUD::updateData(DB::conn(), 'collect_rent',$my_array[$x]['element']['collect_id'],$data1);


	
	}

print "Success";

}else if($_POST['action'] == 'transfer_transaction' ){


	$tr_type= clean($_POST['tr_type']);
	$tr_from= clean($_POST['tr_from']);
	$tr_to= clean($_POST['tr_to']);

	$amount= clean($_POST['amount']);
	$note = clean($_POST['note']);
	$ledger_id = 3;
	$transaction_head_id = 3;
	$transection_by_to = clean($_POST['transection_by_to']);
	$transection_by_from= clean($_POST['transection_by_from']);

	$transection_date = date("Y-m-d", strtotime($_POST['transection_date']));



	
	if($tr_from == 'Cash' ){
		$from_transection_by_id = 0;
		$trFrom_by = 'Cash';
	}else{
		$from_transection_by_id = $tr_from;
		$trFrom_by = 'Bank';

	}

	if( $tr_to == 'Cash'){
		$to_transection_by_id = 0;
		$trTo_by = 'Cash';
	}else{
		$to_transection_by_id = $tr_to;
		$trTo_by = 'Bank';

	}

	$data1 = [
		'transection_type' => 'EXPENSE',
		'ledger_id' => $ledger_id,
		'transection_head_id' => $transaction_head_id,
		'transection_to' => 'Account Head',
		'transection_to_id' => 0,
		'transection_by' => $transection_by_from,
		'brunch_id' => 1,
		'data_inserted_from' => 'MONEY-TRANSFER-FROM',
		'transection_by_id' => $from_transection_by_id,
		'note' => $note,
		'transection_date' => $transection_date,
		'out_amount'	 => 	$amount,
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => time()
	  ];

	  $data2 = [
		'transection_type' => 'INCOME',
		'ledger_id' => $ledger_id,
		'transection_head_id' => $transaction_head_id,
		'transection_to' => 'Account Head',
		'transection_to_id' => 0,
		'data_inserted_from' => 'MONEY-TRANSFER-TO',
		'transection_by' => $transection_by_to,
		'brunch_id' => 1,
		'transection_by_id' => $to_transection_by_id,
		'note' => $note,
		'transection_date' => $transection_date,
		'in_amount'	 => 	$amount,
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => time()
	  ];


	  $TRANSECTIONE1 = SETUP::SETUP_CODE_INSERT_DATA('account_transection');
	  $id1 = $TRANSECTIONE1['last_id'];
	  $query1 =   CRUD::updateData(DB::conn(), 'account_transection',$id1,$data1);



	  $TRANSECTIONE2 = SETUP::SETUP_CODE_INSERT_DATA('account_transection');
	  $id2 = $TRANSECTIONE2['last_id'];
	  $query2 =   CRUD::updateData(DB::conn(), 'account_transection',$id2,$data2);



	print "Transfer Success";

}else if ($_POST['action'] == 'remove_client'){

	$farewel_date = date("Y-m-d", strtotime($_POST['farewel_date']));
	$client_id= clean($_POST['client_id']);
	$collection_type= clean($_POST['collection_type']);
	$new_client_id= clean($_POST['new_client_id']);
	$notes= clean($_POST['notes']);
	$receive_now= clean($_POST['receive_now']);
	$actual_due= clean($_POST['actual_due']);
	$related_id= clean($_POST['related_id']);



		$data1 = [
		'client_status' => 'Gone',
		'farewel_date' => $farewel_date
	
	  ];
	  $query1 =   CRUD::updateData(DB::conn(), 'setup_client',$client_id,$data1);


	  if($collection_type == 'NOGOD-GROHON' ) {

		if($receive_now > 0 ){

			$data2 = [
				'transection_type' => 'INCOME',
				'ledger_id' => 1,
				'transection_head_id' => 1,
				'transection_to' => 'Customer',
				'transection_to_id' =>  $client_id,
				'data_inserted_from' => 'SHOP CLOSING PAYMENT',
				'transection_by' => 'Cash',
				'brunch_id' => 1,
				'transection_by_id' => 0,
				'transection_date' =>  $farewel_date,
				'in_amount'	 => 	$receive_now,
				'date' => date("Y-m-d"),
				'time' =>date("h:i:s a"),
				'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
				'lastupdate' => time()
			  ];
		
			  $TRANSECTIONE = SETUP::SETUP_CODE_INSERT_DATA('account_transection');
			  $transection_id = $TRANSECTIONE['last_id'];
			  $query2 =   CRUD::updateData(DB::conn(), 'account_transection',$transection_id,$data2);
		}

	  }else if ($collection_type == 'FROM-CLIENT') {

		if($actual_due > 0 ){

			$data2 = [
				'transection_type' => 'EXPENSE',
				'ledger_id' => 5,
				'transection_head_id' => 5,
				'transection_to' => 'Customer',
				'transection_to_id' =>  $new_client_id,
				'data_inserted_from' => 'SHOP OPENING PAYMENT',
				'transection_by' => 'Cash',
				'brunch_id' => 1,
				'transection_by_id' => 0,
				'transection_date' =>  $farewel_date,
				'out_amount'	 => 	$actual_due,
				'date' => date("Y-m-d"),
				'time' =>date("h:i:s a"),
				'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
				'lastupdate' => time()
			  ];
		
			  $TRANSECTIONE = SETUP::SETUP_CODE_INSERT_DATA('account_transection');
			  $transection_id = $TRANSECTIONE['last_id'];
			  $query2 =   CRUD::updateData(DB::conn(), 'account_transection',$transection_id,$data2);


			  $data3 = [
				'transection_type' => 'INCOME',
				'ledger_id' => 1,
				'transection_head_id' => 1,
				'transection_to' => 'Customer',
				'transection_to_id' =>  $client_id,
				'data_inserted_from' => 'RENT COLLECTION',
				'transection_by' => 'Cash',
				'brunch_id' => 1,
				'transection_by_id' => 0,
				'transection_date' =>  $farewel_date,
				'in_amount'	 => 	$actual_due,
				'date' => date("Y-m-d"),
				'time' =>date("h:i:s a"),
				'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
				'lastupdate' => time()
			  ];
		
			  $TRANSECTIONE = SETUP::SETUP_CODE_INSERT_DATA('account_transection');
			  $transection_id = $TRANSECTIONE['last_id'];
			  $query2 =   CRUD::updateData(DB::conn(), 'account_transection',$transection_id,$data3);
		}



		}else{

		}

		
		$data1 = [
			'client_status' => 'Gone',		
		  ];
	$query1 =   CRUD::updateData(DB::conn(), 'assign_unit',$related_id,$data1);
	
	
	print $query1['mess'];

}else if ($_POST['action'] == 'save_rent_collection'){


	$invoice_date = date("Y-m-d", strtotime($_POST['invoice_date']));
    $explode = explode("-",$_POST['rent_month_year']);
	$month = $explode[0];
	$rent_year = $explode[1];

		$qry = DB::conn()->prepare("SELECT A.id,A.client_id ,A.rent,A.gus_bill,A.moylar_bill,A.service_charge,A.other_bill FROM `assign_unit`  A 
		JOIN setup_client B ON (A.client_id = B.id) 
		where  B.client_status = 'Active' AND A.aggrement_start < '".$invoice_date."' ");
		$qry->execute();
		$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
		foreach ($fetch_list as $fetch) { 

            
		$check_qry = DB::conn()->prepare("SELECT A.id FROM `collect_rent`  A where  A.client_id = '".$fetch['client_id']."' AND A.rent_year = '".$rent_year."'  AND A.rent_month = '".$month."' AND A.assign_unit_id  =  '".$fetch['id']." '");
		$check_qry->execute();            
		
		if($check_qry->rowCount() > 0 ){

 

			}else{

				
				$data1 = [
					'client_id' => $fetch['client_id'],
					'assign_unit_id' => $fetch['id'],
					'rent' => $fetch['rent'],
					'gus_bill' => $fetch['gus_bill'],
					'moylar_bill' => $fetch['moylar_bill'],
					'service_charge' => $fetch['service_charge'],
					'other_bill' => $fetch['other_bill'],
					'rent_year' => $rent_year,
					'rent_month' => $month,
					'date' => $invoice_date,
					'time' =>date("h:i:s a"),
					'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
					'lastupdate' => time()
				  ];
	
	
				  $query =   CRUD::insert_data(DB::conn(), 'collect_rent', $data1);


			}

		

	
				


	
		}
		

		print "Invoice Created Success";


}else if ($_POST['action'] == 'clear_rent'){


		
		$data = [
			'room_no' => clean($_POST['room_no']),
			'client_id' => clean($_POST['client_id']),
			'zone_id' => clean($_POST['zone_id']),
			'rent' => clean($_POST['rent']),
			'advance' => clean($_POST['advance']),
			'aggrement_start' => clean($_POST['date_start']),
			'aggrement_end' => clean($_POST['date_end']),
			'date' => date("Y-m-d"),
			'time' =>date("h:i:s a"),
			'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
			'lastupdate' => time()
		  ];

			$qry = DB::conn()->prepare("SELECT * FROM `setup_rent_history` where `room_no` = '".$_POST['room_no']."' ");
			$qry->execute();
			$fetch_list = $qry->fetch(PDO::FETCH_ASSOC);

			if($qry->rowCount() > 0 ){
			$query =   CRUD::updateData(DB::conn(), 'setup_rent_history',$fetch_list['id'],$data);
			$mess = $query['mess'] ;
			}else{
			$query =   CRUD::insert_data(DB::conn(), 'setup_rent_history', $data);
			$mess = $query['mess'] ;
			}

	$data2 = [

		'client_id' => NULL,
		'rent' => NULL,
		'advance' => NULL,
		'aggrement_start' => NULL,
		'aggrement_end' => NULL,
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => time()
		];

		$qry = DB::conn()->prepare("SELECT * FROM `setup_rent` where `room_no` = '".$_POST['room_no']."' ");
		$qry->execute();
		$fetch_list = $qry->fetch(PDO::FETCH_ASSOC);

		  $query =   CRUD::updateData(DB::conn(), 'setup_rent',$fetch_list['id'],$data2);
		  $mess = $query['mess'] ;
		  

			print $mess;




}else if ($_POST['action'] == 'save_rent'){

	
    $data = [
		'room_no' => clean($_POST['room_no']),
		'client_id' => clean($_POST['client_id']),
		'zone_id' => clean($_POST['zone_id']),
		'rent' => clean($_POST['rent']),
		'advance' => clean($_POST['advance']),
		'aggrement_start' => clean($_POST['date_start']),
		'aggrement_end' => clean($_POST['date_end']),
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => time()
	  ];

                   
	  $qry = DB::conn()->prepare("SELECT * FROM `setup_rent` where `room_no` = '".$_POST['room_no']."' ");
	  $qry->execute();
	  $fetch_list = $qry->fetch(PDO::FETCH_ASSOC);

      if($qry->rowCount() > 0 ){
		$query =   CRUD::updateData(DB::conn(), 'setup_rent',$fetch_list['id'],$data);
		$mess = $query['mess'] ;
		}else{
		$query =   CRUD::insert_data(DB::conn(), 'setup_rent', $data);
		$mess = $query['mess'] ;
		}


		print $mess;



}else if ($_POST['action'] == 'save_zone'){

    $data = [
		'vobon_name' => clean($_POST['vobon_name']),
		'unit_no' => clean($_POST['unit_no']),
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => time()
	  ];

      if($_POST['related_id'] == 'new_id' ){

        $query =   CRUD::insert_data(DB::conn(), 'setup_vobon', $data);
		$mess = $query['mess'] ;

		for ($x = 1; $x <= $_POST['unit_no']; $x++) {
		
			$data2 = [
				'vobon_id' => $query['last_id'],
				'unit_name' => 'Unit-' . $x . ' ' ,
				'rent' => 0.00,
				'created_date' =>date("h:i:s a") . time(),
				'updated_date' =>date("h:i:s a") . time()
			  ];
		
			  $query2 =   CRUD::insert_data(DB::conn(), 'setup_unit', $data2);

		}


		}else{

			
		$query =   CRUD::updateData(DB::conn(), 'setup_vobon',$_POST['related_id'],$data);
		$mess = $query['mess'] ;

		}

		print $mess;


		
	}else if($_POST['action'] == 'save_unit' ){

$my_array = json_decode($_POST['today_data'], true);


for ($x = 0; $x < $_POST['total_item']; $x++) {

	$data2 = [
		'vobon_id' => $_POST['related_id'],
		'unit_name' => $my_array[$x]['element2']['unit_name'],
		'notes' => $my_array[$x]['element2']['notes'],
		'rent' => $my_array[$x]['element2']['rent'],
		'updated_date' =>date("h:i:s a") . time()
	  ];
	  

	$query =   CRUD::updateData(DB::conn(), 'setup_unit',$my_array[$x]['element2']['unit_id'],$data2);


}


print "Success";





	}else if($_POST['action'] == 'save_expense_transection' ){

		$transection_type = 'EXPENSE';
		$transaction_type= clean($_POST['transaction_type']);
	
		$transection_head_id= clean($_POST['transection_head_id']);
		$amount= clean($_POST['receive_now']);
		$transection_by= clean($_POST['transection_by']);
		$transection_by_id=  !empty ($_POST['transection_by_id']) ? clean($_POST['transection_by_id']) : 0 ;
		$check_number= clean($_POST['check_number']);
		$transection_to= clean($_POST['transection_to']);
		$transection_to_id= clean($_POST['transection_to_id']);
		$note= clean($_POST['note']);
		$data_inserted_from= clean($_POST['data_inserted_from']);
		$ledger_id= clean($_POST['ledger_id']);
	
		$transection_date = date("Y-m-d", strtotime($_POST['transection_date']));
		$check_date = !empty($_POST['check_date']) ?  date("Y-m-d", strtotime($_POST['check_date'])) : date("Y-m-d") ;


		
	
		if($transaction_type == 'PAYMENT' ){
			$amount= $amount;
		}else{
			$amount= -$amount;
		}
		if($_POST['related_id'] == 'new_id' ){

			$TRANSECTIONE = SETUP::SETUP_CODE_INSERT_DATA('account_transection');

			$CODE = $TRANSECTIONE['related_code'];
			$id = $TRANSECTIONE['last_id'];


			$query =DB::conn()->prepare("UPDATE `account_transection` 
	
			SET
			`transection_type` = '".$transection_type."',
			`ledger_id` = '".$ledger_id."',
			`transection_head_id` = '".$transection_head_id."',
			`transection_to` = '".$transection_to."',
			`transection_to_id` = '".$transection_to_id."',
			`transection_by` = '".$transection_by."',
			`transection_by_id` = '".$transection_by_id."',
			`check_number` = '".$check_number."',
			`check_date` = '".$check_date."',
			`note` = '".$note."',
			`status` = 'Done',
			`transection_date` =  '" . $transection_date . "',
			`out_amount` = 	'".$amount."',
			`data_inserted_from` = 	'".$data_inserted_from."',
			`date` = '" . date("Y-m-d") . "',
			`time` =  '" . date("h:i:s a") . "',
			`poster` =   '" . $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . "',
			`brunch_id` =   1,
			`lastupdate` =  '" . $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . ' Date: ' . date("d-M-Y") . ' IP ' . $_SERVER['REMOTE_ADDR'] . "'
	
	
			WHERE `id` = '".$id."'");
	
			$query->execute();
	
			if ($query) {
				$mess =  'Insert Success' ;
			} 
			else {
				$mess =  'Insert Failed';
			}
	


		}else{

			
	
			$CODE = $_POST['code'];
			$id = $_POST['related_id'];


			$ck1 =DB::conn()->prepare("SELECT *  FROM `account_transection` where `id` = '".$id."' ");
			$ck1->execute();
			$fe_ck1 = $ck1->fetch(PDO::FETCH_ASSOC);



			$query =DB::conn()->prepare("UPDATE `account_transection` 

		SET
		`transection_type` = '".$transection_type."',
		`ledger_id` = '".$ledger_id."',
		`transection_head_id` = '".$transection_head_id."',
		`transection_to` = '".$transection_to."',
		`transection_to_id` = '".$transection_to_id."',
		`transection_by` = '".$transection_by."',
		`transection_by_id` = '".$transection_by_id."',
		`check_number` = '".$check_number."',
		`note` = '".$note."',
		`transection_date` =  '" .$transection_date . "',
		`out_amount` = 	'".$amount."',
		`date` = '" . date("Y-m-d") . "',
		`time` =  '" . date("h:i:s a") . "',
		`poster` =   '" . $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . "',
		`lastupdate` =  '" . $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . ' Date: ' . date("d-M-Y") . ' IP ' . $_SERVER['REMOTE_ADDR'] . "'


		WHERE `id` = '".$id."'");

		$query->execute();

		if ($query) {
			$mess =  'Update Success' ;
		} 
		else {
			$mess =  'Update Failed';
		}



		}
	
		print json_encode(array ('mess' => $mess ,'transection_id' => $id));
	
}else if ($_POST['action'] == 'save_bank'){


	$data = [
		'bank_name' => clean($_POST['bank_name']),
		'brunch_name' => clean($_POST['brunch_name']),
		'account_number' => clean($_POST['account_number']),
		'account_name' => clean($_POST['account_name']), 
		'description' => clean($_POST['description']), 
		'status' => clean($_POST['status']), 
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . ' Date: ' . date("d-M-Y") . ' IP ' . $_SERVER['REMOTE_ADDR']
	];
	


	if($_POST['related_id']  == 'New' ){

			$query =   CRUD::insert_data(DB::conn(), 'setup_bank', $data);

	}else{
		$query =   CRUD::updateData(DB::conn(), 'setup_bank',$_POST['related_id'],$data);

	}
	
	
	print $query['mess']; ; 
	  die();

	
}else if($_POST['action'] == 'save_income_transection' ){

	$transection_type= 'INCOME';
	$transection_head_id= clean($_POST['transection_head_id']);
	$amount= clean($_POST['receive_now']);
	$transection_by= clean($_POST['transection_by']);
	$transection_by_id=  !empty ($_POST['transection_by_id']) ? clean($_POST['transection_by_id']) : 0 ;
	$check_number= clean($_POST['check_number']);
	$check_date = !empty($_POST['check_date']) ?  date("Y-m-d", strtotime($_POST['check_date'])) : date("Y-m-d") ;
	$transection_to=  clean($_POST['transection_to']);
	$transection_to_id= clean($_POST['transection_to_id']);
	$note= clean($_POST['note']);
	$data_inserted_from= clean($_POST['data_inserted_from']);
	$ledger_id= clean($_POST['ledger_id']);
	$transaction_type= clean($_POST['transaction_type']);
	$transection_date = date("Y-m-d", strtotime($_POST['transection_date']));


	if($transaction_type == 'PAYMENT' ){
		$amount= -$amount;
	}else{

		$amount= $amount;
	}

	if($_POST['related_id'] == 'new_id' ){


		$TRANSECTIONE = SETUP::SETUP_CODE_INSERT_DATA('account_transection');

		$CODE = $TRANSECTIONE['related_code'];
		$id = $TRANSECTIONE['last_id'];





		$query =DB::conn()->prepare("UPDATE `account_transection` 

		SET
		`transection_type` = '".$transection_type."',
		`ledger_id` = '".$ledger_id."',
        `transection_head_id` = '".$transection_head_id."',
        `transection_to` = '".$transection_to."',
        `transection_to_id` = '".$transection_to_id."',
		`transection_by` = '".$transection_by."',
		`transection_by_id` = '".$transection_by_id."',
        `check_number` = '".$check_number."',
		`check_date` = '".$check_date."',
        `note` = '".$note."',
		`status` = 'Done',
        `transection_date` =  '" . $transection_date . "',
		`in_amount` = 	'".$amount."',
		`data_inserted_from` = 	'".$data_inserted_from."',
		`date` = '" . date("Y-m-d") . "',
		`time` =  '" . date("h:i:s a") . "',
		`poster` =   '" . $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . "',
		`brunch_id` =   '" . $_SESSION['USER_BRUNCH'] . "',
		`lastupdate` =  '" . $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . ' Date: ' . date("d-M-Y") . ' IP ' . $_SERVER['REMOTE_ADDR'] . "'


		WHERE `id` = '".$TRANSECTIONE['last_id']."'");

		$query->execute();


		if ($query) {
			$mess =  'Insert Success' ;
		} 
		else {
			$mess =  'Insert Failed';
		}




	}else{

			$CODE = $_POST['code'];
			$id = $_POST['related_id'];


			$ck1 =DB::conn()->prepare("SELECT *  FROM `account_transection` where `id` = '".$id."' ");
			$ck1->execute();
			$fe_ck1 = $ck1->fetch(PDO::FETCH_ASSOC);



			$query =DB::conn()->prepare("UPDATE `account_transection` 

		SET
		`transection_type` = '".$transection_type."',
		`ledger_id` = '".$ledger_id."',
        `transection_head_id` = '".$transection_head_id."',
        `transection_to` = '".$transection_to."',
        `transection_to_id` = '".$transection_to_id."',
		`transection_by` = '".$transection_by."',
		`transection_by_id` = '".$transection_by_id."',
        `check_number` = '".$check_number."',
        `note` = '".$note."',
        `transection_date` =  '" . $transection_date . "',
		`in_amount` = 	'".$amount."',
		`date` = '" . date("Y-m-d") . "',
		`time` =  '" . date("h:i:s a") . "',
		`poster` =   '" . $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . "',
		`lastupdate` =  '" . $_SESSION['RENT_COLL_SESS_MEMBER_ID'] . ' Date: ' . date("d-M-Y") . ' IP ' . $_SERVER['REMOTE_ADDR'] . "'


		WHERE `id` = '".$id."'");

		$query->execute();

		if ($query) {
			$mess =  'Update Success' ;
		} 
		else {
			$mess =  'Update Failed';
		}




	}


      if($data_inserted_from == 'Invoice Wise Payment' ){
		
		
		$query =DB::conn()->prepare("UPDATE `sales_invoice` 

		SET
		`transection_id` = '".$id."'
		WHERE `id` = '".$_POST['extra_field']."'");

		$query->execute();
	  }


	  

	print json_encode(array ('mess' => $mess ,'transection_id' => $id));


}else{


}