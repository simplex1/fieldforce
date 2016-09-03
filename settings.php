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
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                    <?php                                                                              
                                     $res = $box_type = "";                                
                                     if(isset($_REQUEST['createDevice'])){
                                     require_once("api/Device.php"); 
                                     $emp = new Device();                                     
                                     $info = $emp->__store();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Device created!':'Device NOT created!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }
                                     if(isset($_REQUEST['createCredit'])){
                                     require_once("api/Credit.php"); 
                                     $emp = new Credit();                                     
                                     $info = $emp->__store();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Credit created!':'Credit NOT created!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }
                                     if(isset($_REQUEST['createCategory'])){
                                     require_once("api/ProductCategory.php"); 
                                     $emp = new ProductCategory();                                     
                                     $info = $emp->__store();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Product Category created!':'Product Category NOT created!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }                                     
                                    ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Device</h4>
                            </div>                                
                            <div class="content">                              
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Imei</label>                                                
                                                <input type="text" class="form-control" placeholder="Device Imei" name="imei" id="imei" value="" required>
                                            </div>        
                                        </div>
                                     </div>   

                                    <button type="submit" class="btn btn-info btn-fill pull-right" name="createDevice" id="createDevice" >Create</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">                    
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Customer Credit</h4>
                            </div>                                
                            <div class="content">                              
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Amount</label>                                                
                                                <input type="text" class="form-control" placeholder="Credit Amount" name="amount" id="amount" value="" required>
                                            </div>        
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Duration</label>                                                
                                                <input type="text" class="form-control" placeholder="No. of Days" name="duration" id="duration" value="" required>
                                            </div>        
                                        </div>
                                     </div>   

                                    <button type="submit" class="btn btn-info btn-fill pull-right" name="createCredit" id="createCredit" >Create</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>  
                    
                    <div class="col-md-4">                    
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Product Category</h4>
                            </div>                                
                            <div class="content">                              
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Category</label>                                                
                                                <input type="text" class="form-control" placeholder="Product Category" name="category" id="category" value="" required>
                                            </div>        
                                        </div>                                        
                                     </div>   

                                    <button type="submit" class="btn btn-info btn-fill pull-right" name="createCategory" id="createCategory" >Create</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>                                                          
               
                </div>    
                                
            </div>
        </div>        
        
        <?php require_once("footer.php");?>
        
    </div>   
</div>


</body>

    <?php require_once("scripts.php");?>
    
</html>