<?php 
include('auth.php');
include('connect_me.php');



class FIND {



    static  function getLastDateOfPreviousMonth($dateString) {

        $dateString = date("Y-m-d", strtotime($dateString));

        $date = new DateTime($dateString);
        $date->modify('first day of last month');
        $date->modify('last day of this month');
        return $date->format('Y-m-d');
    }



   
static function RENT_VS_DUE(){

    $query1 = DB::conn()->prepare("SELECT 
    A.rent_year,
    A.rent_month,
    IF(
        MONTH(A.date) > 1,
        MONTH(A.date) - 1,
        12  -- Handle January separately by setting it to 12 (December)
    ) AS transaction_month,
    SUM(A.rent + A.moylar_bill + A.gus_bill + A.service_charge + A.other_bill) AS total_rent  
FROM 
    collect_rent A   
GROUP BY 
    A.rent_year, 
    A.rent_month, 
    MONTH(A.date)
ORDER BY 
    A.date ASC
    ");
    $query1->execute();
    $fetch1 = $query1->fetchAll(PDO::FETCH_ASSOC);
    

    $query2 = DB::conn()->prepare("SELECT 
    MONTH(A.transection_date) AS transaction_month,
    YEAR(A.transection_date) AS transaction_year,
    SUM(A.in_amount) AS total_collection  
FROM 
    account_transection A    
WHERE A.data_inserted_from = 'RENT COLLECTION' 
GROUP BY  
YEAR(A.transection_date),MONTH(A.transection_date)

    ORDER BY A.transection_date ASC
    ");
    $query2->execute();
    $fetch2 = $query2->fetchAll(PDO::FETCH_ASSOC);

    // Combine data from both queries into a single array
    $combined_data = [];
    foreach ($fetch1 as $row) {

    
        $combined_data[] = [

            'y' => $row['rent_month'] . ' ' . $row['rent_year'], // Format month and year
            'a' => $row['total_rent'],
            'b' => 0 // Initialize 'Receive' as 0 for now
        ];
    }
    foreach ($fetch2 as $row) {



            // Get the previous month and year
            $prev_month = $row['transaction_month'] - 1;
            $prev_year = $row['transaction_year'];
            if ($prev_month == 0) { // If the previous month is January, set it to December of the previous year
                $prev_month = 12;
                $prev_year--;
            }
            // Format and print the previous month and year
            $month_year = date('F Y', mktime(0, 0, 0, $prev_month, 1, $prev_year));


        // Search for the matching month and year in $combined_data and update 'Receive'
        foreach ($combined_data as &$item) {
            if ($item['y'] === $month_year) {
                $item['b'] = $row['total_collection'];
                break;
            }
        }
    }

    // Convert combined data to JSON format
    $formatted_data = json_encode($combined_data);

    return $formatted_data;
}


    


    static function RECENT_STATUS($unit_id){
    
        $query1 = DB::conn()->prepare("SELECT * FROM `assign_unit` where unit_id = '".$unit_id."' and client_status = 'Active'");
        $query1->execute();
         
        if( $query1->rowCount() > 0 ){
          
            return 'green';
        }else{
            return 'red';
        }
       
    
    }


    static function SUMMERY(){
    
        $query1 = DB::conn()->prepare("SELECT count(id) as total_vobon,sum(unit_no) as total_unit FROM `setup_vobon` ");
        $query1->execute();
        $fetch1 = $query1->fetch(PDO::FETCH_ASSOC);

        $total_vobon = !empty($fetch1['total_vobon']) ? $fetch1['total_vobon'] : 0  ; 
        $total_unit = !empty($fetch1['total_unit']) ? $fetch1['total_unit'] : 0  ; 

        $query2 = DB::conn()->prepare("SELECT count(id) as total_client  FROM `setup_client` where client_status = 'Active' ");
        $query2->execute();
        $fetch2 = $query2->fetch(PDO::FETCH_ASSOC);


        $total_client = !empty($fetch2['total_client']) ? $fetch2['total_client'] : 0  ; 

        $unit_blank =  0; 
        $query3 = DB::conn()->prepare("SELECT * FROM `setup_unit` ");
        $query3->execute();
        $fetch3 = $query3->fetchAll(PDO::FETCH_ASSOC);
        foreach($fetch3 as $data ){
        
            $color = self::RECENT_STATUS($data['id']);
            $unit_blank += $color == 'red' ? 1 : 0 ;

        }


      

        return array(
            'total_vobon' => $total_vobon,
            'total_unit' => $total_unit,
            'total_client' => $total_client,
            'total_client' => $total_client,
            'unit_blank' => $unit_blank

            );
       
    
    }


    static function CASH_VIEW($TYPE,$DATE_FROM,$DATE_TO){

        if($TYPE == 'All' ){
            $QUERY = " ";
            }else if ($TYPE == 'TODAY' ){
            $QUERY = "  `transection_date`  = cast(Date(Now()) as Date) AND ";
            }else if ($TYPE == 'LAST-WEEK' ){
            
            $QUERY = "  ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 WEEK) ) AND ";
            
            }else if ($TYPE == 'LAST-MONTH' ){
            
            $QUERY = "   ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 MONTH) ) AND ";
            
            }else if ($TYPE == 'LAST-MONTH' ){
            
                $QUERY = "   ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 YEAR) ) AND ";
            
            }else{
            $QUERY = " ";
            }

        $query1 = DB::conn()->prepare("SELECT sum(`in_amount`) - sum(`out_amount`) as `cash_balance` FROM `account_transection`  WHERE  $QUERY `transection_by` = 'Cash'  group by `transection_by` ");
        $query1->execute();
        $fetch1 = $query1->fetch(PDO::FETCH_ASSOC);
        if(!empty($fetch1['cash_balance'])){ $cash_balance = $fetch1['cash_balance'];}else{ $cash_balance = 0.00 ;}

        $query2 = DB::conn()->prepare("SELECT sum(`in_amount`) - sum(`out_amount`) as `bank_balance` FROM `account_transection`  WHERE  $QUERY `transection_by` = 'Bank' group by `transection_by`  ");
        $query2->execute();
        $fetch2 = $query2->fetch(PDO::FETCH_ASSOC);
        if(!empty($fetch2['bank_balance'])){ $bank_balance = $fetch2['bank_balance'];}else{ $bank_balance = 0.00 ;}

        $query3 = DB::conn()->prepare("SELECT sum(`in_amount`) - sum(`out_amount`) as `mobile_balance` FROM `account_transection`  WHERE  $QUERY `transection_by` = 'Mobile-Banking' group by `transection_by`  ");
        $query3->execute();
        $fetch3 = $query3->fetch(PDO::FETCH_ASSOC);
        if(!empty($fetch3['mobile_balance'])){ $mobile_balance = $fetch3['mobile_balance'];}else{ $mobile_balance = 0.00 ;}

        $toal_balance = $cash_balance+$bank_balance+$mobile_balance;
        return array(
            'cash_balance' => $cash_balance,
            'bank_balance' => $bank_balance,
            'mobile_balance' => $mobile_balance,
            'total_balance' => $toal_balance

            );




    }
    

    
    static function RECEIVE_PAYMENT($TYPE,$DATE_FROM,$DATE_TO){

        if($TYPE == 'All' ){
            $QUERY = " ";
            }else if ($TYPE == 'TODAY' ){
            $QUERY = "  `transection_date`  = cast(Date(Now()) as Date) AND ";
            }else if ($TYPE == 'LAST-WEEK' ){
            
            $QUERY = "  ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 WEEK) ) AND ";
            
            }else if ($TYPE == 'LAST-MONTH' ){
            
            $QUERY = "   ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 MONTH) ) AND ";
            
        }else if ($TYPE == 'DATE-RANGE' ){

            $QUERY =  " ( `transection_date` BETWEEN  '".$DATE_FROM."'  AND '".$DATE_TO."' ) AND ";

            }else if ($TYPE == 'LAST-YEAR' ){
            
                $QUERY = "   ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 YEAR) ) AND ";
            
            }else{
            $QUERY = " ";
            }
        
                $query1 = DB::conn()->prepare("SELECT sum(`in_amount`)  as `cash_in` FROM `account_transection`  WHERE  $QUERY `data_inserted_from` = 'ADD INCOME'  group by `data_inserted_from` ");
                $query1->execute();
                $fetch1 = $query1->fetch(PDO::FETCH_ASSOC);

                if(!empty($fetch1['cash_in'])){ $cash_in = $fetch1['cash_in'];}else{ $cash_in = 0.00 ;}
        
                $query2 = DB::conn()->prepare("SELECT sum(`out_amount`)  as `cash_out` FROM `account_transection`  WHERE  $QUERY `data_inserted_from` = 'ADD EXPENSE' group by `data_inserted_from` ");
                $query2->execute();
                $fetch2 = $query2->fetch(PDO::FETCH_ASSOC);
                if(!empty($fetch2['cash_out'])){ $cash_out = $fetch2['cash_out'];}else{ $cash_out = 0.00 ;}
        
               
                $toal_balance = $cash_in-$cash_out;



               



                return array(
                    'cash_in' => $cash_in,
                    'cash_out' => $cash_out,
                    'toal_balance' => $toal_balance        
                    );
        
        
        
        
            }
            
            static function NOT_RECEIVE_YET($rent_month_rent_year){
                

                $query4 = DB::conn()->prepare("SELECT count(id) as num_of_client FROM `collect_rent` where transection_id IS NULL AND  CONCAT(rent_month,' ', rent_year)  = '". $rent_month_rent_year."'");
                $query4->execute();
                $fetch4 = $query4->fetch(PDO::FETCH_ASSOC);
        
                $num_of_not_receive_rent_client = !empty($fetch4['num_of_client']) ? $fetch4['num_of_client'] : 0  ; 

                return array(
                   
                    'num_of_not_receive_rent_client' => $num_of_not_receive_rent_client
        
                    );
            
            }
            
    static function INCOME_TRANSECTION_DONUT($TYPE,$DATE_FROM,$DATE_TO){

        if($TYPE == 'All' ){
        $QUERY = " ";
        }else if ($TYPE == 'TODAY' ){
        $QUERY = "  `transection_date`  = cast(Date(Now()) as Date) AND ";
        }else if ($TYPE == 'LAST-WEEK' ){
        
        $QUERY = "  ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 WEEK) ) AND ";
        
        }else if ($TYPE == 'LAST-MONTH' ){
        
        $QUERY = "   ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 MONTH) ) AND ";
        
        }else if ($TYPE == 'LAST-MONTH' ){
        
            $QUERY = "   ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 YEAR) ) AND ";
        
        }else{
        $QUERY = " ";
        }
        
        
            $query1 = DB::conn()->prepare("SELECT `account_head` AS `label`, sum(A.`in_amount`) AS `value`   FROM `account_transection` A  JOIN `setup_ac_head`  B ON (A.`transection_head_id` = B.`id`) WHERE  $QUERY `transection_type` = 'INCOME'  group by A.`transection_head_id` ");
            $query1->execute();
            $fetch_qry1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
            
            return array(
                'data' => $fetch_qry1
                );
        
            }


            

    static function EXPENSE_TRANSECTION_DONUT($TYPE,$DATE_FROM,$DATE_TO){
        if($TYPE == 'All' ){
            $QUERY = " ";
            }else if ($TYPE == 'TODAY' ){
            $QUERY = "  `transection_date`  = cast(Date(Now()) as Date) AND ";
            }else if ($TYPE == 'LAST-WEEK' ){
            
            $QUERY = "  ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 WEEK) ) AND ";
            
            }else if ($TYPE == 'LAST-MONTH' ){
            
            $QUERY = "   ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 MONTH) ) AND ";
            
            }else if ($TYPE == 'LAST-MONTH' ){
            
                $QUERY = "   ( `transection_date` >= DATE_ADD(NOW(), INTERVAL -1 YEAR) ) AND ";
            
            }else{
            $QUERY = " ";
            }
    
        $query1 = DB::conn()->prepare("SELECT `account_head` AS `label`, sum(A.`out_amount`) AS `value`   FROM `account_transection` A  JOIN `setup_ac_head`  B ON (A.`transection_head_id` = B.`id`) WHERE  $QUERY `transection_type` = 'EXPENSE'  group by A.`transection_head_id` ");
        $query1->execute();
        $fetch_qry1 = $query1->fetchAll(PDO::FETCH_ASSOC);
    
        
        return array(
            'data' => $fetch_qry1
            );
    
        }




    static function CLIENT_LEDGER($ID,$FROM,$TO){


        $conn_me =  DB::conn();


        $movement_array = [];
        $count = 0;
        $date_from = date("Y-m-d", strtotime($FROM));
        $date_to = date("Y-m-d", strtotime($TO));
        
       
        $closing_balance = FIND::CLIENT_ADVANCE($ID);
        $count  = $count+1;
        $movement_array[$count]['serial'] = $count;
        $movement_array[$count]['date'] = $closing_balance['date'];
        $movement_array[$count]['time'] = '07:24:22 am';
        $movement_array[$count]['qty'] = $closing_balance['closing'];
        $movement_array[$count]['description'] = $closing_balance['note'];
        $movement_array[$count]['type'] = 'IN';
        
        
      
        
        $ck131 = $conn_me->prepare("SELECT *  FROM `collect_rent` where  `client_id` = '".$ID."' AND ( `receive_date` BETWEEN '".$date_from."' AND '".$date_to."' )   ");
        $ck131->execute();
        if($ck131->rowCount()>0){
        
            $fe_ck31 = $ck131->fetchAll(PDO::FETCH_ASSOC);
            foreach($fe_ck31 AS $fetch31){
            if($fetch31['receive'] > 0){

                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch31['receive_date'];
                $movement_array[$count]['time'] = $fetch31['time'];
                $movement_array[$count]['qty'] = $fetch31['receive'];
                $movement_array[$count]['description'] = "Rent Collection >> $fetch31[rent_month]";
                $movement_array[$count]['type'] = 'IN';
            }    

            
           
        }
        
        }
        
        
        
        
        $ck131 = $conn_me->prepare("SELECT *  FROM `collect_rent` where  `client_id` = '".$ID."' AND ( `receive_date` BETWEEN '".$date_from."' AND '".$date_to."' )   ");
        $ck131->execute();
        if($ck131->rowCount()>0){
        
            $fe_ck31 = $ck131->fetchAll(PDO::FETCH_ASSOC);
            foreach($fe_ck31 AS $fetch31){
            if($fetch31['receive'] > 0){

                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch31['receive_date'];
                $movement_array[$count]['time'] = $fetch31['time'];
                $movement_array[$count]['qty'] = $fetch31['advance_adjustment'];
                $movement_array[$count]['description'] = "Advance Adjustment >> $fetch31[rent_month]";
                $movement_array[$count]['type'] = 'OUT';
            }    

            
           
        }
        
        }
       
        
        
        
        $stm = $conn_me->prepare("DROP table IF EXISTS tempTable_client_ledger");
        $stm->execute();
        
        $stmt2 = $conn_me->prepare("CREATE TABLE tempTable_client_ledger (
            id int NOT NULL AUTO_INCREMENT,
            movment_date date  NULL,
            time varchar(255)  NULL,
            note varchar(255)  NULL,
            in_amount float(20,2)  NULL,
            out_amount float(20,2)  NULL,
        
            PRIMARY KEY (id)
        );
        ");
        $stmt2->execute();
        
        
        foreach($movement_array as $item) {
           
            $date = date("Y-m-d", strtotime($item['date']));
        
        
        
            $query = $conn_me->exec("INSERT INTO `tempTable_client_ledger` 
            ( 
                `id`, `movment_date`,`time`,`note`,`in_amount`, `out_amount`
            
            ) 
            VALUES
            (
                '0',
                '".$date."',
              '".$item['time']."',
                '".$item['description']."',
                 IF('".$item['type']."' = 'IN','".$item['qty']."',0.00),
                 IF('".$item['type']."' = 'OUT','".$item['qty']."',0.00)
        
        
            ) ");
        
        }
               
        
        
         
                    
                      }
        

                      

    static function CLIENT_ADVANCE($ID){

        $conn_me = DB::conn();

        //$date = date("Y-m-d", strtotime($DATE));
        
        

        $total_in = 0;

       
        
        
        $ck131 = $conn_me->prepare("SELECT *  FROM `setup_rent` where  `client_id` = '".$ID."'   ");
        $ck131->execute();
        if($ck131->rowCount()>0){
        
            $fe_ck31 = $ck131->fetchAll(PDO::FETCH_ASSOC);
            foreach($fe_ck31 AS $fetch31){
            if($fetch31['advance'] > 0){

                $total_in += $fetch31['advance'] ;
             
            }    else{
                $total_in += 0;
            }

            
           
        }
        
        }
        
        

return array(
    'note' => "Advance Received",
    'closing' =>  $total_in,
    'date' =>  ''

);

    
    }



    
static function PREVIOUS_BANK_STATEMENT($BANKID,$DATE)
{
    $conn_me = DB::conn();


    $out_amount = 0 ;
    $in_amount = 0 ;

   $ck4 = $conn_me->prepare("SELECT A.account_head,B.note,B.time,B.out_amount,B.transection_date  FROM   account_transection B JOIN setup_ac_head A ON (B.transection_head_id = A.id)  where  B.data_inserted_from   = 'ADD EXPENSE'   AND B.transection_by = 'Bank' AND ( B.`transection_date` <= '".$DATE."' )  ");
   $ck4->execute();
   
   $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
   foreach($fe_ck4 AS $fetch4){
   

           $out_amount += $fetch4['out_amount'];
           
         
   }


     
   $ck4 = $conn_me->prepare("SELECT A.account_head,B.note,B.time,B.in_amount,B.transection_date  FROM   account_transection B JOIN setup_ac_head A ON (B.transection_head_id = A.id)  where  B.data_inserted_from   = 'MONEY-TRANSFER-TO'   AND  B.transection_by = 'Bank' AND ( B.`transection_date` <= '".$DATE."' )  ");
   $ck4->execute();
   
   $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
   foreach($fe_ck4 AS $fetch4){
          
    $in_amount += $fetch4['in_amount'];

         
   }


   
$ck4 = $conn_me->prepare("SELECT A.account_head,B.note,B.time,B.in_amount,B.transection_date  FROM   account_transection B JOIN setup_ac_head A ON (B.transection_head_id = A.id)  where  B.data_inserted_from   = 'ADD INCOME'   AND B.transection_by = 'Bank' AND ( B.`transection_date` <= '".$DATE."' )  ");
   $ck4->execute();
   
   $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
   foreach($fe_ck4 AS $fetch4){
    $in_amount += $fetch4['in_amount'];

         
   }
 
 
  $ck4 = $conn_me->prepare("SELECT A.account_head,B.note,B.time,B.out_amount,B.transection_date  FROM   account_transection B  JOIN setup_ac_head A ON (B.transection_head_id = A.id) where  B.data_inserted_from   = 'MONEY-TRANSFER-FROM'   AND  B.transection_by = 'Bank' AND ( B.`transection_date` <= '".$DATE."' )  ");
   $ck4->execute();
   
   $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
   foreach($fe_ck4 AS $fetch4){
    $out_amount += $fetch4['out_amount'];

         
   }


    $total_tr = number_format((float)(  ( $in_amount - $out_amount )), 2, '.', '');
    return [
            'previous_amount' => $total_tr
    ];
}



static function BANK_STATEMENT($ID,$FROM,$TO){
                           
                           


        $conn_me = DB::conn();
        

        $movement_array = [];
        $count = 0;
        $date_from = date("Y-m-d", strtotime($FROM));
        $date_to = date("Y-m-d", strtotime($TO));
        
        
      
        $prev_date = date('Y-m-d', strtotime($date_from .' -1 day'));

    
        $closing_balance = FIND::PREVIOUS_BANK_STATEMENT($ID,$prev_date);

        $count  = $count+1;
        $movement_array[$count]['serial'] = $count;
        $movement_array[$count]['date'] = $FROM;
        $movement_array[$count]['time'] = '07:24:22 am';
        $movement_array[$count]['qty'] = $closing_balance['previous_amount'];
        $movement_array[$count]['description'] = 'Closing Balance';
        $movement_array[$count]['type'] = 'HIGH';
        $movement_array[$count]['link'] = 'No LInk';
        

        
   $ck4 = $conn_me->prepare("SELECT A.account_head,B.note,B.time,B.out_amount,B.transection_date  FROM   account_transection B JOIN setup_ac_head A ON (B.transection_head_id = A.id)  where  B.data_inserted_from   = 'ADD EXPENSE'   AND B.transection_by = 'Bank' AND ( B.`transection_date` BETWEEN '".$date_from."' AND '".$date_to."' )  ");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
            if($fetch4['out_amount'] > 0 ){
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['transection_date'];
                $movement_array[$count]['time'] =   $fetch4['time'];
                $movement_array[$count]['qty'] = $fetch4['out_amount'];
                
                $movement_array[$count]['description'] =   $fetch4['account_head'] . ' >> ' . $fetch4['note'];
               
                $movement_array[$count]['type'] = 'LOW'; 
                $movement_array[$count]['link'] = 'No LInk';
            }
               
        
              
        }


          
        $ck4 = $conn_me->prepare("SELECT A.account_head,B.note,B.time,B.in_amount,B.transection_date  FROM   account_transection B JOIN setup_ac_head A ON (B.transection_head_id = A.id)  where  B.data_inserted_from   = 'MONEY-TRANSFER-TO'   AND  B.transection_by = 'Bank' AND ( B.`transection_date` BETWEEN '".$date_from."' AND '".$date_to."' )  ");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
            if($fetch4['in_amount'] > 0 ){
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['transection_date'];
                $movement_array[$count]['time'] =   $fetch4['time'];
                $movement_array[$count]['qty'] = $fetch4['in_amount'];
                
                $movement_array[$count]['description'] =   $fetch4['account_head'] . ' >> ' . $fetch4['note'];
               
                $movement_array[$count]['type'] = 'HIGH'; 
                $movement_array[$count]['link'] = 'No LInk';
            }
               
        
              
        }


        
   $ck4 = $conn_me->prepare("SELECT A.account_head,B.note,B.time,B.in_amount,B.transection_date  FROM   account_transection B JOIN setup_ac_head A ON (B.transection_head_id = A.id)  where  B.data_inserted_from   = 'ADD INCOME'   AND B.transection_by = 'Bank' AND ( B.`transection_date` BETWEEN '".$date_from."' AND '".$date_to."' )  ");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
            if($fetch4['in_amount'] > 0 ){
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['transection_date'];
                $movement_array[$count]['time'] =   $fetch4['time'];
                $movement_array[$count]['qty'] = $fetch4['in_amount'];
                
                $movement_array[$count]['description'] =  $fetch4['account_head'] . ' >> ' . $fetch4['note'];
               
                $movement_array[$count]['type'] = 'HIGH'; 
                $movement_array[$count]['link'] = 'No LInk';
            }
               
        
              
        }
      
      
       $ck4 = $conn_me->prepare("SELECT A.account_head,B.note,B.time,B.out_amount,B.transection_date  FROM   account_transection B  JOIN setup_ac_head A ON (B.transection_head_id = A.id) where  B.data_inserted_from   = 'MONEY-TRANSFER-FROM'   AND  B.transection_by = 'Bank' AND ( B.`transection_date` BETWEEN '".$date_from."' AND '".$date_to."' )  ");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
            if($fetch4['out_amount'] > 0 ){
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['transection_date'];
                $movement_array[$count]['time'] =   $fetch4['time'];
                $movement_array[$count]['qty'] = $fetch4['out_amount'];
                
                $movement_array[$count]['description'] =  $fetch4['account_head'] . ' >> ' .  $fetch4['note'];
               
                $movement_array[$count]['type'] = 'LOW'; 
                $movement_array[$count]['link'] = 'No LInk';
            }
               
        
              
        }
        
