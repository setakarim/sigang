<?php
session_start();
require "../../../koneksi.php";
if (!isset($_SESSION["login_mhs"])) {
header("location:../../../index.php");
exit();
}
$nim = $_SESSION["nim"];

//ambil data mahasiswa dan pkl
$sql = mysqli_query($conn,
"SELECT mhs.nama, mhs.nim, mhs.ttl, mhs.alamat_rumah, mhs.no_hp,
judul.judul, dosen.nama_dosen
FROM proposal LEFT JOIN judul
ON proposal.id_judul=judul.id_judul
LEFT JOIN dosen
ON proposal.dosbing=dosen.nidn
LEFT JOIN mahasiswa mhs
ON judul.nim=mhs.nim
WHERE judul.nim='$nim' AND judul.status_judul='Disetujui'") or die (mysqli_error($conn));
$fetch = mysqli_fetch_array($sql);

require_once "../../../plugins/mpdf/autoload.php";
$mpdf = new \Mpdf\Mpdf(['format' => 'Legal']);

$data = '<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Page Title</title>
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="" crossorigin="anonymous">
	</head>
	<style>
		hr {
			border:0;
			border-top: 10px #000000 double;
		}
		.univ {
			font-family: : "Monospace", Helvetica, sans-serif;
			color: #FF0000;
		}
		.fakultas {
			color: 	#4040ff;
		}
	</style>
	<body>
		<table>
		<tr>
		<td><img src="../../../assets/logo.png" alt="logo-umc" width="100" height="100"></td>
		<td>
		<center>
			<font size="6" class="univ">POLITEKNIK NEGERI JAKARTA</font><BR>
			<font size="5" class="fakultas">JURUSAN TEKNIK ELEKTRO</font><BR>
			<font size="2">Jl. Prof. DR. G.A. Siwabessy, Kukusan, Kecamatan Beji, Kota Depok, Jawa Barat 16424 Telp. (021) 7270036</font><BR>
			<font size="2">Laman: http://www.pnj.ac.id e-pos: elektro@pnj.ac.id </font><BR>

		</center>
		</td>
		</tr>
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		</table>

		<table cellpadding="4" align="center" width="700px">
			<tr>
				<td colspan="3">
				<center>
				<font size="3"><b>FORMULIR PENDAFTARAN</b></font><BR>
				</td>
				</center>
			</tr>
			<tr>
				<td colspan="3">
				<center>
				<font size="3"><b>SEMINAR PROPOSAL</b></font>
				</td>
				</center>
            </tr>
            </table>
            <table>
			<tr>
				<td height="20" colspan="3"></td>
			</tr>
			<tr>
				<td><font size="3">Nama</font></td>
				<td><font size="3">:</font></td>
				<td><font size="3">'.$fetch["nama"].'</font></td>
			</tr>
			<tr>
				<td><font size="3">NIM</font></td>
				<td><font size="3">:</font></td>
				<td><font size="3">'.$fetch["nim"].'</font></td>
			</tr>
			<tr>
				<td width="170px"><font size="3">Tempat, Tanggal Lahir</font></td>
				<td><font size="3">:</font></td>
				<td><font size="3">'.$fetch["ttl"].'</font></td>
			</tr>
			<tr>
				<td><font size="3">Alamat Rumah</font></td>
				<td><font size="3">:</font></td>
				<td><font size="3">'.$fetch["alamat_rumah"].'</font></td>
			</tr>
			<tr>
				<td><font size="3">No. Hp</font></td>
				<td><font size="3">:</font></td>
				<td><font size="3">'.$fetch["no_hp"].'</font></td>
			</tr>
			<tr>
				<td><font size="3">Judul Proposal</font></td>
				<td><font size="3">:</font></td>
				<td><font size="3">'.$fetch["judul"].'</font></td>
			</tr>
			<tr>
				<td height="10" colspan="3"></td>
			</tr>
			<tr>
				<td><font size="3">Persetujuan Pembimbing</font></td>
				<td colspan="2"><font size="4">:</font></td>
			</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="5">
		<tr>
			<td><font size="3"><b>No</b></font></td>
			<td width="200px"><font size="3"><b>Nama Pembimbing</b></font></td>
			<td><font size="3"><b>Tanda Tangan</b></font></td>
		</tr>
		<tbody>
			<tr height="20">
				<td><font size="3">1</font></td>
				<td height="50px"><font size="3">'.$fetch["nama_dosen"].'</font></td>
				<td><font size="3"></font></td>
			</tr>
		</tbody>
		</table>

		<table>
			<tr>
				<td height="10" colspan="3"></td>
			</tr>
		</table>
		<table width="700px">
			<tr>
                <td><font size="3">Persyaratan :</font></td>
            </tr>
            <tr>
                <td><font size="3">1. Pembayaran biaya bimbingan skripsi minimal Rp. 750.000.</font></td>
            </tr>
            <tr>
                <td><font size="3">2. Pembayaran SPP dan SKS semester berjalan minimal 50%</font></td>
            </tr>
            <tr>
                <td><font size="3">3. Menyerahkan naskah proposal ke bagian fakultas sebanyak 4 rangkap ( 2 pembimbing 2 penguji) atau 3 rangkap (1 pembimbing 2 penguji)</font></td>
            </tr>
            <tr>
                <td><font size="3">4. Persetujuan Akademik :</font></td>
			</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="5">
		<tr>
			<td align="center"><font size="3"><b>Nama Pejabat Akademik</b></font></td>
			<td align="center"><font size="3"><b>Lembar Pengesahan<br>(Tanda Tangan dan Stempel)</b></font></td>
		</tr>
		<tbody>
			<tr>
				<td height="50px"><font size="3"></font></td>
				<td><font size="3"></font></td>
			</tr>
		</tbody>
		</table>

		<table cellpadding="7">
			<tr>
				<td height="2" colspan="3"></td>
			</tr>
			<tr>
				<td><font size="3"><b>Cirebon, ...................................................</b></font></td>
			</tr>
			<tr>
				<td><font size="3"><b>Mahasiswa,</b></font><br><br><br><br><br><br><br><br></td>
			</tr>
			<tr height="300">
				<td><font size="3"><b>...................................................................</b></font></td>
			</tr>
		</table>
	</body>
</html>';

$mpdf->WriteHTML($data);
$mpdf->Output('Formulir-Seminar-PKL.pdf', 'I');

 ?>
