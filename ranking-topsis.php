<?php
/* ---------------------------------------------
 * SPK TOPSIS
 * Author: Zunan Arif Rahmanto - 15111131
 * ------------------------------------------- */

/* ---------------------------------------------
 * Konek ke database & load fungsi-fungsi
 * ------------------------------------------- */
require_once('includes/init.php');

/* ---------------------------------------------
 * Load Header
 * ------------------------------------------- */
$judul_page = 'Perankingan Menggunakan Metode TOPSIS';
require_once('template-parts/header.php');

/* ---------------------------------------------
 * Set jumlah digit di belakang koma
 * ------------------------------------------- */
$digit = 4;

/* ---------------------------------------------
 * Fetch semua kriteria
 * ------------------------------------------- */
$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria ORDER BY urutan_order ASC');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $query->fetchAll();

/* ---------------------------------------------
 * Fetch semua pegawai (alternatif)
 * ------------------------------------------- */
$query2 = $pdo->prepare('SELECT id_pegawai, nomer FROM pegawai');
$query2->execute();			
$query2->setFetchMode(PDO::FETCH_ASSOC);
$pegawais = $query2->fetchAll();


/* >>> STEP 1 ===================================
 * Matrix Keputusan (X)
 * ------------------------------------------- */
$matriks_x = array();
foreach($kriterias as $kriteria):
	foreach($pegawais as $pegawai):
		
		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		// Fetch nilai dari db
		$query3 = $pdo->prepare('SELECT nilai FROM nilai_pegawai
			WHERE id_pegawai = :id_pegawai AND id_kriteria = :id_kriteria');
		$query3->execute(array(
			'id_pegawai' => $id_pegawai,
			'id_kriteria' => $id_kriteria,
		));			
		$query3->setFetchMode(PDO::FETCH_ASSOC);
		if($nilai_pegawai = $query3->fetch()) {
			// Jika ada nilai kriterianya
			$matriks_x[$id_kriteria][$id_pegawai] = $nilai_pegawai['nilai'];
		} else {			
			$matriks_x[$id_kriteria][$id_pegawai] = 0;
		}

	endforeach;
endforeach;

/* >>> STEP 3 ===================================
 * Matriks Ternormalisasi (R)
 * ------------------------------------------- */
$matriks_r = array();
foreach($matriks_x as $id_kriteria => $nilai_pegawais):
	
	// Mencari akar dari penjumlahan kuadrat
	$jumlah_kuadrat = 0;
	foreach($nilai_pegawais as $nilai_pegawai):
		$jumlah_kuadrat += pow($nilai_pegawai, 2);
	endforeach;
	$akar_kuadrat = sqrt($jumlah_kuadrat);
	
	// Mencari hasil bagi akar kuadrat
	// Lalu dimasukkan ke array $matriks_r
	foreach($nilai_pegawais as $id_pegawai => $nilai_pegawai):
		$matriks_r[$id_kriteria][$id_pegawai] = $nilai_pegawai / $akar_kuadrat;
	endforeach;
	
endforeach;


/* >>> STEP 4 ===================================
 * Matriks Y
 * ------------------------------------------- */
$matriks_y = array();
foreach($kriterias as $kriteria):
	foreach($pegawais as $pegawai):
		
		$bobot = $kriteria['bobot'];
		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $matriks_r[$id_kriteria][$id_pegawai];
		$matriks_y[$id_kriteria][$id_pegawai] = $bobot * $nilai_r;

	endforeach;
endforeach;


/* >>> STEP 5 ================================
 * Solusi Ideal Positif & Negarif
 * ------------------------------------------- */
$solusi_ideal_positif = array();
$solusi_ideal_negatif = array();
foreach($kriterias as $kriteria):

	$id_kriteria = $kriteria['id_kriteria'];
	$type_kriteria = $kriteria['type'];
	
	$nilai_max = max($matriks_y[$id_kriteria]);
	$nilai_min = min($matriks_y[$id_kriteria]);
	
	if($type_kriteria == 'benefit'):
		$s_i_p = $nilai_max;
		$s_i_n = $nilai_min;
	elseif($type_kriteria == 'cost'):
		$s_i_p = $nilai_min;
		$s_i_n = $nilai_max;
	endif;
	
	$solusi_ideal_positif[$id_kriteria] = $s_i_p;
	$solusi_ideal_negatif[$id_kriteria] = $s_i_n;

endforeach;


/* >>> STEP 6 ================================
 * Jarak Ideal Positif & Negatif
 * ------------------------------------------- */
$jarak_ideal_positif = array();
$jarak_ideal_negatif = array();
foreach($pegawais as $pegawai):

	$id_pegawai = $pegawai['id_pegawai'];		
	$jumlah_kuadrat_jip = 0;
	$jumlah_kuadrat_jin = 0;
	
	// Mencari penjumlahan kuadrat
	foreach($matriks_y as $id_kriteria => $nilai_pegawais):
		
		$hsl_pengurangan_jip = $nilai_pegawais[$id_pegawai] - $solusi_ideal_positif[$id_kriteria];
		$hsl_pengurangan_jin = $nilai_pegawais[$id_pegawai] - $solusi_ideal_negatif[$id_kriteria];
		
		$jumlah_kuadrat_jip += pow($hsl_pengurangan_jip, 2);
		$jumlah_kuadrat_jin += pow($hsl_pengurangan_jin, 2);
	
	endforeach;
	
	// Mengakarkan hasil penjumlahan kuadrat
	$akar_kuadrat_jip = sqrt($jumlah_kuadrat_jip);
	$akar_kuadrat_jin = sqrt($jumlah_kuadrat_jin);
	
	// Memasukkan ke array matriks jip & jin
	$jarak_ideal_positif[$id_pegawai] = $akar_kuadrat_jip;
	$jarak_ideal_negatif[$id_pegawai] = $akar_kuadrat_jin;
	
endforeach;


/* >>> STEP 7 ================================
 * Perangkingan
 * ------------------------------------------- */
$ranks = array();
foreach($pegawais as $pegawai):

	$s_negatif = $jarak_ideal_negatif[$pegawai['id_pegawai']];
	$s_positif = $jarak_ideal_positif[$pegawai['id_pegawai']];	
	
	$nilai_v = $s_negatif / ($s_positif + $s_negatif);
	
	$ranks[$pegawai['id_pegawai']]['id_pegawai'] = $pegawai['id_pegawai'];
	$ranks[$pegawai['id_pegawai']]['nomer'] = $pegawai['nomer'];
	$ranks[$pegawai['id_pegawai']]['nilai'] = $nilai_v;
	
endforeach;
 
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

	<div class="main-content main-content-full the-content">
		
		<h1><?php echo $judul_page; ?></h1>
		
		<!-- STEP 1. Matriks Keputusan(X) ==================== -->		
		<h3>Step 1: Matriks Keputusan (X)</h3>
		<table class="pure-table pure-table-striped">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">No. pegawai</th>
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
						foreach($kriterias as $kriteria):
							$id_pegawai = $pegawai['id_pegawai'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $matriks_x[$id_kriteria][$id_pegawai];
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- STEP 2. Bobot Preferensi (W) ==================== -->
		<h3>Step 2: Bobot Preferensi (W)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr>
					<th>Nama Kriteria</th>
					<th>Type</th>
					<th>Bobot (W)</th>						
				</tr>
			</thead>
			<tbody>
				<?php foreach($kriterias as $hasil): ?>
					<tr>
						<td><?php echo $hasil['nama']; ?></td>
						<td>
						<?php
						if($hasil['type'] == 'benefit') {
							echo 'Benefit';
						} elseif($hasil['type'] == 'cost') {
							echo 'Cost';
						}							
						?>
						</td>
						<td><?php echo $hasil['bobot']; ?></td>							
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- Step 3: Matriks Ternormalisasi (R) ==================== -->
		<h3>Step 3: Matriks Ternormalisasi (R)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">No. pegawai</th>
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
						foreach($kriterias as $kriteria):
							$id_pegawai = $pegawai['id_pegawai'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo round($matriks_r[$id_kriteria][$id_pegawai], $digit);
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>				
			</tbody>
		</table>
		
		
		<!-- Step 4: Matriks Y ==================== -->
		<h3>Step 4: Matriks Y</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">No. pegawai</th>
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
						foreach($kriterias as $kriteria):
							$id_pegawai = $pegawai['id_pegawai'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo round($matriks_y[$id_kriteria][$id_pegawai], $digit);
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>	
			</tbody>
		</table>	
		
		
		<!-- Step 5.1: Solusi Ideal Positif ==================== -->
		<h3>Step 5.1: Solusi Ideal Positif (A<sup>+</sup>)</h3>			
		<table class="pure-table pure-table-striped">
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
						<td>
							<?php
							$id_kriteria = $kriteria['id_kriteria'];							
							echo round($solusi_ideal_positif[$id_kriteria], $digit);
							?>
						</td>
					<?php endforeach; ?>
				</tr>					
			</tbody>
		</table>
		
		<!-- Step 5.2: Solusi Ideal negative ==================== -->
		<h3>Step 5.2: Solusi Ideal Negatif (A<sup>-</sup>)</h3>			
		<table class="pure-table pure-table-striped">
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
						<td>
							<?php
							$id_kriteria = $kriteria['id_kriteria'];							
							echo round($solusi_ideal_negatif[$id_kriteria], $digit);
							?>
						</td>
					<?php endforeach; ?>
				</tr>					
			</tbody>
		</table>		
		
		<!-- Step 6.1: Jarak Ideal Positif ==================== -->
		<h3>Step 6.1: Jarak Ideal Positif (S<sub>i</sub>+)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>					
				<tr>
					<th class="super-top-left">No. pegawai</th>
					<th>Jarak Ideal Positif</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($pegawais as $pegawai ): ?>
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<td>
							<?php								
							$id_pegawai = $pegawai['id_pegawai'];
							echo round($jarak_ideal_positif[$id_pegawai], $digit);
							?>
						</td>						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- Step 6.2: Jarak Ideal Negatif ==================== -->
		<h3>Step 6.2: Jarak Ideal Negatif (S<sub>i</sub>-)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>					
				<tr>
					<th class="super-top-left">No. pegawai</th>
					<th>Jarak Ideal Negatif</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($pegawais as $pegawai ): ?>
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<td>
							<?php								
							$id_pegawai = $pegawai['id_pegawai'];
							echo round($jarak_ideal_negatif[$id_pegawai], $digit);
							?>
						</td>						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		
		<!-- Step 7: Perangkingan ==================== -->
		<?php		
		$sorted_ranks = $ranks;	
		
		// Sorting
		if(function_exists('array_multisort')):
			foreach ($sorted_ranks as $key => $row) {
				$nomer[$key]  = $row['nomer'];
				$nilai[$key] = $row['nilai'];
			}
			array_multisort($nilai, SORT_DESC, $nomer, SORT_ASC, $sorted_ranks);
		endif;
		?>		
		<h3>Step 7: Perangkingan (V)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>					
				<tr>
					<th class="super-top-left">No. pegawai</th>
					<th>Ranking</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sorted_ranks as $pegawai ): ?>
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<td><?php echo round($pegawai['nilai'], $digit); ?></td>											
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>			
		
	</div>

</div><!-- .container -->
</div><!-- .main-content-row -->

<?php
require_once('template-parts/footer.php');