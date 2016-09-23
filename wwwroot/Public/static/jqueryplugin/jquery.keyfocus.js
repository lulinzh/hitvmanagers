/*
 jquery.keyfocus.js  二维矩阵焦点，为了云电视上方向键操作焦点而开发的
 Version 1.0.1
 @requires jQuery 1.2.6
 
 Copyright (c) 2016 gxbrightstar.com
 2016/3/31 9:55:48  create by heqh
 
Example:
<script> 
		$.keyfocus({
				objArray: [],		//矩阵
				curFocus: {x:0,y:0},		//默认焦点索引
				callBackUp:null,
				callBackRight:null,
				callBackDown:null,
				callBackLeft:null,
				isLateral:true					//横向移动
	    });
</script>
*/

(function($) {
	$.keyfocus = function(params){
	    var p = jQuery.extend({},{
			objArray:[],					//二维矩阵
			curFocus:{x:0,y:0},		//默认焦点索引
			callBackUp:null,
			callBackRight:null,
			callBackDown:null,
			callBackLeft:null,
			isLateral:true				// true 矩阵是横向      false 矩阵是竖向
	    },params);
	    if(p.objArray.length < 1){
	    	return;
	    }
	    
	    //设置默认焦点
	    if(p.isLateral){	//横向
	    	$(p.objArray[p.curFocus.y][p.curFocus.x]).focus();
	    }else { //竖向
	    	alert('暂不支持竖向的矩阵');
	    }
	    
    	$(document).keydown(function(event){
    		if(p.isLateral){	//横向
    			var isMove=false;
					switch (event.keyCode) {
						case 37://left
							if(p.curFocus.x>0){
								p.curFocus.x--;
								isMove=true;
							}else{
								if(p.callBackLeft){
									p.callBackLeft();
								}else if(p.curFocus.y>0){
									p.curFocus.y--;
									p.curFocus.x = p.objArray[p.curFocus.y].length-1;
									isMove=true;
								}
							}
							break;
						case 38://up
							if(p.curFocus.y>0){
								p.curFocus.y--;
								if(p.curFocus.x > p.objArray[p.curFocus.y].length-1){
									p.curFocus.x = p.objArray[p.curFocus.y].length-1;
								}
								isMove=true;
							}else{
								if(p.callBackUp){
									p.callBackUp();
								}
							}
							break;
						case 39://right
							if(p.curFocus.x < p.objArray[p.curFocus.y].length-1){
								p.curFocus.x++;
								isMove=true;
							}else{
								if(p.callBackRight){
									p.callBackRight();
								}else if(p.curFocus.y < p.objArray.length-1){
									p.curFocus.y++;
									p.curFocus.x=0;
									isMove=true;
								}
							}
							break;
						case 40://down
							if(p.curFocus.y < p.objArray.length-1){
								p.curFocus.y++;
								if(p.curFocus.x > p.objArray[p.curFocus.y].length-1){
									p.curFocus.x = p.objArray[p.curFocus.y].length-1;
								}
								isMove=true;
							}else{
								if(p.callBackDown){
									p.callBackDown();
								}
							}
							break;
					}
					if(isMove){
						$(p.objArray[p.curFocus.y][p.curFocus.x]).focus();
					}
    		}else{//竖向
    			alert('暂不支持竖向的矩阵');
    		}

    	});
	};
})(jQuery);