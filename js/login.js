function emptyElement(x){
	_(x).innerHTML = "";
	}
function login(){
	var u = _("username").value;
	var p = _("password").value;
	if(u == "" || p == ""){
		_("status").innerHTML = "Fill out all of the form data";
		} 
	else {
		_("loginbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "../php/login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "login_failed"){
					_("status").innerHTML = "Login unsuccessful, please try again.";
					_("loginbtn").style.display = "block";
				} else {
					window.location = "user.php?u="+ajax.responseText;
				}
	        }
        }
        ajax.send("u="+u+"&p="+p);
	}
}