        $stm = $conn_me->prepare("DROP table IF EXISTS tempTable_customer_ledger_movement");
        $stm->execute();
        
        $stmt2 = $conn_me->prepare("CREATE TABLE tempTable_customer_ledger_movement (
            id int NOT NULL AUTO_INCREMENT,
            movment_date date  NULL,
            time varchar(255)  NULL,
            note varchar(255)  NULL,
            due_high float(20,2)  NULL,
            due_low float(20,2)  NULL,
            link TEXT  NULL,
        
            PRIMARY KEY (id)
        );
        ");
        $stmt2->execute();
        
        
        foreach($movement_array as $item) {
           
            $date = date("Y-m-d", strtotime($item['date']));
        
        
        
            $query = $conn_me->exec("INSERT INTO `tempTable_customer_ledger_movement` 
            ( 
                `id`, `movment_date`,`time`,`note`,`due_high`, `due_low`,`link`
            
            ) 
            VALUES
            (
                '0',
                '".$date."',
                '".$item['time']."',
                '".$item['description']."',
                IF('".$item['type']."' = 'HIGH','".$item['qty']."',0.00),
                IF('".$item['type']."' = 'LOW','".$item['qty']."',0.00),
                '".$item['link']."'
        
        
            ) ");
        
        }
               
        
        
                           
}



