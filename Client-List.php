<?php 

$content = '<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-body" id="load_table">
    <div class="table-responsive">

    <table class="table datatable">
    <thead>
    
    <th>নং</th>
    <th>নাম</th>
    <th>ফোন</th>
    <th>ভবন/ইউনিট</th>
    <th>ধার্যকৃত রেন্ট	</th>
    <th>এডিট</th>

    </thead>
        <tbody>'; 
$sl =1;
$qry = DB::conn()->prepare("SELECT D.id as ASSAIN_ID ,A.*,B.vobon_name , C.unit_name ,D.rent
FROM `setup_client`  A 
LEFT JOIN assign_unit D ON (A.id = D.client_id) 
LEFT JOIN setup_vobon B ON (D.vobon_id = B.id) 
LEFT JOIN setup_unit C ON (D.unit_id = C.id) ORDER BY D.`vobon_id` ASC ");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach($fetch_list AS $fetch) {
   
    $content .= '<tr>
    <th>'.$sl++.'</th>

    <th>'.$fetch['client_name'].'</th>
    <th>'.$fetch['mobile'].'</th>';
    if($fetch['client_status'] == 'Active'  ){
 
      if( !empty($fetch['vobon_name']) ){

      $content .= ' <th>'.$fetch['vobon_name'].' '.$fetch['unit_name'].' [ <a class="text-danger" href="Setup/remove_client/'.$fetch['ASSAIN_ID'].'" >রুম খালি করুন <a> ]</th>';
      }else{
        $content .= ' <th> ইউনিট দেয়া হয় নাই            </th>';

      }
    }else{
      $content .= '<th> G O N E</th>';
    }
    $content .= '<th>'.$fetch['rent'].'</th>
    <td>
    <a href="Setup/client/'.$fetch['id'].'" ><i class="fa fa-edit danger"></i><a> | ';

    if( !empty($fetch['vobon_name']) ){

     $content .= '<a class="text-success" href="Setup/assain_client/'.$fetch['ASSAIN_ID'].'" >ভাড়াটিয়ার ইউনিট <a> ';
    }
     
     $content .= ' </td>
   </tr>';
}


            
              
$content .= '</tbody> 
  </table></div>
  </div>
        
</div>

</div>
</div>';

print $content ;?>