<?php
  session_start();
  require 'config/config.php';
  require 'config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  if($_SESSION['role'] != 0) {
    header('Location: login.php');  
  }

  $stat = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stat->execute();
  $result = $stat->fetchAll();

  $blogId = $_GET['id'];

  $statcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
  $statcmt->execute();
  $cmResult = $statcmt->fetchAll();

  $auResult = [];
  if($cmResult) {
    foreach ($cmResult as $key => $value) {
      $authorId = $cmResult[$key]['author_id'];
      $statau = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
      $statau->execute();
      $auResult[] = $statau->fetchAll();
    }
  }  

  if($_POST){
    if(empty($_POST['comment'])) {
        $commentError = "Comment is required";
    }else {
      $blog_id = $_GET['id'];
      $comment = $_POST['comment'];

      $stat = $pdo->prepare("INSERT INTO comments(content,post_id,author_id) VALUES(:content,:post_id,:author_id)");
      $result = $stat->execute(
        array(
          ':content'=>$comment,
          ':post_id'=>$blog_id,
          ':author_id'=>$_SESSION['user_id']
        )
      ); 
      if ($result) {
           header('Location: blogDetail.php?id='.$blogId);
      }
    }   
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left: 0px !important;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <!-- Box Comment -->
        <div class="card card-widget">
          <div class="card-header">
            <div class="card-title" style="text-align: center;float: none;"><h2><?php echo escape($result[0]['title']);?></h2>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <img src="Admin/images/<?php echo escape($result[0]['image']);?>" alt=""><br><br> 
            <p><?php echo escape($result[0]['content']);?></p>
            <h3>Comment</h3><hr>
            <a href="/blog" class="btn  btn-default" type="button">Back</a>
          </div>
          <!-- /.card-body -->
          <div class="card-footer card-comments">
            <div class="card-comment">
              <?php if ($cmResult) { ?>
                <div class="comment-text" style="margin-left: 0px !important;">
                  <?php foreach ($cmResult as $key => $value) { ?>                      
                    <span class="username">
                    <?php echo escape($auResult[$key][0]['name']);?>
                    <span class="text-muted float-right"><?php echo escape($value['created_at']);?></span>
                  </span><!-- /.username -->
                  <?php echo escape($value['content']);?><br>
                <?php
                  }
                ?>
                </div>
              <!-- /.row -->
              <?php
                  }                
              ?> 
              <!-- /.comment-text -->
            </div>
          </div>
          <!-- /.card-footer -->
          <div class="card-footer">
            <form action="" method="post">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <p style="color:red";><?php echo empty($commentError) ? '' : '*'.$commentError; ?></p>
              <div class="img-push">
                <input name="comment" type="text" class="form-control form-control-sm" placeholder="Press enter to post comment">
              </div>
            </form>
          </div>
          <!-- /.card-footer -->
        </div>
    </div>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer col-md-12" style="margin-left: 0px !important;">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-default">Log Out</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="#">A Programmer</a>.</strong> All rights reserved.
  </footer>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
