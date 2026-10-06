<?php 

?>
 <style>
        @media print {
           
            .p1 {
                width: 2% !important;
            }

            .break-after:nth-child(15n) {
    break-after: page;
    }
  


        }
    </style>
<ul class="breadcrumb hidden-print">
    <li><a href="#">Report </a></li>           
    <li class="active"><?php print $_GET['page_identity'];?></li>
</ul>
        
<input  type="hidden" value="1971-01-01" id="date_from" class="date form-control" >
<div class="container">


<div class="row hidden-print">
<div class="col-md-12 form-horizontal">


<div class="panel-body">                                                                        
            
            <div class="col-md-12">
    

                <div class="form-group">
                    <label class="col-md-3 col-xs-12 control-label">ভাড়াটিয়ার নাম</label>
                    <div class="col-md-6 col-xs-12">                                            
                          

<select class="form-control select" id="client_id" name = "client_id[]"  data-live-search=true class="selectpicker"  >
        <option value="">Select One</option>
    <?php 
            $qry = DB::conn()->prepare("SELECT * FROM `setup_client` ORDER BY `id` ASC");
            $qry->execute();
            $fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetch_list as $fetch) { ?>
    
        <option  value="<?php print $fetch['id'];?>"><?php print $fetch['client_name'];?> <?php print $fetch['mobile'];?></option>
    
            <?php } ?>
            </select>
           
</select>

                                                                    
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-3 col-xs-12 control-label">তারিখ</label>
                    <div class="col-md-6 col-xs-12">                                            
                          
                        <input  type="text" value="<?php print date('d-m-Y');?>" id="date_to" class="date form-control" >

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-xs-12 control-label"></label>
                    <div class="col-md-6 col-xs-12">                                            
                        <div class="input-group">
                          
                        <input type="button" onclick="generateReport('Client Ledger','client_id');" class="btn btn-info block" value="SEARCH DATA" id="search_data" name="search_data" ></td>

                        </div>                                            
                    </div>
                </div>

                </div>

                </div>

                <input  type="hidden" value="Day Book Report" id="report_type" >

</div>

</div>




<div class="row panel-body" style="background-color:white">
    <div class="col-12 form-horizontal" id="laod_report">

 </div>
   
</div>

