<?php 
    require("../library/common.php");    
    
    if (empty($_GET)) {
    	$content = "user_guide.html";
    }
    else {
    	$content = $_GET['view'].".html";
    }
?>

<!DOCTYPE html>
<html lang="en">
  <?php include '../library/site_template/head_private_area.php' ?>
  <body>
	<?php include '../library/site_template/navbar.php' ?>   
    <div class="container">
      <div class="jumbotron">
        <?php include "../resources/".$content; ?>
      </div>
    </div>
  </body>
</html>