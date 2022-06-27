<?php require_once('../../condb/include.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$errors = array();
$sukses = false;

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

	$id_pegawai = (isset($result['id_pegawai'])) ? trim($result['id_pegawai']) : '';
	$nomer = (isset($result['nomer'])) ? trim($result['nomer']) : '';
	$nama = (isset($result['nama'])) ? trim($result['nama']) : '';
	$tanggal_input = (isset($result['tanggal_input'])) ? trim($result['tanggal_input']) : '';
}

if(isset($_POST['submit'])):	
	
	$nomer = (isset($_POST['nomer'])) ? trim($_POST['nomer']) : '';
	$nama = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
	$tanggal_input = (isset($_POST['tanggal_input'])) ? trim($_POST['tanggal_input']) : '';
	$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();
	
	// Validasi ID pegawai
	if(!$id_pegawai) {
		$errors[] = 'ID pegawai tidak ada';
	}
	// Validasi
	if(!$nomer) {
		$errors[] = 'Nomor pegawai tidak boleh kosong';
	}
	if(!$tanggal_input) {
		$errors[] = 'Tanggal input tidak boleh kosong';
	}
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$prepare_query = 'UPDATE pegawai SET nomer = :nomer, nama = :nama, tanggal_input = :tanggal_input WHERE id_pegawai = :id_pegawai';
		$data = array(
			'nomer' => $nomer,
			'nama' => $nama,
			'tanggal_input' => $tanggal_input,
			'id_pegawai' => $id_pegawai,
		);		
		$handle = $pdo->prepare($prepare_query);		
		$sukses = $handle->execute($data);
		
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_pegawai (id_pegawai, id_kriteria, nilai) 
				VALUES (:id_pegawai, :id_kriteria, :nilai)
				ON DUPLICATE KEY UPDATE nilai = :nilai');
				$handle->execute( array(
					'id_pegawai' => $id_pegawai,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;
		
		redirect_to('list-pegawai.php?status=sukses-edit');
	
	endif;

endif;
?>

<?php
$judul_page = 'Edit pegawai';
require_once('../../side/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('../../side/sidebar-pegawai.php'); ?>
	
		<div class="main-content the-content">
			<h1>Edit Pegawai</h1>
			
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
			
			<?php if($sukses): ?>
			
				<div class="msg-box">
					<p>Data berhasil disimpan</p>
				</div>	
				
			<?php elseif($ada_error): ?>
				
				<p><?php echo $ada_error; ?></p>
			
			<?php else: ?>				
				
				<form action="edit-pegawai.php?id=<?php echo $id_pegawai; ?>" method="post">
					<div class="field-wrap clearfix">					
						<label>Nomor ID <span class="red">*</span></label>
						<input type="text" name="nomer" value="<?php echo $nomer; ?>">
					</div>					
					<div class="field-wrap clearfix">					
						<label> Nama </label>
						<textarea name="nama" cols="30" rows="2"><?php echo $nama; ?></textarea>
					</div>
					<div class="field-wrap clearfix">					
						<label>Tanggal Input <span class="red">*</span></label>
						<input type="text" name="tanggal_input" value="<?php echo $tanggal_input; ?>" class="datepicker">
					</div>	
					
					<h3>Nilai Kriteria</h3>
					<?php
					$query2 = $pdo->prepare('SELECT nilai_pegawai.nilai AS nilai, kriteria.nama AS nama, kriteria.id_kriteria AS id_kriteria, kriteria.ada_pilihan AS jenis_nilai 
					FROM kriteria LEFT JOIN nilai_pegawai 
					ON nilai_pegawai.id_kriteria = kriteria.id_kriteria 
					AND nilai_pegawai.id_pegawai = :id_pegawai 
					ORDER BY kriteria.urutan_order ASC');
					$query2->execute(array(
						'id_pegawai' => $id_pegawai
					));
					$query2->setFetchMode(PDO::FETCH_ASSOC);
					
					if($query2->rowCount() > 0):
					
						while($kriteria = $query2->fetch()):
						?>
							<div class="field-wrap clearfix">					
								<label><?php echo $kriteria['nama']; ?></label>
								<?php if(!$kriteria['jenis_nilai']): ?>
									<input type="number" step="0.001" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]" value="<?php echo ($kriteria['nilai']) ? $kriteria['nilai'] : 0; ?>">								
								<?php else: ?>
									<select name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">
										<option value="0">-- Pilih Variabel --</option>
										<?php
										$query3 = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
										$query3->execute(array(
											'id_kriteria' => $kriteria['id_kriteria']
										));
										// menampilkan berupa nama field
										$query3->setFetchMode(PDO::FETCH_ASSOC);
										if($query3->rowCount() > 0): while($hasl = $query3->fetch()):
										?>
											<option value="<?php echo $hasl['nilai']; ?>" <?php selected($kriteria['nilai'], $hasl['nilai']); ?>><?php echo $hasl['nama']; ?></option>
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
						<button type="submit" name="submit" value="submit" class="button">Simpan Pegawai</button>
					</div>
				</form>
				
			<?php endif; ?>			
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('../../side/footer.php');