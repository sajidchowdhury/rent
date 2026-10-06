<?php 


if($_GET['related_id'] == 'New'){

    $vobon_name = '';
    $related_id = 'new_id';
    $unit_no = '';
    }else{
    
    $DATA  = SETUP::SETUP_VOBON($_GET['related_id']);
    
    $vobon_name = $DATA['vobon_name'];
    $related_id =  $DATA['id'];
    $unit_no =  $DATA['unit_no'];

    }





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
                    <th>ভবনের নাম </th>
                    <td><input type="text" class="form-control" value="'.$vobon_name.'" name="vobon_name" id="vobon_name"></td>
                    
                 </tr>

                 <tr>
                 <th>ইউনিট সংখ্যা </th>
                 <td><input type="text" class="form-control" value="'.$unit_no.'" name="unit_no" id="unit_no"></td>
                 
              </tr>

             
    
                <tr>
                    
                    <td style="text-align:center;" colspan=2><input type="button" name="save_zone" id="save_zone" class="btn btn-primary" value="সেভ করুন "></td>
                </tr>
          </tbody> 
      </table>
      </div>
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
    <th>নং </th>
    <th>ভবনের নাম    </th>
    <th>ইউনিট সংখ্যা</th>
    <th>মোট ভাড়া </th>

    <th>Action</th>
    </thead>
        <tbody>'; 
$sl =1;
$total_rent = 0 ; 
$qry = DB::conn()->prepare("SELECT * FROM `setup_vobon`  ORDER BY `id` ASC");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
if ($qry->rowCount() > 0)
{
foreach($fetch_list AS $fetch) {

    $query =  DB::conn()->prepare("SELECT sum(rent) as total_rent FROM `setup_unit`  WHERE `vobon_id` = '".$fetch['id']."' ");
    $query->execute();
    $fetch2 = $query->fetch(PDO::FETCH_ASSOC);

    $total_rent += $fetch2['total_rent'] ; 

    $content .= '<tr>
    <th>'.$sl++.'</th>
    <th>'.$fetch['vobon_name'].'</th>
    <th>'.$fetch['unit_no'].'</th>
    <th>'.$fetch2['total_rent'].'</th>

    <td><a href="Setup/vobon_setup/'.$fetch['id'].'" ><i class="fa fa-edit danger"></i><a> | <a href="Setup/unit_setup/'.$fetch['id'].'" >Unit Details<a></td>
   </tr>';
}

}
$content .= '<tfoot>
<tr>
    <th colspan="3" style="text-align:right"><b>Total</b></th>
    <th>' . $total_rent . '</th>
    <th></th>
</tr>
</tfoot>';
              
$content .= '</tbody> 
  </table>
  </div>
  </div>
</div>

</div>
</div>';

print $content ;?>