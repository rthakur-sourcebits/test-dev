var AUTHURL = '';
// CSS Browser selector
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1;},g='gecko',w='webkit',s='safari',o='opera',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?'mobile':is('iphone')?'iphone':is('ipod')?'ipod':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win':is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js'];c=b.join(' ');h.className+=' '+c;return c;};css_browser_selector(navigator.userAgent);

function submit_admin_form(){	
	if($('#device_name_field').val()=='') {
		$('.act-error').show();
		$('#activation_device').css('background','#FEFCF1');
		$('#device_name_field').css('cssText','background:#FEFCF1 !important');
		$('.accounts-setup-activity').css('background','#FBF5D1');
	} else {
		$('#link_to_dropbox').hide();
		$('#do_nothing_button').show();
		$('.dropbox_connect').show();
		device_name	=	$('#device_name_field').val();
		activate_id	=	$('#activate_id').val();
		/*$.ajax({
			type: 'POST',
			url	: '/admin/activateuser',
			data: "device_name="+device_name+"&activate_id="+activate_id,
			dataType: 'json',
			success: function(r){
						if(r[0]['success'] == "1") {
							window.open (r[0]['url'], "Dropbox Authentication");
						} else {
							
						}
					 }
			 });*/
		//$("#company_create").submit();
		window.open ('/admin/activateuser/'+device_name+'/'+activate_id);
	}
}

function update_dropbox()
{
	if($('#edit_device_name_field').val()==''){
			$('.act-error').show();
			$('#edit_device').css('background','#FEFCF1');
			$('#edit_device_name_field').css('cssText','background:#FEFCF1 !important');
			$('.accounts-setup-activity').css('background','#FBF5D1');
	   }
	   else{
		   $('.acc-upgrade-btn').hide();
		   $('#do_nothing_button').show();
		   $('.dropbox_connect').show();
		   device_name	=	$('#edit_device_name_field').val();
			
		   //activate_id	=	$('#activate_id').val();
			/*$.ajax({
				type: 'POST',
				url	: '/admin/setup',
				data: "device_name="+device_name,
				dataType: 'json',
				success: function(r){
							if(r[0]['success'] == "1") {
								window.open (r[0]['url'], "Dropbox Authentication");
							} else {
								
							}
						 }
			});*/
		   window.open ('/admin/setup/'+device_name+'/1');
	   	}
	}

function adjust_scroll_links()
{
	//id = document.getElementById();
	if($(".jScrollPaneTrack").width() == null || $(".jScrollPaneTrack").width() == undefined)
	{
		$(".employee-list").css("width","250px");
	}
}


function submit_dropbox()
{
	if($("#key").val() == "")
	{
		alert("Enter consumer key.");
		return false;
	}
	if($("#secret").val() == "")
	{
		alert("Enter consumer secret.");
		return false;
	}
	return true;
}

function validate_register()
{
	if($("#company_name").val() == "")
	{
		alert("Please enter company name.");
		$("#company_name").focus();
		return false;
	}
	if($("#serialnumber").val() == "")
	{
		alert("Please enter serial number.");
		$("#serialnumber").focus();
		return false;
	}
	if($("#UserEmail").val() == "")
	{
		alert("Please enter email.");
		$("#UserEmail").focus();
		return false;
	}
	if($("#pass").val() == "")
	{
		alert("Please enter password.");
		$("#pass").focus();
		return false;
	}
	if($("#confirmpass").val() == "")
	{
		alert("Please enter confirm password.");
		$("#confirmpass").focus();
		return false;
	}
	if($("#pass").val() != $("#confirmpass").val())
	{
		alert("Passwords does not match.");
		$("#confirmpass").focus();
		return false;
	}
	if(($("#pass").val()).length<6)
	{
		alert("Password should contain atleast 6 characters.");
		$("#confirmpass").focus();
		return false;
	}
	if($("#name").val() == "")
	{
		alert("Please enter billing user name.");
		$("#name").focus();
		return false;
	}
	if($("#cname").val() == "")
	{
		alert("Please enter billing company name.");
		$("#cname").focus();
		return false;
	}
	if($("#address").val() == "")
	{
		alert("Please enter billing address.");
		$("#address").focus();
		return false;
	}
	if($("#city").val() == "")
	{
		alert("Please enter city.");
		$("#city").focus();
		return false;
	}
	if($("#state").val() == "")
	{
		alert("Please enter state.");
		$("#state").focus();
		return false;
	}
	if($("#country").val() == "" || $("#country").val() == 0)
	{
		alert("Please enter country.");
		$("#country").focus();
		return false;
	}
	if($("#zipcode").val() == "")
	{
		alert("Please enter zipcode.");
		$("#zipcode").focus();
		return false;
	}
	if($("#phone").val() == "")
	{
		alert("Please enter phone number.");
		$("#phone").focus();
		return false;
	}
	if($("#terms:checked").length == 0)
	{
		alert("Please agree to terms and conditions.");
		$("#terms").focus();
		return false;
	}
	return true;
}


function dropboxAuthHanlder(status,post_data) {
	if(typeof post_data == undefined) {
		post_data =  new Array();
	}
	if(status	==	'success'){
		$('#link_to_dropbox').hide();
		$('#do_nothing_button').show();
		$('.dropbox_connect').show();
		window.location		=	'/login/';
	} else if(status 		==	'not_approved'){
		$('#link_to_dropbox').show();
		$('#do_nothing_button').hide();
		$('.dropbox_connect').hide();
		activate_id			=	post_data['log_user_activation_id'];
		window.location		=	'/admin/activation/'+activate_id+'/'+'9'+'/';
	} else if(status		==	'device_not_found'){
		$('#link_to_dropbox').show();
		$('#do_nothing_button').hide();
		$('.dropbox_connect').hide();
		activate_id			=	post_data['log_user_activation_id'];
		window.location		=	'/admin/activation/'+activate_id+'/'+'8'+'/';
	} else {
		$('#link_to_dropbox').show();
		$('#do_nothing_button').hide();
		$('.dropbox_connect').hide();
		window.location		=	'/login/';
	}
}

function editDropboxAuthHandler(status,post_data) {
	if(typeof post_data == undefined) {
		post_data =  new Array();
	}
	if(status	==	'success'){
		$.ajax({
			type	: 'POST',
			url		: '/admin/delete_data_on_device_change',
			async	: false,
			success: function(r){
				if(r == "1") {
					$('.acc-upgrade-btn').hide();
					$('#do_nothing_button').show();
					$('.dropbox_connect').show();
					window.location		=	'/login/';
				} else {
					$('.acc-upgrade-btn').show();
					$('#do_nothing_button').hide();	
				}
			}
		});
	} else if(status 		==	'not_approved'){
		$('.acc-upgrade-btn').show();
		$('#do_nothing_button').hide();
		$('.dropbox_connect').hide();
		window.location		=	'/admin/sync/2';
	} else if(status		==	'device_not_found'){
		$('.acc-upgrade-btn').show();
		$('#do_nothing_button').hide();
		$('.dropbox_connect').hide();
		window.location		=	'/admin/sync/3';
	} else {
		$('.acc-upgrade-btn').show();
		$('#do_nothing_button').hide();
		$('.dropbox_connect').hide();
		window.location		=	'/login/';
	}
}
