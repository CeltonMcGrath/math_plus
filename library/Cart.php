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
	private $indexer = 0;
 
	public function __construct($u_id, $db) {
   		$this->user_id = $u_id;
   		$this->database = $db;
   		
	   	$query = "SELECT contents, bursaries
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
	   	
	   	$this->contents = cartStringToArray($row['contents']);
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
			$array_tuples[$temp[0]] = array($temp[1], $temp[2], $temp[3]);
		}
		return $array_tuples; 
	}
	
	
	private function cartArrayToString($string) {
	
	}
	
	public function displayCart() {
		foreach ($cart->contents as $cart_itm) {
			$student = new Student($cart_itm[0], $db);
			$total += $student->programCartDisplay($cart_itm[1], $counter);
			$counter++;
		}
	}
    
	/* Adds programs and student to cart. */  
	public function addProgram($student_id, $program_id) {
    	//array_push($cart->contents, array($student_id, $program_id));
    	//$cart->contents[$indexer] = array($student_id, $program_id);
    	//indexer++;
	}
   
	/* Deletes program in cart in position $index. */
	public function deletePrograms($delete_group) {
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
	
	/* .... */
	public function addBursary($bursary_id) {
	
	}

	/* Checks whether bursaries can be applied. */ 
	private function validBursary() {
	
	}
	
}


?>