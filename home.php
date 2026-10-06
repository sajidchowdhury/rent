<?php 
include('auth.php');
include('layout/head.php');
include('function_query.php');

$templet_content = TEMPLET::TEMPLET_CONTENT($_GET['page_identity'],'');
$topbar = SETUP::TOP_BAR($_GET['page_identity'],'');
$info1 = FIND::SUMMERY();
$formatted_data = FIND::RENT_VS_DUE();


$currentMonth = date('m');
$currentYear = date('Y');
$firstDayOfMonth = date('Y-m-01', strtotime("first day of $currentYear-$currentMonth"));
$lastDayOfMonth = date('Y-m-t', strtotime("last day of $currentYear-$currentMonth"));


$timestampPrevMonth = strtotime('first day of previous month');

// Get the name and year of the previous month
$prevMonthNameYear = date('F Y', $timestampPrevMonth);



$donut1 = FIND::RECEIVE_PAYMENT('DATE-RANGE',$firstDayOfMonth,$lastDayOfMonth);
$donut2 = FIND::NOT_RECEIVE_YET($prevMonthNameYear);
$donut3 = $info1['total_unit'] - $donut2['num_of_not_receive_rent_client'] ; 



?>

    <body> 

<div class="page-container">
            
            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
            <?php include('layout/common_side_bar.php'); ?>

            </div>
            <!-- END PAGE SIDEBAR -->
            
            <!-- PAGE CONTENT -->
            <div class="page-content">
                
           <?php print $topbar['top_bar_content'];?> 
             
                
                <!-- START BREADCRUMB -->
                <ul class="breadcrumb push-down-0">
                    <li class="active"><a href="#">Dashbord </a></li>                    
                </ul>
                <!-- END BREADCRUMB -->                                                
                
                <!-- START CONTENT FRAME -->
                <div class="page-content-wrap">
               

                <div class="row">
                        <div class="col-md-3">
                            
                            <!-- START WIDGET SLIDER -->
                            <div class="widget widget-default widget-carousel">
                                
                            <div class="widget-item-left">
                                    <span class="fa fa-home"></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-int num-count"><?php print $info1['total_vobon'];?></div>
                                    <div class="widget-title">মোট ভবন</div>
                                    <div class="widget-subtitle"></div>
                                </div>      
                                

                                                             
                            </div>         
                            <!-- END WIDGET SLIDER -->
                            
                        </div>
                        <div class="col-md-3">
                            
                            <!-- START WIDGET MESSAGES -->
                            <div class="widget widget-default widget-item-icon" >
                                <div class="widget-item-left">
                                    <span class="fa fa-heart"></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-int num-count"><?php print $info1['total_unit'];?></div>
                                    <div class="widget-title">মোট ইউনিট</div>
                                    <div class="widget-subtitle"></div>
                                </div>      
                                
                            </div>                            
                            <!-- END WIDGET MESSAGES -->
                            
                        </div>
                        <div class="col-md-3">
                            
                            <!-- START WIDGET REGISTRED -->
                            <div class="widget widget-default widget-item-icon" >
                                <div class="widget-item-left">
                                    <span class="fa fa-heart-o"></span>
                                </div>
                                <div class="widget-data">
                                    <div class="widget-int num-count"><?php print $info1['unit_blank'];?></div>
                                    <div class="widget-title">ইউনিট খালি</div>
                                    <div class="widget-subtitle"></div>
                                </div>
                                                           
                            </div>                            
                            <!-- END WIDGET REGISTRED -->
                            
                        </div>
                        <div class="col-md-3">
                            
                            <!-- START WIDGET REGISTRED -->
                            <div class="widget widget-default widget-item-icon" >
                                <div class="widget-item-left">
                                    <span class="fa fa-group"></span>
                                </div>
                                <div class="widget-data">
                                    <div class="widget-int num-count"><?php print $info1['total_client'];?></div>
                                    <div class="widget-title">মোট ভাড়াটিয়া</div>
                                    <div class="widget-subtitle"></div>
                                </div>
                                                           
                            </div>                            
                            <!-- END WIDGET REGISTRED -->
                            
                        </div>
                    </div>

            <div class="row">
            <div class="col-md-6">
                            
                            <!-- START VISITORS BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>কত ইউনিট ভাড়া উঠান বাকি ?</h3>
                                        <span>চলতি মাসের </span>
                                    </div>
                                    
                                </div>
                                <div class="panel-body padding-0">
                                    <div class="chart-holder" id="dashboard-donut-1" style="height: 200px;"></div>
                                </div>
                            </div>
                            <!-- END VISITORS BLOCK -->
                            
                        </div>
                        <div class="col-md-6">
                            
                            <!-- START VISITORS BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>অন্যান্য আয় এবং ব্যয়  </h3>
                                        <span>চলতি মাসের </span>
                                    </div>
                                    
                                </div>
                                <div class="panel-body padding-0">
                                    <div class="chart-holder" id="dashboard-donut-2" style="height: 200px;"></div>
                                </div>
                            </div>
                            <!-- END VISITORS BLOCK -->
                            
                        </div>
                        
            </div>
                    
                    <!-- END WIDGETS -->       
                    <div class="row">
                        <div class="col-md-12">
                            
                            <!-- START USERS ACTIVITY BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>ভাড়া বনাম কালেকশন </h3>
                                        <span>মাস ভিত্তিক </span>
                                    </div>                                    
                                                                       
                                </div>                                
                                <div class="panel-body padding-0">
                                    <div class="chart-holder" id="dashboard-bar-1" style="height: 200px;"></div>
                                </div>                                    
                            </div>
                            <!-- END USERS ACTIVITY BLOCK -->
                            
                        </div> </div>
                </div>
                <!-- END CONTENT FRAME -->
                        





                
            <div class="row">
                        <div class="col-md-12">
                <div id="load_content"><?php  print  $templet_content['content'];?></div>
