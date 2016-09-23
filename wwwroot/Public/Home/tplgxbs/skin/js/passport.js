// 用户登录、注册窗口控制
var Passport = {
	
	init: function(){
		if(parent.UAC != null && "undefined" != typeof(parent.UAC) ){
			parent.UAC.iframeReady = true;
		}
		
		if($("#item_logincode").css("display") != "none"){
			Passport.chgCheckCode("login_imgCode");
		}
	},
	//更好验证码
	chgCheckCode: function(imgid){
		$("#"+imgid).attr('src', '/index.php/Home/Passport/verify/t/'+Math.random());
	},
	//显示登录验证码
	uiShowItemCheckCode: function(){
		Passport.chgCheckCode("login_imgCode");
		$("#item_logincode_none").hide();
		$("#item_logincode").show();
	},

	checkNotEmpty: function(inputid, inputname){
		var val = $("#"+inputid).val();
		var val = $.trim(val);
		$("#"+inputid).val(val);
		if(val == ""){
			$("#"+inputid+"Tip").html("请输入"+inputname);
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		$("#"+inputid+"Tip").html("&nbsp;");
		$("#"+inputid+"Tip").attr("class", "onCorrect");
		return true;
	},	
	
	checkUserName: function(inputid){
		var val = $("#"+inputid).val();
		var val = $.trim(val);
		$("#"+inputid).val(val);
		if(val == ""){
			$("#"+inputid+"Tip").html("请输入帐号");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		if(val.length < 3){
			$("#"+inputid+"Tip").html("帐号应为3-32位之间");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		if(val.length > 32){
			$("#"+inputid+"Tip").html("帐号应为3-32位之间");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		$("#"+inputid+"Tip").html("&nbsp;");
		$("#"+inputid+"Tip").attr("class", "onCorrect");
		return true;
	},
	checkPassword: function(inputid, compareid){
		var val = $("#"+inputid).val();
		if(val == ""){
			$("#"+inputid+"Tip").html("请输入密码");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		if(val.length < 6 || val.length > 20){
			$("#"+inputid+"Tip").html("密码应为6-20位之间");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		if(compareid != null && "undefined" != typeof(compareid) ){
			if(val != $("#"+compareid).val()){
				$("#"+inputid+"Tip").html("重复密码不一致");
				$("#"+inputid+"Tip").attr("class", "onError");
				return false;
			}
		}
		
		$("#"+inputid+"Tip").html("&nbsp;");
		$("#"+inputid+"Tip").attr("class", "onCorrect");
		return true;
	},
	checkValidateCode: function(inputid){
		var val = $("#"+inputid).val();
		var val = $.trim(val);
		$("#"+inputid).val(val);
		if(val == ""){
			$("#"+inputid+"Tip").html("请输入验证码");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		if(val.length !=4){
			$("#"+inputid+"Tip").html("应为4位整数");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		var reg=/^\d{4}$/;
		if(!reg.test(val)){    
			$("#"+inputid+"Tip").html("请输入4位整数");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
    }
		$("#"+inputid+"Tip").html("&nbsp;");
		$("#"+inputid+"Tip").attr("class", "onCorrect");
		return true;
	},
	
	login: function(){
		var checkUserName = Passport.checkUserName('login_userName');
		var checkPassword = Passport.checkPassword('login_password');
		var checkValidateCode = ($("#item_logincode").css("display") == "none") ? true : Passport.checkValidateCode('login_validateCode');
		if(!checkUserName){
			$("#login_userName").focus();
			return false;
		}
		if(!checkPassword){
			$("#login_password").focus();
			return false;
		}
		if(!checkValidateCode){
			$("#login_validateCode").focus();
			return false;
		}

		var param = {
			userName : $("#login_userName").val(),
			password : $("#login_password").val(),
			autoLogin : ($("#login_autoLogin").attr("checked")=="checked") ? 1 : 0,
			validateCode:$("#login_validateCode").val(),
			t:Math.random()
		};
		
		$("#login_btn").css("background","url(/Public/Home/tplgxbs/skin/images/loading.gif) no-repeat center");  //改变登录按钮样式
		$.ajax({
			url : '/index.php/Home/Passport/dologin/',
			data : param,
			dataType:'json',
			success : function(data){
				if(data!=null&&data!=""){
					var obj = data;
					if(obj.code == "000000"){
						$("#login_info").text("登录成功，正在刷新网页");
						if(parent.UAC != null && "undefined" != typeof(parent.UAC) ){
							parent.UAC.showUserInfo();
							parent.UAC.closeUAC();
						}else{
							top.location.href="/";
						}
					}else if(obj.code == '001000'){
						$("#login_info").text(obj.info == null ? "登录失败，未知错误" : obj.info);
						if($("#item_logincode").css("display") != "none"){	Passport.chgCheckCode("login_imgCode");	}
					}else if(obj.code == '001001'){
						$("#login_info").text("验证码错误");
					}else if(obj.code == '001002'){
						$("#login_info").text("您需要输入验证码！");
						Passport.uiShowItemCheckCode();
					}else if(obj.code == "001004"){
						$("#login_info").text("用户名或密码错误");
						if($("#item_logincode").css("display") != "none"){	Passport.chgCheckCode("login_imgCode");	}
					}else if(obj.code == "001009"){
						$("#login_info").text("你的帐户未审核");
						if($("#item_logincode").css("display") != "none"){	Passport.chgCheckCode("login_imgCode");	}
					}else{
						$("#login_info").text("登录失败，未知错误，请联系客服！");
						if($("#item_logincode").css("display") != "none"){	Passport.chgCheckCode("login_imgCode");	}
					}
					$("#login_btn").css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/rep.png) 0 -80px repeat-x");  //改变登录按钮样式
				}
			}
		});


		return false;
	},
	//微信注册
	regWx: function(){
		var checkUserName = Passport.checkUserName('reg_userName');
		var checkPassword = Passport.checkPassword('reg_password');
		var checkNickname = Passport.checkNotEmpty('reg_nickname','昵称');
		if(!checkUserName){
			$("#reg_userName").focus();
			return false;
		}
		if(!checkPassword){
			$("#reg_password").focus();
			return false;
		}
		if(!checkNickname){
			$("#reg_nickname").focus();
			return false;
		}

		var param = {
			scene : $("#reg_scene").val(),
			ticket : $("#reg_ticket").val(),
			openid : $("#reg_openid").val(),
			userName : $("#reg_userName").val(),
			password : $("#reg_password").val(),
			nickname:$("#reg_nickname").val()
		};
		
		
		$("#reg_btn").css("background","url(/Public/Home/tplgxbs/skin/images/loading.gif) no-repeat center");  //改变登录按钮样式
		$("#reg_btn").attr("onclick", "void();");
		$.ajax({
			url : '/index.php/Home/Passport/doregWx/',
			data : param,
			dataType:'json',
			success : function(data){
				if(data!=null&&data!=""){
					var obj = data;
					if(obj.code == "000000"){
						$("#reg_info").text("注册成功，正在刷新网页");
						if(parent.UAC != null && "undefined" != typeof(parent.UAC) ){
							parent.UAC.showUserInfo();
							parent.UAC.closeUAC();
						}else{
							top.location.href="/";
						}
					}else if(obj.code == '001000'){
						$("#reg_info").text(obj.info == null ? "注册失败，未知错误" : obj.info);
					//}else if(obj.code == '001001'){
					//	$("#reg_info").text("昵称格式错误");
					}else{
						$("#reg_info").text(obj.info == null ? "注册失败，未知错误，请联系客服！" : obj.info);
					}
					$("#reg_btn").css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/rep.png) 0 -80px repeat-x");  //改变登录按钮样式
					$("#reg_btn").attr("onclick", "Passport.regWx();");
				}
			}
		});
	},
	//
	regQQ: function(){
		var checkUserName = Passport.checkUserName('reg_userName');
		var checkPassword = Passport.checkPassword('reg_password');
		var checkNickname = Passport.checkNotEmpty('reg_nickname','昵称');
		if(!checkUserName){
			$("#reg_userName").focus();
			return false;
		}
		if(!checkPassword){
			$("#reg_password").focus();
			return false;
		}
		if(!checkNickname){
			$("#reg_nickname").focus();
			return false;
		}

		var param = {
			token : $("#reg_token").val(),
			openid : $("#reg_openid").val(),
			userName : $("#reg_userName").val(),
			password : $("#reg_password").val(),
			nickname:$("#reg_nickname").val()
		};
		
		$("#reg_btn").css("background","url(/Public/Home/tplgxbs/skin/images/loading.gif) no-repeat center");  //改变登录按钮样式
		$("#reg_btn").attr("onclick", "void();");
		$.ajax({
			url : '/index.php/Home/Passport/doregQQ/',
			data : param,
			dataType:'json',
			success : function(data){
				if(data!=null&&data!=""){
					var obj = data;
					if(obj.code == "000000"){
						$("#reg_info").text("注册成功，正在刷新网页");
						if(parent.UAC != null && "undefined" != typeof(parent.UAC) ){
							parent.UAC.showUserInfo();
							parent.UAC.closeUAC();
						}else{
							top.location.href="/";
						}
					}else if(obj.code == '001000'){
						$("#reg_info").text(obj.info == null ? "注册失败，未知错误" : obj.info);
					//}else if(obj.code == '001001'){
					//	$("#reg_info").text("昵称格式错误");
					}else{
						$("#reg_info").text(obj.info == null ? "注册失败，未知错误，请联系客服！" : obj.info);
					}
					$("#reg_btn").css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/rep.png) 0 -80px repeat-x");  //改变登录按钮样式
					$("#reg_btn").attr("onclick", "Passport.regWx();");
				}
			}
		});
	},
	
	checkEmail: function(inputid){
		var val = $("#"+inputid).val();
		var val = $.trim(val);
		$("#"+inputid).val(val);
		if(val == ""){
			$("#"+inputid+"Tip").html("请输入电子邮箱");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		//var reg=/^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$/;
		var reg=/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
		if(!reg.test(val)){    
			$("#"+inputid+"Tip").html("邮箱格式不正确");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
    }
		$("#"+inputid+"Tip").html("&nbsp;");
		$("#"+inputid+"Tip").attr("class", "onCorrect");
		return true;
	},
	checkPhone: function(inputid){
		var val = $("#"+inputid).val();
		var val = $.trim(val);
		$("#"+inputid).val(val);
		if(val == ""){
			$("#"+inputid+"Tip").html("请输入手机号码");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		if(val.length !=11){
			$("#"+inputid+"Tip").html("手机号码应为11位");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		var reg=/^[1][3458][0-9]{9}$/;
		if(!reg.test(val)){
			$("#"+inputid+"Tip").html("手机号码格式不正确");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
    }
		$("#"+inputid+"Tip").html("&nbsp;");
		$("#"+inputid+"Tip").attr("class", "onCorrect");
		return true;
		
	},
	checkPhoneCode: function(inputid){
		var val = $("#"+inputid).val();
		var val = $.trim(val);
		$("#"+inputid).val(val);
		if(val == ""){
			$("#"+inputid+"Tip").html("请输入手机验证码");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		if(val.length !=6){
			$("#"+inputid+"Tip").html("验证码应为6位整数");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
		}
		var reg=/^\d{6}$/;
		if(!reg.test(val)){    
			$("#"+inputid+"Tip").html("请输入6位整数");
			$("#"+inputid+"Tip").attr("class", "onError");
			return false;
    }
		$("#"+inputid+"Tip").html("&nbsp;");
		$("#"+inputid+"Tip").attr("class", "onCorrect");
		return true;
	},
		
	//默认注册
	reg: function(){
		var checkEmail = Passport.checkEmail('reg_email');
		var checkPassword = Passport.checkPassword('reg_password');
		var checkPassword2 = Passport.checkPassword('reg_password2', 'reg_password');
		var checkValidateCode = Passport.checkValidateCode('reg_validateCode');

		if(!checkEmail){	$("#reg_email").focus();	return false;	}
		if(!checkPassword){	$("#reg_password").focus();	return false;	}
		if(!checkPassword2){	$("#reg_password2").focus();	return false;	}
		if(!checkValidateCode){		$("#reg_validateCode").focus();	return false;	}

		var param = {
			email : $("#reg_email").val(),
			password : $("#reg_password").val(),
			password2 : $("#reg_password2").val(),
			validateCode:$("#reg_validateCode").val()
		};
		
		$("#reg_btn").css("background","url(/Public/Home/tplgxbs/skin/images/loading.gif) no-repeat center");  //改变登录按钮样式
		$("#reg_btn").attr("onclick", "void();");
		$.ajax({
			url : '/index.php/Home/Passport/doreg/',
			data : param,
			dataType:'json',
			success : function(data){
				if(data!=null&&data!=""){
					var obj = data;
					if(obj.code == "000000"){
						$("#reg_info").text("注册成功，正在刷新网页");
						if(parent.UAC != null && "undefined" != typeof(parent.UAC) ){
							parent.UAC.showUserInfo();
							parent.UAC.closeUAC();
						}else{
							top.location.href="/";
						}
					}else if(obj.code == '001000'){
						$("#reg_info").text(obj.info == null ? "注册失败，未知错误" : obj.info);
						Passport.chgCheckCode("reg_imgCode");
					}else if(obj.code == '001001'){
						$("#reg_info").text("验证码错误");
					}else{
						$("#reg_info").text(obj.info == null ? "注册失败，未知错误，请联系客服！" : obj.info);
						Passport.chgCheckCode("reg_imgCode");
					}
					$("#reg_btn").css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/rep.png) 0 -80px repeat-x");  //改变登录按钮样式
					$("#reg_btn").attr("onclick", "Passport.reg();");
				}
			}
		});
	},

	vSendPhoneVcode:{obj: null, interval: 0, time:0},		//用于记录发送手机验证码按钮倒计时
	vRegPhoneSts:0,  //注册手机号码状态   0为发送短信验证码   1已经发送短信验证码     2注册提交中ing
  timerPhoneVcodeResend : function(){
    	Passport.vSendPhoneVcode.time--;
    	$(Passport.vSendPhoneVcode.obj).html("重新发送("+Passport.vSendPhoneVcode.time+")");
    	if(Passport.vSendPhoneVcode.time<=0){
    		clearInterval(Passport.vSendPhoneVcode.interval);
	    	$(Passport.vSendPhoneVcode.obj).css('color','#fff'); 
	    	$(Passport.vSendPhoneVcode.obj).html("发送验证码");
	    	Passport.chgCheckCode("reg_imgCode");
	    	$("#reg_validateCode").removeAttr("disabled");
	    	$("#reg_validateCode").focus();
    	}
	},    
	sendPhoneCode: function(thisobj){
		if(Passport.vSendPhoneVcode.time > 0){	//正在发送
			return;
		}
		var checkPhone = Passport.checkPhone('reg_phone');
		var checkValidateCode = Passport.checkValidateCode('reg_validateCode');

		if(!checkPhone){	$("#reg_phone").focus();	return false;	}
		if(!checkValidateCode){		$("#reg_validateCode").focus();	return false;	}
		
		var param = {
			phone : $("#reg_phone").val(),
			validateCode:$("#reg_validateCode").val()
		};
		
		Passport.vSendPhoneVcode.time=60;	//正在发送
		Passport.vSendPhoneVcode.obj = thisobj;
		$(Passport.vSendPhoneVcode.obj).html('正在发送...');

		$.ajax({
			url : '/index.php/Home/Passport/doregPhoneSendCode/',
			data : param,
			dataType:'json',
			success : function(data){
				if(data!=null&&data!=""){
					var obj = data;
					if(obj.code == 0){
						$("#reg_phoneCodeTip").text(obj.info == null ? "发送短信成功" : obj.info);
						$("#reg_phoneCodeTip").attr("class", "onCorrect");
						$("#reg_phone").attr("disabled", "disabled");
						$("#reg_validateCode").attr("disabled", "disabled");
						
						Passport.vRegPhoneSts=1;
						$(Passport.vSendPhoneVcode.obj).html("重新发送("+Passport.vSendPhoneVcode.time+")");
						$(Passport.vSendPhoneVcode.obj).css('color','#ccc'); 
						Passport.vSendPhoneVcode.interval=setInterval("Passport.timerPhoneVcodeResend()",1000);
						$("#reg_btn").attr("onclick", "Passport.regPhone();");
						$("#reg_btn").css("color", "#fff");
				    	
					}else if(obj.code == 1){
						$("#reg_phoneTip").text(obj.info == null ? "此电话号码已被注册" : obj.info);
						$("#reg_phoneTip").attr("class", "onError");						
						Passport.vSendPhoneVcode.time=0;	//可以重新发送
						$(Passport.vSendPhoneVcode.obj).html("发送验证码");
						Passport.chgCheckCode("reg_imgCode");
					}else if(obj.code == 2){
						$("#reg_validateCodeTip").text(obj.info == null ? "图像验证码错误" : obj.info);
						$("#reg_validateCodeTip").attr("class", "onError");												
						Passport.vSendPhoneVcode.time=0;	//可以重新发送
						$(Passport.vSendPhoneVcode.obj).html("发送验证码");
					}else{
						$("#reg_phoneCodeTip").text(obj.info == null ? "发送失败，请联系客服！" : obj.info);
						$("#reg_phoneCodeTip").attr("class", "onError");
						Passport.vSendPhoneVcode.time=0;	//可以重新发送
						$(Passport.vSendPhoneVcode.obj).html("发送验证码");
						Passport.chgCheckCode("reg_imgCode");
					}
				}
			}
		});
		
		
	},
		
	//默认注册
	regPhone: function(){
		if(Passport.vRegPhoneSts==2){
			return;
		}
		var checkPhone = Passport.checkPhone('reg_phone');
		var checkValidateCode = Passport.checkValidateCode('reg_validateCode');

		if(!checkPhone){	$("#reg_phone").focus();	return false;	}
		if(!checkValidateCode){		$("#reg_validateCode").focus();	return false;	}

		if(Passport.vRegPhoneSts==0){
			$("#reg_info").text("请先点击【发送验证码】发送验证短信");
			return;
		}

		var checkPhoneCode = Passport.checkPhoneCode('reg_phoneCode');
		var checkPassword = Passport.checkPassword('reg_password');

		if(!checkPhoneCode){	$("#reg_phoneCode").focus();	return false;	}
		if(!checkPassword){	$("#reg_password").focus();	return false;	}
		
		var param = {
			phone : $("#reg_phone").val(),
			validateCode:$("#reg_validateCode").val(),
			phoneCode : $("#reg_phoneCode").val(),
			password : $("#reg_password").val()
		};
		
		Passport.vRegPhoneSts=2;
		$("#reg_btn").css("background","url(/Public/Home/tplgxbs/skin/images/loading.gif) no-repeat center");  //改变登录按钮样式
		$("#reg_btn").attr("onclick", "void();");
		
		$.ajax({
			url : '/index.php/Home/Passport/doregPhone/',
			data : param,
			dataType:'json',
			success : function(data){
				if(data!=null&&data!=""){
					var obj = data;
					if(obj.code == "000000"){
						$("#reg_info").text("注册成功，正在刷新网页");
						if(parent.UAC != null && "undefined" != typeof(parent.UAC) ){
							parent.UAC.showUserInfo();
							parent.UAC.closeUAC();
						}else{
							top.location.href="/";
						}
					}else if(obj.code == '001000'){
						$("#reg_info").text(obj.info == null ? "注册失败，未知错误" : obj.info);
					}else if(obj.code == '001001'){
						$("#reg_info").text("验证码错误");
					}else{
						$("#reg_info").text(obj.info == null ? "注册失败，未知错误，请联系客服！" : obj.info);
					}
					Passport.vRegPhoneSts=1;
					$("#reg_btn").css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/rep.png) 0 -80px repeat-x");  //改变登录按钮样式
					$("#reg_btn").attr("onclick", "Passport.regPhone();");
				}
			}
		});
	}
		
};
