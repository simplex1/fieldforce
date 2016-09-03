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
                                     $info = $emp->updateEmployeeUser();
                                     $info_arr= json_decode($info);
                                     $res = $info_arr->result>0?'Profile updated!':'update failed!';
                                     $box_type = $info_arr->result>0?'success':'danger';  
                                     }                                   
                                    ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Edit Profile</h4>
                            </div>                                
                            <div class="content">
                              <?php                                                                            
                                     $info = $emp->getEmployeeByUserId();
                                     $info_arr= json_decode($info);                                     
                                    ?>
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Company</label>
                                                <input type="hidden" class="form-control" placeholder="Employee Id" name="employee_id" id="employee_id" value="<?php echo $info_arr->employee_id;?>">
                                                <input type="text" class="form-control" disabled placeholder="Company" value="<?php echo $info_arr->company_name;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" class="form-control" disabled placeholder="Username" value="<?php echo $info_arr->username;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Email address</label>
                                                <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="<?php echo $info_arr->email;?>">
                                            </div>        
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="<?php echo $info_arr->first_name;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Middle Name</label>
                                                <input type="text" class="form-control" placeholder="Middle Name" name="middle_name" id="middle_name" value="<?php echo $info_arr->middle_name;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" placeholder="Last Name"  name="last_name" id="last_name" value="<?php echo $info_arr->last_name;?>">
                                            </div>        
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Home Address"  name="address" id="address" value="<?php echo $info_arr->address;?>">
                                            </div>        
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <input type="text" class="form-control" placeholder="Gender" name="gender" id="gender" value="<?php echo $info_arr->gender;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="text" class="form-control" placeholder="YYYY-MM-DD"  name="dob" id="dob" value="<?php echo $info_arr->dob;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone No</label>
                                                <input type="number" class="form-control" placeholder="Phone No" name="phone_no" id="phone_no" value="<?php echo $info_arr->phone_no;?>">
                                            </div>        
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Employee Code</label>
                                                <input type="text" class="form-control" placeholder="Employee Code" name="employee_code" id="employee_code" value="<?php echo $info_arr->employee_code;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>ID Card No</label>
                                                <input type="text" class="form-control" placeholder="ID Card No"  name="idcard_no" id="idcard_no" value="<?php echo $info_arr->idcard_no;?>">
                                            </div>        
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" placeholder="Password"  name="password" id="password" value="">
                                            </div>        
                                        </div>                                        
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>About Me</label>
                                                <textarea rows="5" class="form-control" placeholder="your description" name="about_me" id="about_me"><?php echo $info_arr->about_me;?></textarea>
                                            </div>        
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info btn-fill pull-right" name="updateEmployee" id="updateEmployee" >Update Profile</button>
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
                            <div class="content">
                                <div class="author">
                                     <a href="#">
                                    <img class="avatar border-gray" src="assets/img/faces/face-0.jpg" alt="..."/>
                                   
                                      <h4 class="title"><?php echo $info_arr->first_name.' '.$info_arr->last_name;?><br />
                                         <small><?php echo $info_arr->username;?></small>
                                      </h4> 
                                    </a>
                                </div>  
                                <p class="description text-center"> <?php echo $info_arr->about_me;?>
                                </p>
                            </div>
                            <hr>
                            <div class="text-center">
                                <button href="#" class="btn btn-simple"><i class="fa fa-facebook-square"></i></button>
                                <button href="#" class="btn btn-simple"><i class="fa fa-twitter"></i></button>
                                <button href="#" class="btn btn-simple"><i class="fa fa-google-plus-square"></i></button>
    
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