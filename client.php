<?php 

if($_GET['related_id'] == 'New'){

    $client_name = '';
    $mobile = '';
    $nid = '';
    $family_member = '';
    $related_id = $_GET['related_id'];


    }else{
    
$DATA  = SETUP::SETUP_CLIENT($_GET['related_id']);
$client_name = $DATA['client_array']['client_name'];
$mobile = $DATA['client_array']['mobile'];

$nid = $DATA['client_array']['nid'];
$family_member = $DATA['client_array']['family_member'];
$related_id = $DATA['client_array']['id'];


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
            <th  class="form-group  control-label">নাম</th>
            <td><input type="text" class="form-control" value="'.$client_name.'" name="client_name" id="client_name"></td>
          
     
        <th  class="form-group  control-label">ফোন</th>
        <td><input type="text" class="form-control" value="'.$mobile.'" name="mobile" id="mobile"></td>
                    
                    
                </tr>

                <th  class="form-group  control-label">NID </th>
                <td><input type="text" class="form-control" value="'.$nid.'" name="nid" id="nid"></td>
                
                
     
                <th  class="form-group  control-label">ফেমেলি মেম্বার 	</th>
                <td><input type="text" class="form-control" value="'.$family_member.'" name="family_member" id="family_member"></td>
                </tr>

                

                <tr>
                <th></th>
                <td>
                
                </td>
           
                    <td  ><input type="button" name="save_client" id="save_client" class="btn btn-primary" value="সেভ করুন "></td>
                </tr>
          </tbody> 
      </table>    </div>
    </form>  
            
    </div>

</div>
</div>';


print $content ;?>