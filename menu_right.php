<ul class="nav navbar-nav navbar-right">
    <?php
        #if logged in, show log out
        if (!empty($_SESSION['uid'])){
            echo('<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>');
         }
        ?>
  <li><a href="admin.php"><span class="glyphicon glyphicon-lock"></span> Admin</a></li>
</ul>
						