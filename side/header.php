<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
	<title><?php
			if (isset($get_title)) {
				echo $get_title;
			}
			?></title>
	<link rel="icon" type="image/x-icon" href="public/stylesheets/new/assets/img/favicon.ico" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
	<link href="public/stylesheets/new/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="public/stylesheets/new/assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
	<link href="public/stylesheets/new/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.css" rel="stylesheet" type="text/css" />
	<link href="public/stylesheets/new/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
	<link href="public/stylesheets/new/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

	<link rel="stylesheet" href="public/stylesheets/style.css">

</head>

<body class="default-sidebar" style="background:#fff;">

	<!--  BEGIN NAVBAR  -->
	<header class="desktop-nav header navbar fixed-top" >
		<div class="nav-logo mr-5 ml-4 d-lg-inline-block d-none">
			<a href="index.php" class=""> 
				<img src="public/images/logo.png" class="img-fluid" alt="logo"></a>
		</div>
		

		<ul class="navbar-nav flex-row ml-lg-auto">
			<li class="nav-item mr-5 d-lg-block d-none">
				<form class="form-inline form-inline search animated-search" role="search">
					<i class="flaticon-search-1 d-lg-none d-block"></i>
					<input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Search...">
				</form>
			</li>

			<li class="nav-item dropdown user-profile-dropdown mr-5  d-lg-inline-block d-none">
				<a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

					<div class="media">
						<img src="public/stylesheets/new/assets/img/90x90.jpg" class="img-fluid mr-2" alt="admin-profile">
						<div class="media-body align-self-center">

							<?php $user_role = get_role(); ?>
							<?php
							if ($user_role == 'admin') { ?>
								<h6 class="mb-1">ArJ Admin</h6>
								<p class="mb-0">User Admin</p>

							<?php } else if ($user_role == 'petugas') { ?>
								<h6 class="mb-1">Petugas</h6>
								<p class="mb-0">Administrator</p>

							<?php } else { ?>
								<h6 class="mb-1">Login First</h6>
								<p class="mb-0">Click Here</p>

							<?php } ?>

						</div>
					</div>
				</a>
				<div class="dropdown-menu  position-absolute p-0" aria-labelledby="user-profile-dropdown">
					<div class="dropdown-item d-flex justify-content-around">
						<p class="mb-0 align-self-center">Your Account</p>
						<div class="">
							<i class="flaticon-star-outline"></i>
						</div>
					</div>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item d-flex" href="#">
						<i class="mr-3 flaticon-user-11"></i> <span class="align-self-center">Profile Setting</span>
					</a>


					<div class="dropdown-item dropdown-item-btn d-flex justify-content-around">
						<?php if (isset($_SESSION['user_id'])) : ?>
							<a href="pages/logreg/logout.php"> <i class="mr-2 flaticon-power-off"></i> <span class="align-self-center">Logout</span></a>
						<?php else : ?>
							<a href="pages/logreg/login.php"> <i class="mr-2 flaticon-power-off"></i> <span class="align-self-center">Login</span></a>
						<?php endif; ?>


					</div>
				</div>
			</li>





			<li class="nav-item dropdown cs-toggle ml-3 mr-lg-4">
				<?php if (isset($_SESSION['user_id'])) : ?>
					<a href="pages/logreg/logout.php"> <span class="icon flaticon-log-3"></span></a>
				<?php else : ?>
					<a href="pages/logreg/login.php"><span class="icon flaticon-log-3"></span></a>
				<?php endif; ?>


			</li>
		</ul>
	</header>
	<!--  END NAVBAR  -->

	<!--  BEGIN MAIN CONTAINER  -->
	<div class="main-container" id="container">
		<div class="overlay"></div>
		<div class="cs-overlay"></div>
		<div class="search-overlay"></div>

		<div class="topbar-nav header navbar fixed-top" role="banner">
			<div id="dismiss" class="d-lg-none text-right"><i class="flaticon-cancel-12 mr-3"></i></div>
			<nav id="topbar">
				<ul class="list-unstyled menu-categories d-lg-flex justify-content-lg-around mb-0" id="topAccordion">

					<?php $user_role = get_role(); ?>
					<?php if ($user_role == 'admin') : ?>
						<li class="menu">
							<a href="#user" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
								<div class="">
									<i class="flaticon-computer-6"></i>
									<span>User</span>
								</div>

								<div>
									<i class="flaticon-down-arrow"></i>
								</div>
							</a>
							<ul class="collapse submenu list-unstyled" id="user" data-parent="#topAccordion">
								<li>

									<ul class="collapse list-unstyled sub-submenu show" id="">
										<li class="active">
											<a href="pages/user/list-user.php"> List User </a>
										</li>
										<li>
											<a href="pages/user/tambah-user.php"> Tambah User </a>
										</li>

									</ul>
								</li>
							</ul>
						</li>

						<li class="menu">
							<a href="#kriteria" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
								<div class="">
									<i class="flaticon-computer-6"></i>
									<span>Kriteria</span>
								</div>

								<div>
									<i class="flaticon-down-arrow"></i>
								</div>
							</a>
							<ul class="collapse submenu list-unstyled" id="kriteria" data-parent="#topAccordion">
								<li>

									<ul class="collapse list-unstyled sub-submenu show" id="">
										<li class="active">
											<a href="pages/kriteria/list-kriteria.php"> List Kriteria </a>
										</li>
										<li>
											<a href="pages/kriteria/tambah-kriteria.php"> Tambah Kriteria </a>
										</li>


									</ul>
								</li>
							</ul>
						</li>

						
					<?php endif; ?>

					<?php if ($user_role == 'admin' || $user_role == 'petugas') : ?>
						<li class="menu">
							<a href="#pegawai" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
								<div class="">
									<i class="flaticon-computer-6"></i>
									<span>Pegawai</span>
								</div>

								<div>
									<i class="flaticon-down-arrow"></i>
								</div>
							</a>
							<ul class="collapse submenu list-unstyled" id="pegawai" data-parent="#topAccordion">
								<li>

									<ul class="collapse list-unstyled sub-submenu show" id="">
										<li class="active">
											<a href="pages/pegawai/list-pegawai.php"> List Pegawai </a>
										</li>
										<li>
											<a href="pages/pegawai/tambah-pegawai.php"> Tambah Pegawai </a>
										</li>

									</ul>
								</li>
							</ul>
						</li>
					<?php endif; ?>

					<li class="menu">
						<a href="#ranking" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
							<div class="">
								<i class="flaticon-computer-6"></i>
								<span>Ranking</span>
							</div>

							<div>
								<i class="flaticon-down-arrow"></i>
							</div>
						</a>
						<ul class="collapse submenu list-unstyled" id="ranking" data-parent="#topAccordion">
							<li>

								<ul class="collapse list-unstyled sub-submenu show" id="">
									<li class="active">
										<a href="pages/ranking/topsis.php"> Topsis </a>
									</li>
									<li>
										<a href="pages/ranking/saw.php"> SAW </a>
									</li>

								</ul>
							</li>
						</ul>
					</li>




				</ul>
			</nav>
		</div>


		<!--  BEGIN CONTENT PART  -->
		<div id="content" class="main-content">
			<div class="container">
				<div class="page-header">
					<div class="page-title">
						<h3>Dashboard</h3>
					</div>
				</div>
				<div id="main">




					<!--  END CONTROL SIDEBAR  -->

					<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
					<script src="public/stylesheets/new/assets/js/libs/jquery-3.1.1.min.js"></script>
					<script src="public/stylesheets/new/bootstrap/js/popper.min.js"></script>
					<script src="public/stylesheets/new/bootstrap/js/bootstrap.min.js"></script>
					<script src="public/stylesheets/new/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
					<script src="public/stylesheets/new/assets/js/app.js"></script>
					<script>
						$(document).ready(function() {
							App.init();
						});
					</script>
					<script src="public/stylesheets/new/assets/js/custom.js"></script>
					<!-- END GLOBAL MANDATORY SCRIPTS -->

					<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
					<script src="public/stylesheets/new/plugins/charts/chartist/chartist.js"></script>
					<script src="public/stylesheets/new/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.min.js"></script>
					<script src="public/stylesheets/new/plugins/maps/vector/jvector/worldmap_script/jquery-jvectormap-world-mill-en.js"></script>
					<script src="public/stylesheets/new/plugins/calendar/pignose/moment.latest.min.js"></script>
					<script src="public/stylesheets/new/plugins/calendar/pignose/pignose.calendar.js"></script>
					<script src="public/stylesheets/new/plugins/progressbar/progressbar.min.js"></script>
					<script src="public/stylesheets/new/assets/js/default-dashboard/default-custom.js"></script>
					<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
</body>

</html>