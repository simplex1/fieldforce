<?php
/*
  Server HTTP app
  Param : censDat
*/
require_once "global.php";
require_once BASEPATH."/shared/init.php";
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class EmployeeTask{
 var $db;
 var $tasks;
 public function __construct(){
  $this->db = dbmani::connect();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'delete'=>'__delete'
                     );                   
 }  
 
  public function __destruct(){
  //$this->db->con->close();
 }
 
 function getTaskCompletion(){
  $lang = "en";
  $qry = "CALL task_completion(?)";
      $stmt = $this->db->con->prepare($qry);          
      $stmt->bind_param('s',$lang);        
      $stmt->execute();      
      $stmt->bind_result($task,$seven,$eight,$nine,$ten,$eleven,$twelve,$one,$two);
      $task_completion_arr = array();
      $task_arr = array();
      $b = 0;
      $max_val = 0;
      while($stmt->fetch()){
        $task_arr[$b] = $task;
        $task_completion_arr[$b] = array($seven,$eight,$nine,$ten,$eleven,$twelve,$one,$two);
        $max_val = $max_val>max($task_completion_arr[$b])?$max_val:max($task_completion_arr[$b]);
        $b++;
      }      
      $stmt->close();      
      $response_all = array('task'=>$task_arr,'task_completion'=>$task_completion_arr,'max_value'=>$max_val+2);
      return json_encode($response_all);        
 }
 
 function __store(){
  if($_REQUEST['task_id'] != "" && $_REQUEST['employee_id'] != "" && $_REQUEST['task_date'] != "" 
  && $_REQUEST['task_time'] != "" && $_REQUEST['latitude'] != "" && $_REQUEST['longitude'] != ""){                 
  $task_id = $_REQUEST['task_id'];
  $employee_id = $_REQUEST['employee_id'];
  $task_date = $_REQUEST['task_date'];
  $task_time = $_REQUEST['task_time'];
  $latitude = $_REQUEST['latitude'];
  $longitude = $_REQUEST['longitude'];
  $data = array('task_id','employee_id','task_date','task_time','latitude','longitude');                
   $tot = count($data);
   $a = 0;
   $hdrs = implode(",",$data);             
   $qry = "INSERT into employee_task($hdrs) VALUES(";             
   foreach($data as $k){
    $$k = (get_magic_quotes_gpc() == 1)?$$k:addslashes($$k);
    $qry .= "'".$$k."'";    
    if($a<$tot-1){$qry .= ",";}
    $a++;
   }         
   $qry .= " )";   
   file_put_contents("../log/employee_task_store.txt",$qry);  
   $this->db->runDml($qry); 
   $ret = $this->db->getAffectedRows();
   return ($ret >= 1)?utilities::respond(array('Result'=>"1")):utilities::respond(array('Result'=>"0"));             
  }else{
   return utilities::respond(array('Result'=>"-1"));   
  }
 }
 
 /*function __fetch($dat){ 
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
$mp = new EmployeeTask();
echo $mp->process();
}
?>
