<ul class="nav navbar-nav">
<?php
	# HOME 
	echo ('<li ');
		if ($thispage == 'index'){echo('class="active"');}
		echo ('><a href="index.php">Home</a></li>');

	# ABOUT
    echo ('<li><a href="doc/">About</a></li>');

	echo ('<li ');
	#if (!empty($_SESSION['uid'])){		
		# MY ACCOUNT
		# need to have a User ID to have an account.
		if ($thispage == 'account'){echo('class="active"');}
		echo ('><a href="account.php">My Account</a></li>');        
    #}else
    #{
	echo ('<li ');
		# REGISTER
		if ($thispage == 'register'){echo('class="active"');}
		echo ('><a href="register.php">Register</a></li>');        
    #}
    ?>
</ul>