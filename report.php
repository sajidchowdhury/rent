<?php 
include('auth.php');
include('layout/head.php');
include('function_query.php');

$topbar = SETUP::TOP_BAR($_GET['page_identity'],'');





?>
<style>

</style>
    <body> 

<div class="page-container">
            
            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar  hiddenprint">
            <?php include('layout/common_side_bar.php'); ?>

            </div>
            <!-- END PAGE SIDEBAR -->
            
            <!-- PAGE CONTENT -->
            <div class="page-content">
                
           <?php print $topbar['top_bar_content'];?> 
             
                
                                                   
                
                <!-- START CONTENT FRAME -->
                <div class="page-content-wrap">
                    <?php include("$_GET[page_identity].php");?>
                </div>
      
                </div>
                <!-- END CONTENT FRAME -->
                
                
                
            </div>            
            <!-- END PAGE CONTENT -->
        </div>


<?php include('javascripts.php'); ?>
<script type="text/javascript" src="script.js">
</script>

    </body>
</html>






