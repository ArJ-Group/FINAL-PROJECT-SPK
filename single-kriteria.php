<?php require_once('../condb/include.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_kriteria = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_kriteria) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$qry = $pdo->prepare('SELECT * FROM kriteria WHERE kriteria.id_kriteria = :id_kriteria');
	$qry->execute(array('id_kriteria' => $id_kriteria));
	$result = $qry->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	}
}
?>

<?php
$get_title = 'Detail Kriteria';
require_once('side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('side/sidebar-kriteria.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $get_title; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>
				
			<?php elseif(!empty($result)): ?>
			
				<h4>Nama Kriteria</h4>
				<p><?php echo $result['nama']; ?></p>
				
				<h4>Type Kriteria</h4>
				<p><?php
				if($result['type'] == 'benefit') {
					echo 'Benefit (keuntungan)';
				} elseif($result['type'] == 'cost') {
					echo 'Cost (kerugian)';
				}
				?></p>
				
				<h4>Bobot Kriteria</h4>
				<p><?php echo $result['bobot']; ?></p>
				
				<h4>Urutan Order</h4>
				<p><?php echo $result['urutan_order']; ?></p>

				<h4>Cara Penilaian</h4>
				
				<p><?php
				if($result['ada_pilihan'] == 1) {
					echo 'Menggunakan Pilihan Variabel';
				} else {
					echo 'Inputan Langsung';
				}				
				?></p>
				
				<?php if($result['ada_pilihan'] == 1): ?>
					<h4>Pilihan Variabel</h4>
						<table id="pilihan-var" class="pure-table pure-table-striped">
							<thead>
								<tr>
									<th>Nama Variabel</th>
									<th>Nilai</th>					
								</tr>
							</thead>
							<tbody>
								
								<?php
								$qry = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
								$qry->execute(array(
									'id_kriteria' => $result['id_kriteria']
								));
								// menampilkan berupa nama field
								$qry->setFetchMode(PDO::FETCH_ASSOC);
								if($qry->rowCount() > 0): while($hasile = $qry->fetch()):
								?>								
									<tr>
										<td><?php echo $hasile['nama']; ?></td>							
										<td><?php echo $hasile['nilai']; ?></td>
									</tr>
								<?php endwhile; endif;?>
								
							</tbody>
						</table>
				<?php endif; ?>
				
				<p><a href="Cedit.php?id=<?php echo $id_kriteria; ?>" class="button"><span class="fa fa-pencil"></span> Edit</a> &nbsp; <a href="hapus-kriteria.php?id=<?php echo $id_kriteria; ?>" class="button button-red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></p>
			
			
			<?php endif; ?>
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('side/footer.php');