 static function CUSTOMER_LEDGER($ID,$FROM,$TO){


        $conn_me = DB::conn();
        

        $movement_array = [];
        $count = 0;
        $date_from = date("Y-m-d", strtotime($FROM));
        $date_to = date("Y-m-d", strtotime($TO));
        
        
      
           $ck4 = $conn_me->prepare("SELECT transection_id,rent_year,rent_month,date,rent ,moylar_bill , gus_bill, service_charge , other_bill FROM `collect_rent`  where  `client_id` = '".$ID."' AND ( `date` BETWEEN '".$date_from."' AND '".$date_to."' ) ");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
                $total_rent = $fetch4['rent'] + $fetch4['moylar_bill'] + $fetch4['gus_bill'] + $fetch4['service_charge'] + $fetch4['other_bill'] ; 
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['date'];
                $movement_array[$count]['time'] =  '07:24:22 am';
                $movement_array[$count]['qty'] = $total_rent;

                if(!empty($fetch4['transection_id'])){
                    $movement_array[$count]['description'] =  $fetch4['rent_month'] . ' ' . $fetch4['rent_year'] . ' এর ভাড়া ';

                }else{
                    $movement_array[$count]['description'] = "<b style=\'color:red;\'>" . $fetch4['rent_month'] . ' ' . $fetch4['rent_year'] . " এর ভাড়া প্রদান করেননি  </b>";

                }
                
                $movement_array[$count]['type'] = 'HIGH'; 
                $movement_array[$count]['link'] = $fetch4['rent_month'] . ' ' . $fetch4['rent_year'];
        
              
        }

       

