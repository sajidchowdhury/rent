
function modal_wihout_refresh(page_name,get_related_id,section) {

  var related_id = $('#'+get_related_id).val();
  if(related_id == '' ){ alert('can not find data for empty data'); return false;}
  $('#exampleModalCenter').modal('show')
  document.getElementById('#exampleModalCenter')
  $('#exampleModalCenter .modal-title').html(section)

  var modal = $(this);        
  document.getElementById('dash22').innerHTML = 'Loading data...';

     $.ajax({
  type: "GET",
  url: page_name+".php",
  
  data: {
    related_id: related_id,
    section: section
  },
  cache: false,
  success: function(data) {
      console.log(data);
      document.getElementById('dash22').innerHTML = data;

      $('select').selectpicker();
  },
  error: function(err) {
      console.log(err);
  }

});
}
function getNum(val) {
  var given_number = parseFloat(val).toFixed(2);
if (isNaN(given_number)) {
return 0.00;
}
return given_number;
}

function calculateResult(a, b, c,d) {

aa = $('#'+a).val();
bb = $('#'+b).val();
cc = $('#'+c).val();
dd = $('#'+d).val();
  aa = aa || 0;
  bb = bb || 0;
  cc = cc || 0;
  dd = dd || 0;
  var result =  (parseInt(aa) + parseInt(bb) + parseInt(cc)) - parseInt(dd);

  document.getElementById('grand_total').value = result;

}


function calculateCollection(a, b) {



  aa = $('#'+a).val();
  bb = $('#'+b).val();

    aa = aa || 0;
    bb = bb || 0;


    var result =  parseInt(aa) -  parseInt(bb)  ;
  
    document.getElementById('grand_total').value = result;


  }
  

  function calculateAdvance(a, b, c) {


    aa = $('#'+a).val();
    bb = $('#'+b).val();
    cc = $('#'+c).val();

      aa = aa || 0;
      bb = bb || 0;
      cc = cc || 0;

      var result =  parseInt(aa) -  parseInt(bb) ;    
      document.getElementById(''+c+'').value = result;

      totalRent(); 

    }
    

function totalRent(){

  let total_shop_number = $('#total_shop_number').val();
  let previous_due = $('#previous_due').val();

let total_aa = 0; // Initialize total_aa to accumulate the values

for (let i = 0; i < total_shop_number; i++) {
    let aa = parseFloat($('#total_after_advance'+i).val()); // Parse the value to a floating-point number
    total_aa += aa; // Accumulate the value of aa
}

document.getElementById('total_sub_total').value = total_aa; // Assign the total to total_sub_total
document.getElementById('due').value = parseFloat(previous_due +total_aa );
}



function checkValue(funcName, value,mess) {

  switch (funcName) {
  case 'isEmpty':
  return isEmpty(value,mess);
  case 'isLessThanZero':
  return isLessThanZero(value,mess);
  case 'isZero':
  return isZero(value,mess);
  case 'isGreater':
  return isGreater(value,mess);
  default:
  return false;
  }
  }
  
   function isEmpty(value,mess) {
  if (value === undefined || value === null || value === '') {
  alert(mess + ' লিখুন');
  return true;
  }
  return false;
  }
  
   function isLessThanZero(value,mess) {
  if (value < 0) {
  alert(mess + ' is less than zero');
  return true;
  }
  return false;
  }
  
   function isZero(value,mess) {
  if (value == 0) {
  alert(mess + ' is zero');
  return true;
  }
  return false;
  }

   function isGreater(value,mess) {

      var value = value.split('@');
      var mess = mess.split('@');

      var num1 = parseInt(value[0]);
      var num2 = parseInt(value[1]);

  if (num1 > num2) {
  alert(mess[0] + ' is greater then ' + mess[1]);
  return true;
  }
  return false;
  }


   function ConvertNum(val,mess) {
  var given_number = parseFloat(val).toFixed(2);
  if (isNaN(given_number)) {
  return 0.00;
  }
  return given_number;
  }



  



$('#save_client').on('click', function() {   

  $("#save_client").attr("disabled", "disabled");

  var related_id = $('#related_id').val();
  var client_name = $('#client_name').val();
  var mobile = $('#mobile').val();
  var nid = $('#nid').val();
  var family_member = $('#family_member').val();

  

if (checkValue('isEmpty', client_name,'ভাড়াটিয়ার নাম ')) { $('#client_name').focus(); $("#save_client").removeAttr("disabled"); return false; }
if (checkValue('isEmpty', mobile,'ফোন ')) { $('#mobile').focus(); $("#save_client").removeAttr("disabled"); return false; }

  
    $.ajax({
      url: "form_action.php",
      type: "POST",
      data: {
        action: 'save_client',
        related_id: related_id,
        client_name: client_name,
        mobile: mobile,
        nid:nid,
        family_member: family_member

      },
      cache: false,
      success: function(dataResult){
        alert(dataResult);
        window.location.replace("Setup/Client/New");
    
      }
    });
    
  });





