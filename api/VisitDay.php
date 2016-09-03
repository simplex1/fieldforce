<?php
/*
  Server HTTP app
  Param : censDat
*/
error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);

require_once "global.php";
require_once BASEPATH."/shared/init.php";
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class VisitDay{
 var $db;
 var $tasks;
 function VisitDay(){
  $this->db = dbmani::connect();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'delete'=>'__delete'
                     );                   
 }    
 
 function getVisitDayList(){
   $fields = array('id','visit_day','day_code');   
   $qry = "CALL visit_day_list()";
      $stmt = $this->db->con->prepare($qry);                        
      $stmt->execute();      
      $stmt->bind_result($id,$visit_day,$day_code);
      $visit_days = array();         
      $b = 0;
      while($stmt->fetch()){
        $visit_dai = array();  
        foreach($fields as $k=>$v){
          $visit_dai[$v] = $$v;
        }
        $visit_days[$b] = $visit_dai;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('visit_days'=>$visit_days);
      return json_encode($response_all);
 }
 
 /*function __store($dat){
  if($dat != ""){  
  list($employee_id,$device_id,$outlet_name,$outlet_address,$phone_no,$email,$first_name,$last_name,$dob,$city,$state,$latitude,$longitude,$call_frequency_id,$entry_date,$entry_time) = explode("|",$dat);       
   $dtt_chk = isset($entry_date)?1:0;
   $entry_date = ($entry_date != "")?$entry_date:"DATE_ADD(UTC_TIMESTAMP(),INTERVAL 1 HOUR)";
   $entry_time = ($entry_time != "")?$entry_time:"DATE_FORMAT(DATE_ADD(UTC_TIMESTAMP(),INTERVAL 1 HOUR),'%r')";
   $call_frequency_id = $this->getCallFrequencyId();
  $data = array('employee_id','device_id','outlet_name','outlet_address','phone_no','email','first_name','last_name',
                'dob','city','state','latitude','longitude','call_frequency_id','entry_date','entry_time'
                );              
   $static_arr = array('entry_date','entry_time');
   $tot = count($data);
   $a = 0;
   $hdrs = implode(",",$data);             
   $qry = "INSERT into customer($hdrs) VALUES(";             
   foreach($data as $k){
    $$k = (get_magic_quotes_gpc() == 1)?$$k:addslashes($$k);
    if(in_array($k,$static_arr)){  
     $qry .= ($dtt_chk == 1)?"'".$$k."'":$$k;
    }    
    else{$qry .= "'".$$k."'";}    
    if($a<$tot-1){$qry .= ",";}
    $a++;
   }         
   $qry .= " )";   
   file_put_contents("../log/customer_store.txt",$qry);  
   $this->db->runDml($qry); 
   $ret = $this->db->getAffectedRows();
   return ($ret >= 1)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));             
  }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  }
 }*/
 
 function __fetch(){  
      $response_rows = array();                
      $employee_id = $_REQUEST['employee_id'];    
      $fields = array('customer_id', 'outlet_name', 'outlet_address', 'phone_no','city','state','latitude','longitude');  
      $qry = "
          SELECT ".implode($fields,',')."          
          FROM customer 
          WHERE 1=1 
          AND employee_id = ?
          AND call_frequency_id = IF(DAYOFWEEK(NOW())>1,DAYOFWEEK(NOW()),1)
          ";
      //echo $qry;    
      $stmt = $this->db->con->prepare($qry);      
      //echo $this->db->con->error;
      $stmt->bind_param('i',$employee_id);
      $stmt->execute();
      $stmt->bind_result($customer_id,$outlet_name,$outlet_address,$phone_no,$city,$state,$latitude,$longitude);
      while($stmt->fetch()){
       $response = array();
       foreach($fields as $k=>$v){
        $response = array_merge($response,array($v=>$$v));
       }
       array_push($response_rows,$response);
      }
      $stmt->close();      
      $response_all = array('rows'=>$response_rows);
      return utilities::respond($response_all); 
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
  $dat = $_REQUEST['params'];
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
 $mp = new VisitDay();
 echo $mp->process();
}
?>
