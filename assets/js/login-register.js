/*
 *
 * login-register modal
 * Autor: Creative Tim
 * Web-autor: creative.tim
 * Web script: http://creative-tim.com
 * 
 */
function showRegisterForm(){
    $('.loginBox').fadeOut('fast',function(){
        $('.registerBox').fadeIn('fast');
        $('.login-footer').fadeOut('fast',function(){
            $('.register-footer').fadeIn('fast');
        });
        $('.modal-title').html('Register with');
    }); 
    $('.error').removeClass('alert alert-danger').html('');
       
}
function showLoginForm(){
    $('#loginModal .registerBox').fadeOut('fast',function(){
        $('.loginBox').fadeIn('fast');
        $('.register-footer').fadeOut('fast',function(){
            $('.login-footer').fadeIn('fast');    
        });
        
        $('.modal-title').html('Login with');
    });       
     $('.error').removeClass('alert alert-danger').html(''); 
}

function openLoginModal(){
    showLoginForm();
    setTimeout(function(){
        $('#loginModal').modal('show');    
    }, 230);
    
}
function openRegisterModal(){
    showRegisterForm();
    setTimeout(function(){
        $('#loginModal').modal('show');    
    }, 230);
    
}

function loginAjax(){      
    var username = $('input[name="username"]').val();
    var password = $('input[name="password"]').val();
    var msg = "username/password cannot be blank";
    if(username=='' || password ==''){shakeModal(msg);return;}
    var inf = "username="+username+"&password="+password;
    $.post( "api/Auth.php?job=loginWeb",inf, function(data) {
            if(data == "ok"){
                window.location.replace("admin.php");            
            } else {
            msg = "Invalid email/password combination";
                 shakeModal(msg); 
            }
        });    
}

function registerAjax(){      
    var first_name = $('input[name="first_name"]').val();
    var last_name = $('input[name="last_name"]').val();
    var username = $('input[name="user_name"]').val();
    var password = $('input[name="pass_word"]').val();
    var confirm_password = $('input[name="confirm_password"]').val();
    var msg = "name/username/password cannot be blank";
    if(username=='' || password =='' || first_name =='' || last_name ==''){shakeModal(msg);return;}
    var msg = "password not the same";
    if(password != confirm_password){shakeModal(msg);return;}
    var inf = "username="+username+"&password="+password+"&first_name="+first_name+"&last_name="+last_name;
    $.post( "api/Auth.php?job=register",inf, function(data) {
            if(data == "ok"){
                window.location.replace("admin.php");            
            } else {
            msg = "Unable to create account";
                 shakeModal(msg); 
            }
        });    
}

function shakeModal(msg){
    $('#loginModal .modal-dialog').addClass('shake');
             $('.error').addClass('alert alert-danger').html(msg);
             $('input[name="username"]').val('');
             $('input[name="password"]').val('');
             setTimeout( function(){ 
                $('#loginModal .modal-dialog').removeClass('shake'); 
    }, 1000 ); 
}

   