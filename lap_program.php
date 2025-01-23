<?php
// Fungsi untuk mengubah bulan dari bahasa Inggris ke bahasa Indonesia
function getBulanIndonesia($bulanInggris) {
    $bulanInggris = strtolower($bulanInggris);
    $namaBulan = array(
        'january' => 'Januari',
        'february' => 'Februari',
        'march' => 'Maret',
        'april' => 'April',
        'may' => 'Mei',
        'june' => 'Juni',
        'july' => 'Juli',
        'august' => 'Agustus',
        'september' => 'September',
        'october' => 'Oktober',
        'november' => 'November',
        'december' => 'Desember'
    );
    return $namaBulan[$bulanInggris];
}

include 'db/db_config.php';
session_start();
// error_reporting(0);
if(empty($_SESSION['id'])){
    header('location:login.php');
}
ob_start(); 

require_once('tcpdf/tcpdf.php');

function hitung_lama_bergabung($tgl_lahir)
{
    $today = date('Y-m-d');
    $now = time();
    list($thn, $bln, $tgl) = explode('-',$tgl_lahir);
    $time_lahir = mktime(0,0,0,$bln, $tgl, $thn);

    $selisih = $now - $time_lahir;

    $tahun = floor($selisih/(60*60*24*365));
    $bulan = round(($selisih % (60*60*24*365) ) / (60*60*24*30));

    return $tahun.' tahun '.$bulan.' bulan';
}

class MYPDF extends TCPDF {
    var $top_margin = 20;

    // Page header
    public function Header() {
        if ($this->page == 1) {
            // Logo
            $image_file = K_PATH_IMAGES.'tcpdf_logo.jpg';
            $this->Image($image_file, 17, 4, 25, 25, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $html = '<strong><font size="18">Badan Pengelola Keuangan Daerah Padang Pariaman</font></strong><br/><br/>
            Nagari Parit Malintang Kecamatan Anam Lingkuang Kabupaten Padang Pariaman
            <br/>
            ';
            $this->writeHTMLCell(
                $w = 0,
                $h = 0,
                $x = 45,
                $y = 7,
                $html,
                $border = 0,
                $ln = 0,
                $fill = false,
                $reseth = true,
                $align = 'L'
            );

            $html = '
            <hr>
            <br>
            <table>
            <tr>
            <td align="center" style="font-size: 15px;"> Data Program Keuangan</td>
            </tr>
            </table>
            <table>
            <tr>
            <td></td>
            </tr>
            </table>
            ';
            $this->writeHTMLCell(
                $w = 0,
                $h = 0,
                $x = 15,
                $y = 33,
                $html,
                $border = 0,
                $ln = 0,
                $fill = false,
                $reseth = true,
                $align = ''
            );
        }
    }

    public function lastPage($resetmargins = false) {
        $this->setPage($this->getNumPages(), $resetmargins);
        $this->isLastPage = true;
    }

    // Page footer
    public function Footer() {
        if ($this->isLastPage) {
            $tgl = date("d F Y");
            // Ubah nama bulan ke bahasa Indonesia
            $bulan = date("F");
            $bulanIndonesia = getBulanIndonesia($bulan);
            $tgl = date("d") . " " . $bulanIndonesia . " " . date("Y");

            $this->SetY(-60); // Adjust the Y position for the form signature

            // Cek apakah user yang login adalah pimpinan
            $namaTandaTangan = $_SESSION['nama']; // Default nama sesuai dengan session
            if ($_SESSION['role'] == 'pimpinan') {
                $namaTandaTangan = "Taslim Leter, SE, A.k"; // Nama sesuai dengan keinginan user
            }

            $html = '
            <table>
                <tr>
                    <td></td>
                    <td align="right">Padang Pariaman, '.$tgl.'</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right">Kepala Bidang Anggaran</td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right" style="height: 70px;"></td> <!-- Space for signature -->
                </tr>
                <tr>
                    <td></td>
                    <td align="right">'.$namaTandaTangan.'</td>
                </tr>
            </table>';
            $this->writeHTML($html, true, false, true, false, 'R');
        }

        // Position at 15 mm from bottom
        $this->SetY(-15);

        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Data header
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$pdf->SetFont('times','',10);
// Add a page
$pdf->AddPage('L');

$htmlTable =
'
<table border="1" cellpadding="4" >
<thead>
        <tr>
            <th>Nama Program</th>
            <th>Anggaran</th>
            <th>Realisasi Anggaran</th>
            <th>Efektifitas Program</th>
            <th>Inovasi dan Teknologi</th>
            <th>Penyerapan Anggaran</th>
            <th>Peningkatan Infrastruktur</th>
        </tr>
    </thead>
    <tbody>';
        $no=1; 
        foreach($db->select('*','program')->get() as $data):
    $htmlTable .='<tr>
            <td nowrap>'.$data['nama'].'</td>
            <td nowrap>'.$data['anggaran'].'</td>
            <td nowrap>'.$data['realisasianggaran'].'</td>
            <td nowrap>'.$data['efektifitas'].'</td>';
            $htmlTable .='<td>'.$data['teknologi'].'</td>
            <td>'.$data['penyerapan'].'</td>
            <td>'.$data['infrastruktur'].'</td>
        </tr>';
        $no++; endforeach;
        $htmlTable .= '</tbody>
    </table>';

$pdf->writeHTML($htmlTable, true, false, true, false, '');
// $pdf->writeHTML($htmlTable, true, 0, true, 0);
// ---------------------------------------------------------
ob_end_clean();
// Close and output PDF document
$pdf->Output('Lap_Karyawan.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>
