<?php
<<<<<<< HEAD
<<<<<<< HEAD
=======
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
$Employe = $query2->fetchAll();


/* >>> STEP 1 ===================================
 * Matrix Keputusan (X)
 * ------------------------------------------- */
$matriks_x = array();
foreach($kriterias as $kriteria):
	foreach($Employe as $pegawai):
		
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
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b

require_once('condb/init.php');

$get_title = 'Perankingan Menggunakan Metode TOPSIS';
$numberr = 4;
require_once('side/header.php');
$qry = $pdo->prepare('SELECT id_kriteria, nama, type, bobot	FROM kriteria ORDER BY urutan_order ASC');
$qry->execute();
$qry->setFetchMode(PDO::FETCH_ASSOC);
$sub_crite = $qry->fetchAll();
$qry2 = $pdo->prepare('SELECT id_pegawai, nomer FROM pegawai');
$qry2->execute();			
$qry2->setFetchMode(PDO::FETCH_ASSOC);
<<<<<<< HEAD
$pegawais = $qry2->fetchAll();

<<<<<<< HEAD
$ResultCal = array();
foreach($getCalculate as $id_kriteria => $nilai_pegawais):
=======
/* >>> STEP 3 ===================================
 * Matriks Ternormalisasi (R)
 * ------------------------------------------- */
$matriks_r = array();
foreach($matriks_x as $id_kriteria => $nilai_Employe):
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
$Employe = $qry2->fetchAll();

$ResultCal = array();
foreach($getCalculate as $id_kriteria => $nilai_Employe):
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
	
	$jumlah_kuadrat = 0;
	foreach($nilai_Employe as $nilai_pegawai):
		$jumlah_kuadrat += pow($nilai_pegawai, 2);
	endforeach;
	$sqrt = sqrt($jumlah_kuadrat);
	
<<<<<<< HEAD
<<<<<<< HEAD
	foreach($nilai_pegawais as $id_pegawai => $nilai_pegawai):
		$ResultCal[$id_kriteria][$id_pegawai] = $nilai_pegawai / $sqrt;
=======
	// Mencari hasil bagi akar kuadrat
	// Lalu dimasukkan ke array $matriks_r
	foreach($nilai_Employe as $id_pegawai => $nilai_pegawai):
		$matriks_r[$id_kriteria][$id_pegawai] = $nilai_pegawai / $akar_kuadrat;
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
	foreach($nilai_Employe as $id_pegawai => $nilai_pegawai):
		$ResultCal[$id_kriteria][$id_pegawai] = $nilai_pegawai / $sqrt;
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
	endforeach;
	
endforeach;


<<<<<<< HEAD
<<<<<<< HEAD

$Cal = array();
foreach($sub_crite as $kriteria):
	foreach($pegawais as $pegawai):
=======
/* >>> STEP 4 ===================================
 * Matriks Y
 * ------------------------------------------- */
$matriks_y = array();
foreach($kriterias as $kriteria):
=======

$Cal = array();
foreach($sub_crite as $kriteria):
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
	foreach($Employe as $pegawai):
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
		
		$bobot = $kriteria['bobot'];
		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $ResultCal[$id_kriteria][$id_pegawai];
		$Cal[$id_kriteria][$id_pegawai] = $bobot * $nilai_r;

	endforeach;
endforeach;


$getCalculate = array();
foreach($sub_crite as $kriteria):
<<<<<<< HEAD
	foreach($pegawais as $pegawai):
=======
	foreach($Employe as $pegawai):
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
		
		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];
		

		$qry3 = $pdo->prepare('SELECT nilai FROM nilai_pegawai
			WHERE id_pegawai = :id_pegawai AND id_kriteria = :id_kriteria');
		$qry3->execute(array(
			'id_pegawai' => $id_pegawai,
			'id_kriteria' => $id_kriteria,
		));			
		$qry3->setFetchMode(PDO::FETCH_ASSOC);
		if($nilai_pegawai = $qry3->fetch()) {
		
			$getCalculate[$id_kriteria][$id_pegawai] = $nilai_pegawai['nilai'];
		} else {			
			$getCalculate[$id_kriteria][$id_pegawai] = 0;
		}

	endforeach;
endforeach;

$S_plus = array();
$S_Min = array();
foreach($sub_crite as $kriteria):

	$id_kriteria = $kriteria['id_kriteria'];
	$type_kriteria = $kriteria['type'];
	
	$nilai_max = max($Cal[$id_kriteria]);
	$nilai_min = min($Cal[$id_kriteria]);
	
	if($type_kriteria == 'benefit'):
		$ip = $nilai_max;
		$in = $nilai_min;
	elseif($type_kriteria == 'cost'):
		$ip = $nilai_min;
		$in = $nilai_max;
	endif;
	
	$S_plus[$id_kriteria] = $ip;
	$S_Min[$id_kriteria] = $in;

endforeach;


<<<<<<< HEAD
<<<<<<< HEAD

$PidealS = array();
$NidealS = array();
foreach($pegawais as $pegawai):
=======
/* >>> STEP 6 ================================
 * Jarak Ideal Positif & Negatif
 * ------------------------------------------- */
$jarak_ideal_positif = array();
$jarak_ideal_negatif = array();
=======

$PidealS = array();
$NidealS = array();
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
foreach($Employe as $pegawai):
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0

	$id_pegawai = $pegawai['id_pegawai'];		
	$jumlah_kuadrat_jip = 0;
	$jumlah_kuadrat_jin = 0;
	
	// Mencari penjumlahan kuadrat
<<<<<<< HEAD
<<<<<<< HEAD
	foreach($Cal as $id_kriteria => $nilai_pegawais):
		
		$hsl_pengurangan_jip = $nilai_pegawais[$id_pegawai] - $S_plus[$id_kriteria];
		$hsl_pengurangan_jin = $nilai_pegawais[$id_pegawai] - $S_Min[$id_kriteria];
=======
	foreach($matriks_y as $id_kriteria => $nilai_Employe):
		
		$hsl_pengurangan_jip = $nilai_Employe[$id_pegawai] - $solusi_ideal_positif[$id_kriteria];
		$hsl_pengurangan_jin = $nilai_Employe[$id_pegawai] - $solusi_ideal_negatif[$id_kriteria];
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
	foreach($Cal as $id_kriteria => $nilai_Employe):
		
		$hsl_pengurangan_jip = $nilai_Employe[$id_pegawai] - $S_plus[$id_kriteria];
		$hsl_pengurangan_jin = $nilai_Employe[$id_pegawai] - $S_Min[$id_kriteria];
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
		
		$jumlah_kuadrat_jip += pow($hsl_pengurangan_jip, 2);
		$jumlah_kuadrat_jin += pow($hsl_pengurangan_jin, 2);
	
	endforeach;
	

	$sqrt_jip = sqrt($jumlah_kuadrat_jip);
	$sqrt_jin = sqrt($jumlah_kuadrat_jin);
	

	$PidealS[$id_pegawai] = $sqrt_jip;
	$NidealS[$id_pegawai] = $sqrt_jin;
	
endforeach;


<<<<<<< HEAD
$ranks = array();
=======
$torank = array();
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
foreach($Employe as $pegawai):

	$s_negatif = $NidealS[$pegawai['id_pegawai']];
	$s_positif = $PidealS[$pegawai['id_pegawai']];	
	
	$nilai_v = $s_negatif / ($s_positif + $s_negatif);
	
	$torank[$pegawai['id_pegawai']]['id_pegawai'] = $pegawai['id_pegawai'];
	$torank[$pegawai['id_pegawai']]['nomer'] = $pegawai['nomer'];
	$torank[$pegawai['id_pegawai']]['nilai'] = $nilai_v;
	
endforeach;
 
?>

<head>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
	<link href="stylesheets/new/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="stylesheets/new/assets/css/plugins.css" rel="stylesheet" type="text/css" />

	<link href="stylesheets/new/plugins/maps/vector/jvector/jqry-jvectormap-2.0.3.css" rel="stylesheet"
		type="text/css" />
	<link href="stylesheets/new/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
	<link href="stylesheets/new/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />

</head>
<div class="main-content-row">
	<div class="container clearfix">

		<div class="main-content main-content-full the-content">

			<h1><?php echo $get_title; ?></h1>


<<<<<<< HEAD
			<h3>Step 1: Matriks Keputusan (X)</h3>
=======
			<h3> Matriks Keputusan (X)</h3>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
			<table class="pure-table pure-table-striped">
				<thead>
					<tr class="super-top">
						<th rowspan="2" class="super-top-left">No. pegawai</th>
						<th colspan="<?php echo count($sub_crite); ?>">Kriteria</th>
					</tr>
					<tr>
						<?php foreach($sub_crite as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
<<<<<<< HEAD
					<?php foreach($pegawais as $pegawai): ?>
=======
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($Employe as $pegawai): ?>
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
					<?php foreach($Employe as $pegawai): ?>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<?php						
						foreach($sub_crite as $kriteria):
							$id_pegawai = $pegawai['id_pegawai'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $getCalculate[$id_kriteria][$id_pegawai];
							echo '</td>';
						endforeach;
						?>
					</tr>
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
					<?php endforeach; ?>
				</tbody>
			</table>


<<<<<<< HEAD
			<h3>Step 2: Bobot Preferensi (W)</h3>
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th>Nama Kriteria</th>
=======
			<h3> Bobot Preferensi </h3>
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th>Kriteria</th>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
						<th>Type</th>
						<th>Bobot</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($sub_crite as $hasil): ?>
<<<<<<< HEAD
=======
				<?php endforeach; ?>
			</tbody>
		</table>
		
		
		<h3>Step 2: Bobot Preferensi</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr>
					<th>Nama Kriteria</th>
					<th>Type</th>
					<th>Bobot</th>						
				</tr>
			</thead>
			<tbody>
				<?php foreach($kriterias as $hasil): ?>
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
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
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
				</tbody>
			</table>


<<<<<<< HEAD
			<h3>Step 3: Matriks Ternormalisasi (R)</h3>
=======
			<h3>
				 Matriks Ternormalisasi (R)</h3>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
			<table class="pure-table pure-table-striped">
				<thead>
					<tr class="super-top">
						<th rowspan="2" class="super-top-left">No. pegawai</th>
						<th colspan="<?php echo count($sub_crite); ?>">Kriteria</th>
					</tr>
					<tr>
						<?php foreach($sub_crite as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
<<<<<<< HEAD
					<?php foreach($pegawais as $pegawai): ?>
=======
				</tr>
			</thead>
			<tbody>
				<?php foreach($Employe as $pegawai): ?>
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
					<?php foreach($Employe as $pegawai): ?>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<?php						
						foreach($sub_crite as $kriteria):
							$id_pegawai = $pegawai['id_pegawai'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo round($ResultCal[$id_kriteria][$id_pegawai], $numberr);
							echo '</td>';
						endforeach;
						?>
					</tr>
					<?php endforeach; ?>
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
				</tbody>
			</table>



<<<<<<< HEAD
			<h3>Step 4: Matriks Y</h3>
=======
			<h3> Matriks </h3>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
			<table class="pure-table pure-table-striped">
				<thead>
					<tr class="super-top">
						<th rowspan="2" class="super-top-left">No. pegawai</th>
						<th colspan="<?php echo count($sub_crite); ?>">Kriteria</th>
					</tr>
					<tr>
						<?php foreach($sub_crite as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
<<<<<<< HEAD
					<?php foreach($pegawais as $pegawai): ?>
=======
				</tr>
			</thead>
			<tbody>
				<?php foreach($Employe as $pegawai): ?>
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
					<?php foreach($Employe as $pegawai): ?>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<?php						
						foreach($sub_crite as $kriteria):
							$id_pegawai = $pegawai['id_pegawai'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo round($Cal[$id_kriteria][$id_pegawai], $numberr);
							echo '</td>';
						endforeach;
						?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>



<<<<<<< HEAD
			<h3>Step 5.1: Solusi Ideal Positif (A<sup>+</sup>)</h3>
=======
			<h3> Solusi Ideal Positif (A<sup>+</sup>)</h3>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
			<table class="pure-table pure-table-striped">
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
						<td>
							<?php
							$id_kriteria = $kriteria['id_kriteria'];							
							echo round($S_plus[$id_kriteria], $numberr);
							?>
						</td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>


<<<<<<< HEAD
			<h3>Step 5.2: Solusi Ideal Negatif (A<sup>-</sup>)</h3>
=======
			<h3> Solusi Ideal Negatif (A<sup>-</sup>)</h3>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
			<table class="pure-table pure-table-striped">
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
						<td>
							<?php
							$id_kriteria = $kriteria['id_kriteria'];							
							echo round($S_Min[$id_kriteria], $numberr);
							?>
						</td>
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>


<<<<<<< HEAD
			<h3>Step 6.1: Jarak Ideal Positif (S<sub>i</sub>+)</h3>
=======
			<h3> Jarak Ideal Positif (S<sub>i</sub>+)</h3>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th class="super-top-left">No. pegawai</th>
						<th>Jarak Ideal Positif</th>
					</tr>
				</thead>
				<tbody>
<<<<<<< HEAD
					<?php foreach($pegawais as $pegawai ): ?>
=======
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
				<?php foreach($Employe as $pegawai ): ?>
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
					<?php foreach($Employe as $pegawai ): ?>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<td>
							<?php								
							$id_pegawai = $pegawai['id_pegawai'];
							echo round($PidealS[$id_pegawai], $numberr);
							?>
						</td>
					</tr>
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
					<?php endforeach; ?>
				</tbody>
			</table>


<<<<<<< HEAD
			<h3>Step 6.2: Jarak Ideal Negatif (S<sub>i</sub>-)</h3>
=======
			<h3> Jarak Ideal Negatif (S<sub>i</sub>-)</h3>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th class="super-top-left">No. pegawai</th>
						<th>Jarak Ideal Negatif</th>
					</tr>
				</thead>
				<tbody>
<<<<<<< HEAD
					<?php foreach($pegawais as $pegawai ): ?>
=======
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
				<?php foreach($Employe as $pegawai ): ?>
>>>>>>> d7545b274c85df9748b5b4175e17673b2a62d6d0
=======
					<?php foreach($Employe as $pegawai ): ?>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<td>
							<?php								
							$id_pegawai = $pegawai['id_pegawai'];
							echo round($NidealS[$id_pegawai], $numberr);
							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>



			<?php		
<<<<<<< HEAD
		$sorted = $ranks;	
=======
		$sorted = $torank;	
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
		

		if(function_exists('array_multisort')):
			foreach ($sorted as $key => $row) {
				$nomer[$key]  = $row['nomer'];
				$nilai[$key] = $row['nilai'];
			}
			array_multisort($nilai, SORT_DESC, $nomer, SORT_ASC, $sorted);
		endif;
		?>
<<<<<<< HEAD
			<h3>Step 7: Perangkingan (V)</h3>
=======
			<h3> Perangkingan </h3>
>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th class="super-top-left">No. pegawai</th>
						<th>Ranking</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($sorted as $pegawai ): ?>
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<td><?php echo round($pegawai['nilai'], $numberr); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
<<<<<<< HEAD

		</div>

=======

		</div>

>>>>>>> 3e7139f0e4dba4691f7d43187ec95ec7f8e7ad8b
	</div>
</div>

<?php
require_once('side/footer.php');