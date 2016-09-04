<?php 
                 $page_title = "";
                 for($a=0;$a<count($mod_arr);$a++){
                 list($mod_id,$mod_name,$mod_file,$mod_icon) = explode(':',$mod_arr[$a]);                 
                 if($file==$mod_file){$page_title = $mod_name;break;};
                 }
                ?>
<nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">    
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?php echo $page_title;?></a>
                </div>
                <div class="collapse navbar-collapse">       
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-dashboard"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-globe"></i>
                                    <b class="caret"></b>
                                    <span class="notification">1</span>
                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="#">New Message</a></li>                                
                              </ul>
                        </li>                         
                    </ul>                                         
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="">
                               Account
                            </a>
                        </li>
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Quick Links
                                    <b class="caret"></b>
                              </a>
                              <ul class="dropdown-menu">
                                <!--li><a href="javascript:demo.startDay();">Start Day</a></li>                                
                                <li class="divider"></li>                                
                                <li><a href="settings.php">Settings</a></li>                                
                                <li class="divider"></li-->
                                <li><a href="profile.php">My Profile</a></li>
                                <!--li class="divider"></li>
                                <li><a href="#">Report</a></li-->
                              </ul>
                        </li>
                        <li>
                            <a href="logout.php">
                                Log out
                            </a>
                        </li> 
                    </ul>
                </div>
            </div>
        </nav>