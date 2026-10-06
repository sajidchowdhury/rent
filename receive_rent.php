<?php
include('function_query.php');


$content = '<input type="hidden"  id="related_id" value="'.$_GET['related_id'].'" >';

$explode = explode("@",$_GET['related_id']);
$client_id = $explode[0]; 
$rent_month = $explode[1]; 
$rent_year = $explode[2]; 
$collection_ids = $explode[3]; 
$invoice_date = $explode[4]; 


$lastDateOfPreviousMonth = FIND::getLastDateOfPreviousMonth($invoice_date);

$collection_id_array = explode(',', $collection_ids);
$collection_id = implode(',', $collection_id_array);      

$query =  DB::conn()->prepare("SELECT A.client_name  FROM `setup_client` A  WHERE A.`id` = '".$client_id."' ");
$query->execute();
$fetch = $query->fetch(PDO::FETCH_ASSOC);


$previous_due = FIND::CUSTOMER_PREVIOUS_DUE($client_id,$lastDateOfPreviousMonth);



$content .= ' 
 <div class="row">
<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data">
        <div class="table-responsive">

        <table class="table table-hover table-condensed table-striped table-bordered">
            <tbody> 
            <input type="hidden"  id="client_id" value="'.$client_id.'" >
            <input type="hidden"  id="rent_month" value="'.$rent_month.'" >
            <input type="hidden"  id="rent_year" value="'.$rent_year.'" >

                <tr>
                <td>কোন মাসের ভাড়া ?</td>
                <td><input type="text" READONLY class="form-control text-danger" value="'.$rent_month.' '.$rent_year.'" ></td>
                                           
                <td>ভাড়াটিয়ার নাম</td>
                <td><input type="text" READONLY class="form-control text-danger" value="'.$fetch['client_name'].' " ></td>
                </tr>

                </tbody>
             </table>

             <table class="table table-condensed table-bordered">
             <tbody> 

            <tr>
                <td colspan="10" style="text-align:center;color:red"> *** ভাড়া কালেকশন রশিদ *** </td>
                
            </tr>



            <tr>
            <th>ভবন/ইউনিট</th>
            <th>ধার্যকৃত রেন্ট</th>
            <th>গ্যাস বিল</th>
            <th>ময়লার বিল</th>
            <th>সার্ভিস চার্জ</th>
            <th>অন্যান্য বিল</th>
            <th>মোট টাকা</th>
            <th>এডভান্স </th>
            <th>এডভান্স কর্তন </th>
            <th>সর্বমোট</th>

        
            </tr>';
            $sl = 0 ; 
            $sub_total = 0 ;
            $total_sub_total = 0 ;
            $qry2 = DB::conn()->prepare("SELECT A.*,C.unit_name,D.vobon_name,B.advance_payment
            FROM `collect_rent`  A   
            JOIN assign_unit B ON (A.assign_unit_id = B.id)
            JOIN setup_unit C ON (B.unit_id = C.id)   
            JOIN setup_vobon D ON (B.vobon_id = D.id)   
            WHERE A.id IN  ({$collection_id})
            ");
            $qry2->execute();
            $fetch_list2 = $qry2->fetchAll(PDO::FETCH_ASSOC);
            foreach($fetch_list2 AS $fetch2) {

                $sub_total = number_format((float)($fetch2['rent'] + $fetch2['gus_bill'] + $fetch2['moylar_bill'] + $fetch2['service_charge'] + $fetch2['other_bill'] ), 2, '.', '') ;


                $content .= ' <tr>
                <td >'.$fetch2['vobon_name'].' '.$fetch2['unit_name'] . '</td>
                <td>'.$fetch2['rent'].' </td>
                <td>'.$fetch2['gus_bill'].' </td>
                <td>'.$fetch2['moylar_bill'].' </td>
                <td>'.$fetch2['service_charge'].' </td>
                <td>'.$fetch2['other_bill'].' </td>
                <td><input type="number" READONLY id="sub_total'.$sl.'"  value="'.$sub_total.'" class="form-control text-danger"></td>
                <td>'.$fetch2['advance_payment'].' </td>';
            
                $content .= '<td><input type="number" id="advance_adjustment'.$sl.'" value="0.00" onkeyup="calculateAdvance(\'sub_total'.$sl.'\',\'advance_adjustment'.$sl.'\',\'total_after_advance'.$sl.'\');" class="form-control"> <input type="hidden" id="collect_id'.$sl.'" value="'.$fetch2['id'].'" > </td>';



                $content .= '
                <td><input type="number" READONLY id="total_after_advance'.$sl.'"  value="'.$sub_total.'" class="form-control text-danger"></td>

                </tr>';

                $total_sub_total += $sub_total ; 
                $sl++; 
            }

            $due = number_format((float)($total_sub_total + $previous_due ), 2, '.', '') ;

            $content .= '
            <input type="hidden"  id="total_shop_number"  value="'.$sl.'" >
            <input type="hidden"  id="previous_due"  value="'.$previous_due.'" >

            <tr>
            <th colspan="9" style="text-align:right">মোট টাকা </th>
            <th><input type="number" READONLY id="total_sub_total"  value="'.$total_sub_total.'" class="form-control text-danger"></th>
            </tr>

            <tr>
            <th colspan="9" style="text-align:right">পূর্বের বকেয়া </th>
            <th ><input type="number" READONLY id="previous_due"  value="'.$previous_due.'" class="form-control text-danger"></th>

            </tr>

            <tr>
            <th colspan="9" style="text-align:right">সর্বমোট বকেয়া</th>
            <th ><input type="number" READONLY id="due"  value="'.$due.'" class="form-control text-danger"></th>

            </tr>

            <tr>
            <th colspan="9" style="text-align:right"> নগদ গ্রহণ </th>
            <th><input type="number" id="receive_now" value="0.00" onkeyup="calculateCollection(\'due\',\'receive_now\');" class="form-control"></th>
            </tr>


            <tr>
            <th colspan="9" style="text-align:right">নতুন বকেয়া </th>
            <th ><input type="number" READONLY id="grand_total"  value="0.00" class="form-control text-danger"></th>
            </tr>

            <tr>
            <th colspan="9" style="text-align:right">লেনদেনের তারিখ </th>
            <th><input type="text"  id="receive_date"  value="'.date("d-m-Y").'" class="datepicker form-control text-danger"></th>
            </tr>

                <tr>
                <td colspan="9"></td>
                    <td > <input type="button" onclick="CreateRecipt()" name="create_recipt" id="create_recipt" class="btn btn-primary" value="রশিদ সেভ করুন"></td>
                </tr>           
          </tbody> 
      </table>
      </div>
    </form>  
            
    </div>

</div>
</div>';


print $content ;
?>
