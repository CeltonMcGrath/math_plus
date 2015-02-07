<?php 
    require("../library/common.php");     
    include '../library/Guardian.php';
    include '../library/forms/html_Generator.php';
       
	/* Load FAQ items from database */
    $query = "SELECT * FROM faq";
     
    try {
    	$stmt = $db->prepare($query);
    	$result = $stmt->execute();
    }
    catch(PDOException $ex)  {
    	echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
    }
    $rows = $stmt->fetchAll();
    $hg = new html_Generator();
?>

<!DOCTYPE html>
<html lang="en">
  <?php include '../library/site_template/head_private_area.php' ?>
  <body>
	<?php include '../library/site_template/navbar.php' ?>   
    <div class="container">
      <div class="jumbotron">
        <h3>Frequently asked questions</h3>
        	<div class="accordion" id="accordion">
			<?php 
			foreach($rows as $row):
				echo $hg->bootstrapAccordion($row['id'], $row['question'], $row['answer']);	
			endforeach;
			?>	
			</div>
      </div>
    </div>
    
    <?php include '../library/site_template/body_end.php' ?>
  </body>
</html>

