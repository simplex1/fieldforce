<?php 
 require_once('utils.php');
 require_once "init.php";
 require_once "mail/Swift_mail/lib/swift_required.php";
 require_once 'Spreadsheet/Classes/PHPExcel.php';
 $utils = new utils();
 $start_dtt = $end_dtt = isset($_REQUEST['tran_date'])?$_REQUEST['tran_date']:date('Y-m-d'); 
 set_time_limit(0); 
 /*error_reporting(E_ALL);
 ini_set('display_errors', TRUE);
 ini_set('display_startup_errors', TRUE);*/ 
 ini_set('memory_limit', '512M');    
 
 function layoutSheet($excel,$sheetName,$caption){    
   $sheetIndex = ($excel->getSheetCount()==1)?0:$excel->getSheetCount()-1;
   $excel->createSheet();   
   $excel->setActiveSheetIndex($sheetIndex);   
   $sheet = $excel->getActiveSheet();      
   if($sheet){
   $sheet->setTitle($sheetName);  
    
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('Company logo');
    $objDrawing->setDescription('Company logo');
    $objDrawing->setPath(HMP.'/design/intermarket.jpg');
    $objDrawing->setHeight(72);
    $objDrawing->setCoordinates('A1');
    $objDrawing->setOffsetX(10);
    $objDrawing->setWorksheet($sheet);
    
    
    $sheet->setCellValue('D2',$caption);
    $sheet->getStyle('D2')->getFont()->setName('Calibri');
    $sheet->getStyle('D2')->getFont()->setSize(20);
    $sheet->getStyle('D2')->getFont()->setBold(true);  
    $sheet->setCellValue('D3','Report Date: '.date('d/m/Y'));
    $sheet->getStyle('D3')->getFont()->setName('Calibri');
    $sheet->getStyle('D3')->getFont()->setSize(12);
    $sheet->getStyle('D3')->getFont()->setItalic(true);        
    return $sheet;
   }else{
    return false;
   }   
 }
 
 function getSkuList($repName,$ddt,$utils){
   $ret = "";
   $qry = "select repLocation,repCode,companyDivision,salesVehicle           
           from outlet_purchase             
            WHERE 1=1
            and repName LIKE '$repName' 
            and entry_dtt between '$ddt' AND DATE_ADD('$ddt',INTERVAL 1 DAY)
            and purchaseType IN('First Call','Visit','Call')         
          ";         
   $det = $utils->db->getAll($qry);
   $region = $det[0]->repLocation;
   $area = $det[0]->repCode;
   $division = $det[0]->companyDivision;
   $vehicle = $det[0]->salesVehicle;   
    
   $qry = "select distinct(product_name) as product_name from products 
           where company_division LIKE '$division' 
           and company_region LIKE '$region' 
           and company_area LIKE '$area' 
           and channel_category LIKE '$vehicle'                      
           ";
   $dat = $utils->db->getAll($qry);
   $tot = count($dat);
   if($tot > 0){
    for($a=0;$a<$tot;$a++){
     $ret[$a] = $dat[$a]->product_name;     
    }
   }
   return $ret;        
  }
  
 //load mail list 
 $qry = "select id, member_name, member_phone, member_email, reports, route_area,route_location, company_name,access_channel from sales_force_mails where company_name IN('Tobacco','Tobacco RB','Blue Arrow','BA - Presales') 
                and member_email not like '' and is_active = 1 and access_channel not in('Security','Field Auditor') /*and member_email in('adenowun.oladipupo@greatbrandsng.com')*/";
 //echo $qry;
 $mailDat = $utils->db->getAll($qry);
 $totRecip = count($mailDat);
 
 $baWeeklyFCP = array('BA - Horeca','BA - Mars','BA - Wrigleys','BA - Presales','BA - Key Accounts','BA - VAN SALES','BA - USL','BA - SAF','Blue Arrow','BA - Alliance');
 
 $gmtOff = 1 * 60 * 60; //12 hours; GMT + 12
	$timeNow = time()+$gmtOff;
	$week_num = gmdate("W",$timeNow);
	$call_priority = 1;
	/*if(($week_num % 2) > 0 && $company == 'BA - Wrigleys'){
   $call_priority = 2;
  }*/
 
 //domain check query
 $domainQry = "CREATE OR REPLACE VIEW rep_outlets_today as 
          SELECT a.repName, a.outletName, a.outletAddress, b.outletLongitude AS plan_longitude, b.outletLatitude AS plan_latitude, a.outletLongitude AS sales_longitude, a.outletLatitude AS sales_latitude,          
         (convertDMStoDD(b.outletLatitude)+convertDMStoDD(b.outletLongitude)) AS planLatLon, (convertDMStoDD(a.outletLatitude)+convertDMStoDD(a.outletLongitude)) AS visitLatLon,
          ((convertDMStoDD(a.outletLatitude)+convertDMStoDD(a.outletLongitude))-(convertDMStoDD(b.outletLatitude)+convertDMStoDD(b.outletLongitude))) AS coordVar,
          /*case ((convertDMStoDD(a.outletLatitude)+convertDMStoDD(a.outletLongitude))-(convertDMStoDD(b.outletLatitude)+convertDMStoDD(b.outletLongitude))) WHEN 0.01 THEN 'Y' WHEN -0.01 THEN 'Y' WHEN 0 THEN 'Y' ELSE 'N' END AS FCP_COMPLY*/
                    IF (((convertDMStoDD(a.outletLatitude)+convertDMStoDD(a.outletLongitude))-(convertDMStoDD(b.outletLatitude)+convertDMStoDD(b.outletLongitude))) BETWEEN -0.1 AND 0.1, 'Y', 'N') AS FCP_COMPLY
          FROM basic b, outlet_purchase a
          WHERE 
           a.repName = b.repName
           AND a.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$start_dtt',INTERVAL 1 DAY)
           AND DAYOFWEEK(b.entry_dtt) = DAYOFWEEK('$start_dtt')
           /*AND DAYOFWEEK(a.entry_dtt) = DAYOFWEEK('$start_dtt')*/                     
          AND a.outletName = b.outletName
          AND a.outletAddress = b.outletAddress          
          AND a.purchaseType IN('First Call','Visit','Call')                                                 
          /*AND SUBSTRING(a.outletLongitude,1,8) NOT LIKE SUBSTRING(b.outletLongitude,1,8)*/ 
          AND b.outletName NOT LIKE '%office'
          AND RIGHT(b.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')
           and b.call_priority = IF(b.companyDivision = 'BA - Wrigleys' && (($week_num % 2) > 0),2,1)
           and b.salesVehicle IN ('Van','Wholesale','Distribution')
         ";
 $utils->db->runDml($domainQry); 
 
 //domain check query
 $domainQry = "CREATE OR REPLACE VIEW daily_outlets_today as 
          SELECT a.repName, a.outletName, a.outletAddress, b.outletLongitude AS plan_longitude, b.outletLatitude AS plan_latitude, a.outletLongitude AS sales_longitude, a.outletLatitude AS sales_latitude,
         (convertDMStoDD(b.outletLatitude)+convertDMStoDD(b.outletLongitude)) AS planLatLon, (convertDMStoDD(a.outletLatitude)+convertDMStoDD(a.outletLongitude)) AS visitLatLon,
          ((convertDMStoDD(a.outletLatitude)+convertDMStoDD(a.outletLongitude))-(convertDMStoDD(b.outletLatitude)+convertDMStoDD(b.outletLongitude))) AS coordVar,
          /*case ((convertDMStoDD(a.outletLatitude)+convertDMStoDD(a.outletLongitude))-(convertDMStoDD(b.outletLatitude)+convertDMStoDD(b.outletLongitude))) WHEN 0.01 THEN 'Y' WHEN -0.01 THEN 'Y' WHEN 0 THEN 'Y' ELSE 'N' END AS FCP_COMPLY*/
                    IF (((convertDMStoDD(a.outletLatitude)+convertDMStoDD(a.outletLongitude))-(convertDMStoDD(b.outletLatitude)+convertDMStoDD(b.outletLongitude))) BETWEEN -0.1 AND 0.1, 'Y', 'N') AS FCP_COMPLY
          FROM basic b, outlet_purchase a
          WHERE 
           a.repName = b.repName
           AND a.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$start_dtt',INTERVAL 1 DAY)
           /*AND DAYOFWEEK(b.entry_dtt) = DAYOFWEEK('$start_dtt')*/
           /*AND DAYOFWEEK(a.entry_dtt) = DAYOFWEEK('$start_dtt')*/                     
          AND a.outletName = b.outletName
          AND a.outletAddress = b.outletAddress          
          AND a.purchaseType IN('First Call','Visit','Call')                                                 
          /*AND SUBSTRING(a.outletLongitude,1,8) NOT LIKE SUBSTRING(b.outletLongitude,1,8)*/ 
          AND b.outletName NOT LIKE '%office'
          AND RIGHT(b.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')
           /*and b.call_priority = $call_priority*/
           and b.salesVehicle IN ('Motorbike','Motorbike - PS')
         ";
 $utils->db->runDml($domainQry);
 
 for($z=0;$z<$totRecip;$z++){  
 set_time_limit(0);
  $company = $mailDat[$z]->company_name;
  $company_str = $company=="Blue Arrow"?"'BA - Horeca','BA - Mars','BA - Wrigleys','BA - Presales','BA - Key Accounts','BA - VAN SALES','BA - USL','BA - SAF','Blue Arrow'":"'$company'";  
  $member_id = $mailDat[$z]->id;
  $member_name = $mailDat[$z]->member_name;         
  $member_email = $mailDat[$z]->member_email;
  $reports = $mailDat[$z]->reports;
  $route_area = $mailDat[$z]->route_area;
  $routeArea_a = explode(',',$route_area);
  $route_location = $mailDat[$z]->route_location;
  $routeLocation_a = explode(',',$route_location);
  $access_channel = $mailDat[$z]->access_channel;
  $channel_a = explode(',',$access_channel);
  $repAreas = $repLocations =$repChannel = "";
  for($g=0;$g<count($routeArea_a);$g++){
   $repA = $routeArea_a[$g];
   $repAreas .= "'$repA'";
   if($g<count($routeArea_a)-1){
    $repAreas .= ",";
   }
  }
  for($q=0;$q<count($routeLocation_a);$q++){
   $repB = $routeLocation_a[$q];
   $repLocations .= "'$repB'";
   if($q<count($routeLocation_a)-1){
    $repLocations .= ",";
   }
  }
  for($w=0;$w<count($channel_a);$w++){
   $repC = $channel_a[$w];
   $repChannel .= "'$repC'";
   if($w<count($channel_a)-1){
    $repChannel .= ",";
   }
  }
  
  
  $companyTitle = ($company == 'Tobacco RB')?'Red Bull':$company;
  $caption = "$companyTitle Executive Sales Summary";

  $xlsFile = "$companyTitle"."_Executive_Sales_Summary_$start_dtt.xls";
        $reportTitle = str_replace('.xls','',$xlsFile);
        $data_path = HMP."/exports/$xlsFile";
        @unlink($data_path);
        $excel = new PHPExcel();
        $excel->getProperties()->setCreator("Mobiletrader")->setLastModifiedBy("Mobiletrader")
							 ->setTitle($reportTitle)
							 ->setSubject($reportTitle)
							 ->setDescription($reportTitle)
							 ->setKeywords("Executive Sales Summary")
							 ->setCategory("Sales Report");        
        
        $excel->setActiveSheetIndex(0);
        
  for($u=0;$u<count($channel_a);$u++){
  $theChannel = $channel_a[$u];  
  
   $domainView = ($theChannel == 'Motorbike')?'daily_outlets_today':'rep_outlets_today';  
  
 $qry = "select a.vehicles_id, a.repName, a.repCode, a.repLocation, a.repArea, a.repRegion, a.repCompany, a.repVehicle, (select count(b.outletName) from basic b where a.repName = b.repName AND RIGHT(b.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL') AND IF(a.repVehicle IN('Van','Wholesale','Distribution'),DAYOFWEEK(b.entry_dtt) = DAYOFWEEK('$start_dtt'),1=1) and b.call_priority = IF(b.companyDivision = 'BA - Wrigleys' && (($week_num % 2) > 0),2,1) and b.companyDivision IN($company_str) /*and b.salesVehicle = '$repChannel'*/) as plan_outlets, 
         (select count(DISTINCT(CONCAT(c.outletName,c.outletAddress))) as tot from outlet_purchase c where a.repName = c.repName AND c.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND c.purchaseType IN('First Call','Visit','Call') AND RIGHT(c.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')) as actual_calls,
         (select count(DISTINCT(CONCAT(d.outletName,d.outletAddress))) as tot from outlet_purchase d where a.repName = d.repName AND d.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND d.purchaseType IN('First Call','Visit','Call') AND d.purchaseVolume > 0 AND RIGHT(d.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')) as effective_calls,
         (select SUM(IF(e.purchaseUnit='Pack',e.purchaseVolume/10,e.purchaseVolume)) from outlet_purchase e where a.repName = e.repName AND e.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) ) as sales_volume,
         (select SUM(k.purchaseValue) from outlet_purchase k where a.repName = k.repName AND k.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) ) as sales_value,
         (select GROUP_CONCAT(CONCAT(j.outletLongitude,'|',j.outletLatitude)) as vcoords from outlet_purchase j where a.repName = j.repName AND j.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) ) as visit_coords, 
         (select GROUP_CONCAT(CONCAT(z.outletLongitude,'|',z.outletLatitude)) as bcoords from basic z where a.repName = z.repName AND DAYOFWEEK(z.entry_dtt) = DAYOFWEEK('$start_dtt') AND z.call_priority = IF(z.companyDivision = 'BA - Wrigleys' && (($week_num % 2) > 0),2,1)) as base_coords,        
         (select GROUP_CONCAT(j.outletName) as voutlets from outlet_purchase j where a.repName = j.repName AND j.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) ) as visit_outlets,
         (select GROUP_CONCAT(z.outletName) as boutlets from basic z where a.repName = z.repName AND DAYOFWEEK(z.entry_dtt) = DAYOFWEEK('$start_dtt') AND z.call_priority = IF(z.companyDivision = 'BA - Wrigleys' && (($week_num % 2) > 0),2,1) ) as base_outlets,
         
		 (select DATE_FORMAT(MIN(f.entry_dtt),'%r') from attendance f where a.repName = f.repName AND f.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND f.actionType LIKE 'Clock In') as first_clock,
		 
		 		 (select DATE_FORMAT(MIN(f.entry_dtt),'%r') from attendance f where a.repName = f.repName AND f.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND f.actionType LIKE 'Clock Out') as clock_out,
		 
         (select DATE_FORMAT(MIN(f.entry_dtt),'%r') from outlet_purchase f where a.repName = f.repName AND f.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND RIGHT(f.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')) as start_time,
         (select DATE_FORMAT(MAX(g.entry_dtt),'%r') from outlet_purchase g where a.repName = g.repName AND g.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND RIGHT(g.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')) as end_time,
         (select DATE_FORMAT(MAX(g.entry_dtt),'%r') from attendance g where a.repName = g.repName AND g.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND g.actionType LIKE 'Clock In') as clock_in,
         (select TIMEDIFF(MAX(h.entry_dtt),MIN(h.entry_dtt)) from outlet_purchase h where a.repName = h.repName AND h.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND RIGHT(h.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')) as time_in_trade,
         (select TIMEDIFF(MAX(h.entry_dtt),MIN(h.entry_dtt)) from outlet_purchase h where a.repName = h.repName AND h.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) ) as total_time,
         (select count(i.outletAvail) from outlet_purchase i where a.repName = i.repName AND i.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND i.outletAvail = '1' AND RIGHT(i.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')) as outlet_avail,
         (select count(j.outletName) from $domainView j where a.repName = j.repName and fcp_comply = 'Y') as sales_onfcp,
         (select DATE_FORMAT(k.entry_dtt,'%d.%m.%Y') from outlet_purchase k where a.repName = k.repName AND k.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND k.outletAvail = '1' LIMIT 0,1) as entry_dtt, 
         (select DATE_FORMAT(l.entry_dtt,'%Y-%m-%d') from outlet_purchase l where a.repName = l.repName AND l.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY) AND l.outletAvail = '1' LIMIT 0,1) as norm_dtt,
         (select GROUP_CONCAT(CONCAT(outletName,' - ',purchaseType,' - ',IF(outletAvail='1','Open','Closed')) SEPARATOR '\r\n') as my_visit from outlet_purchase m where a.repName = m.repName AND m.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY)) as outlets_visited,
         (select GROUP_CONCAT(promoRemark SEPARATOR '\r\n') as my_visit from outlet_purchase n where a.repName = n.repName AND n.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY)) as promo_remark,
         (select GROUP_CONCAT(otherRemark SEPARATOR '\r\n') as my_visit from outlet_purchase o where a.repName = o.repName AND o.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY)) as other_remark,
         (select GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT(sku,' - ','Stock: ',qty,'Rolls - ','Roll Price: N',pack_price,' - ','Pack Price: N',stick_price) SEPARATOR '\r\n') FROM outlet_purchase_dtl WHERE outlet_purchase_id = p.outlet_purchase_id) SEPARATOR '\r\n\r\n') as my_visit from outlet_purchase p where a.repName = p.repName AND p.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY)) as brands_data,
         (select GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT(sku,' - ','Rolls: ',qty,' - ',IF(avail='1','In Stock','OOS')) SEPARATOR '\r\n') FROM outlet_purchase_dtl WHERE outlet_purchase_id = q.outlet_purchase_id and qty > 0) SEPARATOR '\r\n\r\n') as my_visit from outlet_purchase q where a.repName = q.repName AND q.entry_dtt BETWEEN '$start_dtt' AND DATE_ADD('$end_dtt',INTERVAL 1 DAY)) as company_sku
         from vehicles a
         WHERE 1=1           
         AND a.repCompany IN($company_str)
         AND a.repArea IN($repAreas)";
         
         if($repLocations != "''" && $repLocations != ""){$qry .= " AND a.repLocation IN($repLocations)";}
         
         $qry .= "
         AND a.repName NOT LIKE '%-x'
         AND a.repName NOT LIKE '%Vann'
         AND a.repVehicle = '$theChannel'
         AND a.is_active = '1'
         ORDER BY a.repVehicle,a.repRegion,a.repArea,a.repLocation,a.repName
         ";        
       //echo $qry."<br /><br />"; 
       //echo $repLocations;
       //exit;        
        $recdat = $utils->db->getAll($qry);
        //echo mysql_error();
        $tot = count($recdat);
       if($tot > 0){        	                             
        
        $mainSheet = layoutSheet($excel,$theChannel,$caption); 
        if(!$mainSheet){
         continue;
        }        
   
   //headers
  $totalFormat = array(
			'font'    => array(
				'bold'      => true
			),
			'numberformat'    => array(
				'code'      => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFFFF'
	 			)
	 		)
		); 
   
  $cellOutline = array(
	'numberformat'    => array(
				'code'      => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
			),
  'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	  ),
   );
   
   $normFormat = array(	
  'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	  ),
   );   
   
   $start_row = 5;
   $col = 1;
   $headers = array('Date','Company','Vehicle','Region','Area','Location','Rep', 'Rep Code','Plan Outlets','Covered Outlets','% Coverage','Effective Calls','Strike Rate','Total Units Sold','Total Value','Avg. Realization','Drop Size','KM Covered','Units/KM','Attendance','Clock Out','Start Time','Stop Time','Clock In','Time In Trade','Total Time','Customer Avail %','% of visit to POS');
   if($theChannel == 'Wholesale' && $company != 'Tobacco RB'){
    $headers = array('Date','Company','Vehicle','Region','Area','Location','Rep','Rep Code','Plan Outlets','Covered Outlets','% Coverage','Outlets Visited','Promo Remark','Other Remark','Competition Info','Attendance','Clock Out','Start Time','Stop Time','Clock In','Time In Trade','Total Time','% of visit to POS');
   }
   foreach($headers as $k => $v){
    $theCell = $utils->numToAlpha($col).$start_row;
    $mainSheet->setCellValue($theCell,$v);
    $mainSheet->getStyle($theCell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $mainSheet->getStyle($theCell)->getFill()->getStartColor()->setARGB('FF808080');
    $mainSheet->getStyle($theCell)->getFont()->setSize(12);
    $mainSheet->getStyle($theCell)->getFont()->setBold(true);
    $mainSheet->getStyle($theCell)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $mainSheet->getStyle($theCell)->getAlignment()->setWrapText(true);
    $mainSheet->getStyle($theCell)->applyFromArray($cellOutline);    
    $mainSheet->getColumnDimension($utils->numToAlpha($col))->setWidth(10);
    $mainSheet->getColumnDimension('G')->setAutoSize(true);
    if($theChannel == 'Wholesale'){
     $textCols = array('K','L','M','N');
     foreach($textCols as $t=>$g) {
     $mainSheet->getColumnDimension($g)->setAutoSize(true); 
     }    
    }
    $col++;
   }
         
        $giz = 0;
        $distance_info = "INSERT INTO distances(vehicles_id, km_covered, entry_dtt) VALUES";
        $time_start = microtime(true);
        $t_runs = 0;
        $max_lookup = 2;
        $twelve_seconds = 12*1000000;
       for($a=0;$a<$tot;$a++){ 
        $t_runs = $a+1;              
        $vehicles_id = $recdat[$a]->vehicles_id;
        $repName = $recdat[$a]->repName;
		$repCode = $recdat[$a]->repCode;
        $repLocation = $recdat[$a]->repLocation;
        $repArea = $recdat[$a]->repArea;
        $repRegion = $recdat[$a]->repRegion;
        $repCompany = ($recdat[$a]->repCompany == 'Tobacco RB')?'Red Bull':$recdat[$a]->repCompany;//$recdat[$a]->repCompany;
        $repVehicle = $recdat[$a]->repVehicle;
        $plan_outlets = $recdat[$a]->plan_outlets;
        $actual_calls = $recdat[$a]->actual_calls;       
        $effective_calls = $recdat[$a]->effective_calls;
        $sales_volume = $recdat[$a]->sales_volume;
        $sales_value = $recdat[$a]->sales_value;
        //echo $utils->getVisitKm($recdat[$a]->visit_coords);       
        //exit;
        //$entry_dtt = date('d-m-Y');
        $entry_dtt = $recdat[$a]->entry_dtt;
        //$visit_km = ($entry_dtt == "")?0:$utils->getVisitKm($recdat[$a]->visit_coords)/1000;
        $visit_km = 0;
	$first_clock = $recdat[$a]->first_clock;
        $clock_out = $recdat[$a]->clock_out;
        $start_time = $recdat[$a]->start_time;
        $end_time = $recdat[$a]->end_time;
        $clock_in = $recdat[$a]->clock_in;
        $clock_in = ($clock_out == $clock_in)?"":$clock_in;
        $time_in_trade = $recdat[$a]->time_in_trade;
        $total_time = $recdat[$a]->total_time;
        $outlet_avail = $recdat[$a]->outlet_avail;
        $sales_onfcp = $recdat[$a]->sales_onfcp;        
        $percent_coverage = ($actual_calls>0 && $plan_outlets>0)?round($actual_calls/$plan_outlets*100,1).'%':'0%';
        $strike_rate = ($effective_calls>0 && $actual_calls>0)?round($effective_calls/$actual_calls*100,1).'%':'0%';
        $avg_realization = ($sales_volume>0 && $sales_value>0)?round($sales_value/$sales_volume,1):0;
        $drop_size = ($sales_volume>0 && $effective_calls>0)?round($sales_volume/$effective_calls,1):0;
        $unit_per_km = ($sales_volume>0 && $visit_km>0)?round($sales_volume/$visit_km,1):0;
        $percent_outlet_avail = ($outlet_avail>0 && $actual_calls>0)?round($outlet_avail/$actual_calls*100,1).'%':'0%';
        $percent_fcp_comply = ($sales_onfcp>0 && $actual_calls>0)?round($sales_onfcp/$actual_calls*100,1).'%':'0%';
        $outlets_visited = $recdat[$a]->outlets_visited;
        $promo_remark = $recdat[$a]->promo_remark;
        $other_remark = $recdat[$a]->other_remark;
        $brands_data = $recdat[$a]->brands_data;
        $company_sku = $recdat[$a]->company_sku;        
        
       /* if($entry_dtt == ""){    
         continue;
        } */ 
        
        //$rowNum = $start_row+$a+1;
        $giz += 1;
        $rowNum = $start_row+$giz;
        //details
        //$format = ($a == 0)?'firstRow':'otherRows';
        $details = array($entry_dtt,$repCompany,$repVehicle,$repRegion,$repArea,$repLocation,$repName,$repCode,$plan_outlets,$actual_calls,$percent_coverage,$effective_calls,
                         $strike_rate,number_format($sales_volume,1),number_format($sales_value,1),$avg_realization,
                         $drop_size,round($visit_km,3),$unit_per_km,$first_clock,$clock_out,$start_time,$end_time,$clock_in,$time_in_trade,$total_time,$percent_outlet_avail,
                         $percent_fcp_comply
                         );
        if($theChannel == 'Wholesale' && $company != 'Tobacco RB'){
         $details = array($entry_dtt,$repCompany,$repVehicle,$repRegion,$repArea,$repLocation,$repName,$repCode,$plan_outlets,$actual_calls,$percent_coverage,$outlets_visited,
                          $promo_remark,$other_remark,$brands_data,$first_clock,$clock_out,$start_time,$end_time,$clock_in,$time_in_trade,$total_time,$percent_fcp_comply
                         );
        }                 
        if($member_email == 'adenowun.oladipupo@greatbrandsng.com'){
         $distance_info .= "('$vehicles_id','$visit_km','$entry_dtt')"; 
         if($a<$tot-1){$distance_info .= ",";}    
        }            
        
        $col = 1; 
        $normCols = array('G','H','J');                
        foreach($details as $k => $v){                 
         $theCell = $utils->numToAlpha($col).$rowNum;
         $mainSheet->setCellValue($theCell,$v);
         if(!in_array($utils->numToAlpha($col),$normCols)){
         $mainSheet->getStyle($theCell)->applyFromArray($cellOutline);
         }else{
          $mainSheet->getStyle($theCell)->applyFromArray($normFormat);
         }
         $mainSheet->getColumnDimension('F')->setAutoSize(true);
         if($theChannel == 'Wholesale'){
          $textCols = array('K','L','M','N');
         foreach($textCols as $t=>$g) {
          $mainSheet->getColumnDimension($g)->setAutoSize(true); 
         }  
         $mainSheet->getStyle($theCell)->getAlignment()->setWrapText(true);          
        }/*else{
         $textCols = array('AA','AB');
         foreach($textCols as $t=>$g) {
          $mainSheet->getColumnDimension($g)->setAutoSize(true); 
         }  
         $mainSheet->getStyle($theCell)->getAlignment()->setWrapText(true);
        }*/
                  
         $col++;
        } 
        
        //sales per drop
        /*$theSheet = layoutSheet($excel,$repName,"Sales Per Drop (Rolls)");
        //$theSheet = false;//prevent further processing
        if(!$theSheet){
         continue;
        }
        $skus = getSkuList($repName,$start_dtt,$utils);        
        $purchase = $detail = ""; */
        /*if($skus != ""){
         $skuCnt = count($skus);
         $purchase .= ",";                  
         $theSheet->setCellValue($utils->numToAlpha(1).$start_row,"SN");
         $theSheet->getStyle($theCell)->applyFromArray($totalFormat);
         $theSheet->getColumnDimension('A')->setAutoSize(true);
         $theSheet->setCellValue($utils->numToAlpha(2).$start_row,"Outlet Name");
         $theSheet->getStyle($theCell)->applyFromArray($totalFormat);
         $theSheet->getColumnDimension('B')->setAutoSize(true);
         $theSheet->setCellValue($utils->numToAlpha(3).$start_row,"Outlet Address");
         $theSheet->getStyle($theCell)->applyFromArray($totalFormat);
         $theSheet->getColumnDimension('C')->setAutoSize(true);
         $theSheet->setCellValue($utils->numToAlpha(4).$start_row,"Time");
         $theSheet->getStyle($theCell)->applyFromArray($totalFormat);
         $theSheet->getStyle($theCell)->getFont()->setSize(20);                       
         $cal = 5;                  
         for($b=0;$b<$skuCnt;$b++){
          $sku = $skus[$b];
          $purchase .= "(select sum(b.qty) from outlet_purchase_dtl b where b.outlet_purchase_id = a.outlet_purchase_id and b.sku LIKE '$sku') as '$sku'";               
          $theSheet->setCellValue($utils->numToAlpha($cal).$start_row,$sku);
          $theSheet->getStyle($utils->numToAlpha($cal).$start_row)->applyFromArray($totalFormat);
          if($b<$skuCnt-1){$purchase .= ",";}
          $cal++;
          }
          $theSheet->setCellValue($utils->numToAlpha($cal).$start_row,"Total");
          $theSheet->getStyle($utils->numToAlpha($cal).$start_row)->applyFromArray($totalFormat);
         }
         $dropQry = "select a.outlet_purchase_id, a.outletName, a.outletAddress, a.outletLongitude, a.outletLatitude, DATE_FORMAT(a.entry_dtt,'%r') as salesTime $purchase                       
           from outlet_purchase a             
            WHERE 1=1
            and a.repName LIKE '$repName' 
            and a.entry_dtt between '$start_dtt' AND DATE_ADD('$start_dtt',INTERVAL 1 DAY)
            and a.purchaseType = 'First Call'
            AND RIGHT(a.outletName,3) NOT IN(' AO',' CC',' DP',' RO',' WM','BTL')
            ORDER BY a.entry_dtt
          ";*/          
          //$dropDat = $utils->db->getAll($dropQry);
          /*$purchase = "";
          $dropTot = count($dropDat);
          if($dropTot < 0 || $dropTot == 0){continue;}
          $sta_row = $start_row + 1; */
          /*for($c=0;$c<$dropTot;$c++){           
           $sn = $c+1;
           $outlet_id = $dropDat[$c]->outlet_purchase_id;          
           $outletName = $dropDat[$c]->outletName;
           $outletAddress = $dropDat[$c]->outletAddress;
           $outletLongitude = $dropDat[$c]->outletLongitude;
           $outletLatitude = $dropDat[$c]->outletLatitude;
           $salesTime = $dropDat[$c]->salesTime;
           $totPur = 0;
                                 
           $theSheet->setCellValue($utils->numToAlpha(1).$sta_row,$sn);
           $theSheet->getStyle($utils->numToAlpha(1).$sta_row)->applyFromArray($normFormat);
           $theSheet->setCellValue($utils->numToAlpha(2).$sta_row,$outletName);
           $theSheet->getStyle($utils->numToAlpha(2).$sta_row)->applyFromArray($normFormat);
           $theSheet->setCellValue($utils->numToAlpha(3).$sta_row,$outletAddress);
           $theSheet->getStyle($utils->numToAlpha(3).$sta_row)->applyFromArray($normFormat);
           $theSheet->setCellValue($utils->numToAlpha(4).$sta_row,$salesTime);
           $theSheet->getStyle($utils->numToAlpha(4).$sta_row)->applyFromArray($normFormat);           
           $cbl = 5;
           //$details = array($sn,$outletName,$outletAddress);           
           if($skus != ""){
           $skuCnt = count($skus);
           for($d=0;$d<$skuCnt;$d++){
             $sku = $skus[$d];
             $qtyTemp = number_format($dropDat[$c]->$sku,1);
             $totPur += $dropDat[$c]->$sku;
             $theSheet->setCellValue($utils->numToAlpha($cbl).$sta_row,$qtyTemp);
             $theSheet->getStyle($utils->numToAlpha($cbl).$sta_row)->applyFromArray($cellOutline);
             $cbl++;
            }
            $totPur = number_format($totPur,1);
            $theSheet->setCellValue($utils->numToAlpha($cbl).$sta_row,$totPur);
            $theSheet->getStyle($utils->numToAlpha($cbl).$sta_row)->applyFromArray($cellOutline);
           }
           $sta_row++;                            
          }*/   
          //if($t_runs%$max_lookup == 0){usleep($twelve_seconds);}                 
        }
                   
                         
       }     
       else{
       echo 'Nothing to do!';
      } 
 }     
      //finalize workbook and send
        //write only once
        if($member_email == 'adenowun.oladipupo@greatbrandsng.com'){
         $utils->db->runDml($distance_info); 
        }
        set_time_limit(0); 
        $excel->setActiveSheetIndex(0);
        $excelWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $excelWriter->save($data_path);
        //echo "<a href='http://mobiletrader.greatbrandsng.com/exports/$xlsFile'>download</a>";
        //exit;
        
        //reset encoding if required
        if(function_exists('mb_internal_encoding') && ((int) ini_get('mbstring.func_overload')) & 2){
         $mbEncoding = mb_internal_encoding();
         mb_internal_encoding('ASCII');
         echo "Internal encoding is: $mbEncoding, NOT ASCII";
        }
        
        //send mail
        $conf = init::getConf();
        $host = $conf['mail']['host'];
        $port = $conf['mail']['port'];
        $user = $conf['mail']['defaultUser'];
        $usnm = $conf['site']['name'];
        $pwd = $conf['mail']['passwd'];
        //'psalem@greatbrandsng.com','fadyabikhalil@greatbrandsng.com','charleseyanu@greatbrandsng.com,'samuel.lawal@greatbrandsng.com'
        $cc = array('adenowun.oladipupo@greatbrandsng.com'/*,'ranalyst2.ho@greatbrandsng.com'*/);
        
        $transport = Swift_SmtpTransport::newInstance($host, $port);
        $transport->setUsername($user);
        $transport->setPassword($pwd);
        
        $mailer = Swift_Mailer::newInstance($transport);                
        
        $subject = $caption."-".$member_name;
        $msg = Swift_Message::newInstance();
        $msg->setSubject($subject);
        $msg->setFrom(array($user=>$usnm));
        $msg->setContentType("text/html");
        $msg->attach(Swift_Attachment::fromPath(HMP."/exports/$xlsFile"));                               
         
        $msg->setTo($member_email,$member_name);
        $msg->setCc($cc);   
        //$msg->setReadReceiptTo($cc);      
        $message = "
                      <p>Dear $member_name,</p>
                       <br />
                       <p>Please find attached the <b>$company Executive Summary</b> for today.</p>
                       <br />
                       <p>Regards,</p>
                       <br />
                       <br />
                       <p><b>Mobile Trader</b></p>
                    ";
        $msg->setBody($message);                          
        if($mailer->send($msg)){
         $qri = "update sales_force_mails set send_dtt = '$start_dtt' where id = $member_id";
         $utils->db->runDml($qri);
         echo "sent to $member_name<br />";
        } 
        //revert encoding to previous
        if (isset($mbEncoding)){
        echo "Reverting internal encoding to: $mbEncoding";
         mb_internal_encoding($mbEncoding);
        }   
   }
      $time_end = microtime(true);
      $script_duration = round($time_end - $time_start,1);
      echo "script concluded in $script_duration seconds.<br/>";
?>