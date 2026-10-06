<?php
include_once('function_query.php'); 


if ($_POST['SEARCH_BY'] == 'Customer-Wise'){

    $CONTENT = '<input type="hidden" value="customer_id" id="report_wise_code">
    <select class="form-control select" id="customer_id" name = "customer_id"  data-live-search="true">
    <option value="">Select One</option>';

    $qry = DB::conn()->prepare("SELECT * FROM `setup_client`  ");
    $qry->execute();
    $fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fetch_list as $value) 

    {

    $CONTENT .= '<option  value="'.$value['id'].'">'.$value['client_name'].' ::: ' .$value['mobile']. '</option>';

        }
        $CONTENT .= '</select>';



    print json_encode(array ('level' => $_POST['SEARCH_BY'],'value' => $CONTENT));


}else if ($_POST['SEARCH_BY'] == 'Multipal-Customer-Wise'){

    $CONTENT = '<input type="hidden" value="customer_id" id="report_wise_code"><select class="form-control select" id="customer_id" name = "customer_id[]"  data-live-search=true class="selectpicker" multiple data-selected-text-format="count>1" data-all="false">
    ';
   
           $qry = DB::conn()->prepare("SELECT *  FROM `setup_client` ORDER BY `id` ASC");
           $qry->execute();
           $fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
           foreach ($fetch_list as $value) { 

    $CONTENT .= '<option  value="'.$value['id'].'">'.$value['phone'].' ::: ' .$value['business_name']. '</option>';
   
           }
           $CONTENT .= '</select>';
           $CONTENT .= '<input type="button" class="btn btn-info"  onclick="selectAllOptions(\'customer_id\')" value="Select All" >
           <input type="button"  class="btn btn-danger" onclick="unselectAllOptions(\'customer_id\')" value="Unselect All" >';


           
           print json_encode(array ('level' => 'Ledger-List','value' => $CONTENT));
   


}else if ($_POST['SEARCH_BY'] == 'Multipal-Ledger-Wise'){

    $CONTENT = '<input type="hidden" value="ledger_id" id="report_wise_code"><select class="form-control select" id="ledger_id" name = "ledger_id[]"  data-live-search=true class="selectpicker" multiple data-selected-text-format="count>1" data-all="false">
    ';
   
           $qry = DB::conn()->prepare("SELECT *  FROM `setup_ladger_head` ORDER BY `id` ASC");
           $qry->execute();
           $fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
           foreach ($fetch_list as $fetch) { 

            $CONTENT .= '<option  value="'.$fetch['id'].'"> '.$fetch['name'].'</option>';
   
           }
           $CONTENT .= '</select>';
           $CONTENT .= '<input type="button" class="btn btn-info"  onclick="selectAllOptions(\'ledger_id\')" value="Select All" >
           <input type="button"  class="btn btn-danger" onclick="unselectAllOptions(\'ledger_id\')" value="Unselect All" >';


           
           print json_encode(array ('level' => 'Ledger-List','value' => $CONTENT));
   



}else if ($_POST['SEARCH_BY'] == 'Multipal-Head-Wise'){

    $CONTENT = '<input type="hidden" value="head_id" id="report_wise_code"><select class="form-control select" id="head_id" name = "head_id[]"  data-live-search=true class="selectpicker" multiple data-selected-text-format="count>1" data-all="false">
    ';
   
           $qry = DB::conn()->prepare("SELECT *  FROM `setup_ac_head` ORDER BY `id` ASC");
           $qry->execute();
           $fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
           foreach ($fetch_list as $fetch) { 

            $CONTENT .= '<option  value="'.$fetch['id'].'"> '.$fetch['account_head'].'</option>';
   
           }
           $CONTENT .= '</select>';
           $CONTENT .= '<input type="button" class="btn btn-info"  onclick="selectAllOptions(\'head_id\')" value="Select All" >
           <input type="button"  class="btn btn-danger" onclick="unselectAllOptions(\'head_id\')" value="Unselect All" >';
           print json_encode(array ('level' => 'AC-List','value' => $CONTENT));
   





}else if ($_POST['SEARCH_BY'] == 'Zone-Wise'){

    $CONTENT = '<input type="hidden" value="zone_id" id="report_wise_code">
    <select class="form-control select" id="zone_id" name = "zone_id"  data-live-search="true">
    <option value="">Select One</option>';

    $qry = DB::conn()->prepare("SELECT * FROM `setup_zone`  ");
    $qry->execute();
    $fetch_list = $qry->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fetch_list as $value) 
{
    $CONTENT .= '<option  value="'.$value['id'].'"> ' .$value['zone_name']. '</option>';

        }
        $CONTENT .= '</select>';




    print json_encode(array ('level' => $_POST['SEARCH_BY'],'value' => $CONTENT));

}