<?php
/*
  Server HTTP app
  Param : censDat
*/
require_once "../shared/init.php";
require_once "../shared/dbman.php";
require_once "../shared/utilities.php";

class Ajax{
 var $db;
 var $tasks;
 function Ajax(){
  $this->db = dbman::connect();
  $this->tasks = array(                      
                      'fetchOption'=>'__fetchOption'                      
                     );                   
 }  

 function __fetchOption($dat){ 
 if($dat != ""){          
  $ret = utilities::getDBOptions($dat,$this->db);
  return ($ret != "")?$ret:utilities::sendSimpleXml(array('Result'=>"0"));    
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

$mp = new Ajax();
echo $mp->process();
?>
