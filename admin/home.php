<?php
ob_start();
session_start();
if( !isset($_SESSION["user_id"])){
 header("Location: ./login.php");
}
include(__DIR__ . '/header.php');
include(__DIR__ . '/../app/Blog.php');
use app\Blog;

$blog=new Blog;
$posts=$blog->index();
$alertMsg=isset($_SESSION["alert"]["msg"])?$_SESSION["alert"]['msg']:null;
$alertType=isset($_SESSION["alert"]["type"])?$_SESSION["alert"]['type']:null;
if(isset($_POST["alert"])){
  $_SESSION["alert"]=null;
} 
ob_end_flush();
?>
<!-- Main content -->
   
<div class="content">
  
  <div class="container-fluid">
    <div class="card">
      <div class="wrapper">
      
      <div class="card-header">
        <h3 class="card-title">Blog List</h3>
      </div>
      <?php if (isset($_SESSION["alert"])) { ?>
       <form action="" method="POST">
         <input type="hidden" name="alert" >
        <div class="alert 
        <?= $alertType=="error"? "alert-danger":"alert-success" ?>  alert-dismissible fade show" role="alert">
          <?=$alertMsg?>
          <button type="submit" class="close" >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       </form>
    <?php } ?>

      <!-- /.card-header -->
      <div class="card-body">
        <a href="/blog/admin/create.php" class="btn btn-success mb-3">Create New Blog</a>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Tittle</th>
              <th>Content</th>
              <th>Image</th>
              <th style="width: 40px">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($posts as $key => $post) {?>
              <tr>
                <td><?= $post["id"]?></td>
                <td><?=substr($post["title"],0,50)?></td>
                <td><?=substr($post["content"],0,100)?></td>
                <td> 
                  <div  style="width:100px;margin-top:10px;height:100px">
                  <img src="../upload/blog/<?=$post["image"]?>" class=" w-100" alt="">
                  </div>
                </td>
                <td>
                  <div class="d-flex justify-content-between">
                    <a href="/blog/admin/edit.php?id=<?php echo $post['id']?>" class="btn btn-warning mr-2" >edit</a>
                    <a href="./delete.php?id=<?php echo $post['id']?>" onclick="return confirm('Are you sure to delete it')" class="btn btn-danger">delete</a>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
     <?php
        $current=$_SESSION['current_page'];
        $total=$_SESSION['total_page'];
        $btn_limit=2;
     ?>
      <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-right">
          <li class="page-item <?=$current<=1?'disabled':''?>">
            <a class="page-link" href="?<?=isset($_GET['search'])?'search='.$_GET['search'].'&':''?>page=<?=$current-1?>">&laquo;</a>
          </li>
          <li class="page-item <?=$current==$total && $total>$btn_limit  ?'d-block':'d-none'?>"><a class="page-link" href="?<?=isset($_GET['search'])?'search='.$_GET['search'].'&':''?>page=<?=$current-2?>"><?=$current-2?></a></li>
          <li class="page-item <?=$current-1< 1 ?'d-none':''?>"><a class="page-link" href="?<?=isset($_GET['search'])?'search='.$_GET['search'].'&':''?>page=<?=$current-1<1?1:$current-1?>" ><?=$current-1?></a></li>
          <li class="page-item active"><a class="page-link" href="?<?=isset($_GET['search'])?'search='.$_GET['search'].'&':''?>page=<?=$current?>"><?=$current?></a></li>
          <li class="page-item <?=$current+1 > $total?'d-none':''?>"><a class="page-link" href="?<?=isset($_GET['search'])?'search='.$_GET['search'].'&':''?>page=<?=$current+1?>"><?=$current+1?></a></li>
          <li class="page-item <?=$current==1 && $total>$btn_limit   ?'d-block':'d-none'?>"><a class="page-link" href="?<?=isset($_GET['search'])?'search='.$_GET['search'].'&':''?>page=<?=$current+2?>"><?=$current+2?></a></li>
          <li class="page-item  <?=$current==$total?'disabled':''?>">
            <a class="page-link" href="?<?=isset($_GET['search'])?'search='.$_GET['search'].'&':''?>page=<?=$current+1?>">&raquo;</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
</div>
<?php include(__DIR__ . '/footer.php');?>
