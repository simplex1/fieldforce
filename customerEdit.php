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
                    <div class="col-md-8">
                    <?php                                       
                                     require_once("api/Customer.php");
                                     $emp = new Customer();
                                     $res = $box_type = "";
                                     if(isset($_REQUEST['updateCustomer'])){
                                     $info = $emp->updateCustomer();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Customer details updated!':'Customer update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     } 
                                     if(isset($_REQUEST['updateChannel'])){
                                     $info = $emp->updateCustomerChannel();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Channel details updated!':'Channel update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }
                                     if(isset($_REQUEST['updateCredit'])){
                                     $info = $emp->updateCustomerCredit();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Credit details updated!':'Credit update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }
                                     if(isset($_REQUEST['updateVisitDay'])){
                                     $info = $emp->updateCustomerVisitDay();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Visit Day updated!':'Visit Day update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }
                                     if(isset($_REQUEST['updateEmployee'])){
                                     $info = $emp->updateCustomerEmployee();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Sales Rep updated!':'Sales Rep update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }                                  
                                    ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Edit Customer</h4>
                            </div>                                
                            <div class="content">
                              <?php                                                                            
                                     $info = $emp->getCustomer();
                                     $info_arr= json_decode($info);
                                     $customer_id = $info_arr->customers[0]->customer_id;
                                     
                                     $chan_info = $emp->getCustomerChannel($customer_id);                                     
                                     $chan_info_arr= json_decode($chan_info);
                                     $cust_channel_id = $chan_info_arr->channel_id;
                                     
                                     $credit_info = $emp->getCustomerCredit($customer_id);                                     
                                     $credit_info_arr= json_decode($credit_info);
                                     $cust_credit_id = $credit_info_arr->credit_id;                                                                          
                                     
                                     $visit_day_info = $emp->getCustomerVisitDay($customer_id);                                     
                                     $visit_day_info_arr= json_decode($visit_day_info);
                                     $cust_visit_day_id = $visit_day_info_arr->visit_day_id;   
                                     
                                     $employee_info = $emp->getCustomerEmployee($customer_id);                                     
                                     $employee_info_arr= json_decode($employee_info);
                                     $cust_employee_id = $employee_info_arr->employee_id;                                                                                                                                                                                      
                                    ?>
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Outlet Name</label>
                                                <input type="hidden" class="form-control" placeholder="Customer Id" name="customer_id" id="customer_id" value="<?php echo $customer_id;?>">
                                                <input type="text" class="form-control" disabled placeholder="Outlet Name" value="<?php echo $info_arr->customers[0]->outlet_name;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Phone No</label>
                                                <input type="text" class="form-control" placeholder="Phone No" name="phone_no" id="phone_no" value="<?php echo $info_arr->customers[0]->phone_no;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Email address</label>
                                                <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="<?php echo $info_arr->customers[0]->email;?>">
                                            </div>        
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Business Address"  name="outlet_address" id="outlet_address" value="<?php echo $info_arr->customers[0]->outlet_address;?>">
                                            </div>        
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" class="form-control" placeholder="City" name="city" id="city" value="<?php echo $info_arr->customers[0]->city;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <input type="text" class="form-control" placeholder="State"  name="state" id="state" value="<?php echo $info_arr->customers[0]->state;?>">
                                            </div>        
                                        </div>                                        
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="<?php echo $info_arr->customers[0]->first_name;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" value="<?php echo $info_arr->customers[0]->last_name;?>">
                                            </div>        
                                        </div>                                        
                                    </div>                                                                                                                                                                                                                        
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="text" class="form-control" placeholder="YYYY-MM-DD" name="dob" id="dob" value="<?php echo $info_arr->customers[0]->dob;?>">
                                            </div>        
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info btn-fill pull-right" name="updateCustomer" id="updateCustomer" >Update Customer</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                       <div class="card card-user">
                        <div class="image">
                                <img src="assets/img/photo-bg.jpg" alt="..."/>   
                            </div>
                            <div class="author">
                                     <a href="#">
                                    <img class="avatar border-gray" src="assets/img/faces/face-0.jpg" alt="..."/>                                                                          
                                    </a>
                                </div>
                           </div>     
                        <!--<div class="card card-user">
                            <div class="image">
                                <img src="assets/img/photo-bg.jpg" alt="..."/>   
                            </div>
                            <div class="author">
                                     <a href="#">
                                    <img class="avatar border-gray" src="assets/img/faces/face-0.jpg" alt="..."/>                                                                          
                                    </a>
                                </div>-->
                                
                                    <div class="content">                                  
                                <p class="description text-center"> 
                                   <form class="form-horizontal" method="POST" action="">
                                      <input type="hidden" class="form-control" placeholder="Customer Id" name="customer_id" id="customer_id" value="<?php echo $customer_id;?>">
                                      <div class="row">
                                        <div class="col-md-12">                                            
                                                <label>Channel</label>                                                                                                                                                                                                                                              
                                        </div>
                                      <div class="row">
                                        <div class="col-md-9">                                                                                            
                                                <select class="form-control" placeholder="--select--" name="channel_id" id="channel_id">
                                                  <?php 
                                                   require_once("api/Channel.php");
                                                   $emp = new Channel();
                                                   $info = $emp->getChannelList();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->channels);
                                                   for($a=0;$a<$tot;$a++){
                                                   $selected = ($cust_channel_id == $info_arr->channels[$a]->id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $info_arr->channels[$a]->id;?>" <?php echo $selected;?>><?php echo $info_arr->channels[$a]->name;?></option>
                                                  <?php } ?>
                                                </select>                                                                                                                                                                                                
                                        </div>
                                        <div class="col-md-3">                                                                                            
                                                <button type="submit" class="btn btn-info btn-fill pull-right" name="updateChannel" id="updateChannel">Update</button>                                                                                                                                               
                                        </div>
                                    </div>
                                   </form>                                   
                                </p> 
                            </div>
                             
                            <div class="content">                                 
                                <p class="description text-center"> 
                                   <form class="form-horizontal" method="POST" action="">
                                      <input type="hidden" class="form-control" placeholder="Customer Id" name="customer_id" id="customer_id" value="<?php echo $customer_id;?>">
                                      <div class="row">
                                        <div class="col-md-12">                                            
                                                <label>Credit Limit</label>                                                                                                                                                                                                                                              
                                        </div>
                                      <div class="row">
                                        <div class="col-md-9">                                                                                            
                                                <select class="form-control" placeholder="--select--" name="credit_id" id="credit_id">
                                                  <?php 
                                                   require_once("api/Credit.php");
                                                   $emp = new Credit();
                                                   $info = $emp->getCreditList();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->credits);
                                                   for($a=0;$a<$tot;$a++){
                                                   $selected = ($cust_credit_id == $info_arr->credits[$a]->id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $info_arr->credits[$a]->id;?>" <?php echo $selected;?>><?php echo number_format($info_arr->credits[$a]->amount,2).' ('.$info_arr->credits[$a]->duration.' days)';?></option>
                                                  <?php } ?>
                                                </select>                                                                                                                                                                                                
                                        </div>
                                        <div class="col-md-3">                                                                                            
                                                <button type="submit" class="btn btn-info btn-fill pull-right" name="updateCredit" id="updateCredit">Update</button>                                                                                                                                               
                                        </div>
                                    </div>
                                   </form>                                   
                                </p> 
                            </div>
                            
                            <div class="content">                                 
                                <p class="description text-center"> 
                                   <form class="form-horizontal" method="POST" action="">
                                      <input type="hidden" class="form-control" placeholder="Customer Id" name="customer_id" id="customer_id" value="<?php echo $customer_id;?>">
                                      <div class="row">
                                        <div class="col-md-12">                                            
                                                <label>Visit Day</label>                                                                                                                                                                                                                                              
                                        </div>
                                      <div class="row">
                                        <div class="col-md-9">                                                                                            
                                                <select class="form-control" placeholder="--select--" name="visit_day_id" id="visit_day_id">
                                                  <?php 
                                                   require_once("api/VisitDay.php");
                                                   $emp = new VisitDay();
                                                   $info = $emp->getVisitDayList();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->visit_days);                                                                                                      
                                                   for($a=0;$a<$tot;$a++){
                                                   $selected = ($cust_visit_day_id == $info_arr->visit_days[$a]->id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $info_arr->visit_days[$a]->id;?>" <?php echo $selected;?>><?php echo $info_arr->visit_days[$a]->visit_day;?></option>
                                                  <?php } ?>
                                                </select>                                                                                                                                                                                                
                                        </div>
                                        <div class="col-md-3">                                                                                            
                                                <button type="submit" class="btn btn-info btn-fill pull-right" name="updateVisitDay" id="updateVisitDay">Update</button>                                                                                                                                               
                                        </div>
                                    </div>
                                   </form>                                   
                                </p> 
                            </div>
                            
                            <div class="content">                                 
                                <p class="description text-center"> 
                                   <form class="form-horizontal" method="POST" action="">
                                      <input type="hidden" class="form-control" placeholder="Customer Id" name="customer_id" id="customer_id" value="<?php echo $customer_id;?>">
                                      <div class="row">
                                        <div class="col-md-12">                                            
                                                <label>Sales Rep</label>                                                                                                                                                                                                                                              
                                        </div>
                                      <div class="row">
                                        <div class="col-md-9">                                                                                            
                                                <select class="form-control" placeholder="--select--" name="employee_id" id="employee_id">
                                                  <?php 
                                                   require_once("api/Employee.php");
                                                   $emp = new Employee();
                                                   $info = $emp->getEmployees();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->employees);                                                                                                      
                                                   for($a=0;$a<$tot;$a++){
                                                   $selected = ($cust_employee_id == $info_arr->employees[$a]->id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $info_arr->employees[$a]->id;?>" <?php echo $selected;?>><?php echo $info_arr->employees[$a]->last_name.' '.$info_arr->employees[$a]->first_name;?></option>
                                                  <?php } ?>
                                                </select>                                                                                                                                                                                                
                                        </div>
                                        <div class="col-md-3">                                                                                            
                                                <button type="submit" class="btn btn-info btn-fill pull-right" name="updateEmployee" id="updateEmployee">Update</button>                                                                                                                                               
                                        </div>
                                    </div>
                                   </form>                                   
                                </p> 
                            </div>
                            
                                                                                                                
                        <!--</div>-->
                        <!-- Data Elements-->
                        
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