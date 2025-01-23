<?php
ob_start();
include 'db/db_config.php';
session_start();
if (empty($_SESSION['id'])) {
    header('location:login.php?error_login=1');
    exit;
}
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
    public $isLastPage = false;

    public function Header() {
        if ($this->page == 1) {
            $image_file = K_PATH_IMAGES.'tcpdf_logo.jpg';
            $this->Image($image_file, 17, 4, 25, 25, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $html = '<strong><font size="16">Badan Pengelola Keuangan Daerah Padang Pariaman</font></strong><br/><br/>
            Nagari Parit Malintang Kecamatan Anam Lingkuang Kabupaten Padang Pariaman
            <br/>';
            $this->writeHTMLCell(0, 0, 45, 7, $html, 0, 0, false, true, 'L');
            $html = '
                <hr>
                <br>
                <table>
                <tr>
                <td align="center" style="font-size: 15px;">Laporan Hasil Perhitungan SAW Program Keuangan Daerah</td>
                </tr>
                </table>
                <table>
                <tr>
                <td></td>
                </tr>
            ';
            $this->writeHTMLCell(0, 0, 15, 33, $html, 0, 0, false, true, '');
        }
    }

    public function Footer() {
        $this->SetY(-30); // Adjust the Y position for the footer
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        if ($this->isLastPage) {
            $tgl = date("d F Y");
            $this->SetY(-60); // Adjust the Y position for the form signature
            // Cek apakah user yang login adalah pimpinan
            $namaTandaTangan = $_SESSION['nama']; // Default nama sesuai dengan session
            if ($_SESSION['role'] == 'pimpinan') {
                $namaTandaTangan = "Taslim Leter, SE, A.k";
            }

            $html = '
            <table>
                <tr>
                    <td></td>
                    <td align="right">Padang Pariaman, '.$tgl.'</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right" style="height: 50px;"></td> <!-- Space for signature -->
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right">'. $namaTandaTangan.'</td>
                </tr>
            </table>';
            $this->writeHTML($html, true, false, true, false, 'R');
        }
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('times','',10);
$pdf->AddPage();

// Generate Analysis Text
$rankingData = []; // Store ranking data for finding best and worst
$analysisText = '';

// Add Analysis Text
foreach ($db->select('distinct(program.nama), hasil_tpa.*, hasil_spk.*', 'program,hasil_tpa,hasil_spk')
             ->where('program.id_calon_kr=hasil_tpa.id_calon_kr and program.id_calon_kr=hasil_spk.id_calon_kr')
             ->order_by('hasil_spk.hasil_spk', 'desc')
             ->get() as $data) {
    $hasil = number_format(array_sum(array_map(function($dt) use ($db, $data) {
        return $db->rumus($db->getnilaisubkriteria($data[$dt['kriteria']]), $dt['kriteria']) * $db->bobot($dt['kriteria']);
    }, $db->select('kriteria', 'kriteria')->get())), 4); // 4 angka di belakang koma

    $rankingData[] = [
        'nama' => $data['nama'],
        'hasil' => $hasil,
    ];
}

// Determine best and worst programs
$bestProgram = end($rankingData); // Last item in the array is the best
$worstProgram = reset($rankingData); // First item in the array is the worst

$analysisText = '
<p>Berdasarkan Hasil Analisa Kinerja Program Keuangan Pada Badan Pengelola Keuangan Padang Pariaman Menggunakan Metode Simple Additive Weighting, didapatkan hasil kinerja program terbaik yaitu <b>'.$worstProgram['nama'].'</b> dengan nilai <b>'.$worstProgram['hasil'].'</b> dan kinerja terendah yaitu program <b>'.$bestProgram['nama'].'</b> dengan nilai <b>'.$bestProgram['hasil'].'</b>. Berikut Hasil Perankingan Kinerja Program Keuangan :</p>
';

$pdf->writeHTML($analysisText, true, false, true, false, '');

// Generate Table HTML
$htmlTable = '
<br>
<h3>Perankingan</h3>
<table border="1" cellpadding="4">
    <thead>
        <tr>
            <th width="500">Program</th>
            <th width="70">Nilai</th>
            <th width="70">Ranking</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
foreach ($db->select('distinct(program.nama), hasil_tpa.*, hasil_spk.*', 'program,hasil_tpa,hasil_spk')
             ->where('program.id_calon_kr=hasil_tpa.id_calon_kr and program.id_calon_kr=hasil_spk.id_calon_kr')
             ->order_by('hasil_spk.hasil_spk', 'desc')
             ->get() as $data) {
    $hasil = number_format(array_sum(array_map(function($dt) use ($db, $data) {
        return $db->rumus($db->getnilaisubkriteria($data[$dt['kriteria']]), $dt['kriteria']) * $db->bobot($dt['kriteria']);
    }, $db->select('kriteria', 'kriteria')->get())), 4); // 4 angka di belakang koma

    $htmlTable .= '
    <tr>
        <td width="500">'.$data['nama'].'</td>
        <td width="70">'.$hasil.'</td>
        <td width="70">'.$no.'</td>
    </tr>';
    $no++;
}
$htmlTable .= '
    </tbody>    
</table>';

$pdf->writeHTML($htmlTable, true, false, true, false, '');

$pdf->lastPage(); // Ensure to call this method
$pdf->isLastPage = true;
ob_end_clean();
$pdf->Output('Hasil_SAW.pdf', 'I');
?>
