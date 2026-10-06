<?php 

?>  <!-- MESSAGE BOX-->
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

<div class="modal fade" id="exampleModalCenter"  data-sound="alert" tabindex="-1" role="dialog" aria-labelledby="largeModalHead" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-ku">
        <div class="modal-content modal-content">
            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" >

                <div id="dash22" style="background-color:#f5f5f5">
                    <!-- Content goes in here -->
                </div>

            </div>
            <div class="modal-footer">
                <button  type="button"  class="btn btn-default" data-dismiss="modal">Close</button>
 <!-- <button  type="button" class="btn btn-danger right"  onclick="printDiv('printableArea')">Print</button>-->
            </div>
        </div>
    </div>
</div>





<!-- START PRELOADS -->
<audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
<!-- END PRELOADS -->

<!-- START SCRIPTS -->
<!-- START PLUGINS -->
<script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>

<script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<script type='text/javascript' src='js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'></script>
<script type='text/javascript' src='js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'></script>
<script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>                            
<script type="text/javascript" src="js/plugins/highlight/jquery.highlight-4.js"></script>

<!-- END PLUGINS -->




<!-- START THIS PAGE PLUGINS-->

<script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-colorpicker.js"></script>


<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
<script type='text/javascript' src='js/plugins/bootstrap/bootstrap-datepicker.js'></script>

<script type="text/javascript" src="js/plugins/dropzone/dropzone.min.js"></script>
        <script type="text/javascript" src="js/plugins/fileinput/fileinput.min.js"></script>    

        

<script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>

<script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>


<script type="text/javascript" src="js/plugins/smartwizard/jquery.smartWizard-2.0.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery-validation/jquery.validate.js"></script>


<script type="text/javascript" src="js/plugins/owl/owl.carousel.min.js"></script>
<script type="text/javascript" src="js/plugins/moment.min.js"></script>


<script type="text/javascript" src="js/plugins/daterangepicker/daterangepicker.js"></script>
<!-- END THIS PAGE PLUGINS-->
<!-- START TEMPLATE 

-->        <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>    

<script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput.min.js'></script>

 <!-- START TEMPLATE -->
        
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>
        
        <!-- END TEMPLATE -->
<script type="text/javascript" src="js/faq.js"></script>

<!-- THIS PAGE PLUGINS -->

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script type="text/javascript" src="js/plugins/summernote/summernote.js"></script>
<!-- END PAGE PLUGINS -->



<script type="text/javascript" src="js/plugins/tocify/jquery.tocify.min.js"></script>


<style>
.form-group.required.control-label:after{
   content: "*";
   color: red;
}
</style>

<script>
    $('.date').datepicker({  
       format: 'dd-mm-yyyy'  
     }); 

    $(".monthonly").datepicker( {
    format: "mm-yyyy",
    startView: "months", 
    minViewMode: "months"
    });






</script>

