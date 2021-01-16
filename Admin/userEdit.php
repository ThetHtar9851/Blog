<?php
  session_start();
  require '../config/config.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  if($_SESSION['role'] != 1) {
    header('Location: login.php');  
  }

  if($_POST){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($_POST['role'])) {
      $role = 0;
    }else {
      $role = 1;
    } 

      $stat = $pdo->prepare("SELECT * FROM users WHERE id!=:id AND email=:email");
      $stat->execute(array(':id'=>$id,':email'=>$email));
      $user = $stat->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        echo "<script>alert('Email Duplicated');</script>";
      }else {
        $stat = $pdo->prepare("UPDATE users SET name='$name', email='$email', password='$password', role='$role' WHERE id='$id'");
        $result = $stat->execute();
        if($result){
          echo "<script>alert('Successfully Updated');window.location.href='userList.php';</script>";
      }
    }
  }

  $stat = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $stat->execute();
  $result = $stat->fetchAll();

?>

<?php include 'header.php';?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <form class="" action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $result[0]['id']?>">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">E-mail</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $result[0]['email'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control" name="password" value="<?php echo $result[0]['password'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Admin</label>
                    <?php if($result[0]['role'] == 1) { ?>
                      <input type="checkbox" class="" name="role" value="1" checked>
                    <?php }else{ ?>
                      <input type="checkbox" class="" name="role" value="1" unchecked>
                    <?php } ?>  
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="UPDATE">
                    <a href="userList.php" type="button" class="btn btn-warning" name="">Back</a>
                  </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

 <?php include 'footer.html'; ?>