<?php
$numberr = 4;
require_once('../../condb/include.php');
$get_title = 'Perankingan Menggunakan Metode SAW';
require_once('../../side/header.php');
$qry = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria ORDER BY urutan_order ASC');
$qry->execute();
$qry->setFetchMode(PDO::FETCH_ASSOC);
$sub_crite = $qry->fetchAll();


$qry2 = $pdo->prepare('SELECT id_pegawai, nomer FROM pegawai');
$qry2->execute();
$qry2->setFetchMode(PDO::FETCH_ASSOC);
$Employe = $qry2->fetchAll();


$getCalculate = array();
$list_kriteria = array();
foreach ($sub_crite as $kriteria) :
	$list_kriteria[$kriteria['id_kriteria']] = $kriteria;
	foreach ($Employe as $pegawai) :

		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];


		$qry3 = $pdo->prepare('SELECT nilai FROM nilai_pegawai
			WHERE id_pegawai = :id_pegawai AND id_kriteria = :id_kriteria');
		$qry3->execute(array(
			'id_pegawai' => $id_pegawai,
			'id_kriteria' => $id_kriteria,
		));
		$qry3->setFetchMode(PDO::FETCH_ASSOC);
		if ($nilai_pegawai = $qry3->fetch()) {

			$getCalculate[$id_kriteria][$id_pegawai] = $nilai_pegawai['nilai'];
		} else {
			$getCalculate[$id_kriteria][$id_pegawai] = 0;
		}

	endforeach;
endforeach;
$ResultCal = array();
foreach ($getCalculate as $id_kriteria => $nilai_Employe) :

	$type = $list_kriteria[$id_kriteria]['type'];
	foreach ($nilai_Employe as $id_alternatif => $nilai) {
		if ($type == 'benefit') {
			$nilai_normal = $nilai / max($nilai_Employe);
		} elseif ($type == 'cost') {
			$nilai_normal = min($nilai_Employe) / $nilai;
		}

		$ResultCal[$id_kriteria][$id_alternatif] = $nilai_normal;
	}

endforeach;


$torank = array();
foreach ($Employe as $pegawai) :

	$total_nilai = 0;
	foreach ($list_kriteria as $kriteria) {

		$bobot = $kriteria['bobot'];
		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];

		$nilai_r = $ResultCal[$id_kriteria][$id_pegawai];
		$total_nilai = $total_nilai + ($bobot * $nilai_r);
	}

	$torank[$pegawai['id_pegawai']]['id_pegawai'] = $pegawai['id_pegawai'];
	$torank[$pegawai['id_pegawai']]['nomer'] = $pegawai['nomer'];
	$torank[$pegawai['id_pegawai']]['nilai'] = $total_nilai;

endforeach;

?>

<div class="main-content-row">
	<div class="container clearfix">

		<div class="main-content main-content-full the-content">

			<h1><?php echo $get_title; ?></h1>


			<h3> Matriks Keputusan</h3>
			<table class="pure-table pure-table-striped">
				<thead>
					<tr class="super-top">
						<th rowspan="2" class="super-top-left">No. pegawai</th>
						<th colspan="<?php echo count($sub_crite); ?>">Kriteria</th>
					</tr>
					<tr>
						<?php foreach ($sub_crite as $kriteria) : ?>
							<th><?php echo $kriteria['nama']; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($Employe as $pegawai) : ?>
						<tr>
							<td><?php echo $pegawai['nomer']; ?></td>
							<?php
							foreach ($sub_crite as $kriteria) :
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


			<h3> Bobot Preferensi </h3>
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th>Kriteria</th>
						<th>Type</th>
						<th>Bobot</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($sub_crite as $hasil) : ?>
						<tr>
							<td><?php echo $hasil['nama']; ?></td>
							<td>
								<?php
								if ($hasil['type'] == 'benefit') {
									echo 'Benefit';
								} elseif ($hasil['type'] == 'cost') {
									echo 'Cost';
								}
								?>
							</td>
							<td><?php echo $hasil['bobot']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>


			<h3> Matriks Ternormalisasi </h3>
			<table class="pure-table pure-table-striped">
				<thead>
					<tr class="super-top">
						<th rowspan="2" class="super-top-left">No. pegawai</th>
						<th colspan="<?php echo count($sub_crite); ?>">Kriteria</th>
					</tr>
					<tr>
						<?php foreach ($sub_crite as $kriteria) : ?>
							<th><?php echo $kriteria['nama']; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($Employe as $pegawai) : ?>
						<tr>
							<td><?php echo $pegawai['nomer']; ?></td>
							<?php
							foreach ($sub_crite as $kriteria) :
								$id_pegawai = $pegawai['id_pegawai'];
								$id_kriteria = $kriteria['id_kriteria'];
								echo '<td>';
								echo round($ResultCal[$id_kriteria][$id_pegawai], $numberr);
								echo '</td>';
							endforeach;
							?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>



			<?php
			$sorted = $torank;

			if (function_exists('array_multisort')) :
				$nomer = array();
				$nilai = array();
				foreach ($sorted as $key => $row) {
					$nomer[$key]  = $row['nomer'];
					$nilai[$key] = $row['nilai'];
				}
				array_multisort($nilai, SORT_DESC, $nomer, SORT_ASC, $sorted);
			endif;
			?>
			<h3> Perangkingan </h3>
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th class="super-top-left">No. pegawai</th>
						<th>Ranking</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($sorted as $pegawai) : ?>
						<tr>
							<td><?php echo $pegawai['nomer']; ?></td>
							<td><?php echo round($pegawai['nilai'], $numberr); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		</div>

	</div>
</div>

<?php
require_once('../../side/footer.php');
