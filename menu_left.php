<ul class="nav navbar-nav">
<?php
	# HOME 
	echo ('<li ');
		if ($thispage == 'index'){echo('class="active"');}
		echo ('><a href="index.php">Home</a></li>');

	# ABOUT
    echo ('<li><a href="doc/">About</a></li>');

	# VOTE
	echo ('<li ');
		if ($thispage == 'vote'){echo('class="active"');}
		echo ('><a href="vote.php">Vote!</a></li>');        


	# ACCOUNT
	echo ('<li ');
		if ($thispage == 'account'){echo('class="active"');}
		echo ('><a href="account.php">My Account</a></li>');        
	# REGISTER
	echo ('<li ');
	if ($thispage == 'register'){echo('class="active"');}
		echo ('><a href="register.php">Register</a></li>');        
    ?>
</ul>