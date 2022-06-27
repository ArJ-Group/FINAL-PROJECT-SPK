<!DOCTYPE html>

<head>
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
	<meta charset="UTF-8" />
	<title><?php
			if (isset($get_title)) {
				echo $get_title;
			}
			?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
	<link rel="stylesheet" href="public/stylesheets/style.css">
	<script type="text/javascript" src="public/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="public/js/superfish.min.js"></script>
	<script type="text/javascript" src="public/js/main.js"></script>
</head>

<body>
	<div id="page">

		<header id="header">
			<div class="container clearfix">
				<div id="logo-wrap">
					<a href="index.php">


						<h1 class="button">Assesment Employee - TOPSIS & SAW</h1>
					</a>
				</div>

				<div id="header-content" class="clearfix">
					<nav id="nav">
						<ul class="sf-menu">
							<?php $user_role = get_role(); ?>
							<?php if ($user_role == 'admin') : ?>
								<li><a href="pages/user/list-user.php">User</a>
									<ul>
										<li><a href="pages/user/list-user.php">List User</a></li>
										<li><a href="pages/user/tambah-user.php">Tambah User</a></li>
									</ul>
								</li>
								<li><a href="pages/kriteria/list-kriteria.php">Kriteria</a>
									<ul>
										<li><a href="pages/kriteria/list-kriteria.php">List Kriteria</a></li>
										<li><a href="pages/kriteria/tambah-kriteria.php">Tambah Kriteria</a></li>
									</ul>
								</li>
							<?php endif; ?>
							<?php if ($user_role == 'admin' || $user_role == 'petugas') : ?>
								<li><a href="pages/pegawai/list-pegawai.php">Pegawai</a>
									<ul>
										<li><a href="list-pegawai.php">List Pegawai</a></li>
										<li><a href="tambah-pegawai.php">Tambah Pegawai</a></li>
									</ul>
								</li>
							<?php endif; ?>
							<li><a href="pages/ranking/ranking-topsis.php">Ranking</a>
								<ul>
									<li><a href="pages/ranking/ranking-topsis.php">Topsis</a></li>
									<li><a href="pages/ranking/ranking-saw.php">SAW</a></li>
								</ul>
							</li>
						</ul>
					</nav>

					<div id="header-right">
						<?php if (isset($_SESSION['user_id'])) : ?>
							<a href="pages/logreg/logout.php" class="button">Log Out</a>
						<?php else : ?>
							<a href="pages/logreg/login.php" class="button">Log In</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</header>

		<div id="main">