<?php 
include('function_query.php');
  

$qry = DB::conn()->prepare("SELECT * FROM `account_transection`    ");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $value) {


if($value['transection_type'] == 'INCOME' ){

   QUICK_BALANCE::QUICK_TRANSECTION($value['brunch_id'],$value['ledger_id'],$value['transection_head_id'],$value['transection_to_id'],$value['in_amount'],0,0,0,'in_amount',$value['transection_date'],$value['transection_by']);


}else if ($value['transection_type'] == 'EXPENSE'){
    QUICK_BALANCE::QUICK_TRANSECTION($value['brunch_id'],$value['ledger_id'],$value['transection_head_id'],$value['transection_to_id'],0,$value['out_amount'],0,0,'out_amount',$value['transection_date'],$value['transection_by']);


}else{
    // 
}


  }