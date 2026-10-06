<?php 

$qry1 = DB::conn()->prepare("SELECT A.id  as assign_unit_id, A.*,B.*,C.unit_name,D.vobon_name FROM `assign_unit` A 
JOIN setup_client B On (A.client_id = B.id) 
JOIN setup_unit C ON (A.unit_id = C.id)   
JOIN setup_vobon D ON (A.vobon_id = D.id)    
where A.id = '".$_GET['related_id']."' ");
$qry1->execute();
$fetch = $qry1->fetch(PDO::FETCH_ASSOC);

$total_rent =  number_format((float)( $fetch['rent'] + $fetch['gus_bill'] + $fetch['moylar_bill'] + $fetch['other_bill'] + $fetch['service_charge'] ), 2, '.', '') ;

$qry2 = DB::conn()->prepare("SELECT count(rent_month) as total_month FROM `collect_rent` A 
where A.client_id = '".$fetch['client_id']."' AND A.assign_unit_id = '".$fetch['assign_unit_id']."' GROUP BY A.assign_unit_id ");
$qry2->execute();
$fetch2 = $qry2->fetch(PDO::FETCH_ASSOC);

$total_month  = !empty($fetch2['total_month']) ? $fetch2['total_month'] : 0 ;
$total_rent_tk = $total_month*$total_rent; 



$qry3 = DB::conn()->prepare("SELECT sum(in_amount) as total_income FROM `account_transection` A 
where A.transection_to_id = '".$fetch['client_id']."' AND A.transection_to = 'Customer'  AND A.data_inserted_from = 'RENT COLLECTION'  GROUP BY A.transection_to_id ");
$qry3->execute();
$fetch3 = $qry3->fetch(PDO::FETCH_ASSOC);


$qry33 = DB::conn()->prepare("SELECT sum(out_amount) as due_adj FROM `account_transection` A 
where A.transection_to_id = '".$fetch['client_id']."' AND A.transection_to = 'Customer'  AND A.data_inserted_from = 'DUE ADJUSTMENT'  GROUP BY A.transection_to_id ");
$qry33->execute();
$fetch33 = $qry33->fetch(PDO::FETCH_ASSOC);

$due_adj  = !empty($fetch33['due_adj']) ? $fetch33['due_adj'] : 0 ;


$total_rent_collection  = !empty($fetch3['total_income']) ? $fetch3['total_income'] : 0 ;
$due = $total_rent_tk  - $total_rent_collection ;

$qry4 = DB::conn()->prepare("SELECT sum(out_amount) as total_adj FROM `account_transection` A 
where A.transection_to_id = '".$fetch['client_id']."' AND A.transection_to = 'Customer'  AND A.data_inserted_from = 'ADVANCE ADJUSTMENT'  GROUP BY A.transection_to_id ");
$qry4->execute();
$fetch4 = $qry4->fetch(PDO::FETCH_ASSOC);

$total_adj  = !empty($fetch4['total_adj']) ? $fetch4['total_adj'] : 0 ;
$advance_paid  = !empty($fetch['advance_payment']) ? $fetch['advance_payment'] : 0 ;

$left =  $advance_paid - $total_adj   ; 

$new_due = $due - $left  ; 

$actual_due = $new_due + $due_adj ; 
$content = '  <div class="row">
<div class="col-md-12">
    

<input type="hidden" id="client_id" value="'.$fetch['client_id'].'" >
<input type="hidden" id="related_id" value="'.$_GET['related_id'].'" >

    <form class="form-horizontal">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">রুম খালি করুন: <strong>'.$fetch['vobon_name'].'</strong> '.$fetch['unit_name'].'</h3>
            <ul class="panel-controls">
                <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
            </ul>
        </div>
        <div class="panel-body">
           
        </div>
        <div class="panel-body">                                                                        
            
        <div class="col-md-6">



            <div class="form-group">
                <label class="col-md-3 col-xs-12 control-label"></label>
                <div class="col-md-6 col-xs-12">                                            
                    <div class="input-group">
                        <span class="input-group-addon">ধার্যকৃত রেন্ট (a)</span>
                        <input type="number"  READONLY class="form-control text-danger" value="'.$fetch['rent'].'" />
                    </div>                                            
                </div>
            </div>
            

            <div class="form-group" style="display:none">
                <label class="col-md-3 col-xs-12 control-label"></label>
                <div class="col-md-6 col-xs-12">                                            
                    <div class="input-group">
                        <span class="input-group-addon">গ্যাস বিল (b)</span>
                        <input type="number"  READONLY class="form-control text-danger" value="'.$fetch['gus_bill'].'" />
                    </div>                                            
                </div>
            </div>


            <div class="form-group" style="display:none">
                <label class="col-md-3 col-xs-12 control-label"> </label>
                <div class="col-md-6 col-xs-12">                                            
                    <div class="input-group">
                        <span class="input-group-addon">ময়লার বিল (c)</span>
                        <input type="number"  READONLY class="form-control text-danger" value="'.$fetch['moylar_bill'].'" />
                    </div>                                            
                </div>
            </div>

            <div class="form-group" style="display:none">
                <label class="col-md-3 col-xs-12 control-label"></label>
                <div class="col-md-6 col-xs-12">                                            
                    <div class="input-group">
                        <span class="input-group-addon">সার্ভিস চার্জ (d)</span>
                        <input type="number"  READONLY class="form-control text-danger" value="'.$fetch['service_charge'].'" />
                    </div>                                            
                </div>
            </div>

            <div class="form-group" style="display:none">
                <label class="col-md-3 col-xs-12 control-label"> </label>
                <div class="col-md-6 col-xs-12">                                            
                    <div class="input-group">
                        <span class="input-group-addon">অন্যান্য বিল (e)</span>
                        <input type="number"  READONLY class="form-control text-danger" value="'.$fetch['other_bill'].'" />
                    </div>                                            
                </div>
            </div>

            <div class="form-group">
            <label class="col-md-3 col-xs-12 control-label"> </label>
            <div class="col-md-6 col-xs-12">                                            
                <div class="input-group">
                    <span class="input-group-addon">মোট ভাড়া </span>
                    <input type="number"  READONLY class="form-control text-danger" value="'.$total_rent.'" />
                </div>                                            
            </div>
        </div>

        <div class="form-group">
        <label class="col-md-3 col-xs-12 control-label"> </label>
        <div class="col-md-6 col-xs-12">                                            
            <div class="input-group">
                <span class="input-group-addon">মোট মাস (g)</span>
                <input type="number"  READONLY class="form-control text-danger" value="'.$total_month.'" />
            </div>                                            
        </div>
    </div>

    <div class="form-group">
    <label class="col-md-3 col-xs-12 control-label">  </label>
    <div class="col-md-6 col-xs-12">                                            
        <div class="input-group">
            <span class="input-group-addon">মোট টাকা h = (f * g) </span>
            <input type="number"  READONLY class="form-control text-danger" value="'.$total_rent_tk.'" />
        </div>                                            
    </div>
</div>



<div class="form-group">
<label class="col-md-3 col-xs-12 control-label">  </label>
<div class="col-md-6 col-xs-12">                                            
    <div class="input-group">
        <span class="input-group-addon">ভাড়া আদায় (i)</span>
        <input type="number"  READONLY class="form-control text-danger" value="'.$total_rent_collection .'" />
    </div>                                            
</div>
</div>


<div class="form-group">
<label class="col-md-3 col-xs-12 control-label">  </label>
<div class="col-md-6 col-xs-12">                                            
    <div class="input-group">
        <span class="input-group-addon">বকেয়া j =  (h - i) </span>
        <input type="number"  READONLY class="form-control text-danger" value="'.$due.'" />
    </div>                                            
</div>
</div>

<div class="form-group">
<label class="col-md-3 col-xs-12 control-label">  </label>
<div class="col-md-6 col-xs-12">                                            
    <div class="input-group">
        <span class="input-group-addon">এডভান্স প্রদান (k)</span>
        <input type="number"  READONLY class="form-control text-danger" value="'.$advance_paid.'" />
    </div>                                            
</div>
</div>


<div class="form-group">
<label class="col-md-3 col-xs-12 control-label">  </label>
<div class="col-md-6 col-xs-12">                                            
    <div class="input-group">
        <span class="input-group-addon">এডভান্স কর্তন (l)</span>
        <input type="number"  READONLY class="form-control text-danger" value="'.$total_adj.'" />
    </div>                                            
</div>
</div>
            

<div class="form-group">
<label class="col-md-3 col-xs-12 control-label">  	</label>
<div class="col-md-6 col-xs-12">                                            
    <div class="input-group">
        <span class="input-group-addon">এডভান্স বাকি থাকে  m = (k - l )</span>
        <input type="number"  READONLY class="form-control text-danger" value="'.$left.'" />
    </div>                                            
</div>
</div>


<div class="form-group">
<label class="col-md-3 col-xs-12 control-label"> </label>
<div class="col-md-6 col-xs-12">                                            
    <div class="input-group">
        <span class="input-group-addon">নতুন বকেয়া n = (j - m)</span>
        <input type="number"  READONLY class="form-control text-danger" value="'.$new_due.'" />
    </div>                                            
</div>
</div>

<div class="form-group">
<label class="col-md-3 col-xs-12 control-label"> </label>
<div class="col-md-6 col-xs-12">                                            
    <div class="input-group">
        <span class="input-group-addon">ডিউ এডজাস্টমেন্ট  o </span>
        <input type="number"  READONLY class="form-control text-danger" value="'.$due_adj.'" />
    </div>                                            
</div>
</div>


<div class="form-group">
<label class="col-md-3 col-xs-12 control-label"> </label>
<div class="col-md-6 col-xs-12">                                            
    <div class="input-group">
        <span class="input-group-addon">মোট বকেয়া  m = ( n + o ) </span>
        <input type="number"  READONLY class="form-control text-danger" value="'.$actual_due.'" />
    </div>                                            
</div>
</div>


</div>

<div class="col-md-6">

<div class="table-responsive">

<table class="table table-hover table-condensed table-striped table-bordered">
            <tbody> 
            
             <tr>
             <th colspan="4" style="text-align:center">**** ভাড়াটিয়ার বিবরণ  ****</th>
             </tr>


            <tr>
            <th  class="form-group  control-label">নাম</th>
            <td>'.$fetch['client_name'].'</td>
          
     
        <th  class="form-group  control-label">ফোন</th>
        <td>'.$fetch['mobile'].'</td>
                    
                    
                </tr>

                <th  class="form-group  control-label">NID </th>
                <td>'.$fetch['nid'].'</td>
                
                
     
                <th  class="form-group  control-label">ফেমেলি মেম্বার 	</th>
                <td>'.$fetch['family_member'].'</td>
                </tr>

                

             
          </tbody> 
      </table>
      </div>

<div class="form-group">
<label class="col-md-3 col-xs-12 control-label">মোট বকেয়া </label>
<div class="col-md-6 col-xs-12">                                                                                            
<input type="number" id="actual_due" READONLY class="form-control text-danger" value="'.$actual_due .'" />

</div>
</div>





<div class="form-group">
<label class="col-md-3 col-xs-12 control-label"> বকেয়া আদায়ের  ধরন </label>
<div class="col-md-6 col-xs-12">                                                                                            
    <select id="collection_type" class="form-control select" onchange="DueCollectionType(this.value)">
    <option value="" >একটি সিলেক্ট করুন </option>
    <option value="NOGOD-GROHON">নগদ গ্রহণ </option>
    <option value="FROM-CLIENT"> নতুন ভাড়াটিয়া থেকে আদায় </option>
        
    </select>
</div>
</div>

<div class="form-group">
<label class="col-md-3 col-xs-12 control-label" > <b id="type_text"></b>  </label>
<div class="col-md-6 col-xs-12" >                                                                                            
   <div id="type_content"></div>
</div>
</div>


<div class="form-group">
<label class="col-md-3 col-xs-12 control-label" > বিদায় তারিখ  </label>
<div class="col-md-6 col-xs-12" >                                                                                            
<input type="text" class="datepicker form-control" id="farewel_date">
</div>
</div>


<div class="form-group">
<label class="col-md-3 col-xs-12 control-label">নোট</label>
<div class="col-md-6 col-xs-12">                                            
    <textarea class="form-control" id="notes" rows="5"></textarea>
</div>
</div>

<div class="form-group">
<label class="col-md-3 col-xs-12 control-label" > </label>
<div class="col-md-6 col-xs-12" >                                                                                            
<input type="button" name="remove_client" id="remove_client" class="btn btn-primary" value="সেভ করুন ">
</div>
</div>



</div>


</div>





        <div class="panel-footer">
          
        </div>
    </div>
    </form>
    
</div>




</div>                    
';


print $content ;?>