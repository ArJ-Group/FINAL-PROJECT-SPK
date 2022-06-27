<?php require_once('includes/include.php'); ?>

<?php
$ada_error = false;
$result = '';

$id_pegawai = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_pegawai) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT * FROM pegawai WHERE id_pegawai = :id_pegawai');
	$query->execute(array('id_pegawai' => $id_pegawai));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	}
}
?>

<?php
$judul_page = 'Detail pegawai';
require_once('side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('side/sidebar-pegawai.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $judul_page; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>
				
			<?php elseif(!empty($result)): ?>
			
				<h4>Nomor Pegawai</h4>
				<p><?php echo $result['nomer']; ?></p>
				
				<h4>Nama Pegawai</h4>
				<p><?php echo nl2br($result['nama']); ?></p>
				
				<h4>Tanggal Input</h4>
				<p><?php
					$tgl = strtotime($result['tanggal_input']);
					echo date('j F Y', $tgl);
				?></p>
				
				<?php
				$query2 = $pdo->prepare('SELECT nilai_pegawai.nilai AS nilai, kriteria.nama AS nama FROM kriteria 
				LEFT JOIN nilai_pegawai ON nilai_pegawai.id_kriteria = kriteria.id_kriteria 
				AND nilai_pegawai.id_pegawai = :id_pegawai ORDER BY kriteria.urutan_order ASC');
				$query2->execute(array(
					'id_pegawai' => $id_pegawai
				));
				$query2->setFetchMode(PDO::FETCH_ASSOC);
				$kriterias = $query2->fetchAll();
				if(!empty($kriterias)):
				?>
					<h3>Nilai Kriteria</h3>
					<table class="pure-table">
						<thead>
							<tr>
								<?php foreach($kriterias as $kriteria ): ?>
									<th><?php echo $kriteria['nama']; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach($kriterias as $kriteria ): ?>
									<th><?php echo ($kriteria['nilai']) ? $kriteria['nilai'] : 0; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				<?php
				endif;
				?>

				<p><a href="edit-pegawai.php?id=<?php echo $id_pegawai; ?>" class="button"><span class="fa fa-pencil"></span> Edit</a> &nbsp; <a href="hapus-pegawai.php?id=<?php echo $id_pegawai; ?>" class="button button-red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></p>
			
			<?php endif; ?>			
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('side/footer.php');