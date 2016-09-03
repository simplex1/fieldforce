<?php
/*
  Server HTTP app
  Param : censDat
*/
require_once "../shared/init.php";
require_once "../shared/dbman.php";
require_once "../shared/utilities.php";

class Depot{
 var $db;
 var $tasks;
 function Depot(){
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
  list($name,$address,$city,$state) = explode("|",$dat);          
  $data = array('name','address','city','state');                 
   $tot = count($data);
   $a = 0;
   $hdrs = implode(",",$data);             
   $qry = "INSERT into depot($hdrs) VALUES(";             
   foreach($data as $k){
    $$k = (get_magic_quotes_gpc() == 1)?$$k:addslashes($$k);
    $qry .= "'".$$k."'";    
    if($a<$tot-1){$qry .= ",";}
    $a++;
   }         
   $qry .= " )";   
   file_put_contents("../log/depot_store.txt",$qry);  
   $this->db->runDml($qry); 
   $ret = $this->db->getAffectedRows();
   return ($ret >= 1)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));             
  }else{
   utilities::sendSimpleXml(array('Result'=>"-1"));   
  }
 }
 
 function __fetch($dat){ 
 if($dat != ""){
  $search_cols = array('name','city','state');
  list($name,$city,$state) = explode("|",$dat);
  $table = 'depot';
  $labels = $this->db->getColumns($table);
  $qry = "select * from $table WHERE 1=1";
  foreach($search_cols as $k=>$v){
   $qry .= ($$v == "")?"":" AND $v = '$$v'";
  }
  file_put_contents("../log/depot_fetch.txt",$qry);
  $data = $this->db->getAll($qry);
  return utilities::sendDataXml($table,$data,$labels);
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __update($dat){ 
 if($dat != ""){  
  $search_cols = array('col1'=>'col1_val','col2'=>'col2_val','col3'=>'col3_val','col4'=>'col4_val');
  list($key_id,$key_val,$col1,$col1_val,$col2,$col2_val,$col3,$col3_val,$col4,$col4_val) = explode("|",$dat);
  if($key_id=="" || $key_val==""){return utilities::sendSimpleXml(array('Result'=>"-2"));}
  $table = 'depot';
  $labels = $this->db->getColumns($table);
  $qry = "UPDATE $table SET";
  foreach($search_cols as $k=>$v){
   $qry .= ($$k == "" || $$v == "")?"":" $$k = '$$v'";
  }
  $qry .= " WHERE $key_id = '$key_val'";
  file_put_contents("../log/depot_update.txt",$qry);
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret >= 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __delete($dat){ 
 if($dat != ""){        
  $table = 'depot';  
  $qry = "DELETE FROM $table WHERE depot_id IN($dat)";
  file_put_contents("../log/depot_delete.txt",$qry);  
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

$mp = new Depot();
echo $mp->process();
?>
