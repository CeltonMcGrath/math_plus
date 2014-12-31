<?php
	$query = "SELECT * FROM fields";
				
	try {
		$stmt = $db->prepare($query);
		$result = $stmt->execute();
	} catch ( PDOException $ex ) {
		echo("<script>console.log('PHP: ".$ex->getMessage()."')
	   				</script>");
	}
	$rows = $stmt->fetchAll();
	$GLOBALS['text_field'] = array();	
	foreach ($rows as $row):
		$GLOBALS['text_field'][$row['name']] = $row['text']; 
	endforeach;
?>
