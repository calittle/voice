var request;
function deleteUser(u){
	$('#errormessage').html('You do not have authority to delete users.');
	$('#errormessagediv').fadeIn(500);	
	$('#errormessagediv').fadeOut(3000);	
}
function ajaxIt(d,r){
	$('#errormessage').html('');
	$('#errormessagediv').hide();	
	if (request){request.abort();}
	var o;
	request = $.ajax({
			url: "ajx_functions.php",
			type: "post",
			data: d,
			success: function(data){
				var successMsg=false;
				var msg='';
				try{
					o = JSON.parse(data);
					successMsg = o['success'];
					msg = o['message'];
					console.log('Response Data: ' + o['function'] + ' results: ' + o['success']);
				}catch(err){
					msg = err;
				}
				
				if (successMsg==true){
					$('#successmessage').html(msg);
					$('#successmessagediv').fadeIn(500);
					$('#successmessagediv').fadeOut(2500);
					$('#errormessagediv').hide();
					if (r)location.reload();
				}
				else{
					console.log('Unable to execute ' + o['function'] + '. ' + msg + '. Data returned: [' + o + ']');
					this.error(this.xhr,'Unable to perform request.',msg);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html(textStatus + ' Message: '+ errorThrown);
				$('#errormessagediv').fadeIn(500);	
			}
		});	
}
function rejectRegistrant(r){
	//var obj = {"function":"rejectregistrant","par1":r };
	var obj ="function=rejectregistrant&par1=" + r;
	ajaxIt(obj,1);
}
function approveRegistrant(r){
	//var obj = {"function":"approveregistrant","par1":r };
	var obj ="function=approveregistrant&par1=" + r;
	ajaxIt(obj,1);
}
function revokeUserRole(u,r){
	//var obj = {"function":"revokerole","par1":u,"par2":r };
	var obj = "function=revokerole&par1="+u+"&par2="+r;
	ajaxIt(obj,1);
}
function addDistrict(d){
	//var obj = {"function":"districtadd","par1":d };
	var obj = "function=districtadd&par1="+d;
	ajaxIt(obj,1);
}
function updateDistrict(d,r){
	var obj="function=districtupdate&par1="+d+"+&par2="+r;
	ajaxIt(obj,1);
}
function deleteDistrict(d){
	var obj="function=districtdelete&par1="+d;
	ajaxIt(obj,1);
}
function addUserRole(u,r){
	//var obj = {"function":"addrole","par1":u,"par2":r};
	var obj = "function=addrole&par1="+u+"&par2="+r;
	ajaxIt(obj,1);	
}
function affirmRegistrant(r){
	//var obj = {"function":"setaffirmations","par1":r };
	var obj ="function=setaffirmations&par1=" + r;
	ajaxIt(obj,1);
}

