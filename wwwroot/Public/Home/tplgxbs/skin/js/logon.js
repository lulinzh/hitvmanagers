var UAC = {
	iframeReady : false,
	readyCount : 0,
	_charset : "",
	_type : "",
	_callback : "",
	

	//注册或登录后的回调方法
	showUserInfo : function(data){
		$.get("/index.php/Home/Passport/header/t/"+Math.random(),function(data){ $('#nav .passport').remove(); $('#nav').append(data);});
	},
	//打开登录type=0   注册窗口type=1
	openUAC : function(type, callback, purl){
		if(typeof(callback) != "undefined" && callback != null){
			UAC._callback = callback;
		}
		UAC.closeUAC();
		var divWidth=640;
		var divHeight= (type==1) ? 405 : 405;
		var srcurl = "/index.php/Home/Passport/index/act/"+type; // (type==1) ? "/index.php/Home/Passport/regPhone" : "/index.php/Home/Passport/index/act/"+type;
		if(purl!=null && typeof(purl) != "undefined"){
			srcurl = purl;
		}
		
		var node = "<div id=\"uac_div\" style=\"display:none; width:"+divWidth+"px; height:"+divHeight+"px; position:absolute; z-index:100000; background: url(/Public/Home/tplgxbs/skin/images/loading.gif) no-repeat center rgba(90,90,90,0); overflow: hidden; border:1px solid #626262; border-radius:4px; \">";
		node += "<iframe id=\"uac_frame\" src=\""+srcurl+"\" scrolling=\"no\" frameborder=\"0\" width=\"100%\" height=\""+divHeight+"\" allowtransparency=\"true\"/>";
		node += "<span id=\"uac_iframe_close\" onmouseover=\"UAC.closeOver(this,true);\" onmouseout=\"UAC.closeOver(this,false);\" onclick=\"UAC.closeUAC();\" "+
						"style=\"display:block; width:30px;height:30px; position: absolute; z-index: 100001; top: 2px; left: "+(divWidth-32)+"px; cursor: pointer; background: url(/Public/Home/tplgxbs/skin/images/usercenter/btn-bobo.png)	no-repeat -8px -1452px;\"></span>";
		node += "</div>";
		jQuery("body").append(node);
		var tops = (document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.getElementsByTagName("body").item(0).scrollTop;
		tops += ((type==1) ? 160 : 160);
		$("#uac_div").css("top",tops+"px");
		$("#uac_div").css("left",parseInt((parseInt($("body").width()) - parseInt(jQuery("#uac_div").width()))/2-20)+"px");
		UAC.showUAC();
	},
	showUAC : function(){
		$("#uac_div").css("display","block");
	},
	closeUAC : function(){
		if(jQuery("#uac_div").length > 0){
			jQuery("#uac_div").remove();
			UAC.iframeReady = false;
		}
	},
	//打开登录type=0   注册窗口type=1
	openWxQrcode : function(purl, bodyhtml, ptitle){
		UAC.closeUAC();
		var divWidth=320;
		var divHeight=360;
		var srcurl = "/index.php/Home/Passport/wxQrcode/t/"+Math.random();
		var wintitle = "请使用微信扫一扫";
		if(purl!=null && typeof(purl) != "undefined"){
			srcurl = purl;
		}
		if(ptitle!=null && typeof(ptitle) != "undefined"){
			wintitle = ptitle;
		}
		var node = "<div id=\"uac_div\" style=\"display:none; width:"+divWidth+"px; height:"+divHeight+"px; position:absolute; z-index:100000; background: url(/Public/Home/tplgxbs/skin/images/loading.gif) no-repeat center #fc7d18; overflow: hidden; border:1px solid #626262; border-radius:4px; \">";
		node += "<div style=\"height:30px;font-size:14px;font-weight:bold;padding-left:20px;padding:2px 6px 0px 0px;text-align:center;background:rgba(232,105,4,0.8);\">" +wintitle+ "</div>";
		if(bodyhtml!=null && typeof(bodyhtml) != "undefined"){
			node += bodyhtml;
		}else{
			node += "<iframe id=\"wxqrcode_frame\" src=\""+srcurl+"\" scrolling=\"no\" frameborder=\"0\" width=\"100%\" height=\""+divHeight+"\" allowtransparency=\"true\"/>";
		}
		node += "<span id=\"wxqrcode_iframe_close\" onmouseover=\"UAC.closeOver(this,true);\" onmouseout=\"UAC.closeOver(this,false);\" onclick=\"UAC.closeUAC();\" "+
						"style=\"display:block; width:30px;height:30px; position: absolute; z-index: 100001; top: 0px; left: "+(divWidth-30)+"px; cursor: pointer; background: url(/Public/Home/tplgxbs/skin/images/usercenter/btn-bobo.png)	no-repeat -8px -1452px;\"></span>";
		node += "</div>";
		jQuery("body").append(node);
		var tops = (document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.getElementsByTagName("body").item(0).scrollTop;
		tops += 150;
		$("#uac_div").css("top",tops+"px");
		$("#uac_div").css("left",parseInt((parseInt($("body").width()) - parseInt(jQuery("#uac_div").width()))/2-20)+"px");

		UAC.showUAC();
	},
	openQQ : function(){
		UAC.closeUAC();
		var divWidth=720;
		var divHeight= 405;
		var srcurl = '/index.php/Home/Passport/qqlogin';
		var node = "<div id=\"uac_div\" style=\"display:none; width:"+divWidth+"px; height:"+divHeight+"px; position:absolute; z-index:100000; background: url(/Public/Home/tplgxbs/skin/images/loading.gif) no-repeat center #ccc; overflow: hidden; border:1px solid #626262; border-radius:4px; \">";
		node += "<iframe id=\"uac_frame\" src=\""+srcurl+"\" scrolling=\"no\" frameborder=\"0\" width=\"100%\" height=\""+divHeight+"\" allowtransparency=\"true\"/>";
		node += "<span id=\"uac_iframe_close\" onmouseover=\"UAC.closeOverMin(this,true);\" onmouseout=\"UAC.closeOverMin(this,false);\" onclick=\"UAC.closeUAC();\" "+
						"style=\"display:block; width:20px;height:20px; position: absolute; z-index: 100001; top: 2px; left: "+(divWidth-22)+"px; cursor: pointer; background: url(/Public/Home/tplgxbs/skin/images/usercenter/btn-bobo.png)	no-repeat 5px -488px;\"></span>";
		node += "</div>";
		var tops = (document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.getElementsByTagName("body").item(0).scrollTop;
		tops += 160;
		jQuery("body").append(node);
		$("#uac_div").css("top",tops+"px");
		$("#uac_div").css("left",parseInt((parseInt($("body").width()) - parseInt(jQuery("#uac_div").width()))/2-20)+"px");
		UAC.showUAC();
	},
	openWeibo : function(){
		alert('该功能尚未启用，请耐心等待');
	},
	
	closeOver : function(obj,over){
		if(over)
			$(obj).css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/btn-bobo.png)	no-repeat -8px -1500px");
		else
			$(obj).css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/btn-bobo.png)	no-repeat -8px -1452px");
	},
	closeOverMin : function(obj,over){
		if(over)
			$(obj).css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/btn-bobo.png)	no-repeat 5px -468px");
		else
			$(obj).css("background","url(/Public/Home/tplgxbs/skin/images/usercenter/btn-bobo.png)	no-repeat 5px -488px");
	},
	logout : function(returnUrl){
			$("#btnLogout").html("<img src=\"/Public/Home/tplgxbs/skin/images/loading.gif\" />");
      $.ajax({
          url:'/index.php/Home/Passport/logout/',
          data:"t/"+Math.random(),
          type:'get',
          dataType:'json',
          success: function(data){
          	if(returnUrl.indexOf('/User')>=0){
	          	window.location.href = "/";;
          	}else{
	          	window.location.href = returnUrl;;
          	}
          },
          error: function(){
          	$("#btnLogout").html("退出");
          }
      });
	}
};