        $ck4 = $conn_me->prepare("SELECT B.in_amount,A.receive_date,A.rent_month,A.rent_year FROM `collect_rent` A JOIN account_transection B ON (A.transection_id = B.id ) where  A.transection_id IS NOT NULL AND A.`client_id` = '".$ID."' AND ( A.`receive_date` BETWEEN '".$date_from."' AND '".$date_to."' ) group by A.transection_id");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['receive_date'];
                $movement_array[$count]['time'] =  '07:24:22 am';
                $movement_array[$count]['qty'] = $fetch4['in_amount'];
                
                
                $movement_array[$count]['description'] = "<b style=\'color:green;\'> " . $fetch4['rent_month'] . ' ' . $fetch4['rent_year'] .  " এর ভাড়া প্রদান করেছেন </b>";
               
                $movement_array[$count]['type'] = 'LOW'; 
                $movement_array[$count]['link'] = $fetch4['rent_month'] . ' ' . $fetch4['rent_year'];
        
              
        }


        $ck4 = $conn_me->prepare("SELECT B.out_amount,A.receive_date,A.rent_month,A.rent_year  FROM `collect_rent` A JOIN account_transection B ON (A.advance_adj_id = B.id ) where  A.advance_adj_id IS NOT NULL AND A.`client_id` = '".$ID."' AND ( A.`receive_date` BETWEEN '".$date_from."' AND '".$date_to."' )  ");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['receive_date'];
                $movement_array[$count]['time'] =  '07:24:22 am';
                $movement_array[$count]['qty'] = $fetch4['out_amount'];
                
                $movement_array[$count]['description'] = "" . $fetch4['rent_month'] . ' ' . $fetch4['rent_year'] . ' এর ভাড়া থেকে এডভান্স হিসেবে কর্তন ';
               
                $movement_array[$count]['type'] = 'LOW'; 
                $movement_array[$count]['link'] = $fetch4['rent_month'] . ' ' . $fetch4['rent_year'];
        
              
        }



        $ck4 = $conn_me->prepare("SELECT B.out_amount,B.transection_date  FROM   account_transection B   where  B.data_inserted_from   = 'SHOP OPENING PAYMENT'  AND B.`transection_to_id` = '".$ID."' AND ( B.`transection_date` BETWEEN '".$date_from."' AND '".$date_to."' )  ");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
            if($fetch4['out_amount'] > 0 ){
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['transection_date'];
                $movement_array[$count]['time'] =  '07:24:22 am';
                $movement_array[$count]['qty'] = $fetch4['out_amount'];
                
                $movement_array[$count]['description'] = "ইউনিট নেয়ার সময়";
               
                $movement_array[$count]['type'] = 'HIGH'; 
                $movement_array[$count]['link'] = $fetch4['rent_month'] . ' ' . $fetch4['rent_year'];
            }
               
        
              
        }


        $ck4 = $conn_me->prepare("SELECT B.in_amount,B.transection_date  FROM   account_transection B   where  B.data_inserted_from   = 'SHOP CLOSING PAYMENT'  AND B.`transection_to_id` = '".$ID."' AND ( B.`transection_date` BETWEEN '".$date_from."' AND '".$date_to."' ) ");
        $ck4->execute();
        
        $fe_ck4 = $ck4->fetchAll(PDO::FETCH_ASSOC);
        foreach($fe_ck4 AS $fetch4){
        
            if($fetch4['in_amount'] > 0 ){
                $count  = $count+1;
                $movement_array[$count]['serial'] = $count;
                $movement_array[$count]['date'] = $fetch4['transection_date'];
                $movement_array[$count]['time'] =  '07:24:22 am';
                $movement_array[$count]['qty'] = $fetch4['in_amount'];
                
                $movement_array[$count]['description'] = "ইউনিট খালি করার  সময়";
               
                $movement_array[$count]['type'] = 'LOW'; 
                $movement_array[$count]['link'] = 'October 202322222222222';
            }
        
              
        }

        
        $stm = $conn_me->prepare("DROP table IF EXISTS tempTable_customer_ledger_movement");
        $stm->execute();
        
        $stmt2 = $conn_me->prepare("CREATE TABLE tempTable_customer_ledger_movement (
            id int NOT NULL AUTO_INCREMENT,
            movment_date date  NULL,
            time varchar(255)  NULL,
            note varchar(255)  NULL,
            due_high float(20,2)  NULL,
            due_low float(20,2)  NULL,
            link TEXT  NULL,
        
            PRIMARY KEY (id)
        );
        ");
        $stmt2->execute();
        
        
        foreach($movement_array as $item) {
           
            $date = date("Y-m-d", strtotime($item['date']));
        
        
        
            $query = $conn_me->exec("INSERT INTO `tempTable_customer_ledger_movement` 
            ( 
                `id`, `movment_date`,`time`,`note`,`due_high`, `due_low`,`link`
            
            ) 
            VALUES
            (
                '0',
                '".$date."',
                '".$item['time']."',
                '".$item['description']."',
                IF('".$item['type']."' = 'HIGH','".$item['qty']."',0.00),
                IF('".$item['type']."' = 'LOW','".$item['qty']."',0.00),
                '".$item['link']."'
        
        
            ) ");
        
        }
               
        
        
         
                    
                      }


    static function TRANSECTION_BY_RANGE_BALANCE($DATE_FROM,$DATE_TO,$TYPE){
        
      

        $query = DB::conn()->prepare("SELECT sum(`in_amount`) - sum(`out_amount`) AS `balance`  FROM `account_transection`  WHERE ( `transection_date` BETWEEN '".$DATE_FROM."' AND '".$DATE_TO."' )  AND `transection_by` = '".$TYPE."'  GROUP BY `transection_by` ");
        $query->execute();
        $fetch = $query->fetch(PDO::FETCH_ASSOC);

        if(!empty( $fetch['balance'])){
            $balance = $fetch['balance'];
        }else{
            $balance = 0.00;
        }

      
        return $balance
            ;



    }


    static function TRANSECTION_BY_RANGE_BALANCE_WITHOUT_ADVANCE_ADJUSTMENT($DATE_FROM,$DATE_TO,$TYPE){
        
      

        $query = DB::conn()->prepare("SELECT sum(`in_amount`) - sum(`out_amount`) AS `balance`  FROM `account_transection`  WHERE ( `transection_date` BETWEEN '".$DATE_FROM."' AND '".$DATE_TO."' )  AND `transection_by` = '".$TYPE."' AND data_inserted_from <> 'ADVANCE ADJUSTMENT'  GROUP BY `transection_by` ");
        $query->execute();
        $fetch = $query->fetch(PDO::FETCH_ASSOC);

        if(!empty( $fetch['balance'])){
            $balance = $fetch['balance'];
        }else{
            $balance = 0.00;
        }

      
        return $balance
            ;



    }


 static function OPENING_BALANCE($DATE_FROM,$TYPE){
        


        $query = DB::conn()->prepare("SELECT sum(`in_amount`) - sum(`out_amount`) AS `balance`  FROM `account_transection`  WHERE ( `transection_date` < '".$DATE_FROM."' )   AND `transection_by` = '".$TYPE."'  AND data_inserted_from <> 'ADVANCE ADJUSTMENT' GROUP BY `transection_by` ");
        $query->execute();
        $fetch = $query->fetch(PDO::FETCH_ASSOC);

        if(!empty( $fetch['balance'])){
            $balance = $fetch['balance'];
        }else{
            $balance = 0.00;
        }

      
        return  $balance; 



    }





    static function PAYMENT_DATE_RANGE($DATE_FROM,$DATE_TO,$TYPE,$TR_TO,$DATA_FROM,$TR_BY){
        

        $QUERY_TAG  = " AND `data_inserted_from` = '".$DATA_FROM."'  ";


      if ($DATA_FROM == 'ADD INCOME'){
            $amount = "in_amount";
        }else if ($DATA_FROM == 'RENT COLLECTION'){
            $amount = "in_amount";
        }else if ($DATA_FROM == 'ADD EXPENSE'){
            $amount = "out_amount";

        }else if ($DATA_FROM == 'ADVANCE ADJUSTMENT'){
            $amount = "out_amount";

        }else{
            $amount = "in_amount";
        }


        $total_balance = 0;
        $all_ids = '';
        $query = DB::conn()->prepare("SELECT * FROM `account_transection`  WHERE  ( `transection_date` BETWEEN  '".$DATE_FROM."' AND '".$DATE_TO."' )  AND `transection_type` = '".$TYPE."' AND `transection_to` = '".$TR_TO."'  $QUERY_TAG  ");
        $query->execute();
        $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($fetch as  $fetch2) {
 
                 $total_balance +=  $fetch2[$amount];
       
        
        }
       
      

       if(!empty($total_balance)){ $total_balance = $total_balance;  }else{$total_balance = 0; }

        return array(
            'total_balance' => $total_balance,
            'all_ids' => $fetch

            );



    }




    static function PAYMENT_DATE_RANGE_BY_GROUP($DATE_FROM,$DATE_TO,$TYPE,$TR_TO,$DATA_FROM,$TR_BY){
        

        $QUERY_TAG  = " AND `data_inserted_from` = '".$DATA_FROM."'  ";


      if ($DATA_FROM == 'ADD INCOME'){
            $amount = "in_amount";
        }else if ($DATA_FROM == 'RENT COLLECTION'){
            $amount = "in_amount";
        }else if ($DATA_FROM == 'ADD EXPENSE'){
            $amount = "out_amount";
        }else{
            $amount = "in_amount";
        }


        $total_balance = 0;
        $all_ids = '';
        $query = DB::conn()->prepare("SELECT sum($amount) as amount FROM `account_transection`  WHERE  ( `transection_date` BETWEEN  '".$DATE_FROM."' AND '".$DATE_TO."' )  AND `transection_type` = '".$TYPE."' AND `transection_to` = '".$TR_TO."'  $QUERY_TAG GROUP BY  transection_to_id ");
        $query->execute();
        $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($fetch as  $fetch2) {
 
                 $total_balance +=  $fetch2['amount'];
       
        
        }
       
      

       if(!empty($total_balance)){ $total_balance = $total_balance;  }else{$total_balance = 0; }

        return array(
            'total_balance' => $total_balance,
            'all_ids' => $fetch

            );



    }


    
    static function CUSTOMER_PREVIOUS_DUE($client_id, $rent_date){


        $qry1 = DB::conn()->prepare("SELECT sum(rent + moylar_bill + gus_bill + service_charge + other_bill ) as total_rent  FROM collect_rent WHERE  client_id = ? AND date <= ?  ");
        $qry1->execute([$client_id,$rent_date]);
        $fetch1 = $qry1->fetch(PDO::FETCH_ASSOC);
        
        $total_rent = !empty($fetch1['total_rent'] ) ? number_format((float)( $fetch1['total_rent']), 2, '.', '') : 0 ;


        
        $total_collection = 0 ;
        $qry2= DB::conn()->prepare("SELECT B.in_amount  as total_collection FROM collect_rent A JOIN account_transection B  ON (A.transection_id = B.id ) WHERE  transection_id IS NOT NULL and A.client_id  = ?  AND A.date <= ?  GROUP BY A.transection_id");
        $qry2->execute([$client_id,$rent_date]);
        $fetch = $qry2->fetchAll(PDO::FETCH_ASSOC);
 
        foreach($fetch as $fetch2 ){

            $total_collection += !empty($fetch2['total_collection'] ) ? number_format((float)( $fetch2['total_collection']), 2, '.', '') : 0 ;

        }

    

        $total_adj = 0 ;

        $qry3= DB::conn()->prepare("  SELECT B.out_amount  as total_adj FROM collect_rent A JOIN account_transection B  ON (A.advance_adj_id = B.id ) WHERE  advance_adj_id IS NOT NULL and A.client_id  = ?  AND A.date <= ? GROUP BY A.transection_id");
        $qry3->execute([$client_id,$rent_date]);
        $fetch = $qry3->fetchAll(PDO::FETCH_ASSOC);
        foreach($fetch as $fetch3 ){

        $total_adj += !empty($fetch3['total_adj'] ) ? number_format((float)( $fetch3['total_adj']), 2, '.', '') : 0 ;
        }


        $qry4= DB::conn()->prepare("SELECT sum(out_amount) as shop_opening  FROM account_transection WHERE  transection_to_id = ? and transection_to = 'Customer'  AND transection_date <= ? AND data_inserted_from = 'SHOP OPENING PAYMENT' ");
        $qry4->execute([$client_id,$rent_date]);
        $fetch4 = $qry4->fetch(PDO::FETCH_ASSOC);

        $shop_opening = !empty($fetch4['shop_opening'] ) ? number_format((float)( $fetch4['shop_opening']), 2, '.', '') : 0 ;



        $qry5= DB::conn()->prepare("SELECT sum(in_amount) as shop_closing  FROM account_transection WHERE  transection_to_id = ? and transection_to = 'Customer'  AND transection_date <= ? AND data_inserted_from = 'SHOP CLOSING PAYMENT' ");
        $qry5->execute([$client_id,$rent_date]);
        $fetch5 = $qry5->fetch(PDO::FETCH_ASSOC);

        $shop_closing = !empty($fetch5['shop_closing'] ) ? number_format((float)( $fetch5['shop_closing']), 2, '.', '') : 0 ;





            // Calculate the monthly due and previous due
            $previous_due = number_format((float)( ($total_rent + $shop_opening) - ($total_collection + $total_adj +   $shop_closing) )  , 2, '.', '') ;

       
        return $previous_due;
    
          }
        




    static function getCurrentMenuId($LINK) {


$query1 = DB::conn()->prepare("SELECT *  FROM `menu_list` where CONCAT('/',`menu_link`) = '".$LINK."'  ");
$query1->execute();
$fetch = $query1->fetch(PDO::FETCH_ASSOC);
if($query1->rowCount() > 0 ){
    $menu_id = $fetch['id'];
    $parents = $fetch['time'];
}else{
    $menu_id = '';
    $parents = '';
}
      
   
            return array(
                'menu_id' => $menu_id,
                'parents' => $parents
    
                );
    
    
        
    }



}
class TEMPLET 
{