$('#save_assaign_client').on('click', function() {   

  $("#save_assaign_client").attr("disabled", "disabled");

  var related_id = $('#related_id').val();
  var client_id = $('#client_id').val();
  var vobon_id = $('#vobon_id').val();
  var unit_id = $('#unit_id').val();
  var aggrement_start = $('#aggrement_start').val();
  var aggrement_ends = $('#aggrement_ends').val();
  var actual_rent = $('#actual_rent').val();
  var rent = $('#rent').val();
  var advance_payment = $('#advance_payment').val();
  var advance_payment_date = $('#advance_payment_date').val();
  var gus_bill = $('#gus_bill').val();
  var moylar_bill = $('#moylar_bill').val();
  var service_charge = $('#service_charge').val();
  var other_bill = $('#other_bill').val();
  var client_status = $('#client_status').val();


if (checkValue('isEmpty', client_id,'ভাড়াটিয়ার নাম ')) { $('#client_id').focus(); $("#save_assaign_client").removeAttr("disabled"); return false; }
if (checkValue('isEmpty', vobon_id,'ভবনের নাম  ')) { $('#vobon_id').focus(); $("#save_assaign_client").removeAttr("disabled"); return false; }
if (checkValue('isEmpty', unit_id,'ইউনিট নাম')) { $('#vobon_id').focus(); $("#save_assaign_client").removeAttr("disabled"); return false; }
if (checkValue('isEmpty', aggrement_start,'এগ্রিমেন্ট শুরু ')) { $('#aggrement_start').focus(); $("#save_assaign_client").removeAttr("disabled"); return false; }
if (checkValue('isEmpty', aggrement_ends,'এগ্রিমেন্ট শেষ ')) { $('#aggrement_ends').focus(); $("#save_assaign_client").removeAttr("disabled"); return false; }

  
    $.ajax({
      url: "form_action.php",
      type: "POST",
      data: {
        action: 'assain_client',
        related_id: related_id,
        client_id: client_id,
        vobon_id: vobon_id,
        unit_id: unit_id,
        aggrement_start:aggrement_start,
        aggrement_ends: aggrement_ends,
        actual_rent: actual_rent,
        rent: rent,
        advance_payment: advance_payment,
        advance_payment_date: advance_payment_date,
        gus_bill: gus_bill,
        moylar_bill: moylar_bill,
        service_charge: service_charge,
        other_bill: other_bill,
        client_status: client_status

      },
      cache: false,
      success: function(dataResult){
        alert(dataResult);
        window.location.replace("Setup/Client-List/New");
    
      }
    });
    
  });



  function CantPayNow(data_value){


    var x = window.confirm("Are you sure ?");
  if(x){
    
  $("#create_recipt").attr("disabled", "disabled");

    $.ajax({
      url: "form_action.php",
      type: "POST",
      data: {
        action: 'cant_pay_now',
        data_value:data_value
      },
      cache: false,
      success: function(dataResult){
        alert(dataResult);
         location.reload();
    
      }
    });

  }
  }
  
  function RemoveRecipt(data_value){

    $("#create_recipt").attr("disabled", "disabled");
  
    var x = window.confirm("Are you sure ?");
    if(x){
      $.ajax({
        url: "form_action.php",
        type: "POST",
        data: {
          action: 'remove_recipt',
          data_value:data_value
        },
        cache: false,
        success: function(dataResult){
          alert(dataResult);
           location.reload();
      
        }
      });
  
    }
    }


function CreateRecipt(){



  $("#create_recipt").attr("disabled", "disabled");
  cart = [];

  var rent_month = $('#rent_month').val();
  var client_id = $('#client_id').val();
  var rent_year = $('#rent_year').val();
  var total_item = $('#total_shop_number').val();

  if (total_item ==  0) {
    alert(" নেই");
    return false;
    }
  



  var receive_now = $('#receive_now').val();
  var receive_date = $('#receive_date').val();

if (checkValue('isEmpty', receive_date,'লেনদেনের তারিখ')) { $('#receive_date').focus(); $("#create_recipt").removeAttr("disabled"); return false; }
if (checkValue('isEmpty', receive_now,'নগদ গ্রহণ	')) { $('#receive_now').focus(); $("#create_recipt").removeAttr("disabled"); return false; }

if (receive_now < 0 ) {
alert('নগদ গ্রহণ লিখুন');
$('#receive_now').focus();
$("#create_recipt").removeAttr("disabled"); return false;
}


for(var i = 0; i < total_item; i++) {

  var element = {};
  var advance_adjustment =  $('#advance_adjustment'+[i]).val();
  var collect_id =  $('#collect_id'+[i]).val();

  
  element.advance_adjustment = advance_adjustment;
  element.collect_id = collect_id;

  cart.push({element: element});


}



    $.ajax({
      url: "form_action.php",
      type: "POST",
      data: {
        action: 'create_receipt',
        today_data: JSON.stringify(cart),
        rent_year:rent_year,
        rent_month: rent_month,
        total_item: total_item,
        client_id: client_id,
        receive_now: receive_now,
        receive_date: receive_date

      },
      cache: false,
      success: function(dataResult){
  location.reload();
    
      }
    });


}






$('#remove_client').on('click', function() {   

  $("#remove_client").attr("disabled", "disabled");

  var client_id = $('#client_id').val();
  var collection_type = $('#collection_type').val();
  var new_client_id = $('#new_client_id').val();
  var notes = $('#notes').val();
  var farewel_date = $('#farewel_date').val();
  var receive_now = $('#receive_now').val();
  var actual_due = $('#actual_due').val();
  var related_id = $('#related_id').val();

  
  
if (checkValue('isEmpty', collection_type,'বকেয়া আদায়ের ধরন ')) { $('#collection_type').focus(); $("#remove_client").removeAttr("disabled"); return false; }
if (checkValue('isEmpty', farewel_date,'বিদায় তারিখ ')) { $('#farewel_date').focus(); $("#remove_client").removeAttr("disabled"); return false; }

    $.ajax({
      url: "form_action.php",
      type: "POST",
      data: {
        action: 'remove_client',
        client_id: client_id,
        collection_type: collection_type,
        new_client_id: new_client_id,
        notes: notes,
        farewel_date: farewel_date,
        receive_now: receive_now,
        actual_due: actual_due,
        related_id: related_id


      },
      cache: false,
      success: function(dataResult){
       
         alert(dataResult);
        $("#remove_client").removeAttr("disabled"); return false;
 
    
      }
    });
    
  });


