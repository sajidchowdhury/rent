<?php
include_once("auth.php");
include_once("clean.php");
include_once('function_query.php'); 


$start = new DateTime('2024-10-01');
$end = new DateTime('2025-03-01');

while ($start <= $end) {
    $theMonth = $start->format('M Y');
    
   $rent_year =   $rent_year = $start->format('Y');    
   $month = $rent_month = $start->format('F');     

	$data1 = [
					'client_id' => 43,
					'assign_unit_id' => 79 ,
					'rent' => 5000.00,
					'gus_bill' => 0.00,
					'moylar_bill' => 0.00,
					'service_charge' => 0.00,
					'other_bill' => 0.00 ,
					'rent_year' => $rent_year ,
					'rent_month' => $month ,
					'date' => date("Y-m-d") ,
					'time' =>date("h:i:s a"),
					'poster' => 43,
					'lastupdate' => time()
				  ];
    
     $query =   CRUD::insert_data(DB::conn(), 'collect_rent', $data1);
    
    $start->modify('+1 month');
}
?>
