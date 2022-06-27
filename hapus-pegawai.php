<?php require_once('condb/include.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$ada_error = false;
$result = '';

$id_pegawai = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_pegawai) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT id_pegawai FROM pegawai WHERE id_pegawai = :id_pegawai');
	$query->execute(array('id_pegawai' => $id_pegawai));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		
		$handle = $pdo->prepare('DELETE FROM nilai_pegawai WHERE id_pegawai = :id_pegawai');				
		$handle->execute(array(
			'id_pegawai' => $result['id_pegawai']
		));
		$handle = $pdo->prepare('DELETE FROM pegawai WHERE id_pegawai = :id_pegawai');				
		$handle->execute(array(
			'id_pegawai' => $result['id_pegawai']
		));
		redirect_to('list-pegawai.php?status=sukses-hapus');
		
	}
}
?>

<?php
$judul_page = 'Hapus pegawai';
require_once('side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('side/sidebar-pegawai.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $judul_page; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>	
			
			<?php endif; ?>
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('side/footer.php');