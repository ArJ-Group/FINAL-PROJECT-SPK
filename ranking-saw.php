<?php
require_once('includes/init.php');
$judul_page = 'Perankingan Menggunakan Metode SAW';
require_once('side/header.php');


$digit = 4;


$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria ORDER BY urutan_order ASC');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $query->fetchAll();


$query2 = $pdo->prepare('SELECT id_pegawai, nomer FROM pegawai');
$query2->execute();			
$query2->setFetchMode(PDO::FETCH_ASSOC);
$pegawais = $query2->fetchAll();


/*  1 
 * Matrix Keputusan (X)
*/
$matriks_x = array();
$list_kriteria = array();
foreach($kriterias as $kriteria):
	$list_kriteria[$kriteria['id_kriteria']] = $kriteria;
	foreach($pegawais as $pegawai):
		
		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		
		$query3 = $pdo->prepare('SELECT nilai FROM nilai_pegawai
			WHERE id_pegawai = :id_pegawai AND id_kriteria = :id_kriteria');
		$query3->execute(array(
			'id_pegawai' => $id_pegawai,
			'id_kriteria' => $id_kriteria,
		));			
		$query3->setFetchMode(PDO::FETCH_ASSOC);
		if($nilai_pegawai = $query3->fetch()) {
			
			$matriks_x[$id_kriteria][$id_pegawai] = $nilai_pegawai['nilai'];
		} else {			
			$matriks_x[$id_kriteria][$id_pegawai] = 0;
		}

	endforeach;
endforeach;

/*  3 
 * Matriks Ternormalisasi (R)
  */
$matriks_r = array();
foreach($matriks_x as $id_kriteria => $nilai_pegawais):
	
	$type = $list_kriteria[$id_kriteria]['type'];
	foreach($nilai_pegawais as $id_alternatif => $nilai) {
		if($type == 'benefit') {
			$nilai_normal = $nilai / max($nilai_pegawais);
		} elseif($type == 'cost') {
			$nilai_normal = min($nilai_pegawais) / $nilai;
		}
		
		$matriks_r[$id_kriteria][$id_alternatif] = $nilai_normal;
	}
	
endforeach;


/*  4 
 * Perangkingan
  */
$ranks = array();
foreach($pegawais as $pegawai):

	$total_nilai = 0;
	foreach($list_kriteria as $kriteria) {
	
		$bobot = $kriteria['bobot'];
		$id_pegawai = $pegawai['id_pegawai'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $matriks_r[$id_kriteria][$id_pegawai];
		$total_nilai = $total_nilai + ($bobot * $nilai_r);

	}
	
	$ranks[$pegawai['id_pegawai']]['id_pegawai'] = $pegawai['id_pegawai'];
	$ranks[$pegawai['id_pegawai']]['nomer'] = $pegawai['nomer'];
	$ranks[$pegawai['id_pegawai']]['nilai'] = $total_nilai;
	
endforeach;
 
?>

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
							echo $matriks_x[$id_kriteria][$id_pegawai];
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
							echo round($matriks_r[$id_kriteria][$id_pegawai], $digit);
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>				
			</tbody>
		</table>		
		
		
		
		<?php		
		$sorted = $ranks;		
	
		if(function_exists('array_multisort')):
			$nomer = array();
			$nilai = array();
			foreach ($sorted as $key => $row) {
				$nomer[$key]  = $row['nomer'];
				$nilai[$key] = $row['nilai'];
			}
			array_multisort($nilai, SORT_DESC, $nomer, SORT_ASC, $sorted);
		endif;
		?>		
		<h3>Step 4: Perangkingan (V)</h3>			
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