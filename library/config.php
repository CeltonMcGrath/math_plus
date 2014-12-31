<?php
	$query = "SELECT * FROM fields";
				
	try {
		$stmt = $db->prepare($query);
		$result = $stmt->execute();
	} catch ( PDOException $ex ) {
		die ( "Failed to run query: " . $ex->getMessage () );
	}
	$rows = $stmt->fetchAll();
	
	foreach ($rows as $row):
		$text_field[${row['name']] = $row['text']; 
	endforeach; 
?>