$('#search_invoice').on('click', function() {   

  $("#search_invoice").attr("disabled", "disabled");

  var client_id = $('#get_client_id').val();

  if(client_id == 'All' ){
    location.reload();

  }else{
    $.ajax({
      url: "function_tem.php",
      type: "POST",
      data: {
        action: 'search_pending_invoice',
        client_id: client_id

      },
      cache: false,
      success: function(dataResult){
        $('#MSalary').DataTable();
        document.getElementById('get_pending_data').innerHTML = dataResult;
        $("#search_invoice").removeAttr("disabled"); return false;

      }
    });
  }

    
  });



  function DueCollectionType(Type){


    if(Type == '' ){
      document.getElementById('type_text').innerHTML = '';
      document.getElementById('type_content').innerHTML ='';
      alert('বকেয়া আদায়ের ধরন ? ');
      return false ; 

    }else{
      $.ajax({
        url: "function_tem.php",
        type: "POST",
        data: {
          action: 'Due_Collection_Type',
          Type:Type
  
        },
        dataType: 'json',
        cache: false,
        success: function(dataResult){
        
          document.getElementById('type_text').innerHTML = dataResult['type_text'];
          document.getElementById('type_content').innerHTML = dataResult['type_content'];

          $('select').selectpicker();

        }
      });

    }






  }




$('#create_invoice').on('click', function() {   

  $("#create_invoice").attr("disabled", "disabled");

  var rent_month_year = $('#rent_month_year').val();
  var invoice_date = $('#invoice_date').val();

if (checkValue('isEmpty', invoice_date,'ইনভয়েস তারিখ')) { $('#invoice_date').focus(); $("#create_invoice").removeAttr("disabled"); return false; }

    $.ajax({
      url: "form_action.php",
      type: "POST",
      data: {
        action: 'save_rent_collection',
        rent_month_year:rent_month_year,
        invoice_date: invoice_date

      },
      cache: false,
      success: function(dataResult){
        alert(dataResult);
        window.location.replace("Setup/rent-collection/New");
    
      }
    });
    
  });



  $('#save_zone').on('click', function() {
    $("#save_zone").attr("disabled", "disabled");

    var vobon_name = $('#vobon_name').val();
    var related_id = $('#related_id').val();
    var unit_no = $('#unit_no').val();

    
  
if (checkValue('isEmpty', vobon_name,'ভবনের নাম ')) {   $('#vobon_name').focus();$("#save_zone").removeAttr("disabled"); return false; }
if (checkValue('isEmpty', unit_no,'ইউনিট সংখ্যা')) { $('#unit_no').focus(); $("#save_zone").removeAttr("disabled"); return false; }
if (checkValue('isLessThanZero', unit_no,'ইউনিট সংখ্যা')) { $('#unit_no').focus(); $("#save_zone").removeAttr("disabled"); return false; }
if (checkValue('isZero', unit_no,'ইউনিট সংখ্যা')) { $('#unit_no').focus(); $("#save_zone").removeAttr("disabled"); return false; }



      $.ajax({
        url: "form_action.php",
        type: "POST",
        data: {
          vobon_name: vobon_name,
          unit_no: unit_no,
        action: 'save_zone',
        related_id: related_id
        },
        cache: false,
        success: function(dataResult){
          alert(dataResult);
            window.location.replace("Setup/vobon_setup/New");
      
        }
      });

    });



    function SaveUnit(){


      cart2 = [];
    
      var total_item = $('#total_item').val();
      var related_id = $('#related_id').val();

    
      if (total_item ==  0) {
      alert("ইউনিট সংখ্যা নেই");
      return false;
      }
    
    
    
      for(var i = 1; i <= total_item; i++) {
    
    
        var row = document.getElementById("tr_no" + i);
        var cells = row.getElementsByTagName("td");
        for (var y = 0; y < cells.length; y++) {
        cells[y].style.color = "black";
        }
        
        var element2 = {};
    
        var unit_name =  $('#unit_name'+[i]).val();
        var rent =  $('#rent'+[i]).val();
        var notes =  $('#notes'+[i]).val();
        var unit_id =  $('#unit_id'+[i]).val();

    
        if(unit_name == '' ){ alert('Warehouse Cant empty'); return false;}else{}
        if(rent == '' ){ alert('Warehouse Cant empty'); return false;}else{}

        
        element2.unit_name = unit_name;
        element2.rent = rent;
        element2.notes = notes;
        element2.unit_id = unit_id;

       
        cart2.push({element2: element2});
    
    
    
    
      }
    
      $.ajax({
        url: "form_action.php",
        type: "POST",
        data: {
          today_data: JSON.stringify(cart2),
          related_id: related_id,
          total_item: total_item,
          action: 'save_unit'
        },
        cache: false,
        success: function(dataResult){
              alert(dataResult);     
          location.reload();
    
    
        
        }
      });
         }
    


  function delete_cart_row(REFRESH,TABLENAME,ID,RELATED_ID){

    var x = window.confirm("Are you sure to delete this data?");
  if(x){
   $.ajax({
     url: "function_tem.php",
     type: "POST",
     data: {
       TABLENAME: TABLENAME,
       ID: ID,
       action: 'delete_table_row'
     },
     cache: false,
     success: function(dataResult){
        $("#refresh_cart").load(REFRESH+'.php?related_id='+RELATED_ID);
       document.getElementById('mess_box').innerHTML = '<strong style="color:red"> ' + dataResult + '</strong>';
  
          setTimeout(function(){
     document.getElementById('mess_box').innerHTML = '';
   }, 2000);
  
  
   
     }
  
   });
  }
  
  }




