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
                                <h4 class="title">Edit Employee</h4>
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
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="hidden" class="form-control" placeholder="Employee Id" name="employee_id" id="employee_id" value="<?php echo $employee_id;?>">
                                                <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="<?php echo $info_arr->employees[0]->first_name;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Middle Name</label>
                                                <input type="text" class="form-control" placeholder="Middle Name" name="middle_name" id="middle_name" value="<?php echo $info_arr->employees[0]->middle_name;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Last Name</label>
                                                <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" value="<?php echo $info_arr->employees[0]->last_name;?>">
                                            </div>        
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Home Address"  name="address" id="address" value="<?php echo $info_arr->employees[0]->address;?>">
                                            </div>        
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone No</label>
                                                <input type="text" class="form-control" placeholder="Phone No" name="phone_no" id="phone_no" value="<?php echo $info_arr->employees[0]->phone_no;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" placeholder="Email"  name="email" id="phone_no" value="<?php echo $info_arr->employees[0]->email;?>">
                                            </div>        
                                        </div>                                        
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Employee Code</label>
                                                <input type="text" class="form-control" placeholder="Employee Code" name="employee_code" id="employee_code" value="<?php echo $info_arr->employees[0]->employee_code;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ID Card No.</label>
                                                <input type="text" class="form-control" placeholder="ID Card No." name="idcard_no" id="idcard_no" value="<?php echo $info_arr->employees[0]->idcard_no;?>">
                                            </div>        
                                        </div>                                        
                                    </div>                                                                                                                                                                                                                        
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <input type="text" class="form-control" placeholder="Gender" name="gender" id="gender" value="<?php echo $info_arr->employees[0]->gender;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="text" class="form-control" placeholder="YYYY-MM-DD" name="dob" id="dob" value="<?php echo $info_arr->employees[0]->dob;?>">
                                            </div>        
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info btn-fill pull-right" name="updateEmployee" id="updateEmployee" >Update Employee</button>
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
                                      <input type="hidden" class="form-control" placeholder="Employee Id" name="employee_id" id="employee_id" value="<?php echo $employee_id;?>">
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
                                                   $selected = ($emp_channel_id == $info_arr->channels[$a]->id)?'selected':'';
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
                                      <input type="hidden" class="form-control" placeholder="Employee Id" name="employee_id" id="employee_id" value="<?php echo $employee_id;?>">
                                      <div class="row">
                                        <div class="col-md-12">                                            
                                                <label>Device</label>                                                                                                                                                                                                                                              
                                        </div>
                                      <div class="row">
                                        <div class="col-md-9">                                                                                            
                                                <select class="form-control" placeholder="--select--" name="device_id" id="device_id">
                                                  <?php 
                                                   require_once("api/Device.php");
                                                   $emp = new Device();
                                                   $info = $emp->getDeviceList();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->devices);
                                                   for($a=0;$a<$tot;$a++){
                                                   $selected = ($emp_device_id == $info_arr->devices[$a]->device_id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $info_arr->devices[$a]->device_id;?>" <?php echo $selected;?>><?php echo $info_arr->devices[$a]->imei;?></option>
                                                  <?php } ?>
                                                </select>                                                                                                                                                                                                
                                        </div>
                                        <div class="col-md-3">                                                                                            
                                                <button type="submit" class="btn btn-info btn-fill pull-right" name="updateDevice" id="updateDevice">Update</button>                                                                                                                                               
                                        </div>
                                    </div>
                                   </form>                                   
                                </p> 
                            </div>
                            
                            <div class="content">                                 
                                <p class="description text-center"> 
                                   <form class="form-horizontal" method="POST" action="">
                                      <input type="hidden" class="form-control" placeholder="Employee Id" name="employee_id" id="employee_id" value="<?php echo $employee_id;?>">
                                      <div class="row">
                                        <div class="col-md-12">                                            
                                                <label>Branch</label>                                                                                                                                                                                                                                              
                                        </div>
                                      <div class="row">
                                        <div class="col-md-9">                                                                                            
                                                <select class="form-control" placeholder="--select--" name="branch_id" id="branch_id">
                                                  <?php 
                                                   require_once("api/Branch.php");
                                                   $emp = new Branch();
                                                   $info = $emp->getBranchList();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->branches);                                                                                                      
                                                   for($a=0;$a<$tot;$a++){
                                                   $selected = ($emp_branch_id == $info_arr->branches[$a]->branch_id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $info_arr->branches[$a]->branch_id;?>" <?php echo $selected;?>><?php echo $info_arr->branches[$a]->branch;?></option>
                                                  <?php } ?>
                                                </select>                                                                                                                                                                                                
                                        </div>
                                        <div class="col-md-3">                                                                                            
                                                <button type="submit" class="btn btn-info btn-fill pull-right" name="updateBranch" id="updateBranch">Update</button>                                                                                                                                               
                                        </div>
                                    </div>
                                   </form>                                   
                                </p> 
                            </div>
                            
                            <div class="content">                                 
                                <p class="description text-center"> 
                                   <form class="form-horizontal" method="POST" action="">
                                      <input type="hidden" class="form-control" placeholder="Employee Id" name="employee_id" id="employee_id" value="<?php echo $employee_id;?>">
                                      <div class="row">
                                        <div class="col-md-12">                                            
                                                <label>Department</label>                                                                                                                                                                                                                                              
                                        </div>
                                      <div class="row">
                                        <div class="col-md-9">                                                                                            
                                                <select class="form-control" placeholder="--select--" name="department_id" id="department_id">
                                                  <?php 
                                                   require_once("api/Department.php");
                                                   $emp = new Department();
                                                   $info = $emp->getDepartmentList();
                                                   $info_arr= json_decode($info);
                                                   $tot = count($info_arr->departments);                                                                                                      
                                                   for($a=0;$a<$tot;$a++){
                                                   $selected = ($emp_dept_id == $info_arr->departments[$a]->department_id)?'selected':'';
                                                  ?>
                                                   <option value="<?php echo $info_arr->departments[$a]->department_id;?>" <?php echo $selected;?>><?php echo $info_arr->departments[$a]->department;?></option>
                                                  <?php } ?>
                                                </select>                                                                                                                                                                                                
                                        </div>
                                        <div class="col-md-3">                                                                                            
                                                <button type="submit" class="btn btn-info btn-fill pull-right" name="updateDepartment" id="updateDepartment">Update</button>                                                                                                                                               
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