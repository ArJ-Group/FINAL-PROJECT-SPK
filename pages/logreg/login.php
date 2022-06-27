<?php require_once('../condb/include.php'); ?>

<?php
$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['username']) ? trim($_POST['password']) : '';

if (isset($_POST['submit'])) :

	// Validasi
	if (!$username) {
		$errors[] = 'Username tidak boleh kosong';
	}
	if (!$password) {
		$errors[] = 'Password tidak boleh kosong';
	}

	if (empty($errors)) :

		$query = $pdo->prepare('SELECT * FROM user WHERE username = :username');
		$query->execute(array(
			'username' => $username
		));
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$user = $query->fetch();

		if ($user) {
			$hashed_password = sha1($password);
			if ($user['password'] === $hashed_password) {
				$_SESSION["user_id"] = $user["id_user"];
				$_SESSION["username"] = $user["username"];
				$_SESSION["role"] = $user["role"];
				redirect_to("dashboard.php?status=sukses-login");
			} else {
				$errors[] = 'Maaf, anda salah memasukkan username / password';
			}
		} else {
			$errors[] = 'Maaf, anda salah memasukkan username / password';
		}

	endif;

endif;
?>

<?php
$judul_page = 'Log in';
require_once('../../side/header.php');
?>

<head>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
	<link href="stylesheets/new/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="stylesheets/new/assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
	<link href="stylesheets/new/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.css" rel="stylesheet" type="text/css" />
	<link href="stylesheets/new/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
	<link href="stylesheets/new/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
</head>
<div class="main-content-row">
	<div class="container clearfix">


		<div class="main-content the-content">
			<h1>Log in</h1>

			<?php if (!empty($errors)) : ?>

				<div class="msg-box warning-box">
					<p><strong>Error:</strong></p>
					<ul>
						<?php foreach ($errors as $error) : ?>
							<li><?php echo $error; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>

			<?php endif; ?>

			<form action="login.php" method="post">
				<div class="field-wrap clearfix">
					<label>Username:</label>
					<input type="text" name="username" value="<?php echo htmlentities($username); ?>">
				</div>
				<div class="field-wrap clearfix">
					<label>Password:</label>
					<input type="password" name="password">
				</div>
				<div class="field-wrap clearfix">
					<button type="submit" name="submit" value="submit" class="button">Log in</button>
				</div>
			</form>
		</div>

	</div><!-- .container -->
</div><!-- .main-content-row -->

<?php
require_once('../../side/footer.php');
