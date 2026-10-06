<?php 


    $DATA = SETUP::ASSAIN_CLIENT($_GET['related_id']);


    
    $related_id = !empty($DATA['client_array']['id']) ? $DATA['client_array']['id'] : 'New' ;
    $client_id = !empty($DATA['client_array']['client_id']) ? $DATA['client_array']['client_id'] : '' ;
    $vobon_id = !empty($DATA['client_array']['vobon_id']) ? $DATA['client_array']['vobon_id'] : '' ;
    $unit_id = !empty($DATA['client_array']['unit_id']) ? $DATA['client_array']['unit_id'] : '' ;
    $aggrement_start = !empty($DATA['client_array']['aggrement_start']) ? $DATA['client_array']['aggrement_start'] : '' ;
    $aggrement_ends = !empty($DATA['client_array']['aggrement_ends']) ? $DATA['client_array']['aggrement_ends'] : '' ;
    $actual_rent = !empty($DATA['client_array']['actual_rent']) ? $DATA['client_array']['actual_rent'] : '' ;
    $rent = !empty($DATA['client_array']['rent']) ? $DATA['client_array']['rent'] : '' ;
    $advance_payment = !empty($DATA['client_array']['advance_payment']) ? $DATA['client_array']['advance_payment'] : '' ;
    $advance_payment_date = !empty($DATA['client_array']['advance_payment_date']) ? $DATA['client_array']['advance_payment_date'] : '' ;
    $gus_bill = !empty($DATA['client_array']['gus_bill']) ? $DATA['client_array']['gus_bill'] : '' ;
    $moylar_bill = !empty($DATA['client_array']['moylar_bill']) ? $DATA['client_array']['moylar_bill'] : '' ;
    $service_charge = !empty($DATA['client_array']['service_charge']) ? $DATA['client_array']['service_charge'] : '' ;
    $other_bill = !empty($DATA['client_array']['other_bill']) ? $DATA['client_array']['other_bill'] : '' ;
    $client_status = !empty($DATA['client_array']['client_status']) ? $DATA['client_array']['client_status'] : '' ;

$content = ' <div class="row">
<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data">
        
    <input type="hidden" name="related_id" id="related_id" value="'.$related_id.'">
    <div class="table-responsive">

        <table class="table table-hover table-condensed table-striped table-bordered">
            <tbody> 
            
            <tr>
            <th>ভাড়াটিয়া তালিকা             </th>
      
            <td>
            <select class="form-control select" id="client_id" name = "client_id"  data-live-search="true" >
<option value="">Select One</option>';
$qry = DB::conn()->prepare("SELECT * FROM `setup_client` where client_status = 'Active' ");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) { 
$content .= '<option ';
if($client_id == $fetch['id']){
    $content .= ' selected="selected"';
}else{
}
$content .= 'value="'.$fetch['id'].'">' .$fetch['client_name']. '</option>';

}
$content .='</select>
            </td>

                <tr>
                <th>ভবন</th>
                <td>
                <select class="form-control select" id="vobon_id" name = "vobon_id"  data-live-search="true" onchange="VobonWiseUnit(this.value)">
<option value="">Select One</option>';
$qry = DB::conn()->prepare("SELECT * FROM `setup_vobon` ");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) { 
    $content .= '<option ';
    if($vobon_id == $fetch['id']){
        $content .= ' selected="selected"';
    }else{
    }
    $content .= 'value="'.$fetch['id'].'">' .$fetch['vobon_name']. '</option>';

}
$content .='</select>
                </td>
         
                <th>ইউনিটের নাম </th>
                <td id="unit_details">
                <select class="form-control select" id="unit_id" name = "unit_id"  data-live-search="true" onchange="VobonWiseUnit(this.value)">
<option value="">Select One</option>';
$qry = DB::conn()->prepare("SELECT * FROM `setup_unit` ");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) { 
    $content .= '<option ';
    if($unit_id == $fetch['id']){
        $content .= ' selected="selected"';
    }else{
    }
    $content .= 'value="'.$fetch['id'].'">' .$fetch['unit_name']. '</option>';

}
$content .='</select>
                </td>
                </tr>
           
            
        
                <tr>
                <th>জেনারাল রেন্ট </th>
                <td><input type="text" READONLY class="form-control text-danger" value="'.$actual_rent.'" name="actual_rent" id="actual_rent"></td>


                <th>ধার্যকৃত রেন্ট </th>
                <td><input type="number"  class="form-control " value="'.$rent.'" name="rent" id="rent" ></td>
                </tr>

                <tr>
                <th>এগ্রিমেন্ট শুরু </th>
                <td><input type="text" class="datepicker form-control" value="'.$aggrement_start.'" name="aggrement_start" id="aggrement_start"></td>


                <th>এগ্রিমেন্ট শেষ </th>
                <td><input type="text" class="datepicker form-control" value="'.$aggrement_ends.'" name="aggrement_ends" id="aggrement_ends"></td>
                </tr>

                <tr>
                <th>এডভান্স </th>
                <td><input type="number" class="form-control" value="'.$advance_payment.'" name="advance_payment" id="advance_payment"></td>


                <th>এডভান্স নেয়ার তারিখ  </th>
                <td><input type="text" class="datepicker form-control" value="'.$advance_payment_date.'" name="advance_payment_date" id="advance_payment_date"></td>
                </tr>

                <tr>
                <th>গ্যাস বিল </th>
                <td><input type="number" class="form-control" value="'.$gus_bill.'" name="gus_bill" id="gus_bill"></td>


                <th>ময়লার বিল </th>
                <td><input type="number" class="form-control" value="'.$moylar_bill.'" name="moylar_bill" id="moylar_bill"></td>
                </tr>

                <tr>
                <th>সার্ভিস চার্জ </th>
                <td><input type="number" class="form-control" value="'.$service_charge.'" name="service_charge" id="service_charge"></td>


                <th>অন্যান্য বিল </th>
                <td><input type="number" class="form-control" value="'.$other_bill.'" name="other_bill" id="other_bill"></td>
                </tr>

                <tr>
                <th>ভাড়াটিয়ার অবস্তা  </th>
                <td>
                <select class="form-control select" id="client_status" name="client_status">
                <option '; if($client_status == 'Active' ) { $content .= ' selected="selected" '; }else{} $content .= ' value ="Active" > আছে </option>';
                $content .= '<option ';  if($client_status == 'Deactive' ) { $content .= ' selected="selected" '; }else{ } $content .= ' value ="Deactive" > চলে গেছে 
                </option>
                </select>
                </td>
           
                    <td  ><input type="button" name="save_assaign_client" id="save_assaign_client" class="btn btn-primary" value="সেভ করুন "></td>
                </tr>
          </tbody> 
      </table>    </div>
    </form>  
            
    </div>

</div>
</div>';


print $content ;?>