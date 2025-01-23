<?php
session_start();
error_reporting(0);
?>
<?php include 'header.php'; ?>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="page-head-line">Cari Data</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <strong> Cari Data Program</strong>
                    <form method="get" action="cari_program.php">
                        <div class="input-group">
                            <input type="text" class="form-control" name="nama" placeholder="Search">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hovered">
                    <thead>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nilai Akhir</th>
                    </thead>
                    <tbody>
                        <?php
                        $name = $_GET['nama'];
                        $no = 1;

                        // Menggunakan DISTINCT untuk menghindari duplikasi hasil
                        $results = $db->select('DISTINCT program.nama, hasil_spk.hasil_spk', 'program, hasil_spk')
                                      ->where("program.id_calon_kr = hasil_spk.id_calon_kr AND program.nama LIKE '%$name%'")
                                      ->get();

                        foreach ($results as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['nama'] ?></td>
                                <td><?= $row['hasil_spk'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- CONTENT-WRAPPER SECTION END -->

<?php include 'footer.php'; ?>
<script type="text/javascript">
    $(function(){
        $("#home").addClass('menu-top-active');
    });
</script>
