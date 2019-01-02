var checkflag = "false";
function checkall(field) {
	if (checkflag == "false") {
		for (i = 0; i < field.length; i++) {
			field[i].checked = true;
		}
		checkflag = "true";
		return "Uncheck All"; 
	}
	else {
			for (i = 0; i < field.length; i++) {
				field[i].checked = false; 
			}
			checkflag = "false";
			return "Check All"; 
		}
}
function uncheckall(field) {	
	for (i = 0; i < field.length; i++) {
		field[i].checked = false; 
	}
	
	return "Check All"; 		
}
function getValueListCheckBox(list,id)
{

	if(list!=undefined){
	var num=list.length;
	//alert(num);	
	if (num==undefined)
		id.value=list.value;		
	else
		{
			count=0;
			str="";
			for (i=0;i<num;i++)
			{
				if (list[i].checked)
					{
						if (count==0)
							str=list[i].value;
						else
							str = str + "," + list[i].value;
						count++;
					}
			}
		id.value=str;
       }
	}
	//alert(id.value);
}

function getValueListCheckBoxspec(list,id)
{

	if(list!=undefined){
	var num=list.length;
	//alert(num);	
	if (num==undefined)
		id.value=list.value;
	else
		{
			count=0;
			str="";
			for (i=0;i<num;i++)
			{
				if (list[i].checked)
					{
						if (count==0)
							str=list[i].value;
						else
							str = str + "]]o[[" + list[i].value;
						count++;
					}
			}
		id.value=str;
       }
	}
	//alert(id.value);
}

function Submitform(obj,url)
	{				
		obj.action=url;
		obj.method='post';								
		obj.submit();
	}
	

function SubmitLink(obj,url,str1,str2,listck,listid)
			{
				getValueListCheckBox(listck,listid);
				//alert(listid.value);
				obj.action=url;
				obj.method='post';
				
				if (listid.value=='')				
					alert(str1);
				else if(confirm(str2))
					obj.submit();
			}
function SubmitLinkspec(obj,url,str,listck,listid)
			{
				getValueListCheckBoxspec(listck,listid);
				//alert(listid.value);
				obj.action=url;
				obj.method='post';
				
				if (listid.value=='')
					alert(str);
				else if(confirm('Are you sure you want o delete?'))
					obj.submit();
			}
function search(obj,url,listck,listid)
			{
				getValueListCheckBox(listck,listid);
				//alert(listid.value);
				obj.action=url;
				obj.method='post';
				obj.submit();
			}		

function showL(lyr) {
				var lid = '#'+lyr;
				$(lid).toggle("fast");
				//$("button").click(function () {
				//$("p").toggle("slow");
				//});
}

