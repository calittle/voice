var request;
function deleteUser(u){
	$('#errormessage').html('You do not have authority to delete users.');
	$('#errormessagediv').fadeIn(500);	
	$('#errormessagediv').fadeOut(3000);	
}
function ajaxIt(d){
	console.log('AJAX String:' + d);
	
	$('#errormessage').html('');
	$('#errormessagediv').hide();	
	if (request){request.abort();}
	var edata = d;
	var o;
	request = $.ajax({
			url: "ajx_functions.php",
			type: "post",
			data: edata,
			dataType: "json",
			success: function(data){
				var successMsg=false;
				var msg='';
				try{
					//o = JSON.parse(data);
					successMsg = data['success'];
					msg = data['message'];
					console.log('RESPONSE:' + data);
					console.log(data['function'] + ' results: ' + data['success']);
				}catch(err){
					msg = err;
				}
				if (successMsg==true){
					console.log(data['function'] + ' results: ' + data['success']);
					$('#successmessage').html(msg);
					$('#successmessagediv').fadeIn(500);
					$('#successmessagediv').fadeOut(2500);
					$('#errormessagediv').hide();
					location.reload();
				}
				else{
					console.log('Unable to execute ' + data['function'] + '. ' + msg + '. Data returned: [' + data + ']');
					this.error(this.xhr,'Unable to perform that request; ',msg);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html(textStatus + errorThrown);
				$('#errormessagediv').fadeIn(500);	
			}
		});	
}
function rejectRegistrant(r){
	ajaxIt("function=rejectregistrant&par1=" + r);
}
function approveRegistrant(r){
	ajaxIt("function=approveregistrant&par1=" + r);
}
function revokeUserRole(u,r){
	ajaxIt("function=revokerole&par1=" + u + "&par2=" + r)
}
function addUserRole(u,r){
	ajaxIt("function=addrole&par1=" + u + "&par2=" + r);
}
function affirmRegistrant(r){
	ajaxIt("function=setaffirmations&par1=" + r);
}

