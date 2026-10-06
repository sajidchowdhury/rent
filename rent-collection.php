<?php 


$month = date("m");
$year  = date("Y");
$month_year = date('F' . '-' . 'Y', mktime(0, 0, 0, $month - 1, 1, $year));

$invoice_date = date("01-m-Y");
$month  = ['January','February','March','April','May','June','July','August','September','October','November','December'] ;
$year = [2023,2024];

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
                <th>কোন মাসের ভাড়া ?</th>
                <td id="unit_details">
                <div class="form-group">                                        
               
                <div class="col-md-4">
                <input type="text"  READONLY id="rent_month_year"  value="'.$month_year.'" class="form-control text-danger">
       
                </div>
                                                               
            </div>
                </td>
                </tr>

                <tr>
            <th >ইনভয়েস তারিখ </th>
            <th ><input type="text"  id="invoice_date"  value="'.$invoice_date.'" class="datepicker form-control text-danger"></th>
            </tr>


                <tr>
                <th></th>
                    <td><input type="button" name="create_invoice" id="create_invoice" class="btn btn-primary" value="ইনভয়েস তৈরি করুন"></td>
                </tr>           
          </tbody> 
      </table>
    </form>  
            
    </div>

</div>
</div>';



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
WHERE A.collection_status = 'Pending' AND A.pay_later = 0
GROUP BY A.client_id,A.rent_year,A.rent_month
ORDER BY A.client_id ASC");
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
    

    <button type="button" class="btn btn-info btn-rounded btn-sm" onclick="CantPayNow(\''.$data_value.'\');">ভাড়া দিতে অপারগ </button>     


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



print $content ;?>
