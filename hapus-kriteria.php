<?php require_once('../condb/include.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_kriteria = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_kriteria) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$qry = $pdo->prepare('SELECT id_kriteria FROM kriteria WHERE kriteria.id_kriteria = :id_kriteria');
	$qry->execute(array('id_kriteria' => $id_kriteria));
	$result = $qry->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		$handle = $pdo->prepare('DELETE FROM nilai_pegawai WHERE id_kriteria = :id_kriteria');				
		$handle->execute(array(
			'id_kriteria' => $result['id_kriteria']
		));
		$handle = $pdo->prepare('DELETE FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria');				
		$handle->execute(array(
			'id_kriteria' => $result['id_kriteria']
		));
		$handle = $pdo->prepare('DELETE FROM kriteria WHERE id_kriteria = :id_kriteria');				
		$asem = $handle->execute(array(
			'id_kriteria' => $result['id_kriteria']
		));
		redirect_to('list-kriteria.php?status=sukses-hapus');
	}
}
?>

<?php
$get_title = 'Hapus Kriteria';
require_once('side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('side/sidebar-kriteria.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $get_title; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>
			
			<?php endif; ?>
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('side/footer.php');