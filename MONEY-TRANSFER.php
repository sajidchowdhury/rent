<?php 

?>
<ul class="breadcrumb">
    <li><a href="#">Accounts </a></li>           
  <li class="active"><?php print $_GET['page_identity'];?></li>
</ul>

<div class="row">

          <div class="panel panel-default">

          <div class="col-md-12">
          <div class="table-responsive">

<table class="table table-hover table-condensed ">



<tr>
        <th>ট্রান্সফার ধরন </th>
        <td colspan="4">
        <select name="tr_type" id="tr_type" class="form-control select" onchange="TransferType(this.value)"  >
        <option value="">Select One</option>

        <option value="Cash_To_Bank">ক্যাশ থেকে ব্যাংক</option>
        <option value="Bank_To_Cash">ব্যাংক থেকে ক্যাশ </option>


</select>
        </td>
    </tr>




    <tr>
        <td id="level_data" colspan="2"></td>
    </tr>



           



    <tr>
    <th>এমাউন্ট</th>
    <td  colspan="4">
    <input type="number" class="form-control" id="amount" name = "amount" value="">
    </td> 

    </tr>
    
        
    <tr>
    <th>বর্ণনা</th>
    <td colspan="4"><input type="text" id="note" value="" class="form-control"></td>
    </tr>

    <tr>
    <th>লেনদেন তারিখ </th>
    <td colspan="4">
    <input type="text"  class="date form-control" value="<?php print date('d-m-Y');?>" id="transection_date" name="transection_date" >        
    </td>
    </tr>


    
    <tr>
    <th colspan="5">
    <input type="button"   onclick="transfer_transaction()" class="btn btn-warning pull-right" value="স্থানান্তর করুন">
    </th>
    </tr>



</table>
</div></div>
</div>
</div>
