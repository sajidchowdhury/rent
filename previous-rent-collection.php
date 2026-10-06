<?php 
$vobon_id = '';
$unit_id  = '';
$content = ' <div class="row">
<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data">
        
        <table class="table table-hover table-condensed table-striped table-bordered">
            <tbody> 
            
                <tr>
                <th>ভবন</th>
                <td>
                <select class="form-control select" id="get_client_id" name = "get_client_id"  data-live-search="true" >
<option value="All">All </option>';
$qry = DB::conn()->prepare("SELECT A.id,A.client_name,A.mobile FROM `setup_client` A where A.client_status = 'Active' ");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) { 
    $content .= '<option value="'.$fetch['id'].'">' .$fetch['client_name']. ' >>  ' .$fetch['mobile']. '</option>';

}
$content .='</select>
                </td>
                </tr>
               
           
            
                <tr>
                <th></th>
                    <td><input type="button" name="search_invoice" id="search_invoice" class="btn btn-primary" value="ইনভয়েস খুঁজুন"></td>
                </tr>           
          </tbody> 
      </table>
    </form>  
            
    </div>

</div>
</div>';



$content .= '<div id="get_pending_data">';

$content .= '<div class="row">
<div class="col-md-12">

<div class="panel panel-default">
    <div class="panel-body" id="load_table">
   
    <div class="table-responsive">
    <table class="table datatable">
    <thead>
    
    <th>নং</th>
    <th>ভাড়াটিয়ার নাম</th>
    <th>যোগাযোগ</th>
    <th>কোন মাসের ভাড়া ?</th>
    <th>একশন</th>

    </thead>
        <tbody>'; 
$sl =1;
$qry = DB::conn()->prepare("SELECT A.date,A.id,A.client_id,A.rent_month,A.rent_year, GROUP_CONCAT(A.id) as collection_id,GROUP_CONCAT(CONCAT(E.vobon_name, '-', D.unit_name) SEPARATOR '@') AS unit_vobon_names,B.client_name,B.mobile,date_format(A.date, '%d-%m-%Y') AS `invoice_date`
FROM `collect_rent` A
JOIN setup_client B ON (A.client_id = B.id)
JOIN assign_unit C ON (A.assign_unit_id = C.id)
JOIN setup_unit D ON (C.unit_id = D.id)
JOIN setup_vobon E ON (D.vobon_id = E.id)
WHERE A.pay_later = 1
GROUP BY A.client_id,A.rent_year,A.rent_month
ORDER BY A.client_id,A.rent_year,A.rent_month DESC");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach($fetch_list AS $fetch) {
    
    $data_value = $fetch['client_id']  . "@" . $fetch['rent_year']. "@" . $fetch['rent_month'] . "@" . $fetch['collection_id'] . "@" . $fetch['date']; 
    $image_name = "generate/" . $fetch['client_id'] . "_client_phone_call_qr_code.png" ;


    $content .= '<tr><input type="" style="display:none;" id="id_'.$fetch['id'].'" value="'.$fetch['id'].'" >
    <input type="" style="display:none;" id="dataArray_'.$fetch['id'].'" value="'.$data_value.'" >

    <th>'.$sl++.'</th>';

    $content .= '<td><b class="text-danger">'.$fetch['client_name'].' '.$fetch['mobile'].' </b><br><b >ভবন/ইউনিট:</b><br>' ; 
    $unit_vobon_names = explode('@', $fetch['unit_vobon_names']);
 
    foreach ($unit_vobon_names as $value) {
        $content .= '<p style="color:blue">' . $value . '<br></p>'; // Add line break after each value
    }

    
    $content .= '</td>'; 

    $content .= '
    <td>
    <a class="text-danger" href="tel:'.$fetch['mobile'].'"><img style="height:100px" src="'.$image_name.'"> </a>
    </td>
    <th>'.$fetch['rent_month'].' '.$fetch['rent_year'].' <br> ইনভেস তৈরি : '.$fetch['invoice_date'].' </th>
    <td>
    
    <a id="#exampleModalCenter" class="btn btn-success btn-rounded btn-sm" onclick="modal_wihout_refresh(\'receive_rent\',\'dataArray_'.$fetch['id'].'\',\'ভাড়া কালেকশন \');"> রিসিভ করুন </a>
    

    <button type="button" class="btn btn-danger btn-rounded btn-sm" onclick="RemoveRecipt(\''.$data_value.'\');">বাতিল করুন</button>     

    </td>
   </tr>';
}


            
              
$content .= '</tbody> 
  </table>
  </div>
  </div> 
</div>

</div>
</div>';


$content .= '</div>';



print $content ;?>
