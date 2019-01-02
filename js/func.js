// obj1 is a value list
//obj2 is a hidden keep value of list
function checkTime(obj) {
      var strTime = obj.value;
      if(strTime!=null && strTime!="") {
            var arrTime  = strTime.split(':');
            if(arrTime.length==2){
                  if(isNaN(arrTime[0])==true ||isNaN(arrTime[1])==true) {
                        obj.value="00:00";      
                  }else if(isNaN(arrTime[0])==false&& isNaN(arrTime[1])==false) {
                        if(arrTime[0]>23 || arrTime[1]>59 ) {
                              obj.value="00:00";
                        }
                  }
            }else {
                  obj.value="00:00";      
            }     
      }else {
            obj.value="00:00";      
      }     
}

function getLengthtextarear(item1,item2,num)
{
	var str=item2.value;
	item1.value=eval(num)-eval(item2.value.length);
	if (item2.value.length>=eval(num))
	{
		item2.value=str.substring(0,eval(num));
		item1.value=eval(num)-eval(item2.value.length);
	}
}

function checkphone(obj,strvalid)
{
	if (obj!="")
	{											
		var str=obj;
		var valid=strvalid;
		var ok="yes";
		var temp;
		for (var i=0;i<str.length;i++){
			temp = "" +str.substring(i, i+1);
			if (valid.indexOf(temp) == -1) ok = "no";
		}
		if (ok == "no") {
			return false;
		}
	}
	return true;
}

function GetDate(dd,mm,yyyy,obj){
	var selectDate= dd.value;
	var day=0;
	var month=eval(mm.value);
	var Year=eval(yyyy.value);
	
		switch (month){
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12: 
					day=31; 
					break;
			case 4:
			case 6:
			case 9:
			case 11: 
					day=30; 
					break;
			case 2: 
					if (month>0 && Year>0){
						if ((Year%4==0 && Year%100!=0)||(Year%400==0))
								day=29;
							else
								day=28;
					}
					break;
		}
		if (month>0){
			boxlength=dd.length;
			for (i=0;i<boxlength;)
				{
					dd.options[i]=null;
					boxlength=dd.length;
				}
		}
		// Ket thuc
		for (i=1;i<=day;i++){
			if (i<10)
				gt="0"+i;
			else
				gt=i;
			if (eval(selectDate)==i)
				newoption=new Option(gt,gt,true,true);
			else
				newoption=new Option(gt,gt,false,false);
			dd.options[dd.length]=newoption;
		}
		if (selectDate>day)
			dd.options[0].selected;
		obj.value=yyyy.value+"-"+mm.value+"-"+dd.value;
}
function check(field,obj) {
	
	if (obj.checked)
	{
		$("input[name='"+field+"']").each(function(i,val) {
             this.checked = true;
    	});
	}
	else
	{
		$("input[name='"+field+"']").each(function(i,val) {
             this.checked = false;
		});
	}
}
function CheckAll() {
	for (var i=0;i<document.form.elements.length;i++) {
    	var e = document.form.elements[i];
        if ((e.name != 'ckAll') && (e.type=='checkbox')) {
		  	  if (document.form.ckAll.checked)
			  	e.checked=true;
			  else
			  	e.checked=false;
        }
    }
}

function getValueListCheckBox(list,id)
{	
	if(list!=undefined){
	var num=list.length;
	if (num==undefined)
	{
		if (list.checked)
			id.value=list.value;
		else
			id.value="";
	}
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
							str = str + ", " + list[i].value;
						count++;
					}
			}
		id.value=str;
       }
	}
}

function reloadform()
{
	window.close();
	window.opener.location.reload();
}

function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}
 
function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}
 
function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}
function checkEnterKey(evt)
{
	str=trim(document.Notes.content.value, " ");
	var charCode = ( evt.which ) ? evt.which : event.keyCode;
	if (str.length==0 && charCode==13)
		return false;
}