    static function TEMPLET_CONTENT($PAGENAME,$RELATEDID)
    {

        $access = SETUP::ADMIN_SETUP($_SESSION['RENT_COLL_SESS_MEMBER_ID']);


        if($PAGENAME == 'Dashboard' ){



            $content = '';
            

        }else if ($PAGENAME == 'Welcome'){



            $com_profile = SETUP::SETUP_COMPANY('Active');
   

          
     
         $content = ' <div class="error-container animated flash" >
         <div class="error-text">'.$com_profile['company_name'].'</div>
         <div class="error-subtext">'.$com_profile['company_address'].' <br>'.$com_profile['company_phone']. '<br>'.$com_profile['company_email'].'</div>            
     </div>';
     

        
        }else{
            

   
    $content = ' <div class="error-container animated flash" >
    <div class="error-code">You Lost !! </div>
    <div class="error-text">Need Help</div>
    <div class="error-subtext">call 911</div>            
</div>';

        }

        $mess = '';
        return array(
            'content' => $mess . $content

            );


    }



}




CLASS SETUP {




    static function SETUP_LEDGER($ID)
    {

        $qry2 =  DB::conn()->prepare("SELECT * FROM `setup_ladger_head` where `id` = '".$ID."' ");
        $qry2->execute();
        $fetch = $qry2->fetch(PDO::FETCH_ASSOC);
    
        return array(

            'fetch' => $fetch
            );



    }
    

    static function ACCOUNT_TRANSECTION($ID)
    {
      

        $ck1 = DB::conn()->prepare("SELECT *,date_format(transection_date, '%d-%m-%Y') AS `transectiondate`,date_format(check_date, '%d-%m-%Y') AS `check_date`  FROM `account_transection` where `id` = '".$ID."'");
        $ck1->execute();
        $fetch_ck1 = $ck1->fetch(PDO::FETCH_ASSOC);



            if(!empty($fetch_ck1['transection_head_id'])){
            $info2 = SETUP::ACCOUNT_HEAD_SETUP($fetch_ck1['transection_head_id']);
            $transection_head_name = $info2['account_head'];
            }else{
            $transection_head_name = 'Not Set Yet';
            }

        if(!empty($fetch_ck1['ledger_id'])){
        $info2 = SETUP::SETUP_LEDGER($fetch_ck1['ledger_id']);
        $ladger_name = $info2['fetch']['name'];
        }else{
        $ladger_name = 'Not Set Yet';
        }

           
        if($fetch_ck1['transection_by'] == 'Cash' ){
            $bank_name = "";
            $check_no = "";
            $mobile_banking_no = "";
            $check_date =  "";
            $title1 = "";
            $title2 = ""; 
            $title3="";
            $title4="";
            $style="";

        }else if ($fetch_ck1['transection_by'] == 'Bank'){

            $info_bank = SETUP::BANK_SETUP( $fetch_ck1['transection_by_id']);
            $bank_name = "$info_bank[bank_name] $info_bank[account_number]";
            $check_no = "$fetch_ck1[check_number]";
            if($fetch_ck1['check_date'] == '00/00/0000' ){$check_date = "";}else{ $check_date = "$fetch_ck1[check_date]"; }
            
            $mobile_banking_no = "";
            $style="border: 1px solid black";
           if(!empty($check_no)){  
            $title1 = "চেক ট্রানজেকশন";
            $title2 = "ব্যাংকের নামঃ $fetch_ck1[note]";
            $title3="চেক নম্বরঃ $check_no";
            $title4="চেক তারিখঃ $check_date";
            }else{  
            $title1 = "ব্যাংক ট্রানজেকশন" ;
            $title2 = "ব্যাংকের নামঃ $fetch_ck1[note]";
            $title3="ট্রানজেকশন নম্বরঃ $info_bank[account_number]";
            $title4="ট্রানজেকশন তারিখঃ $fetch_ck1[transectiondate]";
            }
        
        }else if($fetch_ck1['transection_by'] == 'Mobile-Banking'){

            $info_bank = SETUP::BANK_MOBILE_BANKING( $fetch_ck1['transection_by_id']);
            $bank_name = "";
            $check_no = "";
            $check_date = "";
            $mobile_banking_no = $info_bank['mobile_number'];
            $title1 = $info_bank['mobile_bank_name'];
            $title2 = "প্রেরণকারীর মোবাইল নম্বরঃ $fetch_ck1[note]";
            $title3="ট্রানজেকশন নম্বরঃ $mobile_banking_no";
            $title4="ট্রানজেকশন তারিখঃ $fetch_ck1[transectiondate]";
            $style="border: 1px solid black";
        }else{
            $bank_name = "";
            $check_no = "";
            $mobile_banking_no = "";
            $check_date = "";
            $title1 = "";
            $title2 = "";
            $title3="";
            $title4="";
            $style="";
        }


    if ($fetch_ck1['transection_to'] == 'Customer'){

        $info1 = SETUP::SETUP_CLIENT($fetch_ck1['transection_to_id']);

        $name = "Customer: " . $info1['client_array']['client_name'] ;
        $only_name= $info1['client_array']['client_name'];
        $only_mobile= $info1['client_array']['mobile'];
        $address="";
        $Transectionnow = $fetch_ck1['in_amount'];
        $copy_text = "কাস্টমার কপি";


        }else if ($fetch_ck1['transection_to'] == 'Employee'){
        $info1 = SETUP::SETUP_EMPLOYEEY($fetch_ck1['transection_to_id']);
        $name = "Employee: $info1[employee_prefix] $info1[code] $info1[name]";
        $only_name="$info1[name]";
        $only_mobile="$info1[mob_no]";
        $address="$info1[house] $info1[po_office] $info1[village]";
        $Transectionnow = "$fetch_ck1[out_amount]";
        $copy_text = "কর্মচারীর কপি";

        }else{
        $name ="No Name";
        $only_name= $transection_head_name;
        $only_mobile="";
        $address="";
        $Transectionnow = 0.00;
       if($fetch_ck1['transection_type'] == 'INCOME' ){
        $copy_text = "দাতার কপি";
       }else{
        $copy_text = "গ্রহিতার কপি";

       }
     

    
    }



        return array(
            'id' => $fetch_ck1['id'],
            'code' => $fetch_ck1['code'],
            'invoice_no' => $fetch_ck1['invoice_no'],
            'transection_type' => $fetch_ck1['transection_type'],
            'poster' => $fetch_ck1['poster'],
            'data_inserted_from' => $fetch_ck1['data_inserted_from'],
            'in_amount' => $fetch_ck1['in_amount'],
            'out_amount' => $fetch_ck1['out_amount'],
            'ladger_name' => $ladger_name,
            'ledger_id' => $fetch_ck1['ledger_id'],

            'transection_head_name' => $transection_head_name,
            'transection_head_id' => $fetch_ck1['transection_head_id'],
            'transection_to' => $fetch_ck1['transection_to'],
            'transection_to_id' => $fetch_ck1['transection_to_id'],
            'transection_by' => $fetch_ck1['transection_by'],
            'transection_by_id' => $fetch_ck1['transection_by_id'],
            'check_number' => $fetch_ck1['check_number'],
            'check_date' => $fetch_ck1['check_date'],
            'note' => $fetch_ck1['note'],
            'copy_text' => $copy_text,
            'details_of_transection_to' => $name,
            'only_name' => $only_name,
            'only_mobile' => $only_mobile,
            'address' => $address,
            'transection_now' => $Transectionnow,
            'transection_date' => $fetch_ck1['transection_date'],
            'transectiondate' => $fetch_ck1['transectiondate'],
        
            'bank_name' => $bank_name,
            'check_no' => $check_no,
            'mobile_banking_no' => $mobile_banking_no,
            'check_date' => $check_date,
            'title1' =>  $title1,
            'title2' =>  $title2,
            'title3' =>  $title3,
            'title4' =>  $title4,
            'style' => $style
            );



    }


    static function ACCOUNT_HEAD_SETUP($ID)
    {

        $ck1 = DB::conn()->prepare("SELECT *  FROM `setup_ac_head` where `id` = '".$ID."'");
        $ck1->execute();
        $fetch_ck1 = $ck1->fetch(PDO::FETCH_ASSOC);
    

        return array(
            'id' => $fetch_ck1['id'],
            'account_head' => $fetch_ck1['account_head'],
            'account_type' => $fetch_ck1['account_type'],
            'description' => $fetch_ck1['description'],
            'status' => $fetch_ck1['status']
    
            );
    
    
    
    }
    


    static function BANK_MOBILE_BANKING($ID)
    {

        $ck1 = DB::conn()->prepare("SELECT *  FROM `setup_mobile_banking` where `id` = '".$ID."'");
        $ck1->execute();
        $fetch_ck1 = $ck1->fetch(PDO::FETCH_ASSOC);


        return array(
            'id' => $fetch_ck1['id'],
            'mobile_bank_name' => $fetch_ck1['mobile_bank_name'],
            'mobile_number' => $fetch_ck1['mobile_number'],
            'description' => $fetch_ck1['description'],
            'status' => $fetch_ck1['status']

            );



    }



    static function BANK_SETUP($ID)
    {

        $ck1 = DB::conn()->prepare("SELECT *  FROM `setup_bank` where `id` = '".$ID."'");
        $ck1->execute();
        $fetch_ck1 = $ck1->fetch(PDO::FETCH_ASSOC);

        $ck2 = DB::conn()->prepare("SELECT sum(`in_amount`) - sum(`out_amount`)  AS `balance` FROM `account_transection` where  `transection_by` = 'Bank'  AND `transection_by_id` = '".$ID."'");
        $ck2->execute();
        $fetch1 = $ck2->fetch(PDO::FETCH_ASSOC);
        if(!empty($fetch1['balance'])){ $balance = $fetch1['balance'];}else{ $balance = 0.00 ;}



        return array(
            'id' => $fetch_ck1['id'],
            'bank_name' => $fetch_ck1['bank_name'],
            'brunch_name' => $fetch_ck1['brunch_name'],
            'account_number' => $fetch_ck1['account_number'],
            'account_name' => $fetch_ck1['account_name'],
            'description' => $fetch_ck1['description'],
            'bank_balance' => $balance,
            'status' => $fetch_ck1['status']

            );



    }



    static function ADMIN_SETUP($ID)
    {
    
        $qry = DB::conn()->prepare("SELECT * FROM `admin`  where `id` = '".$ID."' ");
        $qry->execute();
        $fetch_list = $qry->fetch(PDO::FETCH_ASSOC);
    
    
        return array(
            'id' => $fetch_list['id'],
            'username' => $fetch_list['username'],
            'dypricpt_pass' => $fetch_list['dypricpt_pass'],
            'photo' => $fetch_list['photo'],
            'hr_name' => $fetch_list['hr_name']
        );
    
    }


    static function SETUP_RENT_COLLECTION($room_no,$client_id,$rent_month)
{

    $qry = DB::conn()->prepare("SELECT * FROM `collect_rent` where `room_no` = '".$room_no."' AND DATE_FORMAT(`receive_date`, '%Y-%m') = '".$rent_month."' AND `client_id` = '".$client_id."' ");
    $qry->execute();
    $fetch_list = $qry->fetch(PDO::FETCH_ASSOC);


    return array(
        'collection_array' => $fetch_list
    );

}



static function SETUP_RENT($ROOM_NO)
{

    $qry = DB::conn()->prepare("SELECT * FROM `setup_rent`  where `room_no` = '".$ROOM_NO."' ");
    $qry->execute();
    $fetch_list = $qry->fetch(PDO::FETCH_ASSOC);


    return array(
        'room_array' => $fetch_list
    );

}



static function SETUP_CLIENT($ID)
{

    $qry = DB::conn()->prepare("SELECT * FROM `setup_client`  where `id` = '".$ID."' ");
    $qry->execute();
    $fetch_list = $qry->fetch(PDO::FETCH_ASSOC);


    return array(
        'client_array' => $fetch_list
    );

}


static function ASSAIN_CLIENT($ID)
{

    $qry = DB::conn()->prepare("SELECT * FROM `assign_unit`  where `id` = '".$ID."' ");
    $qry->execute();
    $fetch_list = $qry->fetch(PDO::FETCH_ASSOC);


    return array(
        'client_array' => $fetch_list
    );

}







static function SETUP_VOBON($ID)
{

    $qry = DB::conn()->prepare("SELECT * FROM `setup_vobon`  where `id` = '".$ID."' ");
    $qry->execute();
    $fetch_list = $qry->fetch(PDO::FETCH_ASSOC);
     
    return array(
        'id' => $fetch_list['id'],
        'vobon_name' => $fetch_list['vobon_name'],
        'unit_no' => $fetch_list['unit_no']
    );

}



    static function TOP_BAR()
    {

        
        $info_employee = SETUP::ADMIN_SETUP($_SESSION['RENT_COLL_SESS_MEMBER_ID']);

    

        $active_user_query = DB::conn()->prepare("SELECT * FROM `webpush_member`  WHERE `flag` = 1 ORDER BY `member_id` ASC");
        $active_user_query->execute();
        $fetch_user = $active_user_query->fetchAll(PDO::FETCH_ASSOC);
        $count_user = $active_user_query->rowCount();


       
   
$content = '<ul class="x-navigation x-navigation-horizontal x-navigation-panel hidden-print">';

$content .= '<li class="xn-icon-button">
<a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
</li>';

$content .= '<li class="xn-icon-button pull-right">
<a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>                        
</li> ';

$content .='<li class="xn-icon-button pull-right">
<a href="#"><span class="fa fa-comments"></span></a>
<div class="informer informer-danger">'.$count_user.'</div>
<div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="fa fa-comments"></span> Active User</h3>                                
        <div class="pull-right">
            <span class="label label-danger">'.$count_user.'</span>
        </div>
    </div>
    <div class="panel-body list-group list-group-contacts scroll" style="height: 200px;">';
       
    foreach($fetch_user AS $fetch_user_info) {
        $user_info = SETUP::ADMIN_SETUP($fetch_user_info['member_id']);

        $content .= '<a class="list-group-item">
        <div class="list-group-status status-online"></div>
        <img src="upload/employee_photo/'.$user_info['photo'].'" class="pull-left" alt="John Doe"/>
        <span class="contacts-title">'.$user_info['hr_name'].'</span>
       
    </a>';
    }
    $content .= '</div>     
    <div class="panel-footer text-center">
    </div>                            
</div>                        
</li>';







$content .= '</ul>';
        


    return array(
        'top_bar_content' => $content

        );



    }


    

static function SETUP_COMPANY($status)
{
    $query = DB::conn()->prepare("SELECT * FROM `setup_company`  WHERE `status` = '".$status."' ");
    $query->execute();
    $fetch = $query->fetch(PDO::FETCH_ASSOC);


    $header_content ='<div class="table-responsive"><table style="width:100%;" class="table datatable_simple">';

    $header_content .='<tr><th style="text-align:center"><img style="height:70px" src="upload/Company_Logo/'.$fetch['logo'].'"></th></tr>';
    $header_content .='<tr><td style="text-align:center"><b>'.$fetch['name'].'</b><br>Mobile '.$fetch['phone'].'<br>Email '.$fetch['address'].'<br>'.$fetch['address'].'</td></tr>';

    $header_content .='</table></div>';

    return array(
        'id' => $fetch['id'],
        'company_name' => $fetch['name'],
        'company_short_name' => $fetch['short_name'],
        'company_address' => $fetch['address'],
        'company_phone' => $fetch['phone'],
        'company_email' => $fetch['email'],
        'company_logo' => $fetch['logo'],
        'software_start_date' => $fetch['software_start_date'],
        'software_end_date' => $fetch['software_end_date'],
        'header_content' => $header_content,
        'lastupdate' => $fetch['lastupdate'],
        'time' => $fetch['time'],
        'status' => $fetch['status']
        );


}



static function MENU_PERMISSION($MENU_ID,$EMLOYEE_ID)
{

    $ck1 = DB::conn()->prepare("SELECT *  FROM `menu_permission` WHERE `employee_id` = '".$EMLOYEE_ID."' AND `menu_id` = '".$MENU_ID."'");
    $ck1->execute();
    $fetch_ck1 = $ck1->fetch(PDO::FETCH_ASSOC);

    if ($ck1->rowCount() > 0) { 

        $view_check = $fetch_ck1['view_check'];
        $edit_check = $fetch_ck1['edit_check'];

    }else{

        $view_check = '';
        $edit_check = '';
    }


    return array(
        'view_check' => $view_check,
        'edit_check' => $edit_check,

        );



}


static function SETUP_CODE_INSERT_DATA($table)
    {



    $pdo = DB::conn();
    $CODE = SETUP::SETUP_CODE($table);

    $data = [
		'code' => $CODE['code'],
        'status' => 'Done',
		'date' => date("Y-m-d"),
		'time' =>date("h:i:s a"),
		'poster' => $_SESSION['RENT_COLL_SESS_MEMBER_ID'],
		'lastupdate' => time()
	  ];

    $query =   CRUD::insert_data($pdo, $table, $data);
    $last_insert_id = $pdo->lastInsertId();



        return array(
            'last_id' => $last_insert_id,
            'related_code' => $CODE['code']

        );

    }


    static function SETUP_CODE($table)
    {

        $lc_fetch = DB::conn()->prepare("SELECT `code` FROM `{$table}`  where  `status` = 'Done'  ORDER BY `id` DESC ");
        $lc_fetch->execute();
        if ($lc_fetch->rowCount() > 0)
{
    $fetch_list = $lc_fetch->fetch(PDO::FETCH_ASSOC);
    $number =  $fetch_list['code'];
    $number++;
    $code =  str_pad($number, 4, "0", STR_PAD_LEFT);  //00002

    


}else{
    $code = '0001';
}

$invoice_no =  date("d-m-Y").'-' .$code;



        return array(
            'code' => $code,
            'invoice_no' => $invoice_no


        );

    }



}



