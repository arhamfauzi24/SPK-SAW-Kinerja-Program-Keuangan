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
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <br/>  
              <div class="panel panel-default">
                  <div class="panel-heading">
                  Form Program Keuangan
                  </div>
                  <div class="panel-body">
                      <form method="post" action="insert_program.php" enctype="multipart/form-data">
                          <?php if (!empty($_GET['error_msg'])): ?>
                              <div class="alert alert-danger">
                                  <?= $_GET['error_msg']; ?>
                              </div>
                          <?php endif ?>
                          <div class="form-group">
                              <label for="nama">No.</label>
                              <input type="text" required class="form-control" id="no" name="no">
                          </div>
                          <div class="form-group">
                              <label for="nama">Nama Program Keuangan</label>
                              <input type="text" required rows="2" class="form-control" id="nama" name="nama">
                          </div>
                          <div class="form-group">
                              <label for="jeniskelamin">Anggaran (RP)</label>
                              <input type="text" required rows="2" class="form-control" id="anggaran" name="anggaran">
                          </div>
                          <div class="form-group">
                              <label for="nama">Realisasi Anggaran</label>
                              <textarea type="text" required class="form-control" id="realisasianggaran" name="realisasianggaran"></textarea>
                          </div>
                          <div class="form-group">
                              <label for="nama">Efektifitas Program</label>
                              <input type="text" required class="form-control" id="efektifitas" name="efektifitas"/>
                          </div>
                          <div class="form-group">
                              <label for="ttl">Penyerapan Anggaran</label>
                              <input type="text" required class="form-control" id="penyerapan" name="penyerapan">
                          </div>
                          <div class="form-group">
                              <label for="ttl">Effisiensi Prograam</label>
                              <input type="text" required class="form-control" id="efisiensi" name="efisiensi">
                          </div>
                          <div class="form-group">
                              <label for="nama">Penggunaan Inovasi dan Teknologi</label>
                              <input type="text" required class="form-control" id="teknologi" name="teknologi">
                          </div>
                          <div class="form-group">
                              <label for="nama">Peningkatan Infrastruktur</label>
                              <input required type="text" class="form-control" id="infrastruktur" name="infrastruktur">
                          </div>
                          <div class="form-group">
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
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
    $(function(){
        $("#ck").addClass('menu-top-active');
    });
</script>