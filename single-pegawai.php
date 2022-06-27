<?php require_once('condb/include.php'); ?>

<?php
$ada_error = false;
$result = '';

$id_pegawai = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_pegawai) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$qry = $pdo->prepare('SELECT * FROM pegawai WHERE id_pegawai = :id_pegawai');
	$qry->execute(array('id_pegawai' => $id_pegawai));
	$result = $qry->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	}
}
?>

<?php
$get_title = 'Detail pegawai';
require_once('side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('side/sidebar-pegawai.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $get_title; ?></h1>
			
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
				$qry2 = $pdo->prepare('SELECT nilai_pegawai.nilai AS nilai, kriteria.nama AS nama FROM kriteria 
				LEFT JOIN nilai_pegawai ON nilai_pegawai.id_kriteria = kriteria.id_kriteria 
				AND nilai_pegawai.id_pegawai = :id_pegawai ORDER BY kriteria.urutan_order ASC');
				$qry2->execute(array(
					'id_pegawai' => $id_pegawai
				));
				$qry2->setFetchMode(PDO::FETCH_ASSOC);
				$sub_crite = $qry2->fetchAll();
				if(!empty($sub_crite)):
				?>
					<h3>Nilai Kriteria</h3>
					<table class="pure-table">
						<thead>
							<tr>
								<?php foreach($sub_crite as $kriteria ): ?>
									<th><?php echo $kriteria['nama']; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach($sub_crite as $kriteria ): ?>
									<th><?php echo ($kriteria['nilai']) ? $kriteria['nilai'] : 0; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				<?php
				endif;
				?>

				<p><a href="Pedit.php?id=<?php echo $id_pegawai; ?>" class="button"><span class="fa fa-pencil"></span> Edit</a> &nbsp; <a href="hapus-pegawai.php?id=<?php echo $id_pegawai; ?>" class="button button-red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></p>
			
			<?php endif; ?>			
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('side/footer.php');