function updateDates(dateFrom, dateTo) {
  var dateFromInput = document.getElementById('date_from');
  var dateToInput = document.getElementById('date_to');
  
  // Format the dates as dd-mm-yyyy
  var formattedDateFrom = formatDate(dateFrom);
  var formattedDateTo = formatDate(dateTo);
  
  // Update the input values
  dateFromInput.value = formattedDateFrom;
  dateToInput.value = formattedDateTo;
}

function addDay(REPORT) {
  var dateFromInput = document.getElementById('date_from');
  var dateToInput = document.getElementById('date_to');
  
  // Get the current date values
  var dateFromValue = dateFromInput.value;
  var dateToValue = dateToInput.value;
  
  // Parse the dates using the desired format (dd-mm-yyyy)
  var dateFrom = parseDate(dateFromValue);
  var dateTo = parseDate(dateToValue);
  
  // Add 1 day to the dates
  dateFrom.setDate(dateFrom.getDate() + 1);
  dateTo.setDate(dateTo.getDate() + 1);
  
  // Update the input values
  updateDates(dateFrom, dateTo);

  if(REPORT == 'Day Book Report' ){
    generateReport('Day Book Report','branch_id')
  }
}

function subtractDay(REPORT) {
  var dateFromInput = document.getElementById('date_from');
  var dateToInput = document.getElementById('date_to');
  
  // Get the current date values
  var dateFromValue = dateFromInput.value;
  var dateToValue = dateToInput.value;
  
  // Parse the dates using the desired format (dd-mm-yyyy)
  var dateFrom = parseDate(dateFromValue);
  var dateTo = parseDate(dateToValue);
  
  // Subtract 1 day from the dates
  dateFrom.setDate(dateFrom.getDate() - 1);
  dateTo.setDate(dateTo.getDate() - 1);
  
  // Update the input values
  updateDates(dateFrom, dateTo);

  if(REPORT == 'Day Book Report' ){
    generateReport('Day Book Report','branch_id')
    
  }

  
}

function parseDate(dateString) {
  var parts = dateString.split('-');
  return new Date(parts[2], parts[1] - 1, parts[0]);
}

function formatDate(date) {
  var day = date.getDate();
  var month = date.getMonth() + 1;
  var year = date.getFullYear();
  return (day < 10 ? '0' + day : day) + '-' + (month < 10 ? '0' + month : month) + '-' + year;
}







