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
                    <div class="col-md-6">
                    <?php                                       
                                     require_once("api/Employee.php");
                                     $emp = new Employee();
                                     $res = $box_type = "";
                                     if(isset($_REQUEST['updateEmployee'])){
                                     $info = $emp->updateEmployee();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Employee details updated!':'Employee update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     } 
                                     if(isset($_REQUEST['updateChannel'])){
                                     $info = $emp->updateEmployeeChannel();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Channel details updated!':'Channel update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }
                                     if(isset($_REQUEST['updateDevice'])){
                                     $info = $emp->updateEmployeeDevice();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Device details updated!':'Device update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }
                                     if(isset($_REQUEST['updateBranch'])){
                                     $info = $emp->updateEmployeeBranch();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Branch updated!':'Branch update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }
                                     if(isset($_REQUEST['updateDepartment'])){
                                     $info = $emp->updateEmployeeDepartment();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Department updated!':'Department update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }                                
                                    ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Sales Rep Bin</h4>
                            </div>                                
                            <div class="content">
                              <?php                                                                            
                                     $info = $emp->getEmployeeList();
                                     $info_arr= json_decode($info);
                                     $employee_id = $info_arr->employees[0]->employee_id;                                     
                                     
                                     $chan_info = $emp->getEmployeeChannel($employee_id);                                     
                                     $chan_info_arr= json_decode($chan_info);
                                     $emp_channel_id = $chan_info_arr->channel_id;
                                     
                                     $device_info = $emp->getEmployeeDevice($employee_id);                                     
                                     $device_info_arr= json_decode($emp_info);
                                     $emp_device_id = $device_info_arr->device_id;                                                                          
                                     
                                     $branch_info = $emp->getEmployeeBranch($employee_id);                                     
                                     $branch_info_arr= json_decode($branch_info);
                                     $emp_branch_id = $branch_info_arr->branch_id;   
                                     
                                     $dept_info = $emp->getEmployeeDepartment($employee_id);                                     
                                     $dept_info_arr= json_decode($dept_info);
                                     $emp_dept_id = $dept_info_arr->department_id;                                                                                                                                                                                      
                                    ?>
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-3">
                                                   
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control reload" placeholder="--select--" name="employee_id" id="employee_id">
                                                  <?php 
                                                   require_once("api/Employee.php");
                                                   $emp = new Employee();
                                                   $info = $emp->getEmployees();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->employees);                                                                                                      
                                                   for($a=0;$a<$tot;$a++){     
                                                   $selected = (!empty($_REQUEST['emp_id']) && ($_REQUEST['emp_id'] == $info_arr->employees[$a]->id))?'selected':'';                                              
                                                  ?>
                                                   <option value="<?php echo $info_arr->employees[$a]->id;?>"  <?php echo $selected;?>><?php echo $info_arr->employees[$a]->last_name.' '.$info_arr->employees[$a]->first_name;?></option>
                                                  <?php } ?>
                                                </select>         
                                        </div>
                                        <div class="col-md-3">
                                                   
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-3">
                                                   
                                        </div>
                                        <div class="col-md-6">
                                                 
                                        </div>
                                        <div class="col-md-3">
                                          <button type="button" class="btn btn-danger btn-fill pull-right" name="newEmployeeBin" id="newEmployeeBin">Add New</button>         
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                    <?php if(!empty($_REQUEST['emp_id'])){
                                    $employee_id = $_REQUEST['emp_id'];
                                    ?>
                                        <div class="col-md-12">
                                           <div class="table-full-width">
                                    <table class="table">
                                    <thead>
                                    <th></th>
                                    <th width="40%">Product</th>
                                    <th width="30%">UOM</th>
                                    <th width="20%">Quantity</th>
                                    <th></th>                                    
                                    </thead>
                                        <tbody>
                                        <?php                                      
                                     $info = $emp->getEmployeeBin($employee_id);   
                                     $bin_arr= json_decode($info); 
                                     $bins = $bin_arr->employee_bins;                                                                           
                                     $tot = count($bins);             
                                     
                                     require_once("api/Product.php");
                                     $prod = new Product();
                                     $info = $prod->getProductList();
                                     $prod_arr= json_decode($info);                                      
                                     $prods = $prod_arr->products;
                                     $prod_tot = count($prods);
                                     
                                     require_once("api/ProductUom.php");
                                     $produom = new ProductUom();
                                     $info = $produom->getProductUomList();
                                     $produom_arr= json_decode($info); 
                                     $prod_uoms = $produom_arr->product_uoms;
                                     $produom_tot = count($prod_uoms);
                                                               
                                      for($a=0;$a<$tot;$a++){                                                                      
                                    ?>
                                            <tr id="<?php echo $bins[$a]->employee_bin_id;?>">
                                                <td>
                                                    <label class="checkbox">
                                                        <input type="checkbox" value="<?php echo $bins[$a]->employee_bin_id;?>" data-toggle="checkbox"  name="employee_bin_id" id="employee_bin_id">
                                                    </label>
                                                </td>
                                                <td>
                                                  <select class="form-control" placeholder="--select--" name="product_id" id="product_id">
                                                  <?php                                                                                                                                                          
                                                   for($b=0;$b<$prod_tot;$b++){
                                                   $selected = ($bins[$a]->product_id == $prods[$b]->product_id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $prods[$b]->product_id;?>" <?php echo $selected;?>><?php echo $prods[$b]->prod_name;?></option>
                                                  <?php } ?>
                                                </select>                                           
                                                </td> 
                                                <td>
                                                 <select class="form-control" placeholder="--select--" name="product_uom_id" id="product_uom_id">
                                                  <?php                                                                                                                                                          
                                                   for($c=0;$c<$produom_tot;$c++){
                                                   $selected = ($bins[$a]->product_uom_id == $prod_uoms[$c]->product_uom_id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $prod_uoms[$c]->product_uom_id;?>" <?php echo $selected;?>><?php echo $prod_uoms[$c]->prod_uom;?></option>
                                                  <?php } ?>
                                                </select>
                                                </td> 
                                                <td><input type="text" class="form-control" placeholder="quantity" name="quantity" id="quantity" value="<?php echo $bins[$a]->quantity;?>"></td>
                                                <td><button type="button" onclick="javascript:demo.updateRepBin(<?php echo $bins[$a]->employee_bin_id;?>,<?php echo $employee_id;?>);" class="btn btn-info btn-fill pull-right" name="updateEmployeeBin" id="updateEmployeeBin">Update</button></td>                                                                                                
                                            </tr>
                                      <?php }?>                                                                                             
                                        </tbody>
                                    </table>
                                </div>        
                                        </div>
                                        <?php }?>
                                    </div>                                                                        
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                       <div class="card">
                           <div class="header">
                                <h4 class="title">Bin Returns</h4>
                            </div>                                
                            <div class="content">
                                  <form method="POST" action="">
                                    <div class="row">                                        
                                        <div class="col-md-6">
                                            <select class="form-control" placeholder="--select--" name="employee_id" id="employee_id">
                                                  <?php 
                                                   require_once("api/Employee.php");
                                                   $emp = new Employee();
                                                   $info = $emp->getEmployees();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->employees);                                                                                                      
                                                   for($a=0;$a<$tot;$a++){     
                                                   $selected = (!empty($_REQUEST['emp_id']) && ($_REQUEST['emp_id'] == $info_arr->employees[$a]->id))?'selected':'';                                              
                                                  ?>
                                                   <option value="<?php echo $info_arr->employees[$a]->id;?>"  <?php echo $selected;?>><?php echo $info_arr->employees[$a]->last_name.' '.$info_arr->employees[$a]->first_name;?></option>
                                                  <?php } ?>
                                                </select>         
                                        </div>
                                        <div class="col-md-4"> 
                                            <input type="text" class="form-control" value="<?php echo isset($_REQUEST['sales_date'])?$_REQUEST['sales_date']:date('Y-m-d');?>" placeholder="yyyy-mm-dd" name="sales_date" id="sales_date">       
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-info btn-fill pull-right" name="loadReturns" id="loadReturns">Go</button>       
                                        </div>
                                    </div>
                                    <div class="row">
                                    <?php if(!empty($_REQUEST['emp_id'])){
                                    $employee_id = $_REQUEST['emp_id'];
                                    ?>
                                        <div class="col-md-12">
                                           <div class="table-full-width">
                                    <table class="table">
                                    <thead>
                                    <th></th>
                                    <th width="40%">Product</th>
                                    <th width="30%">UOM</th>
                                    <th width="20%">Quantity</th>
                                    <th></th>                                    
                                    </thead>
                                        <tbody>
                                        <?php                                      
                                     $info = $emp->getEmployeeBin($employee_id);   
                                     $bin_arr= json_decode($info); 
                                     $bins = $bin_arr->employee_bins;                                                                           
                                     $tot = count($bins);             
                                     
                                     require_once("api/Product.php");
                                     $prod = new Product();
                                     $info = $prod->getProductList();
                                     $prod_arr= json_decode($info);                                      
                                     $prods = $prod_arr->products;
                                     $prod_tot = count($prods);
                                     
                                     require_once("api/ProductUom.php");
                                     $produom = new ProductUom();
                                     $info = $produom->getProductUomList();
                                     $produom_arr= json_decode($info); 
                                     $prod_uoms = $produom_arr->product_uoms;
                                     $produom_tot = count($prod_uoms);
                                                               
                                      for($a=0;$a<$tot;$a++){                                                                      
                                    ?>
                                            <tr id="<?php echo $bins[$a]->employee_bin_id;?>">
                                                <td>
                                                    <label class="checkbox">
                                                        <input type="checkbox" value="<?php echo $bins[$a]->employee_bin_id;?>" data-toggle="checkbox"  name="employee_bin_id" id="employee_bin_id">
                                                    </label>
                                                </td>
                                                <td>
                                                  <select class="form-control" placeholder="--select--" name="product_id" id="product_id">
                                                  <?php                                                                                                                                                          
                                                   for($b=0;$b<$prod_tot;$b++){
                                                   $selected = ($bins[$a]->product_id == $prods[$b]->product_id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $prods[$b]->product_id;?>" <?php echo $selected;?>><?php echo $prods[$b]->prod_name;?></option>
                                                  <?php } ?>
                                                </select>                                           
                                                </td> 
                                                <td>
                                                 <select class="form-control" placeholder="--select--" name="product_uom_id" id="product_uom_id">
                                                  <?php                                                                                                                                                          
                                                   for($c=0;$c<$produom_tot;$c++){
                                                   $selected = ($bins[$a]->product_uom_id == $prod_uoms[$c]->product_uom_id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $prod_uoms[$c]->product_uom_id;?>" <?php echo $selected;?>><?php echo $prod_uoms[$c]->prod_uom;?></option>
                                                  <?php } ?>
                                                </select>
                                                </td> 
                                                <td><input type="text" class="form-control" placeholder="quantity" name="quantity" id="quantity" value="<?php echo $bins[$a]->quantity;?>"></td>
                                                <td><button type="button" onclick="javascript:demo.updateRepBin(<?php echo $bins[$a]->employee_bin_id;?>,<?php echo $employee_id;?>);" class="btn btn-info btn-fill pull-right" name="updateEmployeeBin" id="updateEmployeeBin">Update</button></td>                                                                                                
                                            </tr>
                                      <?php }?>                                                                                             
                                        </tbody>
                                    </table>
                                </div>        
                                        </div>
                                        <?php }?>
                                    </div>
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