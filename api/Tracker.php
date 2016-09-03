<?php
/*
  Server HTTP app
  Param : censDat
*/
require_once "../shared/init.php";
require_once "../shared/dbman.php";
require_once "../shared/utilities.php";
require_once "../shared/Spreadsheet/Excel/reader.php";
require_once '../shared/Spreadsheet/Classes/PHPExcel.php';

class Tracker{
 var $db;
 var $tasks;
 function Tracker(){
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
  //upload
  $dataFile = utilities::upload();
  if($file != 'err'){
   set_time_limit(1000*60*120);
   $sz = round(filesize($dataFile)/1000,2).'KB';  
   $sheet = utilities::getExcelSheet($dataFile,0);
   $rows = count($sheet['cells']);    
   $vehicle_no = $sheet['name'];
   $colz = array('gps_valid'=>'0','gps_bs'=>'1','collect_time'=>'2','gps_latitude'=>'3','gps_longitude'=>'4','vehicle_address'=>'5','vehicle_speed'=>'6','gps_direct'=>'7','gps_duration'=>'8','gps_state'=>'9','gps_mileage'=>'10');
   $load_time = "DATE_ADD(UTC_TIMESTAMP(),INTERVAL 1 HOUR)";
   $qry = "insert ignore into tracker(vehicle_no,gps_valid,gps_bs,collect_time,gps_latitude,gps_longitude,map_address,gps_speed,gps_direct,trip_duration,vehicle_state,trip_mileage,load_time) values\n";
   for($a=0;$a<$rows;$a++){                       
     if($a==0){continue;}
     foreach($colz as $k=>$v){
      $$k = (get_magic_quotes_gpc() == 1)?trim($sheet['cells'][$a][$v]):addslashes(trim($sheet['cells'][$a][$v]));      
     }           
     $qry .= "('$vehicle_no','$gps_valid','$gps_bs','$collect_time','$gps_latitude','$gps_longitude','$map_address','$gps_speed','$gps_direct','$trip_duration','$vehicle_state','$trip_mileage',$load_time)\n";
     if($a < $rows-1){
      $qry .= ",";
     }           
    }
    file_put_contents("../log/tracker_store.txt",$qry);
    $this->db->runDml($qry);  
  } 
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
  $table = 'tracker';
  $labels = $this->db->getColumns($table);
  $qry = "select * from $table WHERE 1=1";
  foreach($search_cols as $k=>$v){
   $qry .= ($$v == "")?"":" AND $v = '$$v'";
  }
  file_put_contents("../log/tracker_fetch.txt",$qry);
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
  $table = 'tracker';
  $labels = $this->db->getColumns($table);
  $qry = "UPDATE $table SET";
  foreach($search_cols as $k=>$v){
   $qry .= ($$k == "" || $$v == "")?"":" $$k = '$$v'";
  }
  $qry .= " WHERE $key_id = '$key_val'";
  file_put_contents("../log/tracker_update.txt",$qry);
  $this->db->runDml($qry);
  $ret = $this->db->getAffectedRows();
  return ($ret >= 0)?utilities::sendSimpleXml(array('Result'=>"1")):utilities::sendSimpleXml(array('Result'=>"0"));    
 }else{
   return utilities::sendSimpleXml(array('Result'=>"-1"));   
  } 
 }
 
 function __delete($dat){ 
 if($dat != ""){        
  $table = 'tracker';  
  $qry = "DELETE FROM $table WHERE device_id IN($dat)";
  file_put_contents("../log/tracker_delete.txt",$qry);  
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

$mp = new Tracker();
echo $mp->process();
?>
