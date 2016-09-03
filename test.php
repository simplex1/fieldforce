<?php
error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);

/*$arr = array(
    ['172.1.1.2'] => array
        ([0] => array(['IP'] => '172.1.1.1'
                      ['PlatformBrand'] => 'dlink'
                ),
            [1] => array
                (
                    ['IP'] => '172.1.1.5'
                    ['PlatformBrand'] => 'dlink'
                ),
            [2] => array
                (
                    ['IP'] => '172.1.1.7'
                    ['PlatformBrand'] => 'dlink'
                ),
            [3] => array
                (
                    ['IP'] => '172.1.1.8'
                    ['PlatformBrand'] => 'dlink'
                )
        ),

    ['172.1.1.6'] => array
        (
            [0] => array
                (
                    ['IP'] => '172.1.1.10'
                    ['PlatformBrand'] => 'dlink'
                )
        ),

    ['172.1.1.7'] => array
        (
            [0] => array
                (
                    ['IP'] => '172.1.1.11'
                    ['PlatformBrand'] => 'dlink'
                ),
            [1] => array
                (
                    ['IP'] => '172.1.1.14'
                    ['PlatformBrand'] => 'dlink'
                )
        )
); */
$arr = array('172.1.1.2' => array(
                               array('IP' => '172.1.1.1','PlatformBrand' => 'dlink'),
                               array('IP' => '172.1.1.5','PlatformBrand' => 'dlink'),
                               array('IP' => '172.1.1.7','PlatformBrand' => 'dlink'),
                               array('IP' => '172.1.1.8','PlatformBrand' => 'dlink')
                              ),
             '172.1.1.6' => array(
                               array('IP' => '172.1.1.10','PlatformBrand' => 'dlink')
                              ), 
             '172.1.1.7' => array(
                               array('IP' => '172.1.1.11','PlatformBrand' => 'dlink'),
                               array('IP' => '172.1.1.14','PlatformBrand' => 'dlink')
                              ),                                 
            );                  
$sn = $parent = $sort = 0;
$hdr = "id  | ip  | parent  | sort\r\n";
$hdr .= "--------------------------------\r\n";
foreach($arr as $k => $v){
  $sn++;
  $hdr .= "$sn  | $k  | $parent  | $sort \r\n";
  $parent = $sn;  
  foreach($v as $t => $d){
   $sn++;
   $hdr .= "$sn  | {$d['IP']}  | $parent  | $sort \r\n";
   $sort++;
  }
  $parent = 0; 
  $sort = 0;   
}

echo nl2br($hdr);   
?>
     