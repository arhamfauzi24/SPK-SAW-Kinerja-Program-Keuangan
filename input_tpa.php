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
                    Form Kriteria
                  </div>
                  <div class="panel-body">
                      <form method="post" action="insert_tpa.php" enctype="multipart/form-data">
                          <?php if (!empty($_GET['error_msg'])): ?>
                              <div class="alert alert-danger">
                                  <?= $_GET['error_msg']; ?>
                              </div>
                          <?php endif ?>
                          <div class="form-group col-md-12">
                              <div class="alert alert-info">
                                  <i class="fa fa-info-circle"></i> Nama Yang Ditampilkan adalah Nama siswa yang Belum Dinilai...
                              </div>
                              <label for="nama">Nama siswa </label>
                                  <select required class="form-control" name="id_siswa">
                                  <?php  foreach ($db->select('*','siswa')->where('id_siswa not in (select id_siswa from hasil_tpa)')->get() as $val): ?> 
                                  <option value="<?= $val['id_siswa']?>"><?= $val['nama'] ?></option>
                                  <?php endforeach ?>
                                  </select>
                          </div>
                          
                          <?php foreach ($db->select('id_kriteria,kriteria','kriteria')->get() as $r): ?>
                          <div class="form-group col-md-3">
                              <label><?= $r['kriteria']?></label>
                              <!-- <input type="number" name="place[]" class="form-control"> -->
                              <select required class="form-control" name="place[]">
                                <?php  foreach ($db->select('*','sub_kriteria')->where('id_kriteria = '.$r['id_kriteria'].'')->get() as $val): ?> 
                                <option value="<?= $val['id_subkriteria']?>"><?= $val['subkriteria'] ?> (Nilai = <?= $val['nilai'] ?>)</option>
                                <?php endforeach ?>
                                </select>
                          </div>
                          <?php endforeach ?>
                          
                          <div class="form-group col-md-12">
                              <button class="btn btn-primary">Simpan</button>
                          </div>
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
        $("#tpa").addClass('menu-top-active');
    });
</script>