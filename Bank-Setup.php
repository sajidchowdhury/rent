<?php 

if($_GET['related_id'] == 'New'){


$related_id = 'new_id';
$bank_name = '';
$brunch_name = '';
$account_number = '';
$account_name = '';
$button_text = 'Add++';
$description = '';
$status = '';
}else{

$DATA  = SETUP::BANK_SETUP($_GET['related_id']);

$related_id = $DATA['id'];
$bank_name = $DATA['bank_name'];
$brunch_name = $DATA['brunch_name'];
$account_number = $DATA['account_number'];
$account_name = $DATA['account_name'];
$description = $DATA['description'];
$button_text = 'Update';
$status = $DATA['status'];

?>
<script>
    window.onload = function() {
        calculate_batch();
};
</script>
<?php 
}

?><div class="row">


                        <div class="col-md-12">
                            
                            <form class="form-horizontal">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Setup</strong> Bank</h3>
                                  <input type="hidden" id="related_id" name="related_id" value="<?php print $related_id;?>">
                                </div>
                                
                                <div class="panel-body">                                                                        
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">ব্যাংকের নাম </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                                                <input type="text" id="bank_name" name = "bank_name" value="<?php print $bank_name;?>" class="form-control"/>
                                            </div>                                            
                                         
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">শাখার নাম</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                                                <input type="text" id="brunch_name" name = "brunch_name"  value="<?php print $brunch_name;?>" class="form-control"/>
                                            </div>                                            
                                         
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">হিসাব নাম্বার</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                                                <input type="text" id="account_number" name = "account_number" value="<?php print $account_number;?>"  class="form-control"/>
                                            </div>                                            
                                         
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">হিসাবের নাম</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                                                <input type="text" id="account_name" name = "account_name" value="<?php print $account_name;?>" class="form-control"/>
                                            </div>                                            
                                         
                                        </div>
                                    </div>


                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">বর্ণনা</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <textarea class="form-control" id="description" name = "description" rows="5"><?php print $description;?></textarea>
                                        </div>
                                    </div>
                                    
                                   
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">স্ট্যাটাস</label>
                                        <div class="col-md-6 col-xs-12">                                                                                            
                                            <select class="form-control select" id="status" name = "status">
                                                <option <?php if($status == 'Active'){ ?> selected="selected" <?php }else{ } ?> value="Active">Active</option>
                                                <option <?php if($status == 'Deactive'){ ?> selected="selected" <?php }else{ } ?> value="Deactive">Deactive</option>
                                               
                                            </select>
                                        </div>
                                    </div>
                               
                                  

                                </div>
                                <div class="panel-footer">
                                <input type="button" class="btn btn-info pull-right" value="Save Data" onclick="create_bank();">
                                </div>
                            </div>
                            </form>
                            
                        </div>
                    </div>         


                    <?php 

$content = '<div class="row">
<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-body" id="load_table">
        <div class="table-responsive">

    <table class="table datatable table-hover table-condensed table-striped table-bordered">
        <thead>
        <th>Sl</th>
        <th>ব্যাংক বিবরণ</th>
        <th>স্ট্যাটাস</th>
        <th>Action</th>
        </thead>
            <tbody>'; 
$sl =1;
$qry = DB::conn()->prepare("SELECT * FROM `setup_bank`  ORDER BY `id` ASC");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
if ($qry->rowCount() > 0)
{
    foreach($fetch_list AS $fetch) {



        $content .= '<tr>
        <th>'.$sl++.'</th>
        <th>Bank Name: '.$fetch['bank_name'].' <br>A/c: '.$fetch['account_number'].'</th>
        <th>'.$fetch['status'].'</th>

        <td><a href="setup/Bank-Setup/'.$fetch['id'].'" ><i class="fa fa-edit danger"></i><a></td>
       </tr>';
    }

}
                
                  
$content .= '</tbody> 
      </table></div>
      </div>
            
    </div>

</div>
</div>';

print $content ;
?>