</div></div>
                
                


            </div>        
            
    
            <!-- END PAGE CONTENT -->
        </div>
 <!-- MESSAGE BOX-->
 <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
    <div class="mb-container">
        <div class="mb-middle">
            <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
            <div class="mb-content">
                <p>Are you sure you want to log out?</p>                    
                <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
            </div>
            <div class="mb-footer">
                <div class="pull-right">
                    <a href="logout.php" class="btn btn-success btn-lg">Yes</a>
                    <button class="btn btn-default btn-lg mb-control-close">No</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MESSAGE BOX-->



         <!-- START PRELOADS -->
         <!-- START PLUGINS -->
         <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>        
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
        
        <script type="text/javascript" src="js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="js/plugins/morris/morris.min.js"></script>       
        <script type="text/javascript" src="js/plugins/rickshaw/d3.v3.js"></script>
        <script type="text/javascript" src="js/plugins/rickshaw/rickshaw.min.js"></script>
        <script type='text/javascript' src='js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'></script>
        <script type='text/javascript' src='js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'></script>                
        <script type='text/javascript' src='js/plugins/bootstrap/bootstrap-datepicker.js'></script>                
        <script type="text/javascript" src="js/plugins/owl/owl.carousel.min.js"></script>                 
        
        <script type="text/javascript" src="js/plugins/moment.min.js"></script>
        <script type="text/javascript" src="js/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>
        

        <script>
          $(function(){        




    Morris.Donut({
        element: 'dashboard-donut-2',
        data: [
            {label: "আয়", value: <?php echo $donut1['cash_in'];?>},
            {label: "ব্যয়", value: <?php echo $donut1['cash_out'];?>},
        ],
        colors: [ '#1caf9a', '#FEA223'],
        resize: true
    });
    /* END Donut dashboard chart */


    Morris.Donut({
        element: 'dashboard-donut-1',
        data: [
            {label: "বাকি", value: <?php echo $donut2['num_of_not_receive_rent_client'];?>},
            {label: "ইউনিট", value: <?php print $info1['total_unit'];?>} ,
            {label: "তুলেছি", value: <?php print $donut3 ;?> }        ],

        colors: ['#33414E', '#1caf9a', '#FEA223'],
        resize: true
    });
    /* END Donut dashboard chart */



    /* Bar dashboard chart */
    Morris.Bar({
    element: 'dashboard-bar-1',
    data: <?php echo $formatted_data; ?>,
    xkey: 'y',
    ykeys: ['a', 'b'],
    labels: ['Rent', 'Receive'],
    barColors: ['#33414E', '#1caf9a'],
    gridTextSize: '10px',
    hideHover: true,
    resize: true,
    gridLineColor: '#E5E5E5'
});
    /* END Bar dashboard chart */
    

});

 
        </script>
        <!-- END TEMPLATE -->
    <!-- END SCRIPTS -->         
    </body>
</html>







