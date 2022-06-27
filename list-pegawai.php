<?php require_once('condb/include.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$judul_page = 'List pegawai';
require_once('side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('side/sidebar-pegawai.php'); ?>
	
		<div class="main-content the-content">
			
			<?php
			$status = isset($_GET['status']) ? $_GET['status'] : '';
			$msg = '';
			switch($status):
				case 'sukses-baru':
					$msg = 'Data pegawai baru berhasil ditambahkan';
					break;
				case 'sukses-hapus':
					$msg = 'pegawai behasil dihapus';
					break;
				case 'sukses-edit':
					$msg = 'pegawai behasil diedit';
					break;
			endswitch;
			
			if($msg):
				echo '<div class="msg-box msg-box-full">';
				echo '<p><span class="fa fa-bullhorn"></span> &nbsp; '.$msg.'</p>';
				echo '</div>';
			endif;
			?>
		
			<h1>List Pegawai</h1>
			
			<?php
			$qry = $pdo->prepare('SELECT * FROM pegawai');			
			$qry->execute();
			// menampilkan berupa nama field
			$qry->setFetchMode(PDO::FETCH_ASSOC);
			
			if($qry->rowCount() > 0):
			?>
			
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th>No. Pegawai</th>
						<th>Nama</th>
						<th>Detail</th>						
						<th>Edit</th>
						<th>Hapus</th>
					</tr>
				</thead>
				<tbody>
					<?php while($hasil = $qry->fetch()): ?>
						<tr>
							<td><?php echo $hasil['nomer']; ?></td>							
							<td><?php echo $hasil['nama']; ?></td>							
							<td><a href="single-pegawai.php?id=<?php echo $hasil['id_pegawai']; ?>"><span class="fa fa-eye"></span> Detail</a></td>
							<td><a href="Pedit.php?id=<?php echo $hasil['id_pegawai']; ?>"><span class="fa fa-pencil"></span> Edit</a></td>
							<td><a href="hapus-pegawai.php?id=<?php echo $hasil['id_pegawai']; ?>" class="red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
			
			
			<!-- STEP 1. Matriks Keputusan(X) ==================== -->
			<?php
			// Fetch semua kriteria
			$qry = $pdo->prepare('SELECT id_kriteria, nama, type, bobot FROM kriteria
				ORDER BY urutan_order ASC');
			$qry->execute();			
			$kriterias = $qry->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
			
			// Fetch semua pegawai
			$qry2 = $pdo->prepare('SELECT id_pegawai, nomer FROM pegawai');
			$qry2->execute();			
			$qry2->setFetchMode(PDO::FETCH_ASSOC);
			$pegawais = $qry2->fetchAll();			
			?>
			
			<h3>Matriks Keputusan (X)</h3>
			<table class="pure-table pure-table-striped">
				<thead>
					<tr class="super-top">
						<th rowspan="2" class="super-top-left">No. Pegawai</th>
						<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
					</tr>
					<tr>
						<?php foreach($kriterias as $kriteria ): ?>
							<th><?php echo $kriteria['nama']; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach($pegawais as $pegawai): ?>
						<tr>
							<td><?php echo $pegawai['nomer']; ?></td>
							<?php
							// Ambil Nilai
							$qry3 = $pdo->prepare('SELECT id_kriteria, nilai FROM nilai_pegawai
								WHERE id_pegawai = :id_pegawai');
							$qry3->execute(array(
								'id_pegawai' => $pegawai['id_pegawai']
							));			
							$qry3->setFetchMode(PDO::FETCH_ASSOC);
							$nilais = $qry3->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
							
							foreach($kriterias as $id_kriteria => $values):
								echo '<td>';
								if(isset($nilais[$id_kriteria])) {
									echo $nilais[$id_kriteria]['nilai'];
									$kriterias[$id_kriteria]['nilai'][$pegawai['id_pegawai']] = $nilais[$id_kriteria]['nilai'];
								} else {
									echo 0;
									$kriterias[$id_kriteria]['nilai'][$pegawai['id_pegawai']] = 0;
								}
								
								if(isset($kriterias[$id_kriteria]['tn_kuadrat'])){
									$kriterias[$id_kriteria]['tn_kuadrat'] += pow($kriterias[$id_kriteria]['nilai'][$pegawai['id_pegawai']], 2);
								} else {
									$kriterias[$id_kriteria]['tn_kuadrat'] = pow($kriterias[$id_kriteria]['nilai'][$pegawai['id_pegawai']], 2);
								}
								echo '</td>';
							endforeach;
							?>
							</pre>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<?php else: ?>
				<p>Maaf, belum ada data untuk pegawai.</p>
			<?php endif; ?>
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->

<?php
require_once('side/footer.php');