function DatewiseDashbord(LIMIT){
  document.getElementById('dashboard-donut-RECEIVEPAYMENT').innerHTML = '';
  document.getElementById('t1').innerHTML = LIMIT;
  document.getElementById('t2').innerHTML = LIMIT;
  document.getElementById('t3').innerHTML = LIMIT;


  $.ajax({
    url: "function_tem.php",
    type: "POST",
    data: {
      LIMIT: LIMIT,
      action: 'dash_bord_data'
    },
    dataType: 'json',
    cache: false,
    success: function(dataResult){

      document.getElementById('cb1').innerHTML = dataResult.cash_balance;
      document.getElementById('bb1').innerHTML = dataResult.bank_balance;
      document.getElementById('mb1').innerHTML = dataResult.mobile_balance;


      Morris.Donut({
        element: 'dashboard-donut-RECEIVEPAYMENT',
        data: [
            {label: "RECEIVE", value: dataResult.receivepayment_cash_in },
            {label: "PAYMENT", value: dataResult.receivepayment_cash_out }
        ],
        colors: ['#33414E', '#1caf9a'],
        resize: true
    });


    
    var nvd3Charts = function() {
	
      var myColors = ["#33414E","#8DCA35","#00BFDD","#FF702A","#DA3610",
                      "#80CDC2","#A6D969","#D9EF8B","#FFFF99","#F7EC37","#F46D43",
                      "#E08215","#D73026","#A12235","#8C510A","#14514B","#4D9220",
                      "#542688", "#4575B4", "#74ACD1", "#B8E1DE", "#FEE0B6","#FDB863",                                                
                      "#C51B7D","#DE77AE","#EDD3F2"];
      d3.scale.myColors = function() {
          return d3.scale.ordinal().range(myColors);
      };
      
  var startChart1 = function() {
      //Regular pie chart example
      nv.addGraph(function() {
          var chart = nv.models.pieChart().x(function(d) {
              return d.label;
          }).y(function(d) {
              return d.value;
          }).showLabels(true).color(d3.scale.myColors().range());;
  
          d3.select("#chart-1 svg").datum(exampleData()).transition().duration(350).call(chart);
  
          return chart;
      });
  
      //Donut chart example
      nv.addGraph(function() {
          var chart = nv.models.pieChart().x(function(d) {
              return d.label;
          }).y(function(d) {
              return d.value;
          }).showLabels(true)//Display pie labels
          .labelThreshold(.05)//Configure the minimum slice size for labels to show up
          .labelType("percent")//Configure what type of data to show in the label. Can be "key", "value" or "percent"
          .donut(true)//Turn on Donut mode. Makes pie chart look tasty!
          .donutRatio(0.35)//Configure how big you want the donut hole size to be.
          .color(d3.scale.myColors().range());;
  
          d3.select("#chart-1 svg").datum(exampleData()).transition().duration(350).call(chart);
  
          return chart;
      });
  
      //Pie chart example data. Note how there is only a single array of key-value pairs.
      function exampleData() {
          return dataResult.income_transection;
      }
  
  };
  
  
  var startChart2 = function() {
      //Regular pie chart example
      nv.addGraph(function() {
          var chart = nv.models.pieChart().x(function(d) {
              return d.label;
          }).y(function(d) {
              return d.value;
          }).showLabels(true).color(d3.scale.myColors().range());;
  
          d3.select("#chart-2 svg").datum(exampleData()).transition().duration(350).call(chart);
  
          return chart;
      });
  
      //Donut chart example
      nv.addGraph(function() {
          var chart = nv.models.pieChart().x(function(d) {
              return d.label;
          }).y(function(d) {
              return d.value;
          }).showLabels(true)//Display pie labels
          .labelThreshold(.05)//Configure the minimum slice size for labels to show up
          .labelType("percent")//Configure what type of data to show in the label. Can be "key", "value" or "percent"
          .donut(true)//Turn on Donut mode. Makes pie chart look tasty!
          .donutRatio(0.35)//Configure how big you want the donut hole size to be.
          .color(d3.scale.myColors().range());;
  
          d3.select("#chart-2 svg").datum(exampleData()).transition().duration(350).call(chart);
  
          return chart;
      });
  
      //Pie chart example data. Note how there is only a single array of key-value pairs.
      function exampleData() {
        return dataResult.expense_transection;
      }
  
  };
  
  function stream_layers(n, m, o) {
      if (arguments.length < 3)
          o = 0;
      function bump(a) {
          var x = 1 / (.1 + Math.random()), y = 2 * Math.random() - .5, z = 10 / (.1 + Math.random());
          for (var i = 0; i < m; i++) {
              var w = (i / m - y) * z;
              a[i] += x * Math.exp(-w * w);
          }
      }
  
      return d3.range(n).map(function() {
          var a = [], i;
          for ( i = 0; i < m; i++)
              a[i] = o + o * Math.random();
          for ( i = 0; i < 5; i++)
              bump(a);
          return a.map(stream_index);
      });
  }
  
  function stream_index(d, i) {
      return {
          x : i,
          y : Math.max(0, d)
      };
  }
  
  return {		
      init : function() {
        
          startChart1();
          startChart2();
      }
  };
  }();
  
  nvd3Charts.init();

    }
  });



}



function selectAllOptions(ID) {
  var select = document.getElementById(ID);
  for(var i=0; i<select.options.length; i++) {
    select.options[i].selected = true;
  }
  select.setAttribute('data-selected-text-format', 'count');
  $(select).selectpicker('refresh');
}



function unselectAllOptions(ID) {
  var select = document.getElementById(ID);
  for(var i=0; i<select.options.length; i++) {
    select.options[i].selected = false;
  }
  select.setAttribute('data-selected-text-format', 'count>2');
  $(select).selectpicker('refresh');
}




function screenShot(){// Get the element you want to screenshot
  var element = document.getElementById("myDiv");
  
  // Set the filename for the image
  var image_name = "myImage.png";
  
  // Use html2canvas to take a screenshot of the element's content
  html2canvas(element).then(function(canvas) {
      // Convert the canvas to an image and create a download link with the desired filename
      var imgData = canvas.toDataURL();
      var link = document.createElement("a");
      link.download = image_name;
      link.href = imgData;
      document.body.appendChild(link);
      
      // Click the download link to save the image
      link.click();
      document.body.removeChild(link);
  });
  
}


function printButtn(NAME,TABLEID){

  var report_type = $('#report_type').val();
  var date_from = $('#date_from').val();
  var date_to = $('#date_to').val();

  if(date_from == '' || date_from == 'undefined' ){ var date1 =  '';  }else{ var date1 = 'From: ' + date_from ;}
  if(date_to == '' || date_to == 'undefined' ){ var date2 =  ''; }else{ var date2 = 'To: ' + date_to ; }

  var title_company_name = $('#title_company_name').val();

  var tableData = $('#'+TABLEID).DataTable().data();
  var theadContent = $("#"+TABLEID + " thead").html();

  var printContent = "";
  for (var i = 0; i < tableData.length; i++) {
    printContent += "<tr>";
    for (var key in tableData[i]) {
      printContent += "<td>" + tableData[i][key] + "</td>";
    }
    printContent += "</tr>";
  }

  var printWindow = window.open('', '', 'height=800,width=1200');
  printWindow.document.write('<html><head><style>a {text-decoration: none;}table{width: 100%;border-spacing: 0px;} th{ border: 1px solid black; } td{border: 1px solid black;}</style></head>');
  printWindow.document.write('<body><table><tr><th style="text-align:center">'+title_company_name+'</th></tr><tr><th style="text-align:center">'+report_type+' '+NAME+'</th></tr><tr><th style="text-align:center">'+date1+'  '+date2+'</th></tr></table><table><thead>' + theadContent + '</thead>' + printContent + '</table></body></html>');
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
  printWindow.close();

}

