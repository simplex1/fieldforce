<?php
/*
  Server HTTP app
  Param : censDat
*/
require_once "global.php";
require_once BASEPATH."/shared/init.php";
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class Branch{
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
 
 function getBranchList(){
   $fields = array('branch_id','branch');   
   $qry = "CALL branch_list()";
      $stmt = $this->db->con->prepare($qry);                        
      $stmt->execute();      
      $stmt->bind_result($branch_id,$branch);
      $branches = array();         
      $b = 0;
      while($stmt->fetch()){
        $brch = array();  
        foreach($fields as $k=>$v){
          $brch[$v] = $$v;
        }
        $branches[$b] = $brch;        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('branches'=>$branches);
      return json_encode($response_all);
 }
 
 function __store($dat){
  if($dat != ""){  
  list($imei,$serial,$status,$user_name,$password,$permission_id,$date_created) = explode("|",$dat);       
   $dtt_chk = isset($date_created)?1:0;
   $date_created = ($date_created != "")?$date_created:"DATE_ADD(UTC_TIMESTAMP(),INTERVAL 1 HOUR)";   
  $data = array('imei','serial','status','user_name','password','permission_id','date_created');              
   $static_arr = array('date_created');
   $tot = count($data);
   $a = 0;
   $hdrs = implode(",",$data);             
   $qry = "INSERT into device($hdrs) VALUES(";             
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
   file_put_contents("../log/device_store.txt",$qry);  
   $this->db->runDml($qry); 
   $ret = $this->db->getAffectedRows();
   return ($ret >= 1)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));             
  }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  }
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
$mp = new Branch();
echo $mp->process();
}
?>
