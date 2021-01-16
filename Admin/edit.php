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
    $id = $_POST['id'];;
    $title = $_POST['title'];
    $content = $_POST['content'];

    if($_FILES['image']['name'] != null){
      $file = 'images/'.($_FILES['image']['name']);
      $imageType = pathinfo($file,PATHINFO_EXTENSION);

      if($imageType != 'jpg' && $imageType != 'png' && $imageType != 'jpeg') {
        echo "<script>alert('Image must be png,jpg,jpeg')</script>";
      } else{
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $file);
      
        $stat = $pdo->prepare("UPDATE posts SET title='$title', content='$content', image='$image' WHERE id='$id'");
        $result = $stat->execute();
        if($result){
          echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
        }
      }
    } else{
        $stat = $pdo->prepare("UPDATE posts SET title='$title', content='$content' WHERE id='$id'");
        $result = $stat->execute();
        if($result){
          echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
        }
    }
  }

  $stat = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
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
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" value="<?php echo $result[0]['title'];?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Content</label>
                    <textarea name="content" class="form-control" rows="8" cols="80"><?php echo $result[0]['content'];?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><br>
                    <input type="file" name="image" value=""><br><br>
                    <img src="images/<?php echo $result[0]['image'];?>" width="150px" heigh="150px" alt="">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="UPDATE">
                    <a href="index.php" type="button" class="btn btn-warning" name="">Back</a>
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