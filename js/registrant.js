/*
 * VOICE - Registrant v1.0
 * Developed and maintanined by Charles Little, little_charles1@columbusstate.edu
 * Source repository: https://github.com/calittle/voice
 * Licensed under an Apache 2.0 license. See https://github.com/calittle/voice/blob/master/LICENSE for details.
 */
$(document).ready(function($) {
	
	$(function(){
	    $("[data-hide]").on("click", function(){
    	    $(this).closest("." + $(this).attr("data-hide")).hide();
    	});
	}); // data-hide
	
	$('#phoneInput').mask("(999) 999-9999");
	$('#birthDateInput').mask("9999-99-99",{placeholder:"yyyy-mm-dd}"});
	$('#fedIdInput').mask("9999");
	$('#postCodeInput').mask("99999?-9999");
	$("#stateInput").bind("change keyup", function(event){
		$("#stateResidenceInput").val($("#stateInput option:selected").val());
	});
	
	jQuery.validator.addMethod("lettersonly", function(value, element) {return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);}, "Letters/spaces only please.");

	$('.input-group.date').datepicker({
	    startView: 2,
	    format: "yyyy-mm-dd",
	    showOnFocus: false,
	    autoclose: true
	}); // datepicker
	
	$('#passwordInput').keyup(function(e) {
	     var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	     var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	     var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	     if (false == enoughRegex.test($(this).val())) {
	             $('#passstrength').html('Weak password. Please enter more characters.');
	     } else if (strongRegex.test($(this).val())) {
	             $('#passstrength').removeClass().addClass('label label-success');
	             $('#passstrength').html('Good password.');
	     } else if (mediumRegex.test($(this).val())) {
	             $('#passstrength').removeClass().addClass('label label-warning');
	             $('#passstrength').html('Ok password. Try mixed case and special characters.');
	     } else {
	             $('#passstrength').removeClass().addClass('label label-danger');
	             $('#passstrength').html('Weak password. Add special characters, mixed case, and numbers.');
	     }
	     return true;
	}); // password stregnth keyup
	
	$('#locationform').validate({
	    rules: {	        
	        street1Input: {required:true,minlength:5,maxlength:256},
      	    street2Input: {required:false,minlength:1,maxlength:256},
      	    cityInput: {required:true,minlength:2,maxlength:256,lettersonly: true},
   	        postCodeInput: {required:true},
   	        stateResidenceInput:{required:true},
   	        countryInput:{required:true}
	    },
	    highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	$('#affirmForm').validate({
	    rules: {	        
	        partyInput: {required:false}
	    },
	    highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	$('#registrant_form').validate({
	    rules: {
	        suffixNameInput: {
	            minlength: 1,
	            maxlength: 256,
	            required: false
	        },
	        lastNameInput: {
	            minlength: 3,
	            maxlength: 256,
	            lettersonly: true,
	            required: true
	        },
	        middleNameInput: {
	            minlength: 3,
	            maxlength: 256,
	            lettersonly: true,
	            required: false
	        },
	        firstNameInput: {
	            minlength: 3,
	            maxlength: 256,
	            lettersonly: true,
	            required: true		        
	        },
	        birthDateInput:{
		        minlength: 8,
		        maxlength: 10,
		        required: true
	        },
	        phoneInput: {required:false},
	        stateIdInput: {maxlength:256,required:false},
	        fedIdInput: {maxlength:4,required:true},
	        stateInput: {required:true}
	    },
	    highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	}); //registrant_form - validate
	$('#loginform').validate({
		rules:{
			usernameInput: {
	            minlength: 3,
	            maxlength: 256,
	            required: true
	        },	        
	        passwordInput: {
	            minlength: 8,
	            maxlength: 64,
	            required: true
	        }
	    },
	    highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	$('#createuserform').validate({
	    rules: {
	        usernameInput: {
	            minlength: 3,
	            maxlength: 256,
	            required: true
	        },
	        emailInput: {
	            minlength: 5,
	            maxlength: 256,
	            required: true
	        },
	        passwordInput: {
	            minlength: 8,
	            maxlength: 64,
	            required: true
	        }
	    },
	    highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	}); //createuserform-validate

	var request;	
	
	// AJAX registrant request
	$('#registrant_form').submit(function(event){
		if ($('#registrant_form').valid()!=true){return false;}		
		event.preventDefault();
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button, select, option");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);
		request = $.ajax({
			url: "registrant_form.php",
			type: "post",
			data: serialdata,
			success: function(data){
				var o = JSON.parse(data);
				var didItWork = o['success'];
				if (didItWork==true){
					console.log('Added registrant data: ' + data);					
					
					populate_parties(this);
					populate_affirmations(this);
					setProgress('50');
					$('#header_step_2').fadeOut(1500);
					$('#registrant_div').fadeOut(1500);
					$('#registrant_success').fadeIn(1500);
					$('#registrant_success').fadeOut(5000);
					$('#header_step_3').fadeIn(1500);
					$('#registrant2_div').fadeIn(1500);
				}
				else{
					console.log('Unable to add registrant data:' + data);
					this.error(this.xhr,'Unable to add registrant data',o['message']);
					$inputs.prop("disabled",false)			
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#registrant_error_msg').html("There was a problem adding your registrant data: " + textStatus + errorThrown);
				$('#registrant_error').show();		
				$inputs.prop("disabled",false)
			}					
		}); // ajax
	}); // registrant_form - submit
	$('#loginform').submit(function(event){
		if ($('#loginform').valid()!=true){return false;}
		event.preventDefault();
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);

		request = $.ajax({
		url: "login_form.php",
		type: "post",
		data: serialdata,
		success: function(data){
				var o = JSON.parse(data);
				var didItWork = o['success'];
				if (didItWork == true){
					console.log('User logged in: ' + data);
//					alert(o['register_state']);
					if (o['register_state']){
						$('#header_step_1').fadeOut(500);
						$('#header_login').fadeOut(1500);
						$('#logindiv').fadeOut(1500);
						$('#loginsuccess').fadeIn(1500);
						$('#loginsuccess').fadeOut(5000);
						$('#alreadyregistered').fadeIn(1500);
						setProgress('100');					
					}else{
						setProgress('25');
						$('#header_login').fadeOut(1500);
						$('#logindiv').fadeOut(1500);
						$('#loginsuccess').fadeIn(1500);
						$('#loginsuccess').fadeOut(5000);
						$('#header_step_2').fadeIn(1500);
						$('#registrant_div').fadeIn(1500);
					}
				}else{
					console.log(data);
					this.error(this.xhr,'Unable to login user',o['message']);
				}
		},
		error: function(jqXHR, textStatus, errorThrown){
			$('#loginerrormessage').html("There was a problem logging in your user account: " + textStatus + errorThrown);
			$('#loginerror').show();		
			$inputs.prop("disabled",false)				
		}
	}); // ajax		
	}); //loginform submit
	$('#createuserform').submit(function(event){
		if ($('#createuserform').valid()!=true){return false;}
		event.preventDefault();
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);

		// AJAX: create user
		request = $.ajax({
			url: "user_form.php",
			type: "post",
			data: serialdata,
			success: function(data){
				var o = JSON.parse(data);
				var didItWork = o['success'];
				if (didItWork==true){
					console.log('Created user account: ' + data);										
					login_user(serialdata);
				}
				else{
					console.log(data);
					this.error(this.xhr,'Unable to create user account.',o['message']);			
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#create_user_error_msg').html("There was a problem creating your user account: " + textStatus + errorThrown);
				$('#create_user_error').show();		
				$inputs.prop("disabled",false)
			}
		}); // ajax		
	}); //create-userform submit
	
	$('#affirmForm').submit(function(event){
		if ($('#affirmForm').valid()!=true){return false;}
		event.preventDefault();
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);
		request = $.ajax({
			url: "registrant2_form.php",
			type: "post",
			data: serialdata,
			success: function(data){
				var o = JSON.parse(data);
				var didItWork = o['success'];
				if (didItWork==true){
					console.log('Saved affirmations : ' + data);
					setProgress('75');
					$('#header_step_3').fadeOut(1500);
					$('#registrant2_div').fadeOut(1500);
					$('#registrant_success').fadeIn(1500);
					$('#registrant_success').fadeOut(5000);

					$('#header_step_4').fadeIn(1500);
					$('#residence_div').fadeIn(1500);
					populate_counties();
				}
				else{
					console.log(data);
					this.error(this.xhr,'Unable to save affirmations.',o['message']);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#registrant2_error_msg').html("There was a problem saving your affirmation information: " + textStatus + errorThrown);
				$('#registrant2_error').show();		
				$inputs.prop("disabled",false)
			}
		});
	});
	$('#locationform').submit(function(event){
		if ($('#locationform').valid()!=true){return false;}
		event.preventDefault();
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);
		console.log('location:' + serialdata);		
		request = $.ajax({
			url: "location_add.php",
			type: "post",
			data: serialdata,
			success: function(data){
				console.log(data);
				var o;
				var didItWork = false;
				try {
					o = JSON.parse(data);
					didItWork = o['success'];
					if (didItWork==true){
						//
						// REMOVE THIS FOR NON-TEST STATE!
						// 
						autoApprove();
						//
						// REMOVE THIS FOR NON-TEST STATE!
						//
						console.log('Saved location : ' + data);
						setProgress('100');				
						$('#header_step_3').fadeOut(1500);
						$('#registrant2_div').fadeOut(1500);
						$('#residence_success').fadeIn(1500);
						$('#residence_success').fadeOut(5000);
						$('#header_step_4').fadeOut(1500);
						$('#residence_div').fadeOut(1500);
						$('#final').fadeIn(1000);
					}
					else{
						console.log(data);
						this.error(this.xhr,'Unable to save residence.',o['message']);
					}
				}
				catch (err){
					this.error(this.xhr,'Unable to save residence.',err.message);
				} 
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#residence_error_msg').html("There was a problem saving your residence information: " + textStatus + errorThrown);
				$('#residence_error').show();		
				$inputs.prop("disabled",false)
			}
		});//ajax
	});//locationform
}); //document-ready

function login_user(serialdata){
	//alert('login_user()');
	request = $.ajax({
		url: "login_form.php",
		type: "post",
		data: serialdata,
		success: function(data){
				var o = JSON.parse(data);
				var didItWork = o['success'];
				if (didItWork == true){
					console.log('User logged in: ' + data);
					setProgress('25');			
					$('#header_step_1').fadeOut(1500);
					$('#create_user_div').fadeOut(1500);
					$('#create_user_success').fadeIn(1500);
					$('#create_user_success').fadeOut(5000);
					$('#header_step_2').fadeIn(1500);
					$('#registrant_div').fadeIn(1500);
				}else{
					console.log(data);
					this.error(this.xhr,'Unable to login user',o['message']);
				}
		},
		error: function(jqXHR, textStatus, errorThrown){
			$('#create_user_error_msg').html("There was a problem logging in your user account: " + textStatus + errorThrown);
			$('#create_user_error').show();		
			$inputs.prop("disabled",false)				
		}
	}); // ajax	
}						
function populate_parties(o){
	//alert('populate_parties()');
	$.ajax({
		type: "post",
		url: "ajx_parties.php",
		success: function(data){
			//console.log(data);
			var opts = JSON.parse(data);
			$.each(opts, function(i, d){
				$('#partyInput').append('<option value="' + d.PARTYCD + '">' + d.PARTY + '</option>');
			});
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log(data);
			console.log('Error populating parties: ' + textStatus + ',' +errorThrown);
		}
	});
}
function populate_counties(o){	
//	alert('populate_counties()' + o);
	var serialdata;
	if (o){
		serialdata = o.serialize();
	}
	$.ajax({
		type: "post",
		url: "ajx_counties.php",
		data: serialdata,
		success: function(data){
			console.log('Populated counties.');
			var opts = JSON.parse(data);
			$.each(opts, function(i, d){
				$('#countyInput').append('<option value="' + d.COUNTYCD + '">' + d.COUNTY + '</option>');
			});
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log(data);
			console.log('Error populating counties: ' + textStatus + ',' +errorThrown);
		}
	});
}	
function setProgress(val){
	$(".progress-bar").css("width", val+"%");
	$(".progress-bar").css("aria-valuenow",val);
	$("#progressbarvalue").html(val+"%");			
}
function showlogin(){
	$('#header_step_1').fadeOut(450);
	$('#header_login').fadeIn(450);
	$('#create_user_div').fadeOut(500);
	$('#logindiv').fadeIn(50);
}
function setHeader(e){
//1 user ,2 registrant ,4 location
//possible 
	//alert('setHeader:'+e);
	switch (e){
		case 0://none
			$('#header_step_1').hide();
			$('#alreadyregistered').show();
			setProgress('100');
			break;
		case 1:
		case 3:
		case 5:
		case 7:
			$('#header_step_1').show();
			setProgress('5');
			break;
		case 2:
		case 6:
			$('#header_step_1').hide();
			$('#header_step_2').show();
			setProgress('25');
			break;
		case 4://location	
			populate_counties();		
			$('#header_step_1').hide();
			$('#header_step_4').show();
			$('#residence_div').show();
			setProgress('50');
			break;
	}
}
function populate_affirmations(o){
	//alert('populate_affirmations()');
	$.ajax({
		type: "post",
		url: "ajx_affirms.php",
		success: function(data){
			//console.log(data);
			var opts = JSON.parse(data);
			$.each(opts, function(i, d){
				$('#affirmations').append('<li vid="' + d.AFFIRM_ID + '">' + d.AFFIRMATION + '</li>');
			});
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log(data);
			console.log('Error populating affirmations: ' + textStatus + ',' +errorThrown);
		}
	});
}
function autoApprove(){
	$.ajax({
		type: "get",
		url: "ajx_auto.php",
		success: function(data){
			console.log('AUTO APPROVED AND SET ROLE');
			var o = JSON.parse(data);
			if (o['success']==true){
				console.log('Approved user.');
			}else{
				console.log(data);
				this.error(this.xhr,'Unable to approve user',o['message']);
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log(data);
			console.log('Error auto approving: ' + textStatus + ',' +errorThrown);
		}
	});
}