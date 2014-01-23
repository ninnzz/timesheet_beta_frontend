<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Time Sheet :: Admin</title>
		<meta name="author" content="ninz" />
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/icons.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script type="text/javascript">
			(function(root){
				try{
					c=root.cookie.split(';');
					access_token = (c[0].split('='))[1];
					user_id = (c[1].split('='))[1];
					type = (c[3].split('='))[1];
					type == 'user' && (window.location = '/');
				} catch(err){
					window.location = "/timesheet/login.html?msg=Login to continue";
				}
			})(document);
		</script>
		<script src="js/modernizr.custom.js"></script>
	</head>
	<body>
		<div class="container">
			<!-- Push Wrapper -->
			<div class="mp-pusher" id="mp-pusher">
				<!-- mp-menu -->
				<nav id="mp-menu" class="mp-menu">
					<div class="mp-level">
						<h2 class="icon icon-world">All Categories</h2>
						<ul>
							<li class="icon icon-arrow-left">
								<a class="icon icon-display" href="#">Clients</a>
								<div class="mp-level">
									<h2 class="icon icon-display">Clients</h2>
									<a class="mp-back" href="#">back</a>
									<ul style='height:600px;overflow-y:auto;'>
										<li><a href="#">Client 1</a></li>
										<li><a href="#">Client 2</a></li>
										<li><a href="#">Client 3</a></li>
										<li><a href="#">Client 4</a></li>
										<li><a href="#">Client 1</a></li>
										<li><a href="#">Client 3</a></li>
										<li><a href="#">Client 4</a></li>
									</ul>
								</div>
							</li>
							<li class="icon icon-arrow-left">
								<a class="icon icon-news" href="#">Projects</a>
								<div class="mp-level">
									<h2 class="icon icon-news">Ongoing Projects</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li><a href="#">National Geographic</a></li>
										<li><a href="#">Scientific American</a></li>
										<li><a href="#">The Spectator</a></li>
										<li><a href="#">The Rambler</a></li>
										<li><a href="#">Physics World</a></li>
										<li><a href="#">The New Scientist</a></li>
									</ul>
								</div>
							</li>
							<li class="icon icon-arrow-left">
								<a class="icon icon-shop" href="#">Employees</a>
								<div class="mp-level">
									<h2 class="icon icon-shop">Employees</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li><a href="#">Ninz</a></li>
										<li><a href="#">Joy</a></li>
										<li><a href="#">Jerico dela Cruz</a></li>
										<li><a href="#">Nina</a></li>
										<li><a href="#">Harold</a></li>
										<li><a href="#">Mark</a></li>
									</ul>
								</div>
							</li>
							<li><a class="icon icon-photo" href="#">Settings</a></li>
							<li><a class="icon icon-wallet" href="#" onclick='logout()'>Logout</a></li>
						</ul>
							
					</div>
				</nav>
				<!-- /mp-menu -->

				<div class="scroller"><!-- this is for emulating position fixed of the nav -->
					<div class="scroller-inner">
						<!-- Top Navigation -->
						<div class="codrops-top clearfix">
							<span><a href="#" id="trigger">Here</a></span>
							
						</div>
						<header class="codrops-header">
							<h1>Stratpoint Time Sheet<span> Administrator Portal</span></h1>
						</header>
						<div class="content">
							<div class="block block-100">
								<span class='right'>Download | Reports </span>
							</div>
							<div class="block block-40">
							
							</div>
							<div class="block block-60">
							</div>
							
						</div>
					</div><!-- /scroller-inner -->
				</div><!-- /scroller -->

			</div><!-- /pusher -->
		</div><!-- /container -->
		<div id='loading'>
			<h6>Loading...</h6>
		</div>
		<script src="js/classie.js"></script>
		<script src="js/mlpushmenu.js"></script>
		<script src="../js/jquery-1.7.1.min.js"></script>
		<script src="../js/routes.js"></script>
		<script>

			new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ) );

			function get_projects()
			{

			}

			function get_users()
			{

			}

			function get_clients()
			{
				
			}

			function c_d(d)
			{
			    document.cookie = 'access_token' + "=deleted; expires=" + new Date(0).toUTCString()+';path=/timesheet';
			    document.cookie = 'user_id' + "=deleted; expires=" + new Date(0).toUTCString()+';path=/timesheet';
			    document.cookie = 'login' + "=deleted; expires=" + new Date(0).toUTCString()+';path=/timesheet';
			    document.cookie = 'login_type' + "=deleted; expires=" + new Date(0).toUTCString()+';path=/timesheet';
			    window.location = '/timesheet/login.html';
			}

			function logout()
			{
				document.getElementById('loading').style.display = 'block';
				router.setMethod('post');
				router.setTargetUrl('/users/logout');
				router.setParams({access_token:access_token,user_id:user_id});
				events.setCurrentEvent('c_d(data)');
				events.setErrorEvent('console.log(data)');
				router.connect();
			}
		</script>
	</body>
</html>