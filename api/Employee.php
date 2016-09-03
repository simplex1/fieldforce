<?php
/*
  Server HTTP app
  Param : censDat
*/
/*error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);*/ 
require_once "global.php";
require_once BASEPATH."/shared/init.php";
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class Employee{
 var $db;
 var $tasks;
 var $conf;
 function __construct(){
  $this->db = dbmani::connect();
  $this->conf = init::getConf();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'delete'=>'__delete',
                      'updateEmployeeBin'=>'__updateEmployeeBin'
                     );                   
 }
 
 function __updateEmployeeBin(){    
   $employee_id = $_REQUEST['employee_id'];
   $employee_bin_id = $_REQUEST['employee_bin_id'];   
   $product_id = $_REQUEST['product_id'];
   $product_uom_id = $_REQUEST['product_uom_id'];
   $qty = $_REQUEST['qty'];     
   $qry = "CALL update_employee_bin(?,?,?,?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('iiiid',$employee_id,$employee_bin_id,$product_id,$product_uom_id,$qty);               
      $stmt->execute();                                   
      $stmt->close(); 
      
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();   
            
      return json_encode(array('result'=>$result));
 }
 
 function getEmployeeBin($employee_id){     
   $fields = array('employee_bin_id','product_id','product_uom_id','quantity');
   $qry = "CALL employee_bin(?)";
      $stmt = $this->db->con->prepare($qry); 
      $stmt->bind_param('i',$employee_id);                           
      $stmt->execute();      
      $stmt->bind_result($employee_bin_id,$product_id,$product_uom_id,$quantity);
      $employee_bins = array();         
      $b = 0;
      while($stmt->fetch()){
        $employee_bin = array();  
        foreach($fields as $k=>$v){
          $employee_bin[$v] = $$v;
        }
        $employee_bins[$b] = $employee_bin;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('employee_bins'=>$employee_bins);
      return json_encode($response_all);
 }
 
 function getEmployeeDepartment($employee_id){      
   $qry = "CALL employee_department(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$employee_id);                
      $stmt->execute();      
      $stmt->bind_result($department_id);
      $stmt->fetch();            
      $stmt->close();      
      $response_all = array('department_id'=>$department_id);
      return json_encode($response_all);
 }
 
 function updateEmployeeDepartment(){   
   $employee_id = $_REQUEST['employee_id'];   
   $department_id = $_REQUEST['department_id'];     
   $qry = "CALL update_employee_department(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('ii',$employee_id,$department_id);               
      $stmt->execute();                                   
      $stmt->close(); 
      
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();   
            
      return json_encode(array('result'=>$result));
 }
 
 function getEmployeeBranch($employee_id){      
   $qry = "CALL employee_branch(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$employee_id);                
      $stmt->execute();      
      $stmt->bind_result($branch_id);
      $stmt->fetch();            
      $stmt->close();      
      $response_all = array('branch_id'=>$branch_id);
      return json_encode($response_all);
 }
 
 function updateEmployeeBranch(){   
   $employee_id = $_REQUEST['employee_id'];   
   $branch_id = $_REQUEST['branch_id'];     
   $qry = "CALL update_employee_branch(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('ii',$employee_id,$branch_id);               
      $stmt->execute();                                   
      $stmt->close(); 
      
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();   
            
      return json_encode(array('result'=>$result));
 }  
 
 function getEmployeeDevice($employee_id){      
   $qry = "CALL employee_device(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$employee_id);                
      $stmt->execute();      
      $stmt->bind_result($device_id);
      $stmt->fetch();            
      $stmt->close();      
      $response_all = array('device_id'=>$device_id);
      return json_encode($response_all);
 }
 
 function updateEmployeeDevice(){   
   $employee_id = $_REQUEST['employee_id'];   
   $device_id = $_REQUEST['device_id'];     
   $qry = "CALL update_employee_device(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('ii',$employee_id,$device_id);               
      $stmt->execute();                                   
      $stmt->close(); 
      
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();   
            
      return json_encode(array('result'=>$result));
 }
 
 function getEmployeeChannel($employee_id){      
   $qry = "CALL employee_channel(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$employee_id);                
      $stmt->execute();      
      $stmt->bind_result($channel_id);
      $stmt->fetch();            
      $stmt->close();      
      $response_all = array('channel_id'=>$channel_id);
      return json_encode($response_all);
 }
 
 function updateEmployeeChannel(){   
   $employee_id = $_REQUEST['employee_id'];   
   $channel_id = $_REQUEST['channel_id'];     
   $qry = "CALL update_employee_channel(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('ii',$employee_id,$channel_id);               
      $stmt->execute();                                   
      $stmt->close(); 
      
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();   
            
      return json_encode(array('result'=>$result));
 }
 
 function updateEmployee(){   
   $employee_id = $_REQUEST['employee_id'];   
   $employee_code = $_REQUEST['employee_code'];
   $first_name = $_REQUEST['first_name'];
   $middle_name = $_REQUEST['middle_name'];
   $last_name = $_REQUEST['last_name']; 
   $address = $_REQUEST['address'];
   $phone_no = $_REQUEST['phone_no'];
   $email = $_REQUEST['email'];
   $gender = $_REQUEST['gender'];   
   $dob = $_REQUEST['dob'];     
   $idcard_no = $_REQUEST['idcard_no'];   
   $qry = "CALL update_employee(?,?,?,?,?,?,?,?,?,?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('issssssssss',$employee_id,$employee_code,$first_name,$middle_name,$last_name,$address,$phone_no,$email,$gender,$dob,$idcard_no);               
      $stmt->execute();                                   
      $stmt->close(); 
      
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();   
            
      return json_encode(array('result'=>$result));
 }
 
 function getEmployeeList(){
   $fields = array('employee_id','first_name','last_name','date_created','depot','latitude','longitude','middle_name','address','phone_no','email','gender','dob','idcard_no','about_me','employee_code');   
   $start = 0;
   $qty = !empty($_REQUEST['search_qty'])?$_REQUEST['search_qty']:10;
   $search_param = !empty($_REQUEST['search_param'])?$_REQUEST['search_param']:'';   
   $qry = "CALL employee_list(?,?,?)";
      $stmt = $this->db->con->prepare($qry);
      $stmt->bind_param('iis',$start,$qty,$search_param);                        
      $stmt->execute();      
      $stmt->bind_result($employee_id,$first_name,$last_name,$date_created,$depot,$latitude,$longitude,$middle_name,$address,$phone_no,$email,$gender,$dob,$idcard_no,$about_me,$employee_code);
      $employees = array();         
      $b = 0;
      while($stmt->fetch()){
        $employee = array();  
        foreach($fields as $k=>$v){
          $employee[$v] = $$v;
        }
        $employees[$b] = $employee;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('employees'=>$employees,'img_path'=>$this->conf['site paths']['siteurl']);
      return json_encode($response_all);
 }
 
 function getEmployees(){     
   $fields = array('id','first_name','last_name');
   $qry = "CALL employees()";
      $stmt = $this->db->con->prepare($qry);                            
      $stmt->execute();      
      $stmt->bind_result($id,$first_name,$last_name);
      $employees = array();         
      $b = 0;
      while($stmt->fetch()){
        $employee = array();  
        foreach($fields as $k=>$v){
          $employee[$v] = $$v;
        }
        $employees[$b] = $employee;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('employees'=>$employees);
      return json_encode($response_all);
 }
 
 function getEmployeeByUserId(){
   $user_id = $_SESSION['user_id'];
   $fields = array('employee_id','employee_code','company_name','first_name','middle_name','last_name','address','phone_no','email','gender','dob','idcard_no','username','about_me');   
   $qry = "CALL employee_by_user(?)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('i',$user_id);               
      $stmt->execute();      
      $stmt->bind_result($employee_id,$employee_code,$company_name,$first_name,$middle_name,$last_name,$address,$phone_no,$email,$gender,$dob,$idcard_no,$username,$about_me);      
      $stmt->fetch();            
      $stmt->close(); 
      $response = array();
      foreach($fields as $k=>$v){
       $response[$v] = $$v;
      }           
      return json_encode($response);
 }
 
 function updateEmployeeUser(){   
   $employee_id = $_REQUEST['employee_id'];
   $password = $_REQUEST['password']==''?'guest':$_REQUEST['password'];
   $employee_code = $_REQUEST['employee_code'];
   $first_name = $_REQUEST['first_name'];
   $middle_name = $_REQUEST['middle_name'];
   $last_name = $_REQUEST['last_name'];    
   $address = $_REQUEST['address'];  
   $phone_no = $_REQUEST['phone_no'];
   $email = $_REQUEST['email'];
   $gender = $_REQUEST['gender'];
   $dob = $_REQUEST['dob'];
   $idcard_no = $_REQUEST['idcard_no'];
   $about_me = $_REQUEST['about_me'];
   $qry = "CALL update_employee_user(?,?,?,?,?,?,?,?,?,?,?,?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('issssssssssss',$employee_id,$password,$employee_code,$first_name,$middle_name,$last_name,$address,$phone_no,$email,$gender,$dob,$idcard_no,$about_me);               
      $stmt->execute();                                   
      $stmt->close(); 
      
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();   
            
      return json_encode(array('result'=>$result));
 }
 
 function __store($dat){
  if($dat != ""){  
  list($first_name,$last_name,$employee_code,$depot_id,$address,$phone_no,$email,$date_created) = explode("|",$dat);       
   $dtt_chk = isset($date_created)?1:0;
   $date_created = ($date_created != "")?$date_created:"DATE_ADD(UTC_TIMESTAMP(),INTERVAL 1 HOUR)";   
  $data = array('first_name','last_name','employee_code','depot_id','address','phone_no','email','date_created');              
   $static_arr = array('date_created');
   $tot = count($data);
   $a = 0;
   $hdrs = implode(",",$data);             
   $qry = "INSERT into employee($hdrs) VALUES(";             
   foreach($data as $k){
    $$k = (get_magic_quotes_gpc() == 1)?$$k:addslashes($$k);
    if(in_array($k,$static_arr)){  
     $qry .= ($dtt_chk == 1)?$qry .= "'".$$k."'":$$k;
    }    
    else{$qry .= "'".$$k."'";}    
    if($a<$tot-1){$qry .= ",";}
    $a++;
   }         
   $qry .= " )";   
   file_put_contents("../log/employee_store.txt",$qry);  
   $this->db->runDml($qry); 
   $ret = $this->db->getAffectedRows();
   return ($ret >= 1)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));             
  }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  }
 }
 
 function __fetch($dat){ 
 if($dat != ""){
  $search_cols = array('first_name','last_name','depot_id','email');
  list($first_name,$last_name,$depot_id,$email) = explode("|",$dat);
  $table = 'employee';
  $labels = $this->db->getColumns($table);
  $qry = "select * from $table WHERE 1=1";
  foreach($search_cols as $k=>$v){
   $qry .= ($$v == "")?"":" AND $v = '$$v'";
  }
  file_put_contents("../log/employee_fetch.txt",$qry);
  $data = $this->db->getAll($qry);
  return utilities::sendDataXml($table,$data,$labels);
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __update($dat){ 
  if($dat != ""){  
  list($employee_id,$first_name,$last_name,$imei,$user_name,$password,$channel,$vehicle_no) = explode("|",$dat);
  $channel = $channel=='--Choose--'?'':$channel;
  $vehicle_no = $vehicle_no=='--Choose--'?'':$vehicle_no;
  $qry = "UPDATE employee a INNER JOIN device b ON (a.employee_id = (SELECT employee_id from employee_device WHERE device_id = b.device_id AND status ='1' AND LENGTH(imei) = '15')) 
           SET
           a.first_name = '$first_name',
           a.last_name = '$last_name',
           a.channel_id = '$channel',
           b.imei = '$imei',
           b.vehicle_no = '$vehicle_no',
           b.user_name = '$user_name',
           b.password = '$password'
          WHERE a.employee_id = $employee_id 
          ";
  file_put_contents("../log/employee_update.txt",$qry);
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret > 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __delete($dat){ 
 if($dat != ""){        
  $table = 'employee';  
  $qry = "DELETE FROM $table WHERE employee_id IN($dat)";
  file_put_contents("../log/employee_delete.txt",$qry);  
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret > 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function process(){
  if(!empty($_REQUEST['job'])){
  $dat = $_REQUEST['params'];
  $task = $_REQUEST['job'];
  $func = $this->tasks[$task];              
    return $this->$func(trim($dat));   
  }  
  else{
   return 'Invalid Task!';
  }      
 }
 
}
if(isset($_REQUEST['job'])){
$mp = new Employee();
echo $mp->process();
}
?>
