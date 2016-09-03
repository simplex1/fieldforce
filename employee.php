<?php                        
@session_start();
if(!isset($_SESSION['user_id'])){header('Location: index.php');}
?>
<!doctype html>
<html lang="en">
<?php require_once("header.php");?>
<body> 

<div class="wrapper">
<?php require_once("sidebar.php");?>        
    <div class="main-panel">
     <?php require_once("toolbar.php");?>                                                  
        <?php 
         require_once("api/Employee.php");
         $emp = new Employee();
         $info = $emp->getEmployeeList();                  
         $search = isset($_REQUEST['search_param'])?$_REQUEST['search_param']:'';
         $qty = isset($_REQUEST['search_qty'])?$_REQUEST['search_qty']:'';
        ?>
        <script type="text/javascript">
         var coords = <?php echo $info; ?>;         
        </script>                
          <div class="row">
          <div class="col-md-3"></div>
           <div class="col-md-6">
              <form class="form-inline" method="POST" action="">
                <div class="row">
                  <input type="text" name="search_param" id="search_param" class="form-control" placeholder="Search : Enter first name / last name / address / city etc" value="<?php echo $search;?>" style="width:69%;">
                  <input type="text" name="search_qty" id="search_qty" class="form-control" placeholder="Quantity: 10" value="<?php echo $qty;?>" style="width:20%;">
                  <button type="submit" class="btn btn-info btn-fill pull-right" name="searchCustomer" id="searchCustomer" >Go</button>
                 </div>                                                                        
              </form>                                    
             </div> 
             <div class="col-md-3"></div>     
           </div>  
         <div id="map" class="depot_map"></div>         
               
        <?php require_once("footer.php");?>
        
    </div>   
</div>


</body>

    <?php require_once("scripts.php");?>
    
</html>