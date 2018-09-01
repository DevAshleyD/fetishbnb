<?php

error_reporting(0); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_config_path = '../application/config/production/database.php';

// Only load the classes in case the user submitted the form
if($_POST) {

	// Load the classes and create the new objects
	require_once('includes/core_class.php');
	require_once('includes/database_class.php');

	$core = new Core();
	$database = new Database();


	// Validate the post data
	if($core->validate_post($_POST) == true)
	{
		$redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
		$redir .= "://".$_SERVER['HTTP_HOST'];
		$redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
		$redir = str_replace('install/','',$redir);

		// First create the database, then create tables, then write config file
		if($database->create_database($_POST) == false) {
			$message = $core->show_message('error',"The database could not be created, please verify your settings.");
		} else if ($database->create_tables($_POST) == false) {
			$message = $core->show_message('error',"The database tables could not be created, please verify your settings.");
		} else if ($core->write_config($_POST) == false) {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/development/database.php file to 777");
		} else if ($core->write_config_2($_POST) == false) {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/production/database.php file to 777");
		} else if ($core->write_config_3($redir) == false) {
			$message = $core->show_message('error',"The config file could not be written, please chmod application/config/development/config.php file to 777");
		} else if ($core->write_config_4($redir) == false) {
			$message = $core->show_message('error',"The config file could not be written, please chmod application/config/production/config.php file to 777");
		}

		// If no errors, redirect to registration page
		if(!isset($message)) {
	      	header( 'Location: ' . $redir ) ;
		}

	}
	else {
		$message = $core->show_message('error','All fields are required.');
	}
}

?>
<!doctype html>
<html lang="en">
  <head>
    <title>Installation | Classeventie</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css">
    
    <style type="text/css">
    	body {
		  padding-top: 40px;
		  padding-bottom: 40px;
		  background-color: #eee;
		}

		.form-signin {
		  max-width: 330px;
		  padding: 15px;
		  margin: 0 auto;
		}
		.form-signin .form-signin-heading,
		.form-signin .checkbox {
		  margin-bottom: 10px;
		}
		.form-signin .checkbox {
		  font-weight: 400;
		}
		.form-signin .form-control {
		  position: relative;
		  box-sizing: border-box;
		  height: auto;
		  padding: 10px;
		  font-size: 16px;
		}
		.form-signin .form-control:focus {
		  z-index: 2;
		}
		.form-signin input[type="email"] {
		  margin-bottom: -1px;
		  border-bottom-right-radius: 0;
		  border-bottom-left-radius: 0;
		}
		.form-signin input[type="password"] {
		  margin-bottom: 10px;
		  border-top-left-radius: 0;
		  border-top-right-radius: 0;
		}
    </style>
  </head>
  <body>
  		<div class="container">
	        <h2 class="form-signin-heading">Classeventie | Installer</h2>

			<div class="row">
				<div class="col-md-7">
					
				    <?php if(is_writable($db_config_path)){?>

					<?php if(isset($message)) {echo '<br><div class="alert alert-danger alert-dismissible fade show"  role="alert"><strong>'.$message.'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span></button></div>';} ?>

				    <form class="form-signin" id="install_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<div class="form-group">
							<label for="hostname">Hostname</label>
							<input type="text" id="hostname" placeholder="e.g localhost" class="form-control" name="hostname" aria-describedby="hostnameHelp" autocomplete="false" />
							<small id="hostnameHelp" class="form-text text-muted">Enter Mysql hostname.</small>
						</div>
						
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" id="username" placeholder="e.g root" class="form-control" name="username" aria-describedby="usernameHelp" autocomplete="false"/>
							<small id="usernameHelp" class="form-text text-muted">Enter Mysql username.</small>
						</div>
						
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" id="password" placeholder="e.g *******" class="form-control" name="password" aria-describedby="passwordHelp" autocomplete="false"/>
							<small id="passwordHelp" class="form-text text-muted">Enter Mysql password.</small>
						</div>
						
						<div class="form-group">
							<label for="database">Database</label>
							<input type="text" id="database" placeholder="e.g classeventie_db" class="form-control" name="database" aria-describedby="databaseHelp" autocomplete="false"/>
							<small id="databaseHelp" class="form-text text-muted">Enter new database name.</small>
						</div>
						
			  			<button type="submit" id="submit" class="btn btn-primary">Install</button>

			  		</form>
					<?php } else { ?>
				    <br><br><br><br>
			      	<div class="alert alert-danger" role="alert"><strong>Please make application/config/production and application/config/development folder writable. </strong></div>
			      		<strong>Try</strong>:<br>
			      		<code>chmod -R 777 application/config/production</code><br>
			      		<code>chmod -R 777 application/config/development</code>
			      	
				  	<?php } ?>
				      
				</div>
				<div class="col-md-5 text-left">
					<br><br><br><br>
					<h5 class="text-center">Installation Instructions</h5>
					<ul>
						<li><strong><em>IMPORTANT!</em> Please read the installation instruction in README.md file.</strong></li>
				    	<li>Please dont create any database prior to installation. This will auto create the database. Just enter the database name you want.</li>
				    	<li>Please enable mod_rewrite for .htaccess to work.</li>
				    	<li>If right after installation, you see this -<br>
				    		<code>
				    		Error Number: 1146
							Table 'settings' doesn't exist
							</code>

							Then don't panic, just hit refresh.
						</li>
				    </ul>		
				</div>
			</div>

			<br><br><br><br><br><br>
	      	<footer class="mastfoot text-right">
				<div class="inner">
				<p>A Product By <a href="https://classiebit.com/">Classiebit</a></p>
				</div>
			</footer>

	    </div> <!-- /container -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
  </body>
</html>