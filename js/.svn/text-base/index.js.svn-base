var baseurl = '';
var currenturl = '';
function setbase(url)
{
    baseurl = url;
}
function setcurrenturl(url)
{
	currenturl = url;
}
$(window).load(function(){
		$("#bestdiv").show();
		$("#sponsordiv").show();
	});
$(document).ready(function(){
	$(".muabtn").click(function(){
	//alert('HERE');
		var src = this.id;
		//alert(src);
		//var tmp = src.split('#');
		$.post(baseurl + 'product_prc.php',{'act':'luu-hang','ma-so':src},function(reponse) {
			alert(reponse);
		});
		return false;
	});
	$("#loginbtn").click(function(){
		$(".loginpanel").slideToggle("fast");
	});
	$("#lgin-submit").click(function(){
		//alert('herre');
		var usr_mail = document.getElementById('lgin-email').value;
		var usr_pwd = document.getElementById('lgin-pwd').value;
		if(usr_mail == '' || usr_pwd == '' || check_input(usr_mail,'email') == false || check_input(usr_pwd,'password') == false )
		{
			alert('Dữ liệu nhập không đúng !');
			return false;
		}
		
		$.post(baseurl + 'user_route.php',{'act':'dang-nhap','usr_mail':usr_mail,'usr_pwd':pwdenc(usr_pwd)},function(reponse) {
			//alert(reponse);
			$(".loginpanel").hide();
			msg = "<center>" + reponse + "</center>";
			document.getElementById('msgboxcontent').innerHTML=msg;
			$("#msgbox").slideDown(300).delay(2000).slideUp(400,function(){
					gotopage(currenturl);
				});
			
			
		});
		
	});
	$(".rmenu_item_plus").mouseover(function(){
	    
		$(this).find('span').each(function(){
				$(this).css("display","block").delay(1000); 
		});
		//$(this).find('span').slideDown("fast");
	});
	$(".rmenu_item_plus").mouseout(function(){
		$(this).find('span').each(function(){
				$(this).css("display","none").delay(1000); 
		});
		//$(this).find('span').slideUp("fast");
	});
	$("#lgout-submit").click(function(){
		$.post(baseurl + 'user_route.php',{'act':'dang-xuat','usr_mail':'','usr_pwd':''},function(reponse) {
			$(".loginpanel").hide();
			msg = "<center>" + reponse + "</center>";
			document.getElementById('msgboxcontent').innerHTML=msg;
			$("#msgbox").slideDown(300).delay(2000).slideUp(function(){
					gotopage(currenturl);
				});
		});
		
	});
	
	
	/* HANDLERS */
	var actionobj;
	var action;
	$('body').keyup(function (event) { // KEYBOARD HANDLER
	   var keycode = (event.keyCode ? event.keyCode : event.which);
	   var direction = null;
	   if(keycode == 13)
	   {
			document.getElementById(actionobj).click();
	   }
	  
   });
	$('#lgin-newpwdconf').focus(function(){
		actionobj = "lgin-changepwd";
	});
	$('#lgin-pwd').focus(function(){
		actionobj = "lgin-submit";
	});
	$('#reg-address').focus(function(){
		actionobj = "reg-submit";
	});
	
});
function update_price(sl,pr,unitprice)
{
	var x = sl.value;
	var newpr = x * unitprice;
	var oldpr = document.getElementById(pr).innerHTML;
	document.getElementById(pr).innerHTML = newpr;
	var oldtol = document.getElementById('totalpri').innerHTML;
	var newtol = oldtol - oldpr + newpr;
	document.getElementById('totalpri').innerHTML = newtol;
}
function check_input(inp,type)
{
	return true;
}
function pwdenc(pwd)
{
	return pwd;
}
function gotopage(page)
{
	document.location.href=page;
}
function textCounter(field, countfield, maxlimit) {
	countvar = document.getElementById(countfield);
	if (field.value.length > maxlimit)
		field.value = field.value.substring(0, maxlimit);
	else 
		countvar.innerHTML = maxlimit - field.value.length;
}
function txtState(text,obj,stt)
{
	if(stt == 'on')
	{
		if(obj.value == text)
			obj.value = '';
	}
	else
	{
		if(obj.value == '')
			obj.value = text;
	}
}
function contact_checking()
{
	ten = document.getElementById('ten').value;
	ho = document.getElementById('ho').value;
	email = document.getElementById('email').value;
	dienthoai = document.getElementById('dienthoai').value;
	diachi = document.getElementById('diachi').value;
	quantam = document.getElementById('quantam').value;
	message = document.getElementById('message').innerHTML;
	if(!capchar_check())
		return false;
	if( ten == '' || ten == 'Tên:' || email == '' || email=='Email:' || dienthoai == '' || dienthoai == 'Điện thoại:' || diachi == '' || diachi == 'Địa chỉ:')
	{
		alert('Các trường có dấu (*) là bắt buộc');
		return false;
	}
	return true;
	
}
function capchar_check()
{
	//alert("called");
	return true;
	var recaptcha_response_field = document.getElementById('recaptcha_response_field').value;
	var recaptcha_challenge_field = document.getElementById('recaptcha_challenge_field').innerHTML;
	$.post(baseurl + 'user_route.php',{'act':'check-capchar','recaptcha_response_field':recaptcha_response_field,'recaptcha_challenge_field':recaptcha_challenge_field},function(reponse) {
			alert(reponse);
			if(reponse == 'ng')
			{
				document.getElementById('capchar_msg').innerHTML = 'Chứng thực capchar bị sai ! Vui lòng nhập lại ';
				return false;
			}
			else
			{
				document.getElementById('capchar_msg').innerHTML = '';
				return true;
			}
		});
}
var h,m,s;
function clock_generate()
{
	$.post(baseurl + 'user_route.php',{'act':'thoi-gian'},function(reponse) {
			document.getElementById('timeserver').innerHTML = reponse;
			setInterval("clock_generate()",60000);
		}); 
}
function show_intro(catid)
{
	//alert(catid);
	$.post(baseurl + 'user_route.php',{'act':'thong-tin-danh-muc','cat_id':catid},function(reponse) {
			//alert(reponse);
			document.getElementById('maininfo').innerHTML = reponse;
			document.getElementById('maintitle').innerHTML = "Giới thiệu";
		}); 
}
