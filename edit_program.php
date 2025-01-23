<?php
    session_start();
    error_reporting(0);
    if(empty($_SESSION['id'])){
        header('location:login.php?error_login=1');
    }
?>
<?php include 'header.php';?>
<?php include 'menu.php';?>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <br/>  
              <div class="panel panel-default">
                  <div class="panel-heading">
                  Form Karyawan
                  </div>
                  <div class="panel-body">
                      <form method="post" action="update_program.php" enctype="multipart/form-data">
                          <?php if (!empty($_GET['error_msg'])): ?>
                              <div class="alert alert-danger">
                                  <?= $_GET['error_msg']; ?>
                              </div>
                          <?php endif ?>
                          <?php foreach ($db->select('*','program')->where('id_calon_kr='.$_GET['id'])->get() as $val): ?>
                              <input type="hidden" name="id_calon_kr" value="<?= $val['id_calon_kr']?>">
                              <div class="form-group">
                                  <label for="nama">No</label>
                                  <input type="text" readonly class="form-control" id="no" name="no" value="<?= $val['no']?>">
                              </div>
                              <div class="form-group">
                                  <label for="nama">Nama Karyawan</label>
                                  <input type="text" class="form-control" id="nama" name="nama" value="<?= $val['nama']?>">
                              </div>
                              <div class="form-group">
                                  <label for="nama">Anggaran</label>
                                  <input type="text" class="form-control" id="nama" name="anggaran" value="<?= $val['anggaran']?>">
                              </div>
                              <div class="form-group">
                                  <label for="nama">Realisasi Anggaran</label>
                                  <input type="text" class="form-control" id="nama" name="realisasianggaran" value="<?= $val['realisasianggaran']?>">
                             </div>
                             <div class="form-group">
                                  <label for="nama">Efektifitas Program</label>
                                  <input type="text" class="form-control" id="nama" name="efektifitas" value="<?= $val['efektifitas']?>">
                              </div>
                              <div class="form-group">
                                  <label for="nama">Penyerapan Anggaran</label>
                                  <input type="text" class="form-control" id="nama" name="penyerapan" value="<?= $val['penyerapan']?>">
                              </div>
                              <div class="form-group">
                              <label for="nama">Inovasi Dan Teknologi</label>
                              <input type="text" required class="form-control" id="teknologi" value="<?= $val['teknologi']?>" name="teknologi">
                                </div>
                                <div class="form-group">
                                    <label for="nama">Peningkatan Infrastruktur</label>
                                    <input required type="text" class="form-control" id="infrastruktur" value="<?= $val['infrastruktur']?>" name="infrastruktur">
                                </div>
                              <div class="form-group">
                                  <label for="skill">Efisiensi Anggaran (%)</label>
                                  <input type="text" class="form-control" id="efisiensi" name="efisiensi" value="<?= $val['efisiensi'] ?>">
                              </div>
                              <div class="form-group">
                                  <button class="btn btn-primary">Simpan</button>
                              </div>
                          <?php endforeach ?>
                      </form>
                  </div>
              </div>
            </div>
        </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
    $(function(){
        $("#ck").addClass('menu-top-active');
    });
</script>