function showforgot(dl){
		for(i=1;i<=2;i++){
		var mfg = '#messforgot'+i;
		$(mfg).html('');
		}
		
		$('#emailforgot').val('');
		$('#captchaforgot').val('');		
		
		if(dl){
			$('#mask , .common-popup').fadeOut(300 , function() {
				$('#mask').remove();  
			});
		}
			// Getting the variable's value from a link 
		var forgotBox = $('#forgotpassword').attr('href');

		//Fade in the Popup and add close button
		$(forgotBox).fadeIn(300);
		
		//Set the center alignment padding + border
		var popMargTop = ($(forgotBox).height() + 24) / 2; 
		var popMargLeft = ($(forgotBox).width() + 24) / 2; 
		
		$(forgotBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// show captcha		
		getcaptcha('forgot');
		
		// Add the maskreg to body
		$('body').append('<div id="maskforgot"></div>');
		$('#maskforgot').fadeIn(300);
		
		return false;
	}
	
	function showregister(dl){
		for(i=1;i<=6;i++){
		var m = '#mess'+i;
		$(m).html('');
		}
		
		$('#consultantname').val('');
		$('#email').val('');
		$('#password1').val('');
		$('#password2').val('');
		$('#captcha').val('');
		$('#policy').attr('checked', false);
		
		if(dl == 1){
			$('#mask , .common-popup').fadeOut(300 , function() {
				$('#mask').remove();  
			});
		}
		else if(dl == 2){
			$('#maskforgot , .common-popup').fadeOut(300 , function() {
				$('#maskforgot').remove();  
			});
		}
		
		// remove captcha image forgot
		if($('#oldcaptchaforgot').val() != "")
		removecaptcha($('#oldcaptchaforgot').val());
		
			// Getting the variable's value from a link 
		var regisBox = $('#register').attr('href');

		//Fade in the Popup and add close button
		$(regisBox).fadeIn(300);
		
		//Set the center alignment padding + border
		var popMargTop = ($(regisBox).height() + 24) / 2; 
		var popMargLeft = ($(regisBox).width() + 24) / 2; 
		
		$(regisBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// show captcha		
		getcaptcha('reg');
		
		// Add the maskreg to body
		$('body').append('<div id="maskreg"></div>');
		$('#maskreg').fadeIn(300);
		
		return false;
	}
	
	function showlogin(dl){
	
		if(dl == 1){
			$('#maskreg , .common-popup').fadeOut(300 , function() {
				$('#maskreg').remove();  
			});
		}
		else if(dl == 2){
			$('#maskforgot , .common-popup').fadeOut(300 , function() {
				$('#maskforgot').remove();  
			});
		}
		
		$('#username').val('');
		$('#password').val('');
		
		$('#mess').html('');
		
		// remove captcha image
		if($('#oldcaptchareg').val() != "")
		removecaptcha($('#oldcaptchareg').val());
		if($('#oldcaptchaforgot').val() != "")
		removecaptcha($('#oldcaptchaforgot').val());
			
		// Getting the variable's value from a link 
		var loginBox = $('#login').attr('href');

		//Fade in the Popup and add close button
		$(loginBox).fadeIn(300);
		
		//Set the center alignment padding + border
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="mask"></div>');
		$('#mask').fadeIn(300);
		
		return false;
	}
	
	function showmess(w,h,textshow){
	//var textshow = 'Ch&uacute;c m&#7915;ng b&#7841;n &#273;&atilde; k&iacute;ch ho&#7841;t t&agrave;i kho&#7843;n th&agrave;nh c&ocirc;ng v&agrave; ch&iacute;nh th&#7913;c l&agrave; th&agrave;nh vi&ecirc;n c&#7911;a Vi&#7879;t Nam Ti&#7871;p Th&#7883;.<br /><br /> M&#7901;i b&#7841;n <a href="#login-box" id="login" onclick="$(\'#messokie\').dialog(\'close\');showlogin(1);" class="menu" >&#273;&#259;ng nh&#7853;p</a>';
	//var text1 = '<table border="0" width="360" ><tr><td>Ch&uacute;c m&#7915;ng b&#7841;n &#273;&atilde; k&iacute;ch ho&#7841;t t&agrave;i kho&#7843;n th&agrave;nh c&ocirc;ng v&agrave; ch&iacute;nh th&#7913;c l&agrave; th&agrave;nh vi&ecirc;n c&#7911;a Vi&#7879;t Nam Ti&#7871;p Th&#7883;. <br /><br /> M&#7901;i b&#7841;n <a href="#login-box" id="login" onclick="showlogin(1);" >&#273;&#259;ng nh&#7853;p</a></td></tr><tr><td></td></tr></table>';
	//width: 400,
	//height : 150,	
		// remove captcha image
		if($('#oldcaptchareg').val() != "")
		removecaptcha($('#oldcaptchareg').val());
		if($('#oldcaptchaforgot').val() != "")
		removecaptcha($('#oldcaptchaforgot').val());
		// display message	
		$("#messokie:ui-dialog" ).dialog( "destroy" );
		$("#messokie").dialog({
				dialogClass: 'pl',
				title: 'Th&ocirc;ng b&aacute;o',
				resizable: false,
				width: w,
				height : h,
				modal: true,
				position: { my:'top',at:'top' }
			});
			$('.pl.ui-dialog').css({ top: "200px" });
			$('#messokie').html(textshow);
	
	}
	
	function showmess11(){	
		
		$('#maskreg , .common-popup').fadeOut(300 , function() {
			$('#maskreg').remove();  
		});
		
		// remove captcha image
		if($('#oldcaptchareg').val() != "")
		removecaptcha($('#oldcaptchareg').val());
		if($('#oldcaptchaforgot').val() != "")
		removecaptcha($('#oldcaptchaforgot').val());		
		
		//$('#messokie').html('');	
		// Getting the variable's value from a link 
		//var messBox = $('#messokie').attr('href');
		var messBox = '#mess-box';
		//Fade in the Popup and add close button
		$(messBox).fadeIn(300);
		
		//Set the center alignment padding + border
		var popMargTop = ($(messBox).height() + 24) / 2; 
		var popMargLeft = ($(messBox).width() + 24) / 2; 

//alert('h='+$(messBox).height()+'w='+$(messBox).width());
//alert('top='+popMargTop+'left='+popMargLeft);
		
		$(messBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="maskmess"></div>');
		$('#maskmess').fadeIn(300);
		
		return false;
	}
	
	function getcaptcha(dt) {
	var oldcaptcha = "#oldcaptcha"+dt;
	var imgcaptcha = "#imgcaptcha"+dt;
	var wcaptcha = "#wcaptcha"+dt;
	//alert(imgcaptcha);	
		$.ajax({
			url: $("#base_url").val()+'/index/getcaptcha',
			type: 'POST',
			data: 'oldcaptcha='+ $(oldcaptcha).val(),
			beforeSend: function(){
			     	$("#loading").show();
			    },
		    complete: function(){
		     	$("#loading").hide();
		    },
			success: function(data) {
				//alert(data);
				var getData = $.parseJSON(data);
				var captchadisplay = '<img border="0" src="'+getData.mess2+'" >';
				var idcaptcha = getData.mess1;
				var wordcaptcha = getData.mess3;
				//alert(captchadisplay);
				//alert(idcaptcha);
				// display captcha							
				$(imgcaptcha).html(captchadisplay);
				// set id captcha
				$(oldcaptcha).val(idcaptcha);
				// set word captcha
				$(wcaptcha).val(wordcaptcha);			
			}
		});
	}
	
	function removecaptcha(idcap) {		
		$.ajax({
			url: $("#base_url").val()+'/index/removecaptcha',
			type: 'POST',
			data: 'idcap='+ idcap,
			success: function(data) {
				return true;			
			}
		});
	}
	
	function login() {	
	$.ajax({
		url: $("#base_url").val()+'/index/login/',
		type: 'POST',
		data: 'username='+ $("#username").val()+'&password='+ $("#password").val(),
		beforeSend: function(){
			     	$("#loading").show();
			    },
	    complete: function(){
	     	$("#loading").hide();
	    },
		success: function(data) {
			//alert(data);
			var getData = $.parseJSON(data);			
			if(getData.mess1 != "success"){
			$('#mess').html(getData.mess2);
			}
			else {
			window.location.href = $("#base_url").val()+getData.mess2;
			}
		
		}
	});
}

	function userregister() {
	//alert('pass='+$("#password").val());
	//showmess();
	//$('#messokie').html("B&#7841;n &#273;&atilde; &#273;&#259;ng k&yacute; th&agrave;nh c&ocirc;ng.  Ban hay  V&agrave;o email c&#7911;a <br />  b&#7841;n   k&iacute;ch ho&#7841;t t&agrave;i kho&#7843;n &#273;&#7875; tr&#7903; th&agrave;nh th&agrave;nh vi&ecirc;n ch&iacute;nh th&#7913;c.");
		if(checkform()){
			$.ajax({
				url: $("#base_url").val()+'/admin/addaccount',
				type: 'POST',
				data: 'submit=submit&home=1&policy='+ $("#policy").val()+'&role='+ $("#role").val()+'&pre='+ $("#pre").val()+'&status='+ $("#status").val()+'&captcha='+ $("#captcha").val()+'&consultantname='+ $("#consultantname").val()+'&email='+ $("#email").val()+'&password='+ $("#password1").val()+'&act=a',
				beforeSend: function(){
			     	$("#loading").show();
			    },
			    complete: function(){
			     	$("#loading").hide();
			    },
				success: function(data) {
					//alert(data);
					var getData = $.parseJSON(data);			
					if(getData.mess1 != "success"){
					$('#mess2').html(getData.mess2);
					}
					else {
					//window.location.href = $("#base_url").val()+getData.mess2;
					// remove maskreg
						$('#maskreg , .common-popup').fadeOut(300 , function() {
							$('#maskreg').remove();  
						});
					// show message
					showmess(400,150,getData.mess2);
					//$('#messokie').html(getData.mess2);
					}
				
				}
			});
		}	

	}
	

	function forgotpass() {
		if(checkforgot()){
			$.ajax({
				url: $("#base_url").val()+'/index/forgotpass',
				type: 'POST',
				data: 'submit=submit&home=1&captcha='+ $("#captchaforgot").val()+'&email='+ $("#emailforgot").val(),
				beforeSend: function(){
			     	$("#loading").show();
			    },
			    complete: function(){
			     	$("#loading").hide();
			    },
				success: function(data) {
					//alert(data);
					$('#messforgot2').html("");
					var getData = $.parseJSON(data);			
					if(getData.mess1 != "success"){
					$('#messforgot1').html(getData.mess2);
					}
					else {
					//window.location.href = $("#base_url").val()+getData.mess2;
					// remove  maskforgot					 
				  	$('#maskforgot, .common-popup').fadeOut(300 , function() {
						$('#maskforgot').remove();  
					});
					showmess(400,100,getData.mess2);
					//$('#messokie').html(getData.mess2);
					}
				
				}
			});
		}	

	}
	
	function checkform(){
	var lengpass = $("#password1").val();
	
					if ($("#consultantname").val()==""){
						$('#mess1').html("Vui l&ograve;ng nh&#7853;p t&ecirc;n &#273;&#7847;y &#273;&#7911;!");
						return false;						
                     }
                    
                     else if ($("#email").val()==""){
                     $('#mess1').html("");
                     $('#mess2').html("Vui l&ograve;ng nh&#7853;p email!");
                     return false;
                     }else{
								var at="@";
								var dot=".";
								var obj=$("#email").val();
								var lat=obj.indexOf(at);
								var lstr=obj.length;
								var ldot=obj.indexOf(dot);
								if (obj.indexOf(at)==-1){
									$('#mess1').html("");
									$('#mess2').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false
								}
							   else if (obj.indexOf(at)==-1 || obj.indexOf(at)==0 || obj.indexOf(at)==lstr){
							   		$('#mess1').html("");
									$('#mess2').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							  else if (obj.indexOf(dot)==-1 || obj.indexOf(dot)==0 || obj.indexOf(dot)==lstr){
									$('#mess1').html("");
									$('#mess2').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							 else if (obj.indexOf(at,(lat+1))!=-1){
									$('#mess1').html("");
									$('#mess2').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							 else if (obj.substring(lat-1,lat)==dot || obj.substring(lat+1,lat+2)==dot){
									$('#mess1').html("");
									$('#mess2').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							 else if (obj.indexOf(dot,(lat+2))==-1){
									$('#mess1').html("");
									$('#mess2').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							 else if (obj.indexOf(' ')!=-1){
									$('#mess1').html("");
									$('#mess2').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
										 
							}
                    
                    if ($("#password1").val()==""){
                    					$('#mess1').html("");
                    					$('#mess2').html("");
                                        $('#mess3').html("Vui l&ograve;ng nh&#7853;p m&#7853;t kh&#7849;u!");
                                        return false;
                                }
                                
                     else if (lengpass.length < 6){
                     $('#mess1').html("");
                     $('#mess2').html("");
                     $('#mess3').html("M&#7853;t kh&#7849;u &iacute;t nh&#7845;t 6 k&yacute; t&#7921;!");	                 
	                    return false;
                	}
           
                    else if ($("#password2").val()==""){
                    $('#mess1').html("");
                    $('#mess2').html("");
                    $('#mess3').html("");
                    $('#mess4').html("Vui l&ograve;ng x&aacute;c nh&#7853;n l&#7841;i m&#7853;t kh&#7849;u!");
                    return false;
                     }
                     
                    else if ($("#password1").val() != $("#password2").val()){
                     $('#mess1').html("");
                     $('#mess2').html("");
                     $('#mess3').html("");
					 $('#mess4').html("M&#7853;t kh&#7849;u kh&ocirc;ng kh&#7899;p nhau!");
					 return false;
					}
					
					else if ($("#captcha").val()==""){
					$('#mess1').html("");
                    $('#mess2').html("");
                    $('#mess3').html("");
					$('#mess4').html("");
					$('#mess5').html("Vui l&ograve;ng nh&#7853;p m&atilde; x&aacute;c minh!");
					return false;
                     }
                     
                    else if ($("#captcha").val()!= $("#wcaptchareg").val()){
						$('#mess5').html("Chu&#7895;i x&aacute;c minh nh&#7853;p ch&#432;a &#273;&uacute;ng !");
						return false;
                     } 
                     
                    else if (!$("#policy").attr('checked')){
                    $('#mess1').html("");
                    $('#mess2').html("");
                    $('#mess3').html("");
					$('#mess4').html("");
					$('#mess5').html("");
					$('#mess6').html("Vui l&ograve;ng check n&#7871;u &#273;&atilde; &#273;&#7885;c v&agrave; &#273;&#7891;ng &yacute; th&#7887;a &#432;&#7899;c th&agrave;nh vi&ecirc;n!");
					return false;
                     } 
										
                     return true;
   }
   
	function checkforgot(){
			
					 if ($("#emailforgot").val()==""){
                     $('#messforgot1').html("Vui l&ograve;ng nh&#7853;p email!");
                     return false;
                     }
                     else{
								var at="@";
								var dot=".";
								var obj=$("#emailforgot").val();
								var lat=obj.indexOf(at);
								var lstr=obj.length;
								var ldot=obj.indexOf(dot);
								if (obj.indexOf(at)==-1){
									$('#messforgot1').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false
								}
							   else if (obj.indexOf(at)==-1 || obj.indexOf(at)==0 || obj.indexOf(at)==lstr){
									$('#messforgot1').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							  else if (obj.indexOf(dot)==-1 || obj.indexOf(dot)==0 || obj.indexOf(dot)==lstr){
									$('#messforgot1').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							 else if (obj.indexOf(at,(lat+1))!=-1){
									$('#messforgot1').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							 else if (obj.substring(lat-1,lat)==dot || obj.substring(lat+1,lat+2)==dot){
									$('#messforgot1').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							 else if (obj.indexOf(dot,(lat+2))==-1){
									$('#messforgot1').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
							 else if (obj.indexOf(' ')!=-1){
									$('#messforgot1').html("Email kh&ocirc;ng h&#7907;p l&#7879;!");
									return false;
								}
										 
							}
                    
                    if ($("#captchaforgot").val()==""){
						$('#messforgot1').html("");
	                   	$('#messforgot2').html("Vui l&ograve;ng nh&#7853;p m&atilde; x&aacute;c minh!");
						return false;
                     }
	                else if ($("#captchaforgot").val()!= $("#wcaptchaforgot").val()){
	                	$('#messforgot1').html("");
						$('#messforgot2').html("Chu&#7895;i x&aacute;c minh nh&#7853;p ch&#432;a &#273;&uacute;ng !");
						return false;
	                 }    
                    										
                return true;
   }

function savecart(idp,od){
			var idview = '#'+ idp;
			//var seconds = new Date().getSeconds();
			//$("#base_url").val()+			
			$.ajax({	
				url: 'index.php?act=savecartpro',
				data:'idp='+idp+'&od='+od,
				type: 'POST',
				cache: false,
				beforeSend: function(){
			     	$("#loading").show();
			    },
				complete: function(){
					$("#loading").hide();
				},
				success: function(data) {
					//alert(data);
					var getData = $.parseJSON(data);
					if(getData.mess1=='order' || getData.mess1=='expire'){
						window.location.href=getData.mess2;
					}
					else{
						$("#inviewcart").html(getData.mess1);		
						$(idview).html(getData.mess2);
					}
				}	
			});
	}		
	
	function removecart(idp){	
			var idview = '#'+ idp;			
			$.ajax({	
				url: 'index.php?act=removecartpro',
				data:'idp='+idp,
				type: 'POST',
				cache: false,
				beforeSend: function(){
			     	$("#loading").show();
			    },
				complete: function(){
					$("#loading").hide();
				},
				success: function(data) {
				//alert(data);
				Submitform(document.forms.fcart,'thong-tin-gio-hang.html');
				//window.location.reload();
				//var getData = $.parseJSON(data);								
				//$("#inviewcart").html(getData.mess1);	
				//$(idview).html(getData.mess2);				
				}	
			});
	}
	
		function emptycart(empty){						
			$.ajax({	
				url: 'index.php?act=emptycart',
				data:'st='+empty,
				type: 'POST',
				cache: false,
				beforeSend: function(){
			     	$("#loading").show();
			    },
				complete: function(){
					$("#loading").hide();
				},
				success: function(data) {
				//alert(data);
				//window.location.reload();
				Submitform(document.forms.fcart,'thong-tin-gio-hang.html');
				//var getData = $.parseJSON(data);								
				//$("#inviewcart").html(getData.mess1);	
				//$(idview).html(getData.mess2);				
				}	
			});
	}
	
	function getcount() {			
			$.ajax({
				url: 'index.php?act=get_count',
				type: 'POST',
				data: 'idcom='+1,
				success: function(data) {
					var getData = $.parseJSON(data);					
					$('#showoline').html(getData.soline);
					$('#showtoday').html(getData.stoday);
					$('#showtotal').html(getData.stotal);	
					$('#hotline').html(getData.hotline);				
				}
			});
	}
	
	function getcart() {			
			$.ajax({
				url: 'index.php?act=getcartpro',
				type: 'POST',
				data: 'idcom='+1,
				success: function(data) {
					var getData = $.parseJSON(data);										
					$("#inviewcart").html(getData.mess1);				
				}
			});
	}
	
	function vieworder(idcom){
			$("#divvieworder:ui-dialog" ).dialog( "destroy" );
			$("#divvieworder").dialog({
					dialogClass: 'flora',
					title: '&#272;&#7863;t h&agrave;ng tr&#7921;c tuy&#7871;n',
					resizable: false,
					width: 760,
					height : 400,					
					modal: true,
					position: { my:'top',at:'top' }
				});
				//$('.flora.ui-dialog').css({ top: "2px" });
				//$('.flora.ui-dialog').css({ left: "365px" });
				$('.flora.ui-dialog').css({ top: "120px" });
				$('.flora.ui-dialog').css({ left: "230px" });
			$.ajax({	
				url: 'index.php?act=vieworderpro',
				data:'idcom='+idcom,
				type: 'POST',
				cache: false,
				beforeSend: function(){
			     	$("#loading").show();
			    },
				complete: function(){
					$("#loading").hide();
				},
				success: function(data) {
				//alert(data);
				$("#divvieworder").html(data);
				//$("#divvieworder").toggle("fast");
				//showL('divvieworder');				
				}	
			});
	}
	
	function viewprocedurepay(idcom, postby){
			$("#divvieworder:ui-dialog" ).dialog( "destroy" );
			$("#divvieworder").dialog({
					dialogClass: 'flora',
					title: '&#272;&#7863;t h&agrave;ng tr&#7921;c tuy&#7871;n',
					resizable: false,
					width: 760,
					height : 400,					
					modal: true,
					position: { my:'top',at:'top' }
				});
				//$('.flora.ui-dialog').css({ top: "2px" });
				//$('.flora.ui-dialog').css({ left: "365px" });
				$('.flora.ui-dialog').css({ top: "120px" });
				$('.flora.ui-dialog').css({ left: "230px" });
			$.ajax({	
				url: $("#base_url").val()+'/products/procedurepay',
				data:'idcom='+idcom+'&postby='+postby,
				type: 'POST',
				cache: false,
				beforeSend: function(){
			     	$("#loading").show();
			    },
				complete: function(){
					$("#loading").hide();
				},
				success: function(data) {
				//alert(data);
				$("#divvieworder").html(data);
				//$("#divvieworder").toggle("fast");
				//showL('divvieworder');				
				}	
			});
	}
	
	function viewpaysendcontact(idcom,postby){
			$("#divvieworder:ui-dialog" ).dialog( "destroy" );
			$("#divvieworder").dialog({
					dialogClass: 'flora',
					title: '&#272;&#7863;t h&agrave;ng tr&#7921;c tuy&#7871;n',
					resizable: false,
					width: 760,
					height : 400,					
					modal: true,
					position: { my:'top',at:'top' }
				});
				//$('.flora.ui-dialog').css({ top: "2px" });
				//$('.flora.ui-dialog').css({ left: "365px" });
				$('.flora.ui-dialog').css({ top: "120px" });
				$('.flora.ui-dialog').css({ left: "230px" });
			$.ajax({	
				url: $("#base_url").val()+'/products/paysendcontact',
				data:'idcom='+idcom+'&postby='+postby,
				type: 'POST',
				cache: false,
				beforeSend: function(){
			     	$("#loading").show();
			    },
				complete: function(){
					$("#loading").hide();
				},
				success: function(data) {
				//alert(data);
				$("#divvieworder").html(data);
				//$("#divvieworder").toggle("fast");
				//showL('divvieworder');				
				}	
			});
	}
	
	function get_encode_vntt(){	 
	 	var b64_content = Base64.encode($("#cus_content").val());
		document.getElementById("cus_contentencode").value = encodeURIComponent(b64_content);			
	}
	
	function completeorder(idcom,postby){
		if(check_complete()){
			get_encode_vntt();
			//alert($("#cus_contentencode").val());				
			$("#divvieworder:ui-dialog" ).dialog( "destroy" );
			$("#divvieworder").dialog({
					dialogClass: 'flora',
					title: '&#272;&#7863;t h&agrave;ng tr&#7921;c tuy&#7871;n',
					resizable: false,
					width: 760,
					height : 400,					
					modal: true,
					position: { my:'top',at:'top' }
				});
				//$('.flora.ui-dialog').css({ top: "2px" });
				//$('.flora.ui-dialog').css({ left: "365px" });
				$('.flora.ui-dialog').css({ top: "120px" });
				$('.flora.ui-dialog').css({ left: "230px" });			
				$.ajax({	
					url: $("#base_url").val()+'/products/viewcompleteorder',
					data:'idcom='+idcom+'&postby='+postby+'&fullname='+$("#cus_name").val()+'&tel='+$("#cus_tel").val()+'&email='+$("#cus_email").val()+'&address='+$("#cus_address").val()+'&content='+$("#cus_contentencode").val(),
					type: 'POST',
					cache: false,
					beforeSend: function(){
						$("#loading").show();
					},
					complete: function(){
						$("#loading").hide();
					},
					success: function(data) {
					//alert(data);
					$("#divvieworder").html(data);
					//$("#divvieworder").toggle("fast");
					//showL('divvieworder');				
					}	
				});
			}	
	}
	
	
   
   function sendorder(idcom,postby){			
			$("#divvieworder:ui-dialog" ).dialog( "destroy" );
			$("#divvieworder").dialog({
					dialogClass: 'flora',
					title: '&#272;&#7863;t h&agrave;ng tr&#7921;c tuy&#7871;n',
					resizable: false,
					width: 760,
					height : 400,					
					modal: true,
					position: { my:'top',at:'top' }
				});
				//$('.flora.ui-dialog').css({ top: "2px" });
				//$('.flora.ui-dialog').css({ left: "365px" });
				$('.flora.ui-dialog').css({ top: "120px" });
				$('.flora.ui-dialog').css({ left: "230px" });			
				$.ajax({	
					url: $("#base_url").val()+'/products/sendmailorder',
					data:'idcom='+idcom+'&postby='+postby+'&send=1',
					type: 'POST',
					cache: false,
					beforeSend: function(){
						$("#loading").show();
					},
					complete: function(){
						$("#loading").hide();
					},
					success: function(data) {
					//alert(data);
					$("#divvieworder").html(data);
					/*
					var getData = $.parseJSON(data);			
					if(getData.mess1 != "success"){
					$('#divvieworder').html(getData.mess2);
					}
					else {
					$('#divvieworder').html(getData.mess2);
					}					
					*/
									
					}	
				});				
	}
	
	function update_propay(propay){		
			$.ajax({	
				url: $("#base_url").val()+'/products/updatepropay',
				data:'propay='+propay,
				type: 'POST',
				cache: false,
				beforeSend: function(){
			     	$("#loading").show();
			    },
				complete: function(){
					$("#loading").hide();
				},
				success: function(data) {
					//alert(data);		
					//return true;
				}	
			});
			
			return true;
	}

     function data_change(field){
     
          var check = true;
          var value = field.value; //get characters
          
          //check that all characters are digits, ., -, or ""
          for(var i=0;i < field.value.length; i++)
          {
               var new_key = value.charAt(i); //cycle through characters
              
               if(((new_key < "0") || (new_key > "9")) && 
                    !(new_key == ""))
               {
                    check = false;
                    alert('Vui long chi nhap so!');
                    break;
               }
          }

     }
	 
	 function tryNumberFormat(obj)
{
	var nf = new NumberFormat(obj.value);
	nf.setPlaces(0);// 0, -1: no decimal and not around
	//nf.setSeparators(false);// set no comma
	var num = nf.toFormatted();
	obj.value = num;
	//obj.value = new NumberFormat(obj.value).toFormatted();
}
       	