<?php 

?>
 <style>
      
      @media print {
        
}

    </style>
<ul class="breadcrumb hidden-print">
    <li><a href="#">Report </a></li>           
    <li class="active"><?php print $_GET['page_identity'];?></li>
</ul>
        

<div class="container">


<div class="row hidden-print">
<div class="col-md-12 form-horizontal">
<form id="myform">
<div class="table-responsive">

<table class="table table-hover table-condensed table-striped table-bordered" style="margin-bottom: 0px;">
                <tbody> 
                   
                      
                <tr > 

                <input type="hidden" id="branch_id"  name = "branch_id"   value="<?php print $_SESSION['USER_BRUNCH'];?>">




                    <tr>
                        <th>From</th>
                        <td><input  type="text" value="<?php print date('d-m-Y');?>" id="date_from" class="date form-control" ></td>
                        <th>To</th>
                        <td><input  type="text" value="<?php print date('d-m-Y');?>" id="date_to" class="date form-control" ></td>

                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td colspan="3" style="text-align:center">
                            <input type="button" onclick="generateReport('Year Book Report','branch_id');" class="btn btn-info block" value="SEARCH DATA" id="search_data" name="search_data" ></td>
                    </tr>
</tbody>
</table>
</div>
<input  type="hidden" value="Yearly Report" id="report_type" >



</div>
</div>

</div>
<div class="row" style="background-color:white">
    <div class="col-12 form-horizontal" id="laod_report">

 </div>
   
</div>