function printD(el){

  var restorepage = document.body.innerHTML;
	var printcontent = document.getElementById(el).innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorepage;
location.reload();
}
function transfer_transaction(){

  var tr_type = $('#tr_type').val();
  var tr_from = $('#tr_from').val();
  var tr_to = $('#tr_to').val();
  var amount = $('#amount').val();
  var note = $('#note').val();
  var transection_by_from = $('#transection_by_from').val();
  var transection_by_to = $('#transection_by_to').val();
  var transection_date = $('#transection_date').val();



  var x = window.confirm("Are you sure to make the transfer ?");
  if(x){
  $.ajax({
    url: "form_action.php",
    type: "POST",
    data: {
      tr_type: tr_type,
      tr_from: tr_from,
      tr_to: tr_to,
      amount: amount,
      transection_by_from: transection_by_from,
      transection_by_to: transection_by_to,
      note: note,
      transection_date: transection_date,
      action: 'transfer_transaction'
    },
    cache: false,
    success: function(dataResult){
      alert(dataResult);
      location.reload();
    }

  });
}



}



function TransferType(type){

  $.ajax({
    url: "function_tem.php",
    type: "POST",
    data: {
      action: 'transfer_type',
      type: type
    },
    cache: false,
    dataType: 'json',
    success: function(dataResult){
      $('#level_data').html(dataResult.content); 	
      $('select').selectpicker();
    }
  });

}


function LedgerWiseData(type,ledger_id){

      
  $.ajax({
    url: "function_tem.php", 
    type: "POST",
    data: {
        ledger_id: ledger_id,
        type: type,
          action: 'ledger_wise_accounts'
    },
    cache: false,
    success: function(dataResult){
    
        document.getElementById('load_subhead').innerHTML = dataResult
        $('select').selectpicker();

    }
    });




}


function transection_by_details(TYPE){


  transection_by_id = $('#get_transection_by_id').val();
  check_number = $('#get_check_number').val();
  check_date = $('#get_check_date').val();

  if(TYPE == ''){
    document.getElementById('transection_by_data').innerHTML = '';
  }else{

    $.ajax({
      url: "function_tem.php", 
      type: "POST",
      data: {
              TYPE: TYPE,
              action: 'find_transection_by_details',
              transection_by_id: transection_by_id,
              check_number: check_number,
              check_date: check_date

      },
      cache: false,
      dataType: 'json',
      success: function(dataResult){
  
        document.getElementById('transection_by_data').innerHTML = dataResult.content;
        document.getElementById('transection_by_data2').innerHTML = dataResult.content2;

        $('select').selectpicker();
      }
    });
  }

}


function VobonWiseUnit(id){


  if(id == ''){
    document.getElementById('unit_details').innerHTML = '';
  }else{

    $.ajax({
      url: "function_tem.php", 
      type: "POST",
      data: {
              id: id,
              action: 'find_vobon_wise_unit'

      },
      cache: false,
      dataType: 'json',
      success: function(dataResult){
  
        document.getElementById('unit_details').innerHTML = dataResult.content;
        $('select').selectpicker();
      }
    });
  }

}


function UnitWiseRent(id){


  if(id == ''){
    document.getElementById('actual_rent').value = '';
  }else{

    $.ajax({
      url: "function_tem.php", 
      type: "POST",
      data: {
              id: id,
              action: 'find_unit_wise_rent'

      },
      cache: false,
      dataType: 'json',
      success: function(dataResult){
        document.getElementById('actual_rent').value = dataResult.content;
      }
    });
  }

}






function clear_rent(count){

  var room_no = $('#room_no'+count).val();
  var client_id = $('#client_id'+count).val();
  var rent = getNum($('#rent'+count).val());
  var date_start = $('#date_start'+count).val();
  var date_end = $('#date_end'+count).val();
  var zone_id = $('#zone_id'+count).val();
  var advance = $('#advance'+count).val();


  $.ajax({
    url: "form_action.php", 
    type: "POST",
    data: {
      room_no: room_no,
      client_id: client_id,
      rent: rent,
      date_start: date_start,
      zone_id: zone_id,
      date_end: date_end,
      advance: advance,
      action: 'clear_rent'
    },
    cache: false,
    success: function(dataResult){    
    alert(dataResult);
    }
    });



}


function save_rent(count){

  var room_no = $('#room_no'+count).val();
  var client_id = $('#client_id'+count).val();
  var rent = getNum($('#rent'+count).val());
  var date_start = $('#date_start'+count).val();
  var date_end = $('#date_end'+count).val();
  var zone_id = $('#zone_id'+count).val();
  var advance = $('#advance'+count).val();


  $.ajax({
    url: "form_action.php", 
    type: "POST",
    data: {
      room_no: room_no,
      client_id: client_id,
      rent: rent,
      date_start: date_start,
      zone_id: zone_id,
      date_end: date_end,
      advance: advance,
      action: 'save_rent'
    },
    cache: false,
    success: function(dataResult){    
    alert(dataResult);
    }
    });



}




