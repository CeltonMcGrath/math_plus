<?php

// Load data into array
$rows = array();
if (($handle = fopen("invoice_data.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    	array_push($rows, $data);
    }
    fclose($handle);
}

// Get the roast date
$GLOBALS['roast_date'] = $rows[0][0];
$package_size = 0; 

//Client array 
$labels_by_client = array();

foreach ($rows as $index=>$row) {
	if ($index == 0) {
		$GLOBALS['roast_date'] = $row[0];
	}
	elseif ($index < 3) {
		continue;
	}
	elseif (!empty($row[0])) {
		$package_size = $row[0];
	}
	else {
		$client = $row[1];
		$product = $row[2];
		$qty = $row[3];		
		// New client
		if (!isset($labels_by_client[$client])) {
			$product_array  = array($product => $qty);
			$labels_by_client[$client] = array(
				'products' 		=> $product_array, 
				'package_size' 	=> $package_size
			);
		} 
		// New product
		elseif (!isset($labels_by_client[$client]['products'][$product])) {
			$labels_by_client[$client]['products'][$product] = $qty;
		} 
		// Add qty
		else {
			$labels_by_client[$client]['products'][$product] += $qty;
		}
	}
}

function print_batch($client_name, $product, $qty, $package_size) {
	$amount_remaining = $qty;
	$actual_size = 0;
	while ($amount_remaining > 0) {
		if ($amount_remaining < $package_size) {
			$actual_size = $amount_remaining;
		}
		else {
			$actual_size = $package_size;
		}
		print_label($client_name, $product, $actual_size);
		$amount_remaining -= $actual_size;
	}	
}

function print_label($client_name, $product, $actual_size) {
	echo "<div class='label'>
			Custom roasted for <br/>
			<b>$client_name</b> <br/>
			By<br/>
			<b>Fresh Coffee Network</b> <br/>
			Coffee<br />
			<b>$product</b><br/>
			Weight<br/>
			<b>".$actual_size." Lbs.</b><br/>
			Roast Date<br/>
			<b>".$GLOBALS['roast_date']."</b><br/>
		</div>
		<footer></footer>";
}

?>

<html>
<head>
	<style>
		@font-face {
	    	font-family: 'Concourse'; /*a name to be used later*/	
	    	src: url('concourse.ttf') format('truetype'); /*URL to font*/
		}

		.label {
			font-family: 'Concourse';
			margin-top: 430pt;
			margin-bottom: 4pt;
			margin-left: 30pt;
			font-size: 80%;
		}

		@media print {
			footer {page-break-after: always;}
		}
	</style>
</head>
<body>


<?php

foreach ($labels_by_client as $client_name=>$client_data) {
	$products_array = $client_data['products'];
	$package_size = $client_data['package_size'];
	foreach ($products_array as $product=>$qty) {
		print_batch($client_name, $product, $qty, $package_size);
	}
}

?>

</body>
</html>





