<div class="page-title hidden-print">                    
<h2><span class="fa fa-arrow-circle-o-left"></span>ব্যাংক স্টেটমেন্ট </h2>
</div>
<input type="hidden"  id="report_type" value="Bank Statement"/>
<div class="page-content-wrap">                
                
                <div class="row hidden-print">
                <div class="col-md-3"></div>
                    <div class="col-md-6">

                        <!-- START MODALS -->
                        <div class="panel panel-default">
                      
                            <div class="panel-body">
                            <div class="table-responsive">
                            <table class="table table-striped">
                               
                                <tr>
                                    <th>ব্যাংক </th>
                                    <th>
                                    <select class="form-control select" id="bank_id" name = "bank_id"  data-live-search="true">
<option value="">Select One</option>
<?php 
$qry = DB::conn()->prepare("SELECT * FROM `setup_bank` where `status`  = 'Active'   ORDER BY `id` ASC");
$qry->execute();
$fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
foreach ($fetch_list as $fetch) { 
    $bank_details = "$fetch[account_number] :: $fetch[brunch_name] :: $fetch[bank_name]";
    ?>
    <option value="<?php print $fetch['id'];?>"><?php print $bank_details ;?></option>

<?php } ?>
</select>
                                    </th>

                                    <th><b id="level-name"></b></th>
                        <td><b id="level-data"></b></td>    


                                </tr>

                                <tr>
                                    <th>Date From</th>
                                    <th>
                                    <input type="date"  id="date_from" class="form-control" value="<?php print date('Y-m-d');?>"/>
                                    </th>
                       
                                    <th>Date To</th>
                                    <th>
                                    <input type="date"  id="date_to" class="form-control" value="<?php print date('Y-m-d');?>"/>
                                    </th>
                                </tr>

                               <tr>
                                    <th colspan="2"><input type="button"  id="search_data" value="S E A R C H" class="btn  btn-info" 
                                    onclick="generateReport('Bank Statement','bank_id')" ></th>
                                   
                                </tr>



                            </table>
                                                </div>
                          
                            </div>
                        </div>
                        <!-- END MODALS -->

                    </div>
                    <div class="col-md-3"></div>


</div>

<div class="row">
                <div class="col-md-12" id="laod_report"></div>

</div>
</div>

