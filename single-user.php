<?php require_once('condb/include.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_user = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_user) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$qry = $pdo->prepare('SELECT id_user, username, nama, email, alamat, role FROM user WHERE user.id_user = :id_user');
	$qry->execute(array('id_user' => $id_user));
	$result = $qry->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	}
}
?>

<?php
$get_title = 'Detail User';
require_once('side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('side/sidebar-user.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $get_title; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>
				
			<?php elseif(!empty($result)): ?>
			
				<h4>Username</h4>
				<p><?php echo $result['username']; ?></p>
				
				<h4>Nama</h4>
				<p><?php echo $result['nama']; ?></p>
				
				<h4>Email</h4>
				<p><?php echo $result['email']; ?></p>
				
				<h4>Alamat</h4>
				<p><?php echo $result['alamat']; ?></p>
				
				<h4>Role</h4>
				<p><?php
				if($result['role'] == 1) {
					echo 'Administrator';
				} elseif($result['role'] == 2) {
					echo 'Petugas';
				}
				?></p>
			
			<?php endif; ?>
			
			<p><a href="Uedit.php?id=<?php echo $id_user; ?>" class="button"><span class="fa fa-pencil"></span> Edit</a> &nbsp; <a href="hapus-user.php?id=<?php echo $id_user; ?>" class="button button-red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></p>
			
			
		</div>
	
	</div>
	</div>


<?php
require_once('side/footer.php');