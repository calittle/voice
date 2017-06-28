<ul class="nav navbar-nav">
<?php
	# HOME 
	echo ('<li ');
		if ($thispage == 'index'){echo('class="active"');}
		echo ('><a href="index.php">Home</a></li>');

	# ABOUT
    echo ('<li><a href="doc/">About</a></li>');

	# MY ACCOUNT
	# need to have a User ID to have an account.
	if (!empty($_SESSION['uid'])){		
		echo('<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">My Account<span class="caret"></span></a><ul class="dropdown-menu">');
		echo('<li><a href="#">Hi, '.$_SESSION['username'].'</a></li><li role="separator" class="divider">');
		if (empty($_SESSION['rid']))
		{
			echo('<li ');
			if ($thispage=='registrant')
			{
				echo('class="active"');
			}
			echo('><a href="registrant.php">Add Registrant</a></li>');
		}	
		else
		{
			echo('<li ');
			if ($thispage=='Something'){echo('class="active"');}
			echo('><a href="#">View Registrant</a></li>');
		}			
		if (empty($_SESSION['lid']))
		{
			echo('<li ');
			if ($thispage=='addlocation')
			{
				echo('class="active"');
			}
			echo('><a href="addlocation.php">Add Location</a></li>');			
		}else
		{
			echo('<li ');
			if ($thispage=='Something')
			{
				echo('class="active"');
			}
			echo('><a href="#">Edit Location</a></li>');			
		}
		echo('</ul></li>');		
    }else
    {

	# REGISTER
		echo ('<li ');
			if ($thispage == 'register'){echo('class="active"');}
			echo ('><a href="user.php">Register</a></li>');        
    }
    ?>
</ul>