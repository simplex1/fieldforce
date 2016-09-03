<?php     
  class utilities{
  
  function utilities(){
  
  }
  
  function respond($arr){
  return json_encode($arr);
 }
 
 function storeLogin($db,$user_id,$status){
  $ip = $_SERVER['SERVER_ADDR'];
  $url = $_SERVER['REQUEST_URI'];
  $u_a = $_SERVER['HTTP_USER_AGENT'];
  $qry = "CALL store_login(?,?,?,?,?)";
  $stmt = $db->con->prepare($qry);
  $stmt->bind_param('issss',$user_id,$ip,$url,$u_a,$status);
  $stmt->execute();
  $stmt->close();
 }
  
  function getExcelSheet($file,$sheetIdx,$s_name="no"){     
    $reader=new Spreadsheet_Excel_Reader();
    $reader->setUTFEncoder('iconv');
    $reader->setOutputEncoding('UTF-8');
    $reader->setRowColOffset(0);
    //$reader->setDefaultFormat('%.2f');
    $reader->read($file);
    $sheetname = $reader->boundsheets[$sheetIdx]['name'];
    if($s_name=="yes"){return $sheetname;}
    $rows = $reader->sheets[$sheetIdx]['numRows'];
    $cols = $reader->sheets[$sheetIdx]['numCols'];
    $sheet = $reader->sheets[$sheetIdx];
    return $sheet;
  }
  
  function upload(){    
   if($_FILES['name'] != ""){          
    $baseFol = HMP.'/download';    
    $files = $_FILES;
    if(!is_dir($baseFol))
      mkdir($baseFol);                 
      $fnm = $files['name'];
      $tmp = $files['tmp_name'];
      $newFile = $baseFol.'/'.$fnm;          
     move_uploaded_file($tmp,$newFile); 
     return $newFile;                    
   }else{
    $ret = "err";
    return $ret;       
   }   
  }
  
  function getDBOptions($dat,$db){
    list($table,$inCol,$outCol,$curVal) = explode("|",$dat);  
  $qry = "SELECT DISTINCT($inCol) AS $inCol, $outCol FROM $table where $outCol <> ''";   
  $rec = $db->getAll($qry);
  $ret = "";  
  $tot = count($rec);
  if($tot >0){
  $ret = "<option value='0'>--Choose--</option>\n";
   for($a=0;$a<$tot;$a++){
    $selected = ($rec[$a]->$outCol == $curVal)?'selected':'';
    $ret .= "<option value='".$rec[$a]->$inCol."' $selected>".$rec[$a]->$outCol."</option>"; 
    if($a<$tot-1){$ret .= "\n";}    
   }
  } 
   file_put_contents("../log/ajax_fetch_$table.txt",$qry);  
  return $ret;
  }
  
  function sendSimpleXml($arr_info=array()){        
    $ret = "";
    $cnt = count($arr_info);
    $a=0;
    foreach($arr_info as $label=>$value){
		 $ret .= $label.":".$value;
     if($a<$cnt-1){$ret .= "|";}
     $a++;
    }				
    return $ret;
   }
   
   function sendDataXml($table,$data,$labels=array()){                
    $cnt = count($data);
    $icnt = count($labels);    
    if($cnt<=0){return;}    
    $ret = "";   
    for($a=0;$a<$cnt;$a++){ 
     $b = 0;        
     foreach($labels as $key=>$label){
		  $ret .= $label.':'.$data[$a]->$label;
      if($b<$icnt-1){$ret .= "|";}
      $b++;
     }		
     if($a<$cnt-1){$ret .= "^";}
    }		
    return $ret;
   }
   
   function makeOptions($arr,$fld,$selValue){
    $ret = "
           <select id='$fld' name='$fld'>
            <option value='dummy'>Rows to Show</option>";
    $selected = "";        
    foreach($arr as $k=>$v){
     $selected = ($selValue==$v)?'selected':'';
     $ret .= "<option value='$v' $selected>$v</option>";
    }       
    $ret .= "</select>";
    return $ret;
   }
   
   function generatePager($mainPage,$items,$inPage,$inSide,$prefix){    
    $ret = "";        
    $thispage = $mainPage ;
    $num = $items; // number of items in list
    $per_page = $inSide; // Number of items to show per page
    $showeachside = $inSide; //  Number of items to show either side of selected page
    if(empty($start))$start=$inPage;  // Current start position

    $max_pages = ceil($num / $per_page); // Number of pages
    $cur = ceil($start / $per_page)+1; // Current page number
    if(($start-$per_page) >= 0)
    {
    $next = $start-$per_page;    
    $ret .= '<a class="previous-off" href="'.$thispage.($next>0?($prefix."t_start=").$next:"").'">&laquo; Previous</a>';
    }
    $ret .= "$cur of ($max_pages) :: ($num) rows";
    if($start+$per_page<$num)
    {
    $ret .= '<a href="'."$thispage"."$prefix"."t_start=".max(0,$start+$per_page).'">';
    }
    $eitherside = ($showeachside * $per_page);
    if($start+1 > $eitherside)$ret .= " .... ";
    $ret .= "</a>";
    $pg=1;
    for($y=0;$y<$num;$y+=$per_page)
    {
    $class=($y==$start)?"current":"";
    if(($y > ($start - $eitherside)) && ($y < ($start + $eitherside)))
    {
       $ret .= '<a class="'.$class.'" href="'.$thispage.($y>0?($prefix."t_start=").$y:"").'">'.$pg.'</a>&nbsp;';
    }
    $pg++;
   }   
   $ret .= '<a class="next" href="'.$thispage.($next>0?($prefix."t_start=").$next:"").'">Next &raquo;</a>';
   //if(($start+$eitherside)<$num) $ret .= " .... ";
   //for($x=$start;$x<min($num,($start+$per_page)+1);$x++)$ret .= $items[$x]."<br>";
   return $ret;    
  }  
   
   function sendSimpleXmlCore($arr_info=array()){
    //header('Content-type: text/xml');
    $conf = init::getConf();
    $rootTag = $conf['site']['name'];
    $ret = "";
    $ret .= '<'.$rootTag.'>';
    foreach($arr_info as $label=>$value){
		 $ret .= '<'.$label.'>'.$value.'</'.$label.'>';
    }		
		$ret .= '</'.$rootTag.'>';
    return $ret;
   }
   
   function sendDataXmlCore($table,$data,$labels=array()){
    //header('Content-type: text/xml');    
    $rootTag = $table;
    $cnt = count($data);
    if($cnt<=0){return;}    
    $ret = "";
    $ret .= '<'.$rootTag.'>';
    $ret .= '<Result>1</Result>';
    for($a=0;$a<$cnt;$a++){
     $row = "record";
     $ret .= '<'.$row.'>';
     foreach($labels as $key=>$label){
		  $ret .= '<'.$label.'>'.$data[$a]->$label.'</'.$label.'>';
     }		
     $ret .= '</'.$row.'>';
    }
		$ret .= '</'.$rootTag.'>';
    return $ret;
   }
   
  }
?>
