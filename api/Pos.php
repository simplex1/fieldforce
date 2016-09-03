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

class Pos{
 var $db;
 var $tasks;
 public function __construct(){
  $this->db = dbmani::connect();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'getProducts'=>'getProducts',
                      'delete'=>'__delete'
                     );                   
 }  
 
  public function __destruct(){
  //$this->db->con->close();
 }
 
 function __store(){      
   $route_id = $_REQUEST['route_id'];
   $sales_date = $_REQUEST['sales_date'];
   $sales_time = $_REQUEST['sales_time'];
   $sales_latitude = $_REQUEST['sales_latitude'];
   $sales_longitude = $_REQUEST['sales_longitude'];
   $sales_volume = $_REQUEST['sales_volume'];
   $sales_value = $_REQUEST['sales_value'];    
   $product_info = $_REQUEST['product_info'];       
   $qry = "CALL create_sales(?,?,?,?,?,?,?,@result)";
      $stmt = $this->db->con->prepare($qry);   
      $stmt->bind_param('iddssdd',$route_id,$sales_volume,$sales_value,$sales_date,$sales_time,$sales_latitude,$sales_longitude);               
      $stmt->execute();                                   
      $stmt->close(); 
      
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();
      
      $sales_id = $result;
      
      $prods = explode("#",$product_info);
      foreach($prods as $k=>$v){
       list($emp_bin_id,$qty,$amt) = explode(":",$v);
       $qry = "CALL create_sales_product(?,?,?,?,?,@result)";
       $stmt = $this->db->con->prepare($qry);   
       $stmt->bind_param('siidd',$sales_date,$sales_id,$emp_bin_id,$qty,$amt);               
       $stmt->execute();                                   
       $stmt->close();
      }   
            
      return json_encode(array('result'=>$result));
 }
 
 function getProducts(){  
      $response_rows = array();                
      $employee_id = $_REQUEST['employee_id'];    
      $fields = array('bin_id','product_name', 'uom', 'stock', 'price');  
      $qry = "CALL load_employee_bin(?)";          
      $stmt = $this->db->con->prepare($qry);            
      $stmt->bind_param('i',$employee_id);
      $stmt->execute();
      $stmt->bind_result($bin_id,$product_name,$uom,$stock,$price);
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
 $mp = new Pos();
 echo $mp->process();
}
?>
