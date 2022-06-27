<?php require_once('condb/include.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$errors = array();
$sukses = false;

$nomer = (isset($_POST['nomer'])) ? trim($_POST['nomer']) : '';
$nama = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();


if(isset($_POST['submit'])):	
	
	// Validasi
	if(!$nomer) {
		$errors[] = 'Nomor Pegawai tidak boleh kosong';
	}	
	
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$handle = $pdo->prepare('INSERT INTO pegawai (nomer, nama, tanggal_input) VALUES (:nomer, :nama, :tanggal_input)');
		$handle->execute( array(
			'nomer' => $nomer,
			'nama' => $nama,
			'tanggal_input' => date('Y-m-d')
		) );
		$sukses = "pegawai no. <strong>{$nomer}</strong> berhasil dimasukkan.";
		$id_pegawai = $pdo->lastInsertId();
		
		// Jika ada kriteria yang diinputkan:
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_pegawai (id_pegawai, id_kriteria, nilai) VALUES (:id_pegawai, :id_kriteria, :nilai)');
				$handle->execute( array(
					'id_pegawai' => $id_pegawai,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;
		
		redirect_to('list-pegawai.php?status=sukses-baru');		
		
	endif;

endif;
?>

<?php
$get_title = 'Tambah pegawai';
require_once('side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('side/sidebar-pegawai.php'); ?>
	
		<div class="main-content the-content">
			<h1>Tambah Pegawai</h1>
			
			<?php if(!empty($errors)): ?>
			
				<div class="msg-box warning-box">
					<p><strong>Error:</strong></p>
					<ul>
						<?php foreach($errors as $error): ?>
							<li><?php echo $error; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				
			<?php endif; ?>			
			
			
				<form action="tambah-pegawai.php" method="post">
					<div class="field-wrap clearfix">					
						<label>Nomor ID <span class="red">*</span></label>
						<input type="text" name="nomer" value="<?php echo $nomer; ?>">
					</div>					
					<div class="field-wrap clearfix">					
						<label>Nama Pegawai</label>
						<textarea name="nama" cols="30" rows="2"><?php echo $nama; ?></textarea>
					</div>			
					
					<h3>Nilai Kriteria</h3>
					<?php
					$qry = $pdo->prepare('SELECT id_kriteria, nama, ada_pilihan FROM kriteria ORDER BY urutan_order ASC');			
					$qry->execute();
					// menampilkan berupa nama field
					$qry->setFetchMode(PDO::FETCH_ASSOC);
					
					if($qry->rowCount() > 0):
					
						while($kriteria = $qry->fetch()):							
						?>
						
							<div class="field-wrap clearfix">					
								<label><?php echo $kriteria['nama']; ?></label>
								<?php if(!$kriteria['ada_pilihan']): ?>
									<input type="number" step="0.001" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">								
								<?php else: ?>
									
									<select name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">
										<option value="0">-- Pilih Variabel --</option>
										<?php
										$qry3 = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
										$qry3->execute(array(
											'id_kriteria' => $kriteria['id_kriteria']
										));
										// menampilkan berupa nama field
										$qry3->setFetchMode(PDO::FETCH_ASSOC);
										if($qry3->rowCount() > 0): while($hasl = $qry3->fetch()):
										?>
											<option value="<?php echo $hasl['nilai']; ?>"><?php echo $hasl['nama']; ?></option>
										<?php
										endwhile; endif;
										?>
									</select>
									
								<?php endif; ?>
							</div>	
						
						<?php
						endwhile;
						
					else:					
						echo '<p>Kriteria masih kosong.</p>';						
					endif;
					?>
					
					<div class="field-wrap clearfix">
						<button type="submit" name="submit" value="submit" class="button">Tambah Pegawai</button>
					</div>
				</form>
					
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('side/footer.php');