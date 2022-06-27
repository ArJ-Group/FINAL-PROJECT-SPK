<?php


require_once('condb/init.php');


$judul_page = 'Perankingan Menggunakan Metode TOPSIS';
require_once('side/header.php');


$digit = 4;


$qry = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria ORDER BY urutan_order ASC');
$qry->execute();
$qry->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $qry->fetchAll();


$qry2 = $pdo->prepare('SELECT id_pegawai, nomer FROM pegawai');
$qry2->execute();			
$qry2->setFetchMode(PDO::FETCH_ASSOC);
$pegawais = $qry2->fetchAll();



$getCalculate = array();
foreach($kriterias as $kriteria):
	foreach($pegawais as $pegawai):
		
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


$ResultCal = array();
foreach($getCalculate as $id_kriteria => $nilai_pegawais):
	
	$jumlah_kuadrat = 0;
	foreach($nilai_pegawais as $nilai_pegawai):
		$jumlah_kuadrat += pow($nilai_pegawai, 2);
	endforeach;
	$sqrt = sqrt($jumlah_kuadrat);
	
	foreach($nilai_pegawais as $id_pegawai => $nilai_pegawai):
		$ResultCal[$id_kriteria][$id_pegawai] = $nilai_pegawai / $sqrt;
	endforeach;
	
endforeach;



$matriks_y = array();
foreach($kriterias as $kriteria):
	foreach($pegawais as $pegawai):
		
		$bobot = $kriteria['bobot'];
		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $ResultCal[$id_kriteria][$id_pegawai];
		$matriks_y[$id_kriteria][$id_pegawai] = $bobot * $nilai_r;

	endforeach;
endforeach;



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
	

	$sqrt_jip = sqrt($jumlah_kuadrat_jip);
	$sqrt_jin = sqrt($jumlah_kuadrat_jin);
	

	$jarak_ideal_positif[$id_pegawai] = $sqrt_jip;
	$jarak_ideal_negatif[$id_pegawai] = $sqrt_jin;
	
endforeach;


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

	<link href="stylesheets/new/plugins/maps/vector/jvector/jqry-jvectormap-2.0.3.css" rel="stylesheet"
		type="text/css" />
	<link href="stylesheets/new/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
	<link href="stylesheets/new/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />

</head>
<div class="main-content-row">
	<div class="container clearfix">

		<div class="main-content main-content-full the-content">

			<h1><?php echo $judul_page; ?></h1>


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
							echo $getCalculate[$id_kriteria][$id_pegawai];
							echo '</td>';
						endforeach;
						?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>


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
							echo round($ResultCal[$id_kriteria][$id_pegawai], $digit);
							echo '</td>';
						endforeach;
						?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>



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



			<?php		
		$sorted = $ranks;	
		

		if(function_exists('array_multisort')):
			foreach ($sorted as $key => $row) {
				$nomer[$key]  = $row['nomer'];
				$nilai[$key] = $row['nilai'];
			}
			array_multisort($nilai, SORT_DESC, $nomer, SORT_ASC, $sorted);
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
					<?php foreach($sorted as $pegawai ): ?>
					<tr>
						<td><?php echo $pegawai['nomer']; ?></td>
						<td><?php echo round($pegawai['nilai'], $digit); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		</div>

	</div>
</div>

<?php
require_once('side/footer.php');