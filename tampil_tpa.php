<?php
    session_start();
    error_reporting(0);
    if (empty($_SESSION['id'])) {
        header('location:login.php?error_login=1');
    }
?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Penilaian Program Keuangan</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($_GET['error_msg'])): ?>
                      <div class="alert alert-danger">
                          <?= $_GET['error_msg']; ?>
                      </div>
                    <?php endif ?>
                </div>
            </div>  
            <div class="row">
                <?php if ($_SESSION['role'] != 'pimpinan'): ?>
                    <div><a href="input_tpa.php" class="btn btn-info">Tambah Data</a></div>
                    <br>
                <?php endif; ?>
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <?php foreach ($db->select('kriteria','kriteria')->get() as $kr ): ?>
                                <th><?= $kr['kriteria']?></th>
                                <?php endforeach ?>
                                <?php if ($_SESSION['role'] != 'pimpinan'): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($db->select('program.id_calon_kr,program.nama,hasil_tpa.*','program,hasil_tpa')->where('program.id_calon_kr=hasil_tpa.id_calon_kr')->get() as $data): ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $data['nama']?></td>
                                <?php foreach ($db->select('kriteria','kriteria')->get() as $k): ?>
                                <td><?= $db->getnamesubkriteria($data[$k['kriteria']])?> (Nilai = <?= $db->getnilaisubkriteria($data[$k['kriteria']])?>)</td>
                                <?php endforeach ?>
                                <?php if ($_SESSION['role'] != 'pimpinan'): ?>
                                    <td>
                                        <a class="btn btn-warning" href="edit_tpa.php?id=<?php echo $data['id_calon_kr']?>">Edit</a>
                                        <a class="btn btn-danger" onclick="return confirm('Yakin Hapus?')" href="delete_tpa.php?id=<?php echo $data['id_calon_kr']?>">Hapus</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php $no++; endforeach; ?>
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->

<?php include 'footer.php'; ?>
<script type="text/javascript">
    $(function(){
        $("#tpa").addClass('menu-top-active');
    });
</script>
<script type="text/javascript">
    $(function() {
        $('#example1').dataTable();
    });
</script>
