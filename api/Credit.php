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

class Credit{
 var $db;
 var $tasks;
 function Credit(){
  $this->db = dbmani::connect();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'delete'=>'__delete'
                     );                   
 }    
 
 function getCreditList(){
   $fields = array('id','amount','duration');   
   $qry = "CALL credit_list()";
      $stmt = $this->db->con->prepare($qry);                        
      $stmt->execute();      
      $stmt->bind_result($id,$amount,$duration);
      $credits = array();         
      $b = 0;
      while($stmt->fetch()){
        $credit = array();  
        foreach($fields as $k=>$v){
          $credit[$v] = $$v;
        }
        $credits[$b] = $credit;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('credits'=>$credits);
      return json_encode($response_all);
 }
 
 function __store(){
  $amount = $_REQUEST['amount'];
  $duration = $_REQUEST['duration'];           
   $qry = "CALL create_credit(?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('di',$amount,$duration);               
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
 $mp = new Credit();
 echo $mp->process();
}
?>