function income_transection(){

 
  var related_id = $('#related_id').val();
  var transection_head_id = $('#transection_head_id').val();
  var receive_now = getNum($('#amount').val());
  var transection_by = $('#transection_by').val();
  var transection_by_id = $('#transection_by_id').val();
  var check_number = $('#check_number').val();
  var check_date = $('#check_date').val();
  var transection_to_id = $('#transection_to_id').val();
  var transection_to = $('#transection_to').val();
  var ledger_id = $('#ledger_id').val();
  var extra_field = $('#extra_field').val();
  var transection_date = $('#transection_date').val();


  var note = $('#note').val();
  var code = $('#code').val();
  var data_inserted_from = $('#data_inserted_from').val();


if(transection_to == 'Customer' ){
  var transaction_type = $('#transaction_type').val();

}else{
  var transaction_type = 'RECEIVE';

}
  if(transection_by == 'Bank'){

    var check_number = $('#check_number').val();
    var check_date = $('#check_date').val();
    if (transection_by_id ==  '') {alert("select Bank"); $('#transection_by_id').focus(); return false; }

  }else if (transection_by == 'Mobile-Banking'){

    var check_number = '';
    var check_date = '';
    if (transection_by_id ==  '') {alert("select Mobile No"); $('#transection_by_id').focus(); return false; }


  }else if(transection_by == 'Cash'){

    var check_number = '';
    var check_date = '';

  }else{

    var check_number = '';
    var check_date = '';
  }


  if (transection_to_id ==  '') {alert("select Transaction To"); $('#transection_to_id').focus(); return false; }
  if (transection_head_id ==  '') {alert("Select Head"); $('#transection_head_id').focus(); return false; }
  if (transection_by ==  '') {alert("Select Transaction By"); $('#transection_by').focus(); return false; }

  if (receive_now ==  '') {alert("Amount is empty"); $('#amount').focus(); return false; }
  if (receive_now < 0) {alert("Amount can not nagative"); $('#amount').focus(); return false; }
  if (receive_now == 0) {alert("Amount can not zero"); $('#amount').focus(); return false; }

  $.ajax({
    url: "form_action.php", 
    type: "POST",
    data: {
          related_id: related_id,
          transection_date:transection_date,
          ledger_id: ledger_id,
          transection_head_id: transection_head_id,
          receive_now: receive_now,
          transection_by: transection_by,
          transection_by_id: transection_by_id,
          check_number: check_number,
          transection_to: transection_to,
          check_date: check_date,
          transection_to_id: transection_to_id,
          note: note,
          extra_field: extra_field,
          transaction_type: transaction_type,
          action: 'save_income_transection',
          code: code,
          data_inserted_from: data_inserted_from
    },
    cache: false,
    dataType: 'json',
    success: function(dataResult){
    
    alert(dataResult.mess);

location.reload();
    
    
    }
    });




}


function create_bank(){

  var related_id = $('#related_id').val();
  var bank_name = $('#bank_name').val();
  var brunch_name = $('#brunch_name').val();
  var account_number = $('#account_number').val();
  var account_name = $('#account_name').val();
  var description = $('#description').val();
  var status = $('#status').val();


    if (bank_name ==  '') {alert("Bank Name is Empty"); $('#bank_name').focus(); return false; }
    if (brunch_name ==  '') {alert("Brunch Name Empty"); $('#brunch_name').focus(); return false; }
    if (account_number ==  '') {alert("A/c Number Empty"); $('#account_number').focus(); return false; }

    if (account_name ==  '') {alert("A/C Name empty"); $('#account_name').focus(); return false; }
  

$.ajax({
url: "form_action.php", 
type: "POST",
data: {
      related_id: related_id,
      bank_name: bank_name,
      brunch_name: brunch_name,
      action: 'save_bank',
      account_number: account_number,
      account_name: account_name,
      description: description,
      status: status
},
cache: false,
success: function(dataResult){

alert(dataResult);
location.reload();


}
});





}



$('#create_ac_head').on('click', function() {


  var related_id = $('#related_id').val();
  var description = $('#description').val();
  var status = $('#status').val();


  if($('#parent_id').is(':disabled')){
      
      var parent_id = 'New_Parent';
      var ladger_head = $('#new_ladger_head').val();
      var account_head = '';
      var account_type = 'All';

      if (ladger_head ==  '') {alert("New Ladger Empty"); $('#ladger_head').focus(); return false; }

  }else{
      var parent_id = $('#parent_id').val();
      var ladger_head = '';
      var account_head = $('#account_head').val();
      var account_type = $('#account_type').val();

      if (parent_id ==  '') {alert("Ledger Head Empty"); $('#parent_id').focus(); return false; }
      if (account_head ==  '') {alert("AC Head is Empty"); $('#account_head').focus(); return false; }

  }

  

$.ajax({
url: "form_action.php", 
type: "POST",
data: {
      related_id: related_id,
      account_head: account_head,
      account_type: account_type,
      action: 'save_ac_head',
      description: description,
      status: status,
      ladger_head: ladger_head,
      parent_id:parent_id
},
cache: false,
success: function(dataResult){

alert(dataResult);
location.reload();


}
});

  });


  
