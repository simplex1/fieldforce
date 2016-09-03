<?php
/*
  Server HTTP app
  Param : censDat
*/
/*error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);  */

require_once "global.php";
require_once BASEPATH."/shared/init.php";
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class Customer{
 var $db;
 var $tasks;
 public function __construct(){
  $this->db = dbmani::connect();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'updateCustomer'=>'updateCustomer',
                      'delete'=>'__delete'
                     );                   
 }  
 
  public function __destruct(){
  //$this->db->con->close();
 }
 
 function getCallFrequencyId(){
   $ret = 2;
   return $ret;
 }
 
 function getCustomerEmployee($customer_id){      
   $qry = "CALL customer_employee(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$customer_id);                
      $stmt->execute();      
      $stmt->bind_result($employee_id);
      $stmt->fetch();            
      $stmt->close();      
      $response_all = array('employee_id'=>$employee_id);
      return json_encode($response_all);
 }
 
 function updateCustomerEmployee(){   
   $customer_id = $_REQUEST['customer_id'];   
   $employee_id = $_REQUEST['employee_id'];        
   $qry = "CALL update_customer_employee(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('ii',$customer_id,$employee_id);               
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
 
  function getCustomerVisitDay($customer_id){      
   $qry = "CALL customer_visit_day(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$customer_id);                
      $stmt->execute();      
      $stmt->bind_result($visit_day_id);
      $stmt->fetch();            
      $stmt->close();      
      $response_all = array('visit_day_id'=>$visit_day_id);
      return json_encode($response_all);
 }
 
 function updateCustomerVisitDay(){   
   $customer_id = $_REQUEST['customer_id'];   
   $visit_day_id = $_REQUEST['visit_day_id'];        
   $qry = "CALL update_customer_visit_day(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('ii',$customer_id,$visit_day_id);               
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
 
  function getCustomerCredit($customer_id){      
   $qry = "CALL customer_credit(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$customer_id);                
      $stmt->execute();      
      $stmt->bind_result($credit_id);
      $stmt->fetch();            
      $stmt->close();      
      $response_all = array('credit_id'=>$credit_id);
      return json_encode($response_all);
 }
 
 function updateCustomerCredit(){   
   $customer_id = $_REQUEST['customer_id'];   
   $credit_id = $_REQUEST['credit_id'];        
   $qry = "CALL update_customer_credit(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('ii',$customer_id,$credit_id);               
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
 
 function getCustomerChannel($customer_id){      
   $qry = "CALL customer_channel(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$customer_id);                
      $stmt->execute();      
      $stmt->bind_result($channel_id);
      $stmt->fetch();            
      $stmt->close();      
      $response_all = array('channel_id'=>$channel_id);
      return json_encode($response_all);
 }
 
 function updateCustomerChannel(){   
   $customer_id = $_REQUEST['customer_id'];   
   $channel_id = $_REQUEST['channel_id'];     
   $qry = "CALL update_customer_channel(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('ii',$customer_id,$channel_id);               
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
 
 function getCustomerList(){
   $fields = array('customer_id','outlet_name','outlet_address','phone_no','email','first_name','last_name','dob','city','state','latitude','longitude','date_created');
   $start = 0;
   $qty = !empty($_REQUEST['search_qty'])?$_REQUEST['search_qty']:10;
   $search_param = !empty($_REQUEST['search_param'])?$_REQUEST['search_param']:'';
   $qry = "CALL customer_list(?,?,?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('iis',$start,$qty,$search_param);                
      $stmt->execute();      
      $stmt->bind_result($customer_id,$outlet_name,$outlet_address,$phone_no,$email,$first_name,$last_name,$dob,$city,$state,$latitude,$longitude,$date_created);
      $customers = array();         
      $b = 0;
      while($stmt->fetch()){
        $customer = array();  
        foreach($fields as $k=>$v){
          $customer[$v] = $$v;
        }
        $customers[$b] = $customer;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('customers'=>$customers);
      return json_encode($response_all);
 }
 
 function getCustomer(){
   $fields = array('customer_id','outlet_name','outlet_address','phone_no','email','first_name','last_name','dob','city','state','latitude','longitude','date_created');
   $start = 0;  
   $search_param = !empty($_REQUEST['search_param'])?$_REQUEST['search_param']:'';
   $qry = "CALL load_customer(?)";
      $stmt = $this->db->con->prepare($qry);  
      $stmt->bind_param('i',$search_param);                
      $stmt->execute();      
      $stmt->bind_result($customer_id,$outlet_name,$outlet_address,$phone_no,$email,$first_name,$last_name,$dob,$city,$state,$latitude,$longitude,$date_created);
      $customers = array();         
      $b = 0;
      while($stmt->fetch()){
        $customer = array();  
        foreach($fields as $k=>$v){
          $customer[$v] = $$v;
        }
        $customers[$b] = $customer;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('customers'=>$customers);
      return json_encode($response_all);
 }
 
 function updateCustomer(){   
   $customer_id = $_REQUEST['customer_id'];   
   $outlet_address = $_REQUEST['outlet_address'];
   $phone_no = $_REQUEST['phone_no'];
   $email = $_REQUEST['email'];
   $first_name = $_REQUEST['first_name'];
   $last_name = $_REQUEST['last_name'];    
   $dob = $_REQUEST['dob'];  
   $city = $_REQUEST['city'];
   $state = $_REQUEST['state'];   
   $qry = "CALL update_customer(?,?,?,?,?,?,?,?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('issssssss',$customer_id,$outlet_address,$phone_no,$email,$first_name,$last_name,$dob,$city,$state);               
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
 
 function getCustomerByChannel(){
  $qry = "CALL customer_by_channel()";
      $stmt = $this->db->con->prepare($qry);                  
      $stmt->execute();      
      $stmt->bind_result($outlet_cnt,$channel);
      $outlet_cnt_arr = array();
      $channel_arr = $channel;
      $b = 0;
      while($stmt->fetch()){
        $outlet_cnt_arr[$b] = $outlet_cnt;
        $channel_arr[$b] = $channel;
        $b++;
      }      
      $stmt->close();   
      $tot = array_sum($outlet_cnt_arr);
      $outlet_cnt_pct = array();
      for($a=0;$a<count($outlet_cnt_arr);$a++){
        $outlet_cnt_pct[$a] = $outlet_cnt_arr[$a]==0?$channel_arr[$a].' '.$outlet_cnt_arr[$a]." (0%)":$channel_arr[$a].' '.$outlet_cnt_arr[$a].' ('.round((($outlet_cnt_arr[$a]/$tot)*100),1).'%)';
      }
      $response_all = array('channel'=>$channel_arr,'outlet_cnt'=>$outlet_cnt_arr,'outlet_cnt_pct'=>$outlet_cnt_pct);
      return json_encode($response_all);        
 }
 
 function __store(){
   $employee_id = $_REQUEST['employee_id'];   
   $outlet_name = $_REQUEST['outlet_name'];
   $outlet_address = $_REQUEST['outlet_address'];
   $phone_no = $_REQUEST['phone_no'];
   $email = $_REQUEST['email'];
   $first_name = $_REQUEST['first_name'];
   $last_name = $_REQUEST['last_name'];    
   $dob = $_REQUEST['dob'];  
   $city = $_REQUEST['city'];
   $state = $_REQUEST['state'];
   $latitude = $_REQUEST['latitude'];
   $longitude = $_REQUEST['longitude'];   
   $qry = "CALL create_customer(?,?,?,?,?,?,?,?,?,?,?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('isssssssssdd',$employee_id,$outlet_name,$outlet_address,$phone_no,$email,$first_name,$last_name,$dob,$city,$state,$latitude,$longitude);               
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
 
 function __fetch(){  
      $response_rows = array();                
      $employee_id = $_REQUEST['employee_id'];    
      $fields = array('route_id','customer_id', 'outlet_name', 'outlet_address', 'phone_no','email','first_name','last_name','dob','city','state','latitude','longitude');  
      $qry = "CALL route_list(?)";          
      $stmt = $this->db->con->prepare($qry);            
      $stmt->bind_param('i',$employee_id);
      $stmt->execute();
      $stmt->bind_result($route_id,$customer_id,$outlet_name,$outlet_address,$phone_no,$email,$first_name,$last_name,$dob,$city,$state,$latitude,$longitude);
      while($stmt->fetch()){
       $response = array();
       foreach($fields as $k=>$v){
        $response = array_merge($response,array($v=>$$v));
       }
       array_push($response_rows,$response);
      }
      $stmt->close();      
      $response_all = array('rows'=>$response_rows);
      return json_encode($response_all); 
 }
 
 /*function __update($dat){ 
 if($dat != ""){  
  list($customer_id,$outlet_name,$outlet_address,$phone_no,$email,$city,$state,$call_frequency) = explode("|",$dat);
  $qry = "UPDATE customer 
           SET
           outlet_name = '$outlet_name',
           outlet_address = '$outlet_address',
           phone_no = '$phone_no',
           email = '$email',
           city = '$city',
           state = '$state',
           call_frequency_id = '$call_frequency'
          WHERE customer_id = $customer_id 
          ";
  file_put_contents("../log/customer_update.txt",$qry);
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret > 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __delete($dat){ 
 if($dat != ""){        
  $table = 'customer';  
  $qry = "DELETE FROM $table WHERE customer_id IN($dat)";
  file_put_contents("../log/customer_delete.txt",$qry);  
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret > 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }*/
 
 function process(){
  if(!empty($_REQUEST['job'])){
  $task = $_REQUEST['job'];
  $func = $this->tasks[$task];              
    return $this->$func();   
  }  
  else{
   return 'Invalid Task!';
  }      
 }
 
}

if(isset($_REQUEST['job'])){
 $mp = new Customer();
 echo $mp->process();
}
?>
