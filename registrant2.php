<?php
# connect to database (perhaps via include?)	
# get chosen state affirmations and party.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>VOICE &#9745;</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel=stylesheet href="css/mystyles.css"/>    
    <link href="https://fonts.googleapis.com/css?family=Lora|Raleway|Source+Code+Pro" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <header>
        <nav class="navbar navbar-inverse">
	        <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">VOICE</a>
                </div>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="nav navbar-nav">
                        <li ><a href="index.html">Home</a></li>
                        <li class="active"><a href="user.html">Register</a></li>
                        <li><a href="#">My Account</a></li>
						<li><a href="doc/">About</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
				      <li><a href="#"><span class="glyphicon glyphicon-lock"></span> Admin</a></li>
				    </ul>
                </div>
	        </div>
        </nav>
        <h1 class="sr-only">Home</h1>
    </header>
	<div class="container">
	    <section>
			<div class="jumbotron">
	            <h3>Register to Vote (Page 3)</h3>
				<p>Almost done! You need to affirm the following statements for your chosen registration state, and if you wish, may select a political party affiliation.</p>
	        </div>	
			<div class="well well-lg">
				<form method="POST" action="registrant2_form.php">
					<!-- Note: in a single-state implementation you could simply hard-code this value and not use this input -->
					<div class="form-group">
				    	<label for="stateInput">Registration State</label>
						<p class="help-block">Choose the state in which you are registering</p>
						<div class="input-group">
							<select class="form-control required" id="stateInput" name="stateInput" required="true">
								<option value="">-- Select State --</option>
<?php
/* query database to populate:
Affirmations
Party
*/
echo "<option value='GA'>GA (Georgia)</option>"
?>	
							</select>
						</div>
					</div>
				  <button type="submit" class="btn btn-default">Register!</button>
				</form>
			</div>  
	    </section>
	    <div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 75%;">
			</div>
		</div>
    </div>
    <nav id="pagefooter" class="navbar navbar-default navbar-fixed-bottom navbar-inverse">
        <div class="container">
            <div class="col-xs-12 text-center navbar-text">
				<p class="text-muted">Copyright &copy; 2017 <a href="mailto:little_charles1@columbusstate.edu">Charles Little</a>, All rights reserved.</p>
            </div>
        </div>
    </nav>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
<script>
$(document).ready(function($) {
	
	$('#birthDateInput').mask("9999-99-99",{placeholder:"yyyy-mm-dd}"});
	jQuery.validator.addMethod("lettersonly", function(value, element) {return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);}, "Letters/spaces only please.");
	$('.input-group.date').datepicker({
	    startView: 2,
	    format: "yyyy-mm-dd",
	    autoclose: true
	});
	$('form').validate({
	    rules: {	        
	        firstNameInput: {
	            minlength: 3,
	            maxlength: 256,
	            lettersonly: true,
	            required: true		        
	        },
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
	});
});
</script>
    </body>
</html>