function expense_transection(){
  
  var related_id = $('#related_id').val();
  var transection_to = $('#transection_to').val();
  var transection_head_id = $('#transection_head_id').val();
  var receive_now = getNum($('#amount').val());
  var transection_by = $('#transection_by').val();
  var transection_by_id = $('#transection_by_id').val();
  var check_number = $('#check_number').val();
  var check_date = $('#check_date').val();
  var transection_to_id = $('#transection_to_id').val();
  var note = $('#note').val();
  var code = $('#code').val();
  var data_inserted_from = $('#data_inserted_from').val();
  var ledger_id = $('#ledger_id').val();
  var transection_date = $('#transection_date').val();

  if(transection_to == 'Supplier' ){
    var transaction_type = $('#transaction_type').val();

  }else{
    var transaction_type = 'PAYMENT';

  }


  if(transection_by == 'Bank'){

    var check_number = $('#check_number').val();
    var check_date = $('#check_date').val();
      if (transection_by_id ==  '') {alert("select Bank"); $('#transection_by_id').focus(); return false; }

  }else if (transection_by == 'Mobile-Banking'){

    var check_number = '';
    var check_date = '';
      if (transection_by_id ==  '') {alert("select Mobile No"); $('#transection_by_id').focus(); return false; }


  }else if(transection_by == 'Cash'){

    var check_number = '';
    var check_date = '';

  }else{

    var check_number = '';
    var check_date = '';
  }

  if (transection_to ==  '') {alert("Select Transaction To"); $('#transection_to').focus(); return false; }
  if (transection_to_id ==  '') {alert("Select Transaction To"); $('#transection_to_id').focus(); return false; }
  if (transection_head_id ==  '') {alert("Select Head"); $('#transection_head_id').focus(); return false; }
  if (transection_by ==  '') {alert("Select Transaction BY"); $('#transection_by').focus(); return false; }

  if (receive_now ==  '') {alert("Amount is empty"); $('#amount').focus(); return false; }
  if (receive_now < 0) {alert("Amount can not nagative"); $('#amount').focus(); return false; }
  if (receive_now == 0) {alert("Amount can not zero"); $('#amount').focus(); return false; }

  $.ajax({
    url: "form_action.php", 
    type: "POST",
    data: {
          related_id: related_id,
          ledger_id: ledger_id,
          transection_head_id: transection_head_id,
          receive_now: receive_now,
          transection_by: transection_by,
          transection_by_id: transection_by_id,
          check_number: check_number,
          check_date: check_date,
          transection_to: transection_to,
          transection_to_id: transection_to_id,
          transection_date: transection_date,
          transaction_type: transaction_type,
          note: note,
          action: 'save_expense_transection',
          code: code,
          data_inserted_from: data_inserted_from
    },
    cache: false,
    dataType: 'json',
    success: function(dataResult){
    
    alert(dataResult.mess);
 
    location.reload();
    }
    });


}



  function newLadgerHead(TYPE){

    if(TYPE == 'YES' ){
        document.getElementById("parent_id").disabled = true;  
        document.getElementById('new_ladger_div').style.display = 'block';
        document.getElementById('section_head').style.display = 'none';
        document.getElementById('cHbutton').innerHTML = '<a onclick="newLadgerHead(\'NO\');" target="_blink" class="btn btn-danger" > <span class="fa fa-minus"> </span></a>';
    
    }else{
        document.getElementById("parent_id").disabled = false; 
        document.getElementById('new_ladger_div').style.display = 'none';
        document.getElementById('section_head').style.display = 'block';
        document.getElementById('cHbutton').innerHTML = '<a onclick="newLadgerHead(\'YES\');" target="_blink" class="btn btn-danger" > <span class="fa fa-plus-circle"> </span></a>';
    
    }
    
    
    }



function Change_Type(TYPE,LEVELDIV,VALUEDIV){

  var search_type = $('#' + TYPE ).val();

  

  $.ajax({
    url: "search_by_type.php",
    type: "POST",
    data: {
      SEARCH_BY: search_type
    },
    cache: false,
    dataType: 'json',
    success: function(dataResult){

      $('#' + LEVELDIV ).html(dataResult.level); 	
      $('#' + VALUEDIV ).html(dataResult.value); 	
      $('select').selectpicker();

    }
  });


}

function SimplePrint(){

  print();
}



function CommonReportGenerator(REPORTNAME){

  var date_from = $('#date_from').val();
  var date_to = $('#date_to').val();
  var report_type = $('#report_type').val();
  var report_wise_code = $('#report_wise_code').val();
  var branch_id = $('#branch_id').val();

  var related_id = $('#'+ report_wise_code).val();

  if(report_type == '' ){ alert('Please select a report type');return false; }
  var spinner = "<div class='col-md-4'></div><div class='col-md-4'><img src='report.gif'  style='height:350px;' alt='loading...' /></div><div class='col-md-4'></div>";
  $('#laod_report').html(spinner); 	

  document.getElementById("search_data").disabled = true;            

  $.ajax({
    url: "generate_report.php",
    type: "GET",
    data: {
  
      action: REPORTNAME,
      date_from: date_from,
      date_to: date_to,
      related_id:related_id,
      branch_id: branch_id,
      report_type: report_type


    },
    cache: false,
    success: function(html){

     document.getElementById("laod_report").innerHTML = html;
     document.getElementById("search_data").disabled = false;            
     $('#MSalary').DataTable();

    }

  });


}


function generateReport(REPORTNAME,EXTRAFILED){


  document.getElementById("search_data").disabled = true;            

  var date_from = $('#date_from').val();
  var date_to = $('#date_to').val();
  var report_type = $('#report_type').val();
  var EXTRAFILED = $('#'+ EXTRAFILED).val();

  var spinner = "<div class='col-md-4'></div><div class='col-md-4'><img src='report.gif'  style='height:350px;' alt='loading...' /></div><div class='col-md-4'></div>";
  $('#laod_report').html(spinner); 	


  $.ajax({
    url: "generate_report.php",
    type: "GET",
    data: {
  
      action: REPORTNAME,
      date_from: date_from,
      date_to: date_to,
      report_type:report_type,
      EXTRAFILED: EXTRAFILED
    },
    cache: false,
    success: function(html){

     document.getElementById("laod_report").innerHTML = html;
     document.getElementById("search_data").disabled = false;            
     $('#MSalary').DataTable();

    }

  });



      
}

