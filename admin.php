<?php 
@session_start();
if(!isset($_SESSION['user_id'])){header('Location: index.php');}
?>
<!doctype html>
<html lang="en">
<?php require_once("header.php");?>
<body> 

<div class="wrapper">
<?php require_once("sidebar.php");?>        
    <div class="main-panel">
     <?php require_once("toolbar.php");?>                                             
        <div class="content">
            <div class="container-fluid">    
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Customers</h4>
                                <p class="category">By Channel</p>
                            </div>
                            <div class="content">                                
                                <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div>
                                
                                <div class="footer">
                                    <?php 
                                     require_once("api/Customer.php");
                                     $cust = new Customer();
                                     $info = $cust->getCustomerByChannel();
                                    ?>
                                    <script language="javascript">
                                     var pieChartInfo =  <?php echo $info;?>;
                                    </script>                                    
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-clock-o"></i> as at <?php echo date("F j, Y, g:i a");?>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Task Completion</h4>
                                <p class="category">24 Hours performance as at <?php echo date("F j, Y, g:i a");?></p>
                            </div>
                            <div class="content">
                                <div id="chartHours" class="ct-chart"></div>
                                <div class="footer">
                                <?php 
                                     require_once("api/EmployeeTask.php");
                                     $task = new EmployeeTask();
                                     $info = $task->getTaskCompletion();                                     
                                    ?>
                                    <script language="javascript">
                                     var chartSeries =  <?php echo $info;?>;
                                    </script>
                                    <?php 
                                     $color = array('text-info','text-danger','text-warning');
                                     $tot = count($color);
                                    ?>
                                    <div class="legend">
                                         <?php 
                                         $info_arr= json_decode($info);                                         
                                         for($a=0;$a<$tot;$a++){?>
                                        <i class="fa fa-circle <?php echo $color[$a];?>"></i> <?php echo $info_arr->task[$a];?> 
                                        <?php }?>                                       
                                    </div>                                                                        
                                </div>
                            </div>
                        </div>
                    </div>                   
                </div>
                    
                
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card ">
                            <div class="header">
                                <h4 class="title"><?php echo date('Y');?> Sales By Category</h4>
                                <p class="category">All products in Cartons</p>
                            </div>
                            <div class="content">                               
                                <div id="chartActivity" class="ct-chart"></div>
                    
                                <div class="footer">
                                <?php 
                                     require_once("api/Sales.php");
                                     $sales = new Sales();
                                     $info = $sales->getYtdSales();                                     
                                    ?>
                                    <script language="javascript">
                                     var salesData =  <?php echo $info;?>;
                                    </script>
                                    <?php 
                                     $color = array('text-info','text-danger','text-warning');
                                     $tot = count($color);
                                    ?>
                                    <div class="legend">
                                        <?php 
                                         $info_arr= json_decode($info);                                         
                                         for($a=0;$a<$tot;$a++){?>
                                        <i class="fa fa-circle <?php echo $color[$a];?>"></i> <?php echo $info_arr->category[$a];?> 
                                        <?php }?> 
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-check"></i> as at <?php echo date("F j, Y, g:i a");?>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card ">
                            <div class="header">
                                <h4 class="title"><?php echo date('Y');?> Sales By SKU</h4>
                                <p class="category">volume in Cartons</p>
                            </div>
                            <div class="content">                      
                                <div class="table-full-width">
                                    <table class="table">
                                    <thead>
                                    <th></th>
                                    <th>SKU</th>
                                    <th>YTD</th>
                                    <th>SPLY</th>
                                    <th>VAR</th>
                                    <th>VAR(%)</th>
                                    </thead>
                                        <tbody>
                                        <?php 
                                     require_once("api/Sales.php");
                                     $sales = new Sales();
                                     $info = $sales->getYtdSkuSales();   
                                     $info_arr= json_decode($info); 
                                     $prod = $info_arr->product;
                                     $sales_vol = $info_arr->sales_volume; 
                                     $tot = count($prod);                                       
                                      for($a=0;$a<$tot;$a++){
                                      $ytd = $sales_vol[$a][0]; 
                                      $sply = $sales_vol[$a][1];                                
                                    ?>
                                            <tr>
                                                <td>
                                                    <label class="checkbox">
                                                        <input type="checkbox" value="" data-toggle="checkbox">
                                                    </label>
                                                </td>
                                                <td><?php echo $prod[$a];?></td> 
                                                <td><?php echo number_format($ytd,0);?></td> 
                                                <td><?php echo number_format($sply,0);?></td>
                                                <td><?php echo number_format($ytd-$sply,0);?></td>
                                                <td><?php echo ($ytd==0||$sply==0)?0:round((($ytd/$sply)*100)-100,2);?></td>                                                
                                            </tr>
                                      <?php }?>      
                                            <!--<tr>
                                                <td>
                                                    <label class="checkbox">
                                                        <input type="checkbox" value="" data-toggle="checkbox" checked="">
                                                    </label>
                                                </td>
                                                <td>Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>-->                                            
                                        </tbody>
                                    </table>
                                </div>
                                                              
                            </div>
                        </div>
                    </div>                   
                </div>        
            </div>    
        </div>        
        
        <?php require_once("footer.php");?>
        
    </div>   
</div>


</body>

    <?php require_once("scripts.php");?>
    
</html>