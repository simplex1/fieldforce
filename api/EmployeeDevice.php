<?php
/*
  Server HTTP app
  Param : censDat
*/
require_once "../shared/init.php";
require_once "../shared/dbman.php";
require_once "../shared/utilities.php";

class EmployeeDevice{
 var $db;
 var $tasks;
 function EmployeeDevice(){
  $this->db = dbman::connect();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'delete'=>'__delete'
                     );                   
 }  
 
 function __store($dat){
  if($dat != ""){  
  list($employee_id,$device_id,$status,$entry_date) = explode("|",$dat);       
   $dtt_chk = isset($entry_date)?1:0;
   $entry_date = ($entry_date != "")?$entry_date:"DATE_ADD(UTC_TIMESTAMP(),INTERVAL 1 HOUR)";   
  $data = array('employee_id','device_id','status','entry_date');              
   $static_arr = array('entry_date');
   $tot = count($data);
   $a = 0;
   $hdrs = implode(",",$data);             
   $qry = "INSERT into employee_device($hdrs) VALUES(";             
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
   file_put_contents("../log/employee_device_store.txt",$qry);  
   $this->db->runDml($qry); 
   $ret = $this->db->getAffectedRows();
   return ($ret >= 1)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));             
  }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  }
 }
 
 function __fetch($dat){ 
 if($dat != ""){
  $search_cols = array('employee_id','device_id','status','entry_date');
  list($employee_id,$device_id,$status,$entry_date) = explode("|",$dat);
  $table = 'employee_device';
  $labels = $this->db->getColumns($table);
  $qry = "select * from $table WHERE 1=1";
  foreach($search_cols as $k=>$v){
   $qry .= ($$v == "")?"":" AND $v = '$$v'";
  }
  file_put_contents("../log/employee_device_fetch.txt",$qry);
  $data = $this->db->getAll($qry);
  return utilities::sendDataXml($table,$data,$labels);
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __update($dat){ 
 if($dat != ""){  
  $search_cols = array('col1'=>'col1_val','col2'=>'col2_val','col3'=>'col3_val');
  list($key_id,$key_val,$col1,$col1_val,$col2,$col2_val,$col3,$col3_val) = explode("|",$dat);
  if($key_id=="" || $key_val==""){return utilities::sendSimpleXml(array('Result'=>"-2"));}
  $table = 'employee_device';
  $labels = $this->db->getColumns($table);
  $qry = "UPDATE $table SET";
  foreach($search_cols as $k=>$v){
   $qry .= ($$k == "" || $$v == "")?"":" $$k = '$$v'";
  }
  $qry .= " WHERE $key_id = '$key_val'";
  file_put_contents("../log/employee_device_update.txt",$qry);
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret >= 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __delete($dat){ 
 if($dat != ""){        
  $table = 'employee_device';  
  $qry = "DELETE FROM $table WHERE employee_device_id IN($dat)";
  file_put_contents("../log/employee_device_delete.txt",$qry);  
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret >= 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
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

$mp = new EmployeeDevice();
echo $mp->process();
?>