CLASS CRUD {

    static function insert_data($conn,$table, $data, $check_duplicate = null) {
    
        // Check for duplicates
        if ($check_duplicate !== null) {
            $conditions = [];
            foreach ($check_duplicate as $key => $value) {
                $conditions[] = "$key = :$key";
            }
            $where_clause = implode(" AND ", $conditions);
            $stmt = $conn->prepare("SELECT COUNT(*) FROM $table WHERE $where_clause");
            foreach ($check_duplicate as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                return array(
                    'mess' => "Duplicate data found. Record not inserted.",
                    'last_id' => null
                );
            }
        }
    
        // Prepare the insert statement
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $stmt = $conn->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
    
        // Bind the values to the placeholders
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
    
        // Execute the insert statement
        if ($stmt->execute()) {
            $lastId = $conn->lastInsertId();
            $mess = "Record created successfully.";
        } else {
            $mess = "Error: " . implode(", ", $stmt->errorInfo());
            $lastId = null;
        }
    
        return array(
            'mess' => $mess,
            'last_id' => $lastId
        );
    }




      static function updateData($pdo, $tableName, $id, $columnValues) {
        // Prepare the UPDATE statement
        $columns = [];
        $placeholders = [];
        foreach ($columnValues as $column => $value) {
            $columns[] = $column . ' = :' . $column;
            $placeholders[':' . $column] = $value;
        }
        $stmt = $pdo->prepare('UPDATE ' . $tableName . ' SET ' . implode(', ', $columns) . ' WHERE id = :id');
    
        // Bind the values to the placeholders
        $placeholders[':id'] = $id;

        if ($stmt->execute($placeholders)) {
            // Print a success message if the update was successful
            $mess =  'Update successful';
        } else {
            // Print an error message if the update failed
            $mess=  'Update failed';
        }

        return array(
            'mess' => $mess

            );
    }




}


