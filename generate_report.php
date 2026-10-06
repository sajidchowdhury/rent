<?php
include_once("auth.php");
include('function_query.php');

?>
 <link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>


 <?php


if ( $_GET['action'] == 'Day Book Report'){




    $date_from = date("Y-m-d", strtotime($_GET['date_from']));
    $date_to = date("Y-m-d", strtotime($_GET['date_to']));



     $content = '';


    $content .= '<div class="panel-heading hidden-print">
   <div class="btn-group pull-right">
       <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
       <ul class="dropdown-menu">
           <li><a onclick="SimplePrint()"><img src="img/icons/json.png" width="24">Print</a></li>
       </ul>
   </div>                                    
   
   </div>';
   


    $content .= '<div id="myDiv" class="mydivclass"><div class="row">';


     $content .= ' <div class="row ">';
       $content .= '<div class="col-sm-12">';
       $content .= ' <div class="page-title" ><h3 style="color:red;text-align:center"> <input type="button" class="btn btn-info" onclick="subtractDay(\''.$_GET['action'].'\')" value="<<<">Cash Book : From '.$_GET['date_from'].'  TO '.$_GET['date_to'].'  <input type="button" class="btn btn-info" onclick="addDay(\''.$_GET['action'].'\')" value=">>>"></h4></div>';
       $content .= '</div>';
       $content .= '</div>';


$opening_balance_bank = FIND::OPENING_BALANCE($date_from,'Bank');
$opening_balance_cash =  FIND::OPENING_BALANCE($date_from,'Cash');

$opening_balance = $opening_balance_bank + $opening_balance_cash ; 

$content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-condensed table-striped table-bordered" >';
   
       $content .= '<tr>';
       $content.= '<th># Opening Balance</th>';
       $content.= '<th style="text-align:right">'.number_format((float)($opening_balance), 2, '.', '').'</th>';
   
       $content .= '</tr>';
   
       $content .= '<tr>';
       $content.= '<td style="padding-left:25px;">Bank Accounts</td>';
       $content.= '<td style="text-align:left">'.number_format((float)($opening_balance_bank), 2, '.', '').'</td>';
       $content .= '</tr>';
   

   $content .= '<tr>';
   $content.= '<td style="padding-left:25px;">Cash in Hand  </td>';
   $content.= '<td style="text-align:left">'.number_format((float)($opening_balance_cash), 2, '.', '').'</td>';
   $content .= '</tr>';
   
   
   
       $content .= '</table></div>';
       $content .= '</div>';
       $content .= '</div>';


  $content .= '<div class="row">';
   
       $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered" >';
   
       $content .= '<tr>';
       $content.= '<th colspan="3" style="text-align:center">INCOME</th>';
       $content .= '</tr>';
   
       $customer_payment = FIND::PAYMENT_DATE_RANGE($date_from,$date_to,'INCOME','Customer','RENT COLLECTION','Any');
   
       $content .= '<tr>';
       $content.= '<th style="padding-left:25px;" colspan="2">#Rent Collection  </th>';
       $content.= '<th style="text-align:right">'.$customer_payment['total_balance'].'</th>';
       $content .= '</tr>';


       $content .= '<tr>';
       $content.= '<th style="padding-left:45px;width:10%" class="p1"> Date</th>';
       $content.= '<th style="padding-left:45px;width:30%">Note    </th>';
       $content.= '<th style="padding-left:45px;width:10%">Amount</th>';
   
       $content .= '</tr>';
   
       $total_rent = 0;
       
       
       /*

       
       $total_rent = 0;
       
       
       $query = DB::conn()->prepare("SELECT group_concat(transection_to_id) as `total_items`,sum(`in_amount`) as `receive_amount`,`id`,`transection_date` FROM `account_transection`  WHERE in_amount > 0 AND ( `transection_date` BETWEEN  '".$date_from."' AND '".$date_to."' )  AND  `data_inserted_from` = 'RENT COLLECTION' GROUP BY `transection_date`  order by transection_date ");
       $query->execute();
       $fetchall = $query->fetchAll(PDO::FETCH_ASSOC);
       foreach ($fetchall  as $value2) {
   
           $content.= '<tr>';
   
           $content.= '<td style="padding-left:45px;font-size:12px;  white-space: nowrap !important;" class="p1">'.$value2['transection_date'].' </td>';

           $content.= '<td style="padding-left:45px;font-size:12px">';
           
           $items = explode(',', $value2['total_items']);
           foreach ($items as $item) {
            $info = SETUP::SETUP_CLIENT($item);
            $content.= '<a target="_BLINK" href="generate_report.php?action=Date Wise Collection&date='.$value2['transection_date'].'">'.$info['client_array']['client_name'].' </a>, ';
        }
           
           
           $content.= ' </td>';

           $content.= '<td style="padding-left:45px;font-size:12px">'.$value2['receive_amount'].' </td>';
           $content.= '</tr>';
           $total_rent += $value2['receive_amount'];
       }
      
       */


       $query = DB::conn()->prepare("SELECT COUNT(*) as `total_items`,sum(`in_amount`) as `receive_amount`,`id`,`transection_date` FROM `account_transection`  WHERE in_amount > 0 AND ( `transection_date` BETWEEN  '".$date_from."' AND '".$date_to."' )  AND  `data_inserted_from` = 'RENT COLLECTION' GROUP BY `transection_date`  order by transection_date ");
       $query->execute();
       $fetchall = $query->fetchAll(PDO::FETCH_ASSOC);
       foreach ($fetchall  as $value2) {
   
           $content.= '<tr>';
   
           $content.= '<td style="padding-left:45px;font-size:12px;  white-space: nowrap !important;" class="p1">'.$value2['transection_date'].' </td>';
           $content.= '<td style="padding-left:45px;font-size:12px"><a target="_BLINK" href="generate_report.php?action=Date Wise Collection&date='.$value2['transection_date'].'">'.$value2['total_items'].' ভাড়াটিয়ার ভাড়া </a></td>';
           $content.= '<td style="padding-left:45px;font-size:12px">'.$value2['receive_amount'].' </td>';
           $content.= '</tr>';
           $total_rent += $value2['receive_amount'];
       }
      






       

    $cash_receive = FIND::PAYMENT_DATE_RANGE($date_from,$date_to,'INCOME','Account Head','ADD INCOME','Cash');
      $content .= '<tr class="nowrap-row">';
       $content.= '<th style="padding-left:25px;" colspan="2" >#Other Receive   </th>';
       $content.= '<th style="text-align:right">'.$cash_receive['total_balance'].'</th>';
       $content .= '</tr>';
       $content .= '<tr>';
       $content.= '<th style="padding-left:45px;" class="p1">Date</th>';
       $content.= '<th style="padding-left:45px;">Note    </th>';
       $content.= '<th style="padding-left:45px;">Amount</th>';
   
       $content .= '</tr>';
       $total_other_receive = 0;
       foreach ($cash_receive['all_ids'] as $value3) {
   
           $tr_info3 =  SETUP::ACCOUNT_TRANSECTION($value3['id']); 
           $content.= '<tr>';
           $content.= '<td style="padding-left:45px;font-size:12px" class="p1">'.$tr_info3['transection_date'].' </td>';
           $content.= '<td style="padding-left:45px;font-size:12px">'.$tr_info3['only_name'].' >> '.$tr_info3['note'].'</td>';
           $content.= '<td style="padding-left:45px;font-size:12px">'.$tr_info3['in_amount'].' </td>';
   
           $content.= '</tr>';
           $total_other_receive += $tr_info3['in_amount'];
       }
   
   
   
   
   
       $content .= '</table></div>';
       $content .= '</div>';


       $cash_paid = FIND::PAYMENT_DATE_RANGE($date_from,$date_to,'EXPENSE','Account Head','ADD EXPENSE','All');
   
   
       $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered">';
   
       $content .= '<tr>';
       $content.= '<th colspan="3" style="text-align:center">EXPENSE</th>';
       $content .= '</tr>';
       $content .= '<tr>';
       $content.= '<th style="padding-left:25px;" colspan="2">Cash Paid </th>';
       $content.= '<th style="text-align:right">'.$cash_paid['total_balance'].'</th>';
       $content .= '</tr>';
   
       $content .= '<tr>';
       $content.= '<th style="padding-left:45px;" class="p1">Date</th>';
       $content.= '<th style="padding-left:45px;">Note    </th>';
       $content.= '<th style="padding-left:45px;">Amount</th>';
   
       $content .= '</tr>';
       $total_expense = 0;
       foreach ($cash_paid['all_ids'] as $value6) {
   
           $tr_info6 =  SETUP::ACCOUNT_TRANSECTION($value6['id']); 
           $content.= '<tr>';
           $content.= '<td style="padding-left:45px;font-size:12px" class="p1">'.$tr_info6['transection_date'].' </td>';
           $content.= '<td style="padding-left:45px;font-size:12px">'.$tr_info6['only_name'].' >> '.$tr_info6['note'].'</td>';
           $content.= '<td style="padding-left:45px;font-size:12px">'.$tr_info6['out_amount'].' </td>';
   
           $content.= '</tr>';
           $total_expense += $tr_info6['out_amount'];
       }
   
   
       $content .= '</table></div>';
       $content .= '</div>';
   

       $total_income = number_format((float)($opening_balance+$total_rent+$total_other_receive), 2, '.', '');

 $content .= '</div>';
   
       $content .= '<div class="row">';
       $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered">';
       
       $content .= '<tr>';
       $content.= '<th style="padding-left:25px;" colspan="2">TOTAL INCOME</th>';
       $content.= '<th style="text-align:right">'. $total_income.'</th>';
       $content .= '</tr>';
   
   
       $content .= '</table></div>';
       $content .= '</div>';


      $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered">';
       $content .= '<tr>';
       $content.= '<th style="padding-left:25px;" colspan="2">TOTAL EXPENSE</th>';
       $content.= '<th style="text-align:right">'.number_format((float)($total_expense), 2, '.', '').'</th>';
       $content .= '</tr>';
       $content .= '</table></div>';
       $content .= '</div>';
   
   
       $content .= '</div>';
   

       $closing_balance = number_format((float)($total_income - $total_expense), 2, '.', '');



       $content .= '<div class="row">';
   
       $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered">';
   
       $content .= '<tr>';
       $content.= '<th># Closing Balance</th>';
       $content.= '<th style="text-align:right">'.number_format((float)($closing_balance), 2, '.', '').'</th>';
       $content .= '</tr>';

           $bank_closing =  FIND::TRANSECTION_BY_RANGE_BALANCE($date_from,$date_to,'Bank'); 
           $cash_closing =  FIND::TRANSECTION_BY_RANGE_BALANCE_WITHOUT_ADVANCE_ADJUSTMENT($date_from,$date_to,'Cash'); 

            $total_bank_closing = $bank_closing + $opening_balance_bank ; 
            $total_cash_closing = $cash_closing + $opening_balance_cash ; 
      $content .= '<tr>';
       $content.= '<th style="padding-left:25px;">Bank Accounts</th>';
       $content.= '<th >'.$total_bank_closing.'</th>';
       $content .= '</tr>';
   

   $content .= '<tr>';
   $content.= '<th style="padding-left:25px;">Cash in Hand  </th>';
   $content.= '<th >'.$total_cash_closing.'</th>';
   $content .= '</tr>';
   
   
   
       $content .= '</table></div>';
       $content .= '</div>';
       $content .= '</div></div>';




          print  $content ;



}else if ( $_GET['action'] == 'Special Statement'){


    




    $date_from = date("Y-m-d", strtotime($_GET['date_from']));
    $date_to = date("Y-m-d", strtotime($_GET['date_to']));



     $content = '';


    $content .= '<div class="panel-heading hidden-print">
   <div class="btn-group pull-right">
       <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
       <ul class="dropdown-menu">
           <li><a onclick="SimplePrint()"><img src="img/icons/json.png" width="24">Print</a></li>
       </ul>
   </div>                                    
   
   </div>';
   


    $content .= '<div id="myDiv" class="mydivclass"><div class="row">';


$opening_balance_bank = FIND::OPENING_BALANCE($date_from,'Bank');
$opening_balance_cash =  FIND::OPENING_BALANCE($date_from,'Cash');

$opening_balance = $opening_balance_bank + $opening_balance_cash ; 

$content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-condensed table-striped table-bordered" >';
   
       $content .= '<tr>';
       $content.= '<th colspan="2"># Opening Balance</th>';
       $content.= '<th style="text-align:right">'.number_format((float)($opening_balance), 2, '.', '').'</th>';
   
       $content .= '</tr>';
   
       $content .= '<tr>';
       $content.= '<td colspan="2" style="padding-left:25px;">Bank Accounts</td>';
       $content.= '<td style="text-align:left">'.number_format((float)($opening_balance_bank), 2, '.', '').'</td>';
       $content .= '</tr>';
   

   $content .= '<tr>';
   $content.= '<td colspan="2" style="padding-left:25px;">Cash in Hand  </td>';
   $content.= '<td style="text-align:left">'.number_format((float)($opening_balance_cash), 2, '.', '').'</td>';
   $content .= '</tr>';
   
   
   
       $content .= '</table></div>';
       $content .= '</div>';
       $content .= '</div>';


  $content .= '<div class="row">';
   
       $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered" >';
   
       $content .= '<tr>';
       $content.= '<th colspan="4" style="text-align:center">ভাড়া কালেকশন </th>';
       $content .= '</tr>';
   
       $customer_payment = FIND::PAYMENT_DATE_RANGE($date_from,$date_to,'INCOME','Customer','RENT COLLECTION','Any');
   
      


       $content .= '<tr>';
       $content.= '<th style="padding-left:45px;width:10%" class="p1"> ভাড়াটিয়ার নাম </th>';
       $content.= '<th style="padding-left:45px;width:30%"> ভবন/ইউনিট</th>';
       $content.= '<th style="padding-left:45px;width:30%"> ভাড়া </th>';
       $content.= '<th style="padding-left:45px;width:10%">ভাড়া পেয়েছি </th>';
   
       $content .= '</tr>';
   
       $total_rent = 0;
       
       




       $query = DB::conn()->prepare("SELECT sum(A.rent) as total_rent ,GROUP_CONCAT(CONCAT(B.vobon_name, '-', C.unit_name) SEPARATOR '@') AS unit_vobon_names,A.*,D.client_name
       FROM `assign_unit` A  
       JOIN setup_vobon B ON (A.vobon_id = B.id) 
       JOIN setup_unit C ON (A.unit_id = C.id)
       JOIN setup_client D ON (A.client_id = D.id) 
       GROUP BY A.client_id

 ");
       $query->execute();
       $fetchall = $query->fetchAll(PDO::FETCH_ASSOC);
       foreach ($fetchall  as $value2) {
   

        $content .= '<td>'.$value2['client_name'] .'</td>'; 

           $content.= '<td>';
           $unit_vobon_names = explode('@', $value2['unit_vobon_names']);
     
        foreach ($unit_vobon_names as $value) {
            $content .=   $value . '<br>' ; // Add line break after each value
        }
           
        $content .= '<td>'.$value2['total_rent'] .'</td>'; 

        $content.= '</td>';



        $ck4 = DB::conn()->prepare("SELECT sum(in_amount) as total_receive  FROM   account_transection   where  data_inserted_from   = 'RENT COLLECTION' and transection_to = 'Customer' and transection_to_id = '".$value2['client_id']."' GROUP BY  transection_to_id ");
        $ck4->execute();
        $fe_ck4 = $ck4->fetch(PDO::FETCH_ASSOC);


           $content.= '<td>' . $fe_ck4['total_receive'] . '</td>';
           


           $content.= '</tr>';
           $total_rent += $fe_ck4['total_receive'];
       }
      
       
       $content .= '<tr>';
       $content.= '<th colspan="4" style="text-align:center">অন্যান্য আয় </th>';
       $content .= '</tr>';


    $cash_receive = FIND::PAYMENT_DATE_RANGE($date_from,$date_to,'INCOME','Account Head','ADD INCOME','Cash');
      $content .= '<tr class="nowrap-row">';
       $content.= '<th colspan="3" > </th>';
       $content.= '<th > </th>';
       $content .= '</tr>';
       $content .= '<tr>';
       $content.= '<th  > তারিখ</th>';
       $content.= '<th  colspan="2" >নোট    </th>';
       $content.= '<th>পরিমাণ</th>';
   
       $content .= '</tr>';
       $total_other_receive = 0;
       foreach ($cash_receive['all_ids'] as $value3) {
   
           $tr_info3 =  SETUP::ACCOUNT_TRANSECTION($value3['id']); 
           $content.= '<tr>';
           $content.= '<td >'.$tr_info3['transection_date'].' </td>';
           $content.= '<td  colspan="2" >'.$tr_info3['only_name'].' >> '.$tr_info3['note'].'</td>';
           $content.= '<td >'.$tr_info3['in_amount'].' </td>';
   
           $content.= '</tr>';
           $total_other_receive += $tr_info3['in_amount'];
       }
   
   
   
   
   
       $content .= '</table></div>';
       $content .= '</div>';


       $cash_paid = FIND::PAYMENT_DATE_RANGE($date_from,$date_to,'EXPENSE','Account Head','ADD EXPENSE','All');
   
   
       $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered">';
   
       $content .= '<tr>';
       $content.= '<th colspan="4" style="text-align:center">সকল খরচ</th>';
       $content .= '</tr>';
      
   
       $content .= '<tr>';
       $content.= '<th  > তারিখ</th>';
       $content.= '<th  colspan="2" >নোট    </th>';
       $content.= '<th>পরিমাণ</th>';
   
       $content .= '</tr>';
       $total_expense = 0;
       foreach ($cash_paid['all_ids'] as $value6) {
   
           $tr_info6 =  SETUP::ACCOUNT_TRANSECTION($value6['id']); 
           $content.= '<tr>';
           $content.= '<td style="padding-left:45px;font-size:12px" class="p1">'.$tr_info6['transection_date'].' </td>';
           $content.= '<td colspan="2" style="padding-left:45px;font-size:12px">'.$tr_info6['only_name'].' >> '.$tr_info6['note'].'</td>';
           $content.= '<td style="padding-left:45px;font-size:12px">'.$tr_info6['out_amount'].' </td>';
   
           $content.= '</tr>';
           $total_expense += $tr_info6['out_amount'];
       }
   
   

       
       $content .= '</table></div>';




       $content .= '</div>';
   

       $total_income = number_format((float)($total_rent + $total_other_receive), 2, '.', '');

 $content .= '</div>';
   
       $content .= '<div class="row">';
       $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered">';
       
       $content .= '<tr>';
       $content.= '<th style="padding-left:25px;" colspan="2">মোট আয়</th>';
       $content.= '<th style="text-align:right" colspan="2">'. $total_income.'</th>';
       $content .= '</tr>';
   
   
       $content .= '</table></div>';
       $content .= '</div>';


      $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered">';
       $content .= '<tr>';
       $content.= '<th style="padding-left:25px;" colspan="2">সর্বমোট খরচ</th>';
       $content.= '<th style="text-align:right" colspan="2">'. $total_expense.'</th>';
       $content .= '</tr>';
       $content .= '</table></div>';
       $content .= '</div>';



   
   
       $content .= '</div>';
   

       $closing_balance = number_format((float)($total_income - ($total_expense )), 2, '.', '');



       $content .= '<div class="row">';
   
       $content .= '<div class="col-sm-6">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered">';
   
       $content .= '<tr>';
       $content.= '<th>#ক্লোজিং ব্যালেন্স</th>';
       $content.= '<th style="text-align:right">'.number_format((float)($closing_balance), 2, '.', '').'</th>';
       $content .= '</tr>';

           $bank_closing =  FIND::TRANSECTION_BY_RANGE_BALANCE($date_from,$date_to,'Bank'); 
           $cash_closing =  FIND::TRANSECTION_BY_RANGE_BALANCE_WITHOUT_ADVANCE_ADJUSTMENT($date_from,$date_to,'Cash'); 

            $total_bank_closing = $bank_closing + $opening_balance_bank ; 
            $total_cash_closing = $cash_closing + $opening_balance_cash ; 
      $content .= '<tr>';
       $content.= '<th style="padding-left:25px;">ব্যাংক</th>';
       $content.= '<th >'.$total_bank_closing.'</th>';
       $content .= '</tr>';
   

   $content .= '<tr>';
   $content.= '<th style="padding-left:25px;">ক্যাশ ইন হ্যান্ড   </th>';
   $content.= '<th >'.$total_cash_closing.'</th>';
   $content .= '</tr>';
   
   
   
       $content .= '</table></div>';
       $content .= '</div>';
       $content .= '</div></div>';




          print  $content ;


}else if ( $_GET['action'] == 'Bank Statement'){



    $conn_me =  DB::conn();
    $date_from = date("Y-m-d", strtotime($_GET['date_from']));
    $date_to = date("Y-m-d", strtotime($_GET['date_to']));


    FIND::BANK_STATEMENT($_GET['EXTRAFILED'],$date_from,$date_to);
    $info = SETUP::BANK_SETUP($_GET['EXTRAFILED']);

      
        $content = '<div class="btn-group pull-right hidden-print">
        <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
        <ul class="dropdown-menu">
        <li><a  onclick="SimplePrint()" ><img src="img/icons/json.png" width="24"/>Print</a></li>
        </ul>
        </div> ';
 

        $content .= ' <div class="row ">';
        $content .= '<div class="col-sm-12">';
        $content .= ' <div class="page-title" >
        <h4 style="color:black;text-align:center"> STATEMENT OF ACCOUNT <br>
        Statement Date: '.$date_from.' to '.$date_to.' <br>Bank Name : '.$info['bank_name'].' | Acc No. '.$info['account_number'].'</h4></div>';
        $content .= '</div>';
        $content .= '</div>';


    $content .= '<div class="row">
    <div class="col-md-12">
    
        <div class="panel panel-default">
            <div class="panel-body">
            <div class="table-responsive">

            <table class="table" id="MSalary1">
            <thead>

            <th>Date</th>
            <th>Particular</th>
            
            <th>Deposit(Cr) </th>
                        <th>Withdrawal(Dr)</th>

            <th>Balance</th>
            </thead>
                <tbody>'; 
                $item_count = 0;
                $total_due_high =0;
                $total_due_low =0;
   $due = 0 ;
$query = DB::conn()->prepare("
SELECT
  `movment_date`,
  `time`,
  `link`,
  `note`,
  `due_high`,
  `due_low`,
  (@rt := @rt + due_high - due_low) AS runningbalance
  FROM
  `tempTable_customer_ledger_movement`,
  (SELECT @rt := 0) rt
ORDER BY
  `movment_date` ASC;");
$query->execute();
            
$sl = 1;
$fetch_list = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($fetch_list AS $fetch) {


            if ($item_count % 17 == 0 && $item_count > 0 ) {

            $content .= '<tr>
          
            <td>'.date("d-m-Y", strtotime($fetch['movment_date'])).'</td>';
            
            if($fetch['link'] === 'No LInk' ){
                $content .= '<td>'.$fetch['note'].'</td>';
            }else{
                $content .= '<td><a href="'.$fetch['link'].'">'.$fetch['note'].'</a></td>';
            }
            
            if($fetch['note'] == 'Closing Balance' ){
                $content .= '<td><p style="display:none">'.$fetch['due_high'].'</p></td>';
                $content .= '<td><p style="display:none">'.$fetch['due_low'].'</p></td>';

            }else{
                $content .= '<td>'.$fetch['due_high'].'</td>';
                $content .= '<td>'.$fetch['due_low'].'</td>';


            }


            

            $content .= '
            <td>'.$fetch['runningbalance'].' </td>
           

           </tr>';
        }else{

            $content .= '<tr class="break-after">
           
            <td>'.date("d-m-Y", strtotime($fetch['movment_date'])).'</td>';
            
            if($fetch['link'] === 'No LInk' ){
                $content .= '<td>'.$fetch['note'].'</td>';
            }else{
                $content .= '<td><a href="'.$fetch['link'].'">'.$fetch['note'].'</a></td>';
            }
            
            if($fetch['note'] == 'Closing Balance' ){
                $content .= '<td><p style="display:none">'.$fetch['due_high'].'</p></td>';
                $content .= '<td><p style="display:none">'.$fetch['due_low'].'</p></td>';

            }else{
                $content .= '<td>'.$fetch['due_high'].'</td>';
                $content .= '<td>'.$fetch['due_low'].'</td>';


            }


            

            $content .= '
            <td>'.$fetch['runningbalance'].' </td>
           

           </tr>';


        }

           $total_due_high +=$fetch['due_high'];
           $total_due_low += $fetch['due_low'];
          $due = number_format((float)( $fetch['runningbalance'] ), 2, '.', '') ;

          $item_count++; 
          $sl++;

        }
    
    
        $content .= '
        <tr class="text-info">
            <th colspan="2" style="text-align:right;"><b>Total </b></th>
            <th>' . $total_due_high . '</th>
            <th>' . $total_due_low . '</th>
            <th style="font-size:18px;"> '.$due.'</th>

        </tr>';
        
        
              
    $content .= '</tbody> 
          </table>
          </div>
          </div>     
        </div>
    
    </div>
    </div>';


print    $content ; 
}else if ( $_GET['action'] == 'Account Head Wise Report'){


$content = '' ;


    $date_from = date("Y-m-d", strtotime($_GET['date_from']));
    $date_to = date("Y-m-d", strtotime($_GET['date_to']));




        $selectedValues = implode(',', $_GET['related_id']);


        if($_GET['report_type'] == 'Multipal-Ledger-Wise' ){
        $QUERY = " WHERE A.ledger_id   IN  ({$selectedValues})  AND  A.transection_date BETWEEN '".$date_from."' AND '".$date_to."'   ";

        }else if ($_GET['report_type'] == 'Multipal-Head-Wise'){
        $QUERY = "  where A.`transection_head_id` IN  ({$selectedValues})  AND A.transection_date BETWEEN '".$date_from."' AND '".$date_to."';  ";

        }else{

        $QUERY = "";
        }


$content .= '<div class="row mydivclass">
    <div class="col-md-12">
    
        <div class="panel panel-default">
            <div class="panel-body" id="load_table">
           <div class="table-responsive">
           <table class="table table-hover table-condensed table-striped table-bordered datatable"  id="MSalary">
           <thead>';

    $content .='<tr>
    <th>তারিখ</th>
    <th>লেজার / অ্যাকাউন্ট হেড  </th>
    <th>বিস্তারিত </th>
    <th>নোট</td>
    <th> + </th>
    <th> - </th>
    <th> Action </th>


    </tr> </thead>';

$sl=1;
$total_in = 0;
$total_out = 0;
$qry1 = DB::conn()->prepare("SELECT A.*  ,B.`account_head`,C.`name`, date_format(transection_date, '%d-%m-%Y') AS `transectiondate`,

A.in_amount, A.out_amount, (A.in_amount - A.out_amount) AS closing_stock
FROM account_transection A
JOIN `setup_ac_head` B ON (A.`transection_head_id` = B.`id`)
JOIN `setup_ladger_head` C ON (A.`ledger_id` = C.`id`)

$QUERY   ");
$qry1->execute();
$fetch_list11 = $qry1->fetchAll(PDO::FETCH_ASSOC);
foreach($fetch_list11 AS $fetch22){

if($fetch22['transection_to'] == 'Customer' ){
    $info1 = SETUP::SETUP_CLIENT($fetch22['transection_to_id']);
    $details =    $info1['client_array']['client_name'];

}else{
    $details =   'Account Head Wise';
}

    $content .= '<tr>
            <td>'.$fetch22['transectiondate'].'</td>
            <td>লেজার: '.$fetch22['name'].'<br> অ্যাকাউন্ট: '.$fetch22['account_head'].'</td>
            <td>'.$details.'</td>
            <td>'.$fetch22['note'].'</td>
            <td>'.$fetch22['in_amount'].'</td>
            <td>'.$fetch22['out_amount'].'</td>';

            if( $fetch22['data_inserted_from'] == 'RENT COLLECTION'  || $fetch22['data_inserted_from'] == 'ADVANCE ADJUSTMENT' || $fetch22['data_inserted_from'] == 'MONEY-TRANSFER-FROM' || $fetch22['data_inserted_from'] == 'MONEY-TRANSFER-TO' || $fetch22['data_inserted_from'] == 'SHOP CLOSING PAYMENT' || $fetch22['data_inserted_from'] == 'SHOP OPENING PAYMENT'  ){


                $content .= ' <td></td>';

            }else{
                $content .= ' <td><a href="setup/'.$fetch22['transection_type'].'/'.$fetch22['id'].'">Edit '.$fetch22['transection_type'].'</a></td>';

            }

            $content .= '</tr>';

$total_in += $fetch22['in_amount'];
$total_out += $fetch22['out_amount'];

}



$content .= '<tfoot>
<tr>
    <th colspan="4" style="text-align:right"><b>Total</b></th>
    <th>' . $total_in . '</th>
    <th>' . $total_out . '</th>
</tr>
</tfoot>';

$content .= '</table>';

       

    



print $content;


}else if ( $_GET['action'] == 'Date Wise Collection'){


    $content = '';

  $content .= '<div class="row">';
   
       $content .= '<div class="col-sm-12">';
       $content .= '<div class="table-responsive"><table class="table table-hover table-condensed table-striped table-bordered" >';
   
       $content .= '<tr>';
       $content.= '<th colspan="3" style="text-align:center">RENT COLLECTION DATE: '.$_GET['date'].'</th>';
       $content .= '</tr>';

        $content .= '<tr>';
        $content.= '<th>Sl</th><th>Client Name</th><th>Collection</th>';
        $content .= '</tr>';

  
 $total_rent  = 0 ;
 $sl = 1 ; 
  $query = DB::conn()->prepare("SELECT * FROM `account_transection`  WHERE ( `transection_date` =  '".$_GET['date']."'  )  AND  `data_inserted_from` = 'RENT COLLECTION'  order by transection_date ");
       $query->execute();
       $fetchall = $query->fetchAll(PDO::FETCH_ASSOC);
       foreach ($fetchall  as $value2) {
   
           $info_client = SETUP::SETUP_CLIENT($value2['transection_to_id']);
           $content.= '<tr>';
           $content.= '<td>'.$sl++.' </td>';
           $content.= '<td> '.$info_client['client_array']['client_name'].' <br> '.$info_client['client_array']['family_member'].' >  '.$info_client['client_array']['mobile'].'</td>';
                      $content.= '<td style="padding-left:45px;font-size:12px">'.$value2['in_amount'].' </td>';

           $content.= '</tr>';
           $total_rent += $value2['in_amount'];
       }
   
      
    $content .= '<tr>';
    $content.= '<th colspan="2" style="text-align:right">TOTAL</th>';
        $content.= '<th>'.$total_rent.'</th>';
    $content .= '</tr>';

   print  $content ;

}else if ( $_GET['action'] == 'Client Due'){



    $conn_me =  DB::conn();
    $date_from = date("Y-m-d", strtotime($_GET['date_from']));
    $date_to = date("Y-m-d", strtotime($_GET['date_to']));
      
        $content = '<div class="btn-group pull-right hidden-print">
        <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
        <ul class="dropdown-menu">
        <li><a  onclick="SimplePrint()" ><img src="img/icons/json.png" width="24"/>Print</a></li>
        </ul>
        </div> ';
 

        $content .= ' <div class="row ">';
        $content .= '<div class="col-sm-12">';
        $content .= ' <div class="page-title" >
        </div>';
        $content .= '</div>';
        $content .= '</div>';


    $content .= '<div class="row">
    <div class="col-md-12">
    
        <div class="panel panel-default">
            <div class="panel-body">
            <div class="table-responsive">

            <table class="table" id="MSalary1">
            <thead>
            <th>Sl</th>

            <th>ভাড়াটিয়ার নাম</th>
            <th>Description</th>
            <th>Due</th>
            </thead>
                <tbody>'; 

                $total_due_high =0;
               
$query = DB::conn()->prepare("SELECT A.* FROM `setup_client` A where A.client_status = 'Active' ");
$query->execute();
            
$sl = 1;
$fetch_list = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($fetch_list AS $fetch) {


            $previous_due = FIND::CUSTOMER_PREVIOUS_DUE($fetch['id'],$date_to);
 
            if($previous_due > 0 ){

                $content .= '<tr>
                <td>'.$sl++.'</td>';
                
                    $content .= '<td>'.$fetch['client_name'].'</td>';
                    $content .= '<td></td>';
                
    
                $content .= '
                <td>'.$previous_due.' </td>
               
    
               </tr>';
              

            }

            $total_due_high +=$previous_due;
          
        }
    
    
        $content .= '<tfoot>
        <tr>
            <th colspan="3" style="text-align:right"><b>Total</b></th>
            <th>' . $total_due_high . '</th>

        </tr>
        </tfoot>';
        
        
              
    $content .= '</tbody> 
          </table>
          </div> </div>
                
        </div>
    
    </div>
    </div>';


print    $content ; 



}else if ( $_GET['action'] == 'Client Ledger'){

    $conn_me =  DB::conn();
    $date_from = date("Y-m-d", strtotime($_GET['date_from']));
    $date_to = date("Y-m-d", strtotime($_GET['date_to']));


    FIND::CUSTOMER_LEDGER($_GET['EXTRAFILED'],$date_from,$date_to);
    $info = SETUP::SETUP_CLIENT($_GET['EXTRAFILED']);

      
        $content = '<div class="btn-group pull-right hidden-print">
        <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
        <ul class="dropdown-menu">
        <li><a  onclick="SimplePrint()" ><img src="img/icons/json.png" width="24"/>Print</a></li>
        </ul>
        </div> ';
 

        $content .= ' <div class="row ">';
        $content .= '<div class="col-sm-12">';
        $content .= ' <div class="page-title" >
        <h4 style="color:black;text-align:center"> ভাড়াটিয়ার লেজার বই '.$_GET['date_to'].'  তারিখ পর্যন্ত <br> ভাড়াটিয়ার নাম: '.$info['client_array']['client_name'].'</h4></div>';
        $content .= '</div>';
        $content .= '</div>';


    $content .= '<div class="row">
    <div class="col-md-12">
    
        <div class="panel panel-default">
            <div class="panel-body">
            <div class="table-responsive">

            <table class="table" id="MSalary1">
            <thead>
            <th>Sl</th>

            <th>Date</th>
            <th>Description</th>
            <th> + </th>
            <th> - </th>
            <th>Due</th>
            </thead>
                <tbody>'; 
                $item_count = 0;
                $total_due_high =0;
                $total_due_low =0;
   $due = 0 ;
$query = DB::conn()->prepare("SELECT
`movment_date`,
`time`,
`link`,
`note`,
`due_high`,
`due_low`,
(@rt := @rt + due_high - due_low) AS runningbalance
FROM
`tempTable_customer_ledger_movement`,
(SELECT @rt := 0) rt
ORDER BY STR_TO_DATE(link, '%M %Y');
");
$query->execute();
            
$sl = 1;
$fetch_list = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($fetch_list AS $fetch) {


            if ($item_count % 17 == 0 && $item_count > 0 ) {

            $content .= '<tr>
            <td>'.$sl.'</td>
            <td>'.date("d-m-Y", strtotime($fetch['movment_date'])).'</td>';
            
            $content .= '<td>'.$fetch['note'].'</td>';

            
            if($fetch['note'] == 'Closing Balance' ){
                $content .= '<td><p style="display:none">'.$fetch['due_high'].'</p></td>';
                $content .= '<td><p style="display:none">'.$fetch['due_low'].'</p></td>';

            }else{
                $content .= '<td>'.$fetch['due_high'].'</td>';
                $content .= '<td>'.$fetch['due_low'].'</td>';


            }


            

            $content .= '
            <td>'.$fetch['runningbalance'].' </td>
           

           </tr>';
        }else{

            $content .= '<tr class="break-after">
            <td>'.$sl.'</td>
            <td>'.date("d-m-Y", strtotime($fetch['movment_date'])).'</td>';
            $content .= '<td>'.$fetch['note'].'</td>';

            
            if($fetch['note'] == 'Closing Balance' ){
                $content .= '<td><p style="display:none">'.$fetch['due_high'].'</p></td>';
                $content .= '<td><p style="display:none">'.$fetch['due_low'].'</p></td>';

            }else{
                $content .= '<td>'.$fetch['due_high'].'</td>';
                $content .= '<td>'.$fetch['due_low'].'</td>';


            }


            

            $content .= '
            <td>'.$fetch['runningbalance'].' </td>
           

           </tr>';


        }

           $total_due_high +=$fetch['due_high'];
           $total_due_low += $fetch['due_low'];
          $due = number_format((float)( $fetch['runningbalance'] ), 2, '.', '') ;

          $item_count++; 
          $sl++;

        }
    
    
        $content .= '
        <tr class="text-info">
            <th colspan="3" style="text-align:right;"><b>মোট হিসাব </b></th>
            <th>' . $total_due_high . '</th>
            <th>' . $total_due_low . '</th>
            <th style="font-size:18px;"> '.$due.'</th>

        </tr>';
        
        
              
    $content .= '</tbody> 
          </table>
          </div>
          </div>     
        </div>
    
    </div>
    </div>';


print    $content ; 
    
}else{


}