<div class="sidebar" data-color="purple" data-image="assets/img/sidebar-4.jpg">    
    
    <!--   
        
        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" 
        Tip 2: you can also add an image using data-image tag
        
    -->
       <?php          
        $file = basename($_SERVER['REQUEST_URI']);  
        $modules = $_SESSION['modules'];
        $mod_arr = explode('|',$modules);                                     
       ?>
    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="http://www.soldip.com" class="simple-text">
                    Field Force
                </a>
            </div>
                       
            <ul class="nav">
                <?php 
                 $page_title = "";
                 for($a=0;$a<count($mod_arr);$a++){
                 list($mod_id,$mod_name,$mod_file,$mod_icon) = explode(':',$mod_arr[$a]);
                 $active = ($file==$mod_file)?'active':'';
                 $page_title = ($file==$mod_file)?$mod_name:'Dashboard';
                ?>
                <li class="<?php echo $active;?>">
                    <a href="<?php echo $mod_file;?>">
                        <i class="<?php echo $mod_icon;?>"></i> 
                        <p><?php echo $mod_name;?></p>
                    </a>            
                </li>
                <?php } ?>                
            </ul> 
    	</div>
      </div>    