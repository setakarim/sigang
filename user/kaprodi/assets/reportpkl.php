<?php 
session_start();
require "../../../koneksi.php";
if (!isset($_SESSION["login_kaprodi"])) {
header("location:../../index.php");
exit();
}
if	(isset($_POST['jadwalkan'])) {
$angkatan = $_POST["angkatan"];
$sql = mysqli_query($conn, "SELECT mahasiswa.nim, mahasiswa.nama,
                            pkl.judul_laporan, dosen.nama_dosen, pkl.instansi, mahasiswa.status_pkl
                            FROM mahasiswa LEFT JOIN pkl
                            ON mahasiswa.nim=pkl.nim
                            LEFT JOIN dosen_wali
                            ON pkl.id_dosenwali=dosen_wali.id_dosenwali
                            LEFT JOIN dosen 
                            ON dosen_wali.nidn=dosen.nidn
                            WHERE mahasiswa.angkatan='$angkatan' ORDER BY mahasiswa.nim ASC") or die
                            (mysqli_erorr($conn));

require_once "../../../plugins/mpdf/autoload.php";

$mpdf = new \Mpdf\Mpdf(['format' => 'Legal']);
$mpdf->AddPage('L');

$data .= '
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak kartu bimbingan</title>
</head>

<body>';

$data .= '
<table align="center">
<tr>
<td align="center" colspan="3"><h3>LAPORAN PRAKTIK KERJA LAPANGAN MAHASISWA</h3></td>
</tr>
<tr>
<td align="center" colspan="3"><h3>PRODI TEKNIK INFROMATIKA</h3></td>
</tr>
<tr>
<td align="center" colspan="3"><br></td>
</tr>
</table>
<BR>';
$tgl = date('d-M-Y');
$data .='
        <h3>Dicetak Pada : '.$tgl.'</h3>
        <h3>Angkatan : '.$angkatan.'</h3>
        <h3>Dicetak Oleh : Kaprodi</h3>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead align="center">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th width="200px">NAMA</th>
                    <th width="300px">JUDUL</th>
                    <th>Instansi</th>
                    <th width="200px">PEMBIMBING</th>
                    <th width="80px">Status</th>
                </tr>
            </thead>';
        $no = 1;
       foreach ($sql as $key => $d) {
                $data .=  '<tr height="700px">
                            <td>'.$no++.'</td>
                            <td>'.$d["nim"].'</td>
                            <td>'.$d["nama"].'</td>
                            <td>'.$d["judul_laporan"].'</td>
                            <td>'.$d["instansi"].'</td>
                            <td>'.$d["nama_dosen"].'</td>
                            <td>'.$d["status_pkl"].'</td>
                            </tr>';
             }

$data .='
        </tbody>
        </table>
</body>
</html>
';


$mpdf->WriteHTML($data);
$mpdf->Output('laporadatapkl_kaprodi.pdf', 'I');
}
?>