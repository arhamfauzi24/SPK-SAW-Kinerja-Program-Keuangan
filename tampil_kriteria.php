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
                    <h4 class="page-head-line">Data Kriteria</h4>
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
                    <div><a href="input_kriteria.php" class="btn btn-info">Tambah Data</a></div>
                    <br>
                <?php endif; ?>
                <div class="table-responsive">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <?php if ($_SESSION['role'] != 'pimpinan'): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($db->select('*','kriteria')->get() as $data): ?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $data['kriteria']?></td>
                                <td><?= $data['bobot']?></td>
                                <?php if ($_SESSION['role'] != 'pimpinan'): ?>
                                    <td>
                                        <a class="btn btn-warning" href="edit_kriteria.php?id=<?php echo $data['0']?>">Edit</a>
                                        <a class="btn btn-danger" onclick="return confirm('Yakin Hapus?')" href="delete_kriteria.php?id=<?php echo $data['0']?>">Hapus</a>
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
        $("#ds").addClass('menu-top-active');
    });
</script>
<script type="text/javascript">
    $(function() {
        $('#example1').dataTable();
    });
</script>
