<?php 
    require("../library/common.php");     
    include '../library/Guardian.php';
       
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
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
?> 
	<section class="content">
		<h1>Frequently asked questions</h1>
			<section id="accordion">
			<?php 
			foreach ($rows as $row) {
			echo "<div class='contact'>
					<input class='accordion' type='checkbox' 
    					id='".$row['id']."' />
					<label for='".$row['id']."'>".$row['question']."</label>
					<article><p>".$row['answer']."</p></article>
				</div>";	
			endforeach;
			?>					
			</section>
	</section>
	<?php include '../library/site_template/footer.php';?>
</html>

