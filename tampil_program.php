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
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Data Program Keuangan</h4>
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
                    <div><a href="input_program.php" class="btn btn-info">Tambah Data</a></div>
                    <br>
                <?php endif; ?>
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Program Keuangan</th>
                                <th>Anggaran</th>
                                <th>Realisasi Anggaran</th>
                                <th>Efektifitas Program (%)</th>
                                <th>Penyerapan Anggaran (%)</th>
                                <th>Effisiensi Anggaran</th>
                                <th>Penggunaan Inovasi Teknologi</th>
                                <th>Peningkatan Infrastruktur</th>
                                <?php if ($_SESSION['role'] != 'pimpinan'): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($db->select('*','program')->get() as $data): ?>
                            <tr>
                                <td><?= $data['no'];?></td>
                                <td><?= $data['nama']?></td>
                                <td><?= $data['anggaran']?></td>
                                <td><?= $data['realisasianggaran']?></td>
                                <td><?= $data['efektifitas']?></td>     
                                <td><?= $data['penyerapan']?></td>
                                <td><?= $data['efisiensi']?></td>
                                <td><?= $data['teknologi']?></td> 
                                <td><?= $data['infrastruktur']?></td>
                                <?php if ($_SESSION['role'] != 'pimpinan'): ?>
                                    <td>
                                        <a class="btn btn-warning" href="edit_program.php?id=<?php echo $data['0']?>">Edit</a>
                                        <a class="btn btn-danger" onclick="return confirm('Yakin Hapus?')" href="delete_program.php?id=<?php echo $data['0']?>">Hapus</a>
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
</div>
<?php include 'footer.php'; ?>
<script type="text/javascript">
    $(function(){
        $("#ck").addClass('menu-top-active');
    });
</script>
<script type="text/javascript">
    $(function() {
        $('#example1').dataTable();
    });
</script>
