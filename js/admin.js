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
$(document).ready(function(){
	$("#lgin-submit").click(function(){
		//alert('herre');
		var usr_mail = document.getElementById('lgin-email').value;
		var usr_pwd = document.getElementById('lgin-pwd').value;
		if(usr_mail == '' || usr_pwd == '' || check_input(usr_mail,'email') == false || check_input(usr_pwd,'password') == false )
		{
			alert('Dữ liệu nhập không đúng');
			return false;
		}
		
		$.post(baseurl + 'admin_route.php',{'act':'dang-nhap','usr_mail':usr_mail,'usr_pwd':pwdenc(usr_pwd)},function(reponse) {
			alert(reponse);
			gotopage(currenturl);
		});
		
	});
	$("#lgout-submit").click(function(){
		$.post(baseurl + 'admin_route.php',{'act':'dang-xuat','usr_mail':'','usr_pwd':''},function(reponse) {
			alert(reponse);
			gotopage(currenturl);
		});
		
	});
	$(".activestatus").change(function(){
		value = this.value;
		if(this.checked == true)
			state = 1;
		else
			state = 0;
			
		//alert(value + " AND " + state);
		$.post('route.php',{'act':'changestatus','maso_lg':value,'active':state},function(reponse) {
			//alert(reponse);
		 });
		});
	$("#siteoffline").change(function(){
		if(this.checked == true)
			sitestatus = "offline";
		else
			sitestatus = "online";
		$.post('route.php',{'act':'sitestatus','sitestatus':sitestatus},function(reponse) {
			history.go(0);
		 });
	
	});
	$("#listtype").change(function(){
		var type = this.value;
		var loading = document.getElementById('loadingimg');
		loading.style.visibility = "visible";
		$.post('admin_route.php',{'act':'liet-ke-ds','type':type,'offset':0 },function(reponse) {
			document.getElementById("danh-sach-hoa-don").innerHTML = reponse;
			var loading = document.getElementById('loadingimg');
			loading.style.visibility = "hidden";
		 });
	
	});
	$("activestatus").change(function(){
		value = this.value;
		if(this.checked == true)
			state = 1;
		else
			state = 0;
			
		//alert(value + " AND " + state);
		$.post('route.php',{'act':'changestatus','maso_lg':value,'active':state},function(reponse) {
			//alert(reponse);
		 });
		});
});
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
function BrowseServer(objid)
{
	var finder = new CKFinder();
	finder.selectActionData = objid;
	finder.basePath = '../';	// The path for the installation of CKFinder (default = "/ckfinder/").
	finder.selectActionFunction = SetFileField;
	finder.popup();
}
function SetFileField( fileUrl, data)
{
	var fullpath = fileUrl;
	//alert(fileUrl);
	var indexofroot = fullpath.indexOf("products/");	
	var filepath = fullpath.substr(indexofroot);
	var newobj = data['selectActionData'];
	document.getElementById( newobj ).value = filepath;
}
function toggle_slideshow(obj,id)
{
	
	if(obj.checked == true)
		stt = "slideshow";
	else
		stt = "";
	$.post('admin_route.php',{'act':'toggle_slideshow','p_id':id,'p_reverse':stt},function(reponse) {
		
	 });
}
function toggle_bestproduct(obj,id)
{
	
	if(obj.checked == true)
		stt = "hot";
	else
		stt = "";
	$.post('admin_route.php',{'act':'toggle_bestproduct','p_id':id,'p_reverse':stt},function(reponse) {
		
	 });
}
function next_page(obj)
{
	var loading = document.getElementById('loadingimg');
		loading.style.visibility = "visible";
	var type=document.getElementById('listtype').value;
	var offset = obj.innerHTML;
	//alert("called offset " + offset);
	$.post('admin_route.php',{'act':'liet-ke-ds','type':type,'offset':offset },function(reponse) {
			document.getElementById("danh-sach-hoa-don").innerHTML = reponse;
			var loading = document.getElementById('loadingimg');
			loading.style.visibility = "hidden";
		 });
	return false;
}
function change_order_status(val)
{
	var x = document.getElementById('btnsavestt');
	x.disabled = "disabled";
	var newstt = document.getElementById('order-status').value;
	$.post('admin_route.php',{'act':'thay-doi-stt','newstt':newstt,'odid':val },function(reponse) {
			var x = document.getElementById('btnsavestt');
			if(reponse == 0)
			{	
				x.disabled = "disabled";
				x.value= "Đã lưu tình trạng mới";
			}
			else
			{
				x.disabled = false;
				x.value= "Vui lòng nhấn lại";
			}
		 });
	return false;
}