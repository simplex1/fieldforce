<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<style>body{padding-top: 60px;}</style>
	
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
 
	<link href="assets/css/login-register.css" rel="stylesheet" />
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
	
	<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.js" type="text/javascript"></script>
	<script src="assets/js/login-register.js" type="text/javascript"></script>

  <title>Fieldforce :: Sales Management Solution</title>
</head>
<body>
    <div class="container">        
        <div class="row">
         <div class="col-sm-4"></div>
          <div class="col-sm-4">
          <img src="assets/img/ic_launcher.png" />
          <span style="font-size:2em;">Field Force</span>
         <p>...complete Sales Management Solution</p>      
         </div>
         <div class="col-sm-4"></div>
        </div>
        <div class="row">
         <div class="col-sm-4"></div>
          <div class="col-sm-4">
          <span style="display:block;margin:10px auto 10px 25%;font-size:2em;">Licensed to</span><br />
          <img src="assets/img/default_logo.png" />
          <span style="font-size:2em;">Limited</span>               
         </div>
         <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-2">
            <a class="btn big-login" data-toggle="modal" href="javascript:void(0)" onclick="openLoginModal();">Log in</a>
            </div>
            <div class="col-sm-2">                 
                 <a class="btn big-register" data-toggle="modal" href="javascript:void(0)" onclick="openRegisterModal();">Register</a></div>
            <div class="col-sm-4"></div>
        </div>
       
         
		 <div class="modal fade login" id="loginModal">
		      <div class="modal-dialog login animated">
    		      <div class="modal-content">
    		         <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Login with</h4>
                    </div>
                    <div class="modal-body">  
                        <div class="box">
                             <div class="content">
                                <div class="social">
                                    <a class="circle github" href="#">
                                        <i class="fa fa-github fa-fw"></i>
                                    </a>
                                    <a id="google_login" class="circle google" href="#">
                                        <i class="fa fa-google-plus fa-fw"></i>
                                    </a>
                                    <a id="facebook_login" class="circle facebook" href="#">
                                        <i class="fa fa-facebook fa-fw"></i>
                                    </a>
                                </div>
                                <div class="division">
                                    <div class="line l"></div>
                                      <span>or</span>
                                    <div class="line r"></div>
                                </div>
                                <div class="error"></div>
                                <div class="form loginBox">
                                    <form method="post" action="#" accept-charset="UTF-8">
                                    <input id="username" class="form-control" type="text" placeholder="User Name" name="username">
                                    <input id="password" class="form-control" type="password" placeholder="Password" name="password">
                                    <input class="btn btn-default btn-login" type="button" value="Login" onclick="loginAjax()">
                                    </form>
                                </div>
                             </div>
                        </div>
                        <div class="box">
                            <div class="content registerBox" style="display:none;">
                             <div class="form">
                                <form method="post" html="{:multipart=>true}" data-remote="true" action="#" accept-charset="UTF-8">
                                <input id="first_name" class="form-control" type="text" placeholder="First Name" name="first_name">
                                <input id="last_name" class="form-control" type="text" placeholder="Last Name" name="last_name">
                                <input id="user_name" class="form-control" type="text" placeholder="User Name" name="user_name">
                                <input id="pass_word" class="form-control" type="password" placeholder="Password" name="pass_word">
                                <input id="confirm_password" class="form-control" type="password" placeholder="Repeat Password" name="confirm_password">
                                <input class="btn btn-default btn-register" type="button" value="Create account" onclick="registerAjax()">
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="forgot login-footer">
                            <span>Looking to 
                                 <a href="javascript: showRegisterForm();">create an account</a>
                            ?</span>
                        </div>
                        <div class="forgot register-footer" style="display:none">
                             <span>Already have an account?</span>
                             <a href="javascript: showLoginForm();">Login</a>
                        </div>
                    </div>        
    		      </div>
		      </div>
		  </div>
    </div>
</body>
</html>
