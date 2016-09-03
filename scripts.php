<!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
  
  <!--  Datepicker   -->
  <!--script type="text/javascript" src="assets/js/moment-with-locales.js"></script-->
    <script type="text/javascript" src="assets/js/bootstrap-datepicker.js"></script>
	
	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
	
	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>
    
    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>        
	
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js"></script>
	
	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
	
	<script type="text/javascript">
    	$(document).ready(function(){
        	
           /*date picker */             
              $("#sales_date").datepicker({ format: "yyyy-mm-dd"/*, autoclose: true, todayHighlight: true */});
          
          if($("#chartPreferences").length >0 || $("#chartHours").length >0 || $("#chartActivity").length >0){
        	 demo.initChartist(); 
          }       	        	
            
            if($(".customer_map").length >0 ){          
             demo.initGoogleMaps();  
            }
            
            if($(".depot_map").length >0 ){          
             demo.depotGoogleMaps();  
            }
            
            if($("select.reload").length >0 ){          
             $("select#employee_id").change(function (){
                demo.loadRepBinPage(); 
             }); 
            }
              
            
            <?php 
                             if($res != ''){
                            ?>                                                            
                                  demo.showMessageBox('<?php echo $res;?>','<?php echo $box_type;?>');                               
                            <?php } ?>                                                                            
            
    	});
	</script>