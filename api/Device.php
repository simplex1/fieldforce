<?php
/*
  Server HTTP app
  Param : censDat
*/
require_once "global.php";
require_once BASEPATH."/shared/init.php";
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class Device{
 var $db;
 var $tasks;
 function __construct(){
  $this->db = dbmani::connect();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'delete'=>'__delete'
                     );                   
 }  
 
 function getDeviceList(){
   $fields = array('device_id','imei');   
   $qry = "CALL device_list()";
      $stmt = $this->db->con->prepare($qry);                        
      $stmt->execute();      
      $stmt->bind_result($device_id,$imei);
      $devices = array();         
      $b = 0;
      while($stmt->fetch()){
        $device = array();  
        foreach($fields as $k=>$v){
          $device[$v] = $$v;
        }
        $devices[$b] = $device;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('devices'=>$devices);
      return json_encode($response_all);
 }
 
 function __store(){
   $imei = $_REQUEST['imei'];           
   $qry = "CALL create_device(?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('s',$imei);               
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
 
 function __fetch($dat){ 
 if($dat != ""){
  $search_cols = array('imei','user_name','password');
  list($imei,$user_name,$password) = explode("|",$dat);
  $table = 'device';
  $labels = $this->db->getColumns($table);
  $qry = "select * from $table WHERE 1=1";
  foreach($search_cols as $k=>$v){
   $qry .= ($$v == "")?"":" AND $v = '$$v'";
  }
  file_put_contents("../log/device_fetch.txt",$qry);
  $data = $this->db->getAll($qry);
  return utilities::sendDataXml($table,$data,$labels);
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __update($dat){ 
 if($dat != ""){  
  $search_cols = array('col1'=>'col1_val','col2'=>'col2_val','col3'=>'col3_val','col4'=>'col4_val','col5'=>'col5_val');
  list($key_id,$key_val,$col1,$col1_val,$col2,$col2_val,$col3,$col3_val,$col4,$col4_val,$col5,$col5_val) = explode("|",$dat);
  if($key_id=="" || $key_val==""){return utilities::sendSimpleXml(array('Result'=>"-2"));}
  $table = 'device';
  $labels = $this->db->getColumns($table);
  $qry = "UPDATE $table SET";
  foreach($search_cols as $k=>$v){
   $qry .= ($$k == "" || $$v == "")?"":" $$k = '$$v'";
  }
  $qry .= " WHERE $key_id = '$key_val'";
  file_put_contents("../log/device_update.txt",$qry);
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret >= 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __delete($dat){ 
 if($dat != ""){        
  $table = 'device';  
  $qry = "DELETE FROM $table WHERE device_id IN($dat)";
  file_put_contents("../log/device_delete.txt",$qry);  
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret >= 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   utilities::sendSimpleXml(array('Result'=>"-1"));   
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
$mp = new Device();
echo $mp->process();
}
?>
