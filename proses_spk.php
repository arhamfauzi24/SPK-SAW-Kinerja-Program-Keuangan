<?php
session_start();
error_reporting(0);
if (empty($_SESSION['id'])) {
    header('location:login.php?error_login=1');
    exit;
}
?>
<?php include 'header.php'; ?>
<?php include 'menu.php'; ?>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="page-head-line">Proses SPK</h4>
            </div>
        </div>
        <div class="row">
            <h3>Tabel Hasil Penilaian</h3>
            <div class="table-responsive">
                <table id="example1" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nama Program Keuangan</th>
                            <?php foreach ($db->select('kriteria', 'kriteria')->get() as $k) : ?>
                                <th>
                                    <?php
                                    $tmp = explode('_', $k['kriteria']);
                                    echo ucwords(implode(' ', $tmp));
                                    ?>
                                </th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($db->select('program.nama,hasil_tpa.*', 'program,hasil_tpa')->where('program.id_calon_kr=hasil_tpa.id_calon_kr')->get() as $data) :
                        ?>
                            <tr>
                                <td><?= $data['nama'] ?></td>
                                <?php foreach ($db->select('kriteria', 'kriteria')->get() as $td) : ?>
                                    <td><?= number_format($db->getnilaisubkriteria($data[$td['kriteria']]), 4) ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($_SESSION['role'] != 'pimpinan') : ?>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-lg" onclick="tpl()">PROSES</button>
                </div>
            </div>
            <br>
        <?php endif; ?>
        <div id="proses_spk" style="<?php echo $_SESSION['role'] == 'pimpinan' ? 'display: block;' : 'display: none;'; ?>">
            <div class="row">
                <h3>Normalisasi</h3>
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama Program Keuangan</th>
                                <?php foreach ($db->select('kriteria', 'kriteria')->get() as $k) : ?>
                                    <th>
                                        <?php
                                        $tmp = explode('_', $k['kriteria']);
                                        echo ucwords(implode(' ', $tmp));
                                        ?>
                                    </th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($db->select('program.nama,hasil_tpa.*', 'program,hasil_tpa')->where('program.id_calon_kr=hasil_tpa.id_calon_kr')->get() as $data) :
                            ?>
                                <tr>
                                    <td><?= $data['nama'] ?></td>
                                    <?php foreach ($db->select('kriteria', 'kriteria')->get() as $td) : ?>
                                        <td><?= number_format($db->rumus($db->getnilaisubkriteria($data[$td['kriteria']]), $td['kriteria']), 4); ?></td>
                                    <?php endforeach ?>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <h3>Proses Penentuan</h3>
                <div class="table-responsive">
                    <table id="example3" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($db->select('program.id_calon_kr,program.nama,hasil_tpa.*', 'program,hasil_tpa')->where('program.id_calon_kr=hasil_tpa.id_calon_kr')->get() as $data) :
                            ?>
                                <tr>
                                    <td><?= $data['nama'] ?></td>
                                    <td>
                                        <?php
                                        $hasil = [];
                                        foreach ($db->select('kriteria', 'kriteria')->get() as $dt) {
                                            array_push($hasil, $db->rumus($db->getnilaisubkriteria($data[$dt['kriteria']]), $dt['kriteria']) * $db->bobot($dt['kriteria']));
                                        }
                                        echo $h = number_format(array_sum($hasil), 4);
                                        
                                        // Insert or update the results without date columns
                                        $existing = $db->select('id_calon_kr', 'hasil_spk')->where("id_calon_kr='$data[id_calon_kr]'")->count();
                                        if ($existing == 0) {
                                            $db->insert('hasil_spk', "'','$data[id_calon_kr]','$h'")->count();
                                        } else {
                                            $db->update('hasil_spk', "hasil_spk='$h'")->where("id_calon_kr='$data[id_calon_kr]'")->count();
                                        }

                                        ?>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <h3>Perankingan</h3>
                <div class="table-responsive">
                    <table id="example4" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama Program</th>
                                <?php 
                                $no = 1;
                                foreach ($db->select('kriteria', 'kriteria')->get() as $th) : ?>
                                    <th>K<?= $no ?></th>
                                <?php 
                                $no++;
                                endforeach; ?>
                                <th rowspan="2" style="padding-bottom:25px">Hasil</th>
                                <th rowspan="2" style="padding-bottom:25px">Ranking</th>
                            </tr>
                            <tr>
                                <th>Bobot</th>
                                <?php foreach ($db->select('bobot', 'kriteria')->get() as $th) : ?>
                                    <th><?= $th['bobot'] ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch data and order by the 'hasil_spk' descending to determine ranking
                            $results = $db->select('distinct(program.nama), hasil_tpa.*, hasil_spk.*', 'program, hasil_tpa, hasil_spk')
                                ->where('program.id_calon_kr = hasil_tpa.id_calon_kr AND program.id_calon_kr = hasil_spk.id_calon_kr')
                                ->order_by('hasil_spk.hasil_spk', 'DESC')
                                ->get();

                            $ranking = 1; // Initialize ranking
                            foreach ($results as $data) :
                            ?>
                                <tr>
                                    <td><?= $data['nama'] ?></td>
                                    <?php foreach ($db->select('kriteria', 'kriteria')->get() as $td) : ?>
                                        <td><?= number_format($db->rumus($db->getnilaisubkriteria($data[$td['kriteria']]), $td['kriteria']), 4); ?></td>
                                    <?php endforeach; ?>
                                    <td>
                                        <?php
                                        $hasil = [];
                                        foreach ($db->select('kriteria', 'kriteria')->get() as $dt) {
                                            array_push($hasil, $db->rumus($db->getnilaisubkriteria($data[$dt['kriteria']]), $dt['kriteria']) * $db->bobot($dt['kriteria']));
                                        }
                                        echo $r = number_format(array_sum($hasil), 4);
                                        ?>
                                    </td>
                                    <td>
                                        <?= $ranking; ?>
                                    </td>
                                </tr>
                            <?php
                                $ranking++;
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CONTENT-WRAPPER SECTION END -->

<script>
    function tpl() {
        document.getElementById('proses_spk').style.display = 'block';
    }

    // Jika yang login adalah pimpinan, tampilkan hasil langsung
    <?php if ($_SESSION['role'] == 'pimpinan') : ?>
        window.onload = function() {
            document.getElementById('proses_spk').style.display = 'block';
        };
    <?php endif; ?>
</script>

<?php include 'footer.php'; ?>