function addRow(num)
{
	 frm= document.newFile;
	 if (num<=5)
	 {
	 var table = document.getElementById("tblGrid");
	 var rowcount=table.rows.length; 
	 var newRow = table.insertRow(rowcount); 
	 var oCell = newRow.insertCell(0);
	 	 oCell.height=15;
		 oCell.innerHTML = "";
		 oCell = newRow.insertCell(1);
		 oCell.innerHTML = "";
		 oCell = newRow.insertCell(2);
		 oCell.innerHTML = "";
	 var table = document.getElementById("tblGrid");
	 var rowcount=table.rows.length; 
	 var newRow = table.insertRow(rowcount); 
	 var oCell = newRow.insertCell(0);
		 oCell.innerHTML = "<b>File:</b>";
		
		 oCell = newRow.insertCell(1);
		 oCell.innerHTML = "<input type='file' name='fileupload"+num+"' id='fileupload"+num+"' style='width:250px' onChange=\"getnameFile(this,document.newFile.fu"+num+");\"><input type='hidden' name='fu"+num+"'>";
		 
	 var table = document.getElementById("tblGrid");
	 var rowcount=table.rows.length; 
	 var newRow = table.insertRow(rowcount); 
	 var oCell = newRow.insertCell(0);
	 	 oCell.height=15;
		 oCell.innerHTML = "";
		 oCell = newRow.insertCell(1);
		 oCell.innerHTML = "";
		 oCell = newRow.insertCell(2);
		 oCell.innerHTML = "";
	 var table = document.getElementById("tblGrid");
	 var rowcount=table.rows.length; 
	 var newRow = table.insertRow(rowcount); 
	 var oCell = newRow.insertCell(0);
	 	 oCell.vAlign = "top";
		 oCell.innerHTML = "<b>Description:</b>";
		
		 oCell = newRow.insertCell(1);
		 oCell.innerHTML = "<textarea name='description"+num+"' rows='6' style='width:390px' onKeyPress='return maxChar(this)' id='description"+num+"'></textarea>";
	 }
	 
	if (num>5)
	{
		frm.numfile.value=5;
		document.getElementById('addlinkfile').innerHTML="";
	}
	else
		frm.numfile.value=eval(frm.numfile.value)+1;
}

function maxChar(obj,num)
{
	if (obj.value.length>num)
	{
		alert("Exceed maximum characters");
		obj.value=obj.value.substring(0,num);
		return false;
	}
	else
		return true;
}

function submitLog(page)
{
	document.access_log.page.value=page;
	document.access_log.submit();
}
function checkfeature()
{
	flag=false;
	for (i=0;i<document.templatePermission.feature.length;i++)
		if (document.templatePermission.feature[i].checked)
			flag=true;
	if (flag==false)
	{
		alert('Please select at least one feature.');
		return false;
	}
	return true;
}

function checkNumDigit(evt,obj) {
	var charCode = ( evt.which ) ? evt.charCode : evt.keyCode;
	if (charCode >= 48 && charCode <= 57 || charCode==0 || charCode>=37 && charCode<=40 || charCode==46 || charCode==44 || charCode==9)
	{
		return true;
	}
	else
		return false; 
}

function setactivebutton()
{
	if(document.templatePermission.role_id.value!=0)
		document.templatePermission.loadtemplate.disabled=false;
	else
		document.templatePermission.loadtemplate.disabled=true;
}

function getnameFile(obj,obj1)
{
	arr= obj.value.split('\\');
	obj1.value=arr[arr.length-1];
}

function checkMail(object)
{
	var x = object;
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(x)) return true;
	else return false;
}

function uncheckAllCheckbox(cbname,txtName,hdField){
	$(txtName).val('Please select...');
	$(hdField).val('');
	$("input[name='"+cbname+"']").each(function(i,val) {
             this.checked = false;
    });
}

function countCheck(cbname,txtName,hdField){
	num=0;
	$("input[name='"+cbname+"']:checked").each(function(i,val) {
		  if (num==0)
		  {
		  	str=$(this).val();
			strname=$(this).next().text();
		  }
		 else
		 {
		 	str=str+","+$(this).val();
			strname=strname+", "+$(this).next().text();
		 }
		
		num++;
    });
	if (num>0)
	{
		$(txtName).val(num+' items selected');
		$(hdField).val(str);
		$('#lb'+trim(hdField,'#')).html('Selected: '+strname);
		
	}
	else
	{
		$(txtName).val('Please select...');
		$(hdField).val();
		$('#lb'+trim(hdField,'#')).html('');
	}
	//alert($(hdField).val());
}

