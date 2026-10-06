<?php 

    $DATA  = SETUP::SETUP_VOBON($_GET['related_id']);
    $vobon_name = $DATA['vobon_name'];
    $related_id =  $DATA['id'];
    $unit_no =  $DATA['unit_no'];


$content = ' <div class="row">
<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data">
        
    <input type="hidden" name="related_id" id="related_id" value="'.$related_id.'">

        <table class="table table-hover table-condensed table-striped table-bordered">
            <tbody> 
            <tr><th colspan="2" style="text-align:center">ভবনের নাম '.$vobon_name.'</th> </tr>
            <tr><th colspan="2" style="text-align:center">ইউনিট সংখ্যা '.$unit_no.' </th></tr>
            <input type="hidden" name="total_item" id="total_item" value="'.$unit_no.'"/>


            <tr>
            <td style="text-align:center;" colspan=2> <input type="button" onclick="SaveUnit();" class="btn btn-info"  id="action_bar"   value="সেভ করুন">
            </td>
        </tr>
          </tbody> 
      </table>
    </form>  
            
    </div>

</div>
</div>';


$content .= ' <div class="row">
<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data">
        
    <input type="hidden" name="related_id" id="related_id" value="'.$related_id.'">
    <div class="table-responsive">

        <table class="table table-hover table-condensed table-striped table-bordered">
            <tbody> ';
            $sl = 1;

            $content .= '<tr>
            <th>No.</th> 
            <th>ইউনিটের নাম </th>
            <th>ভাড়া</th>
            <th>নোট</th>
            <th> রুম স্ট্যাটাস </th>
    
            </tr>';


    $query = DB::conn()->prepare("SELECT *  FROM setup_unit where vobon_id = '".$related_id."'  ORDER BY `id` ASC");
    $query->execute();
    $fetch_list = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fetch_list as $fetch) { 
          
        $find_status =  FIND::RECENT_STATUS($fetch['id']);

        $content .= '<tr '. $sl .'" id="tr_no'. $sl .'">
        <input value="'.$fetch['id'].'"  type="hidden" name="unit_id[]" id="unit_id'.$sl.'" data-srno="'.$sl.'" class="form-control unit_id"/>
        <th>'.$sl.'</th> 
        <th><input value="'.$fetch['unit_name'].'"  type="text" name="unit_name[]" id="unit_name'.$sl.'" data-srno="'.$sl.'" class="form-control unit_name"/></th> 
       <th><input value="'.$fetch['rent'].'"  type="text" name="rent[]" id="rent'.$sl.'" data-srno="'.$sl.'" class="form-control rent"/></th> 
      <th><input value="'.$fetch['notes'].'"  type="text" name="notes[]" id="notes'.$sl.'" data-srno="'.$sl.'" class="form-control notes"/></th> 
        <th style="background-color:'.$find_status.';color:black">  </th>

        </tr>';
 $sl++; 
     }



          $content .= '</tbody> 
      </table></div>
    </form>  
            
    </div>

</div>
</div>';

print $content ;?>