<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">BucketLyst</a>
        <div class="nav-collapse collapse">
          <ul class="nav navbar-nav pull-right">
            <li><a href="index.php">Home</a></li>

            <?php
            if (loggedin()){
              print "<li><a href=\"logout.php\">Logout</a></li>";
            }
            else{
              print "<li><a href=\"login.php\">Login</a></li>";
            }
            ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div> <!--navbar-->