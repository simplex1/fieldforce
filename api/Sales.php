<?php
require_once "global.php";
require_once BASEPATH."/shared/init.php";
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class Sales{
 var $db;
 var $tasks;
 function Sales(){
  $this->db = dbmani::connect();
  $this->tasks = array(
                      'store'=>'__store',
                      'fetch'=>'__fetch',
                      'update'=>'__update',
                      'delete'=>'__delete'
                     );                   
 }
 
 function getYtdSales(){
  $year = date("Y");
  $qry = "CALL ytd_sales(?)";
      $stmt = $this->db->con->prepare($qry);          
      $stmt->bind_param('i',$year);        
      $stmt->execute();      
      $stmt->bind_result($category,$jan,$feb,$mar,$apr,$may,$jun,$jul,$aug,$sep,$oct,$nov,$dec);
      $sales_volume_arr = array();
      $category_arr = array();
      $b = 0;      
      while($stmt->fetch()){
        $category_arr[$b] = $category;
        $sales_volume_arr[$b] = array($jan,$feb,$mar,$apr,$may,$jun,$jul,$aug,$sep,$oct,$nov,$dec);        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('category'=>$category_arr,'sales_volume'=>$sales_volume_arr);
      return json_encode($response_all);        
 }
 
 function getYtdSkuSales(){
  $year = date("Y");
  $qry = "CALL ytd_sku_sales(?)";
      $stmt = $this->db->con->prepare($qry);          
      $stmt->bind_param('i',$year);        
      $stmt->execute();      
      $stmt->bind_result($product,$ytd,$sply);
      $sales_volume_arr = array();
      $product_arr = array();
      $b = 0;      
      while($stmt->fetch()){
        $product_arr[$b] = $product;
        $sales_volume_arr[$b] = array($ytd,$sply);        
        $b++;
      }      
      $stmt->close();      
      $response_all = array('product'=>$product_arr,'sales_volume'=>$sales_volume_arr);
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
$mp = new Sales();
echo $mp->process();
}


?>