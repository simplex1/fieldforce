<div class="sidebar" data-color="purple" data-image="assets/img/sidebar-4.jpg">    
    
    <!--   
        
        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" 
        Tip 2: you can also add an image using data-image tag
        
    -->
       <?php          
        $file = basename($_SERVER['REQUEST_URI']);                               
       ?>
    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="http://www.soldip.com" class="simple-text">
                    Field Force
                </a>
            </div>
                       
            <ul class="nav">
                <li class="<?php echo $file=='admin.php'?'active':'';?>">
                    <a href="admin.php">
                        <i class="pe-7s-graph"></i> 
                        <p>Dashboard</p>
                    </a>            
                </li>
                <li class="<?php echo $file=='employee.php'?'active':'';?>">
                    <a href="employee.php">
                        <i class="pe-7s-user"></i> 
                        <p>Employee</p>
                    </a>
                </li> 
                <li class="<?php echo $file=='customer.php'?'active':'';?>">
                    <a href="customer.php">
                        <i class="pe-7s-map-marker"></i> 
                        <p>Customer</p>
                    </a>        
                </li>                
                <li class="<?php echo $file=='sales_rep_bin.php'?'active':'';?>">
                    <a href="sales_rep_bin.php">
                        <i class="pe-7s-note2"></i> 
                        <p>Sales Rep Bin</p>
                    </a>        
                </li>
                <!--
                <li>
                    <a href="typography.html">
                        <i class="pe-7s-news-paper"></i> 
                        <p>Typography</p>
                    </a>        
                </li>
                <li>
                    <a href="icons.html">
                        <i class="pe-7s-science"></i> 
                        <p>Icons</p>
                    </a>        
                </li>                
                <li>
                    <a href="notifications.html">
                        <i class="pe-7s-bell"></i> 
                        <p>Notifications</p>
                    </a>        
                </li>-->
            </ul> 
    	</div>
      </div>    