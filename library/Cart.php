<?php
class Cart {
	
	// User who cart belongs too
	private $user_id;
	
	/* $contents - array of 3-tuples:
	 * ($index, $student_id, $program_id, $bursary_id)
	 * Each 3 tuple represent one shopping cart item.
	 * $bursary_id is default false if no bursary applied
	 */ 
	private $contents;
	
	private $database;
 
	public function __construct($u_id, $db) {
   		$this->user_id = $u_id;
   		$this->database = $db;
   		
	   	$query = "SELECT contents
	    		FROM cart 
	    		WHERE user_id = :user_id";
	   	
	   	$query_params = array(':user_id' => $this->user_id);
	   	 
	   	try {
	   		$stmt = $this->database->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		return False;
	   	}
	   	$row = $stmt->fetch();
	   	
	   	$this->contents = this->cartStringToArray($row['contents']);
	}

	/* Returns array representation of string. */
	private function cartStringToArray($string) {
		/* Cart contents is stored in database as string:
		 * "$index1:$student_id1:$program_id1:$bursary_id1;
		 * $index2:$student_id2:$program_id2:$bursary_id2;" */
		$array_strings = explode(";", $string);
		$array_tuples = array();
		foreach ($array_strings as $cart_item) {
			$temp = explode(":", $cart_item);
			$array_tuples[$temp[0]] = array(
				'student_id' => $temp[1],
    			'program_id' => $temp[2], 
    			'bursary_id' => $temp[3]);
		}
		return $array_tuples; 
	}
	
	/* Returns a string representation of cart array. */
	private function cartArrayToString($array) {
		$string = "";
		foreach ($array as $cart_item) {
			$string .= implode(":", $cart_item).";";
		}
		return $string;
	}
	
	/* Displays cart for site/cart.php */
	public function displayCart() {
		foreach ($cart->contents as $cart_itm) {
			$student = new Student($cart_itm['student_id'], $db);
			
			$total += $student->programCartDisplay($cart_itm[1], $counter);
			$counter++;
		}
	}
    
	/* Adds programs and student to cart. */  
	public function addProgram($student_id, $program_id) {
    	array_push($cart->contents, 
    		array('student_id' => $student_id,
    		'program_id' => $program_id, 
    		'bursary_id' => false));
    	return true;
	}
   
	/* Deletes program in cart in position $index. */
	public function deletePrograms($selected_programs) {
		foreach ($_POST['delete_group'] as $index) {
			unset($cart->contents($index));
		}
	}
	
	/* Registers students in programs and empties cart.
	 * Called upon successful payment. */
	public function registerStudents($transactionId, $orderTime, $amt) {
		
	}
	
	/* Returns True iff this cart is empty.*/
	public function isEmpty() {
		return empty($cart->contents);
	}
	
	/* Add bursary code. */
	public function applyBursary($bursary_id, $selected_programs) {
		$cart->contents[$selected_program[0]]['bursary_id'] = $bursary_id;
	}

	/* Checks whether bursary can be applied to program at $index. 
	 * Bursary may be already used or not exist, or may not be applicable
	 * to program. */
	public static function validBursary($bursary_id, $index) {
		$query = "SELECT *
	    		FROM bursaries
	    		WHERE bursary_id = :bursary_id, status = 0";
		 
		$query_params = array(':bursary_id' => $$bursary_id);
		
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			return False;
		}
		$row = $stmt->fetch();
		if (empty($row)) {
			return false;
		}
		elseif ($row[0]['program_id']) {
			
		}
		else {
			return true;
		}
	}
	
	private static syncDatabase() {
		
	}
 }


?>