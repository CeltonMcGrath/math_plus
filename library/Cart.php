<?php
include 'Student.php';

class Cart {
	
	//User whom this cart belongs too
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
	   	} catch ( PDOException $ex ) {
			error_log($ex->getMessage());
		}
	   	$row = $stmt->fetch();
	    
	    if (empty($row)) {
			$this->createNew();		
		}
		else {
	   		$this->contents = $this->cartStringToArray($row['contents']);
		} 	
	}

	/* Creates cart in database. */
	private function createNew() {
		$query = "INSERT INTO cart (user_id, contents)
	   			VALUES
				(:user_id, :contents)";
		
		$query_params = array(':user_id' => $this->user_id, ':contents' => "");
		 
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
		$this->contents = array();
	}
	
	/* Returns array representation of string. */
	private function cartStringToArray($db_string) {
		/* Cart contents is stored in database as string:
		 * "$index1:$student_id1:$program_id1:$bursary_id1;
		 * $index2:$student_id2:$program_id2:$bursary_id2;" */
		$array_strings = explode(";", $db_string);
		$array_tuples = array();
		foreach ($array_strings as $cart_item) {
			$temp = explode(":", $cart_item);
			//echo("<script>console.log('".print_r($temp)."')</script>");
			if (count($temp)>1) {
				$array_tuples[$temp[0]] = array(
					'student_id' => $temp[1],
    				'program_id' => $temp[2], 
    				'bursary_id' => $temp[3]);
			}		
		}
		return $array_tuples; 
	}
	
	/* Returns a string representation of cart array. */
	private function cartArrayToString() {
		$string = "";
		foreach ($this->contents as $index=>$cart_item) {
			$string .= $index.":".implode(":", $cart_item).";";
		}
		return $string;
	}

	public function getContents() {
		return $this->contents;
	}
	
	
	/* Returns an array of cart items, each a tuple 
	 * index => (student name,  program name, cost) */
	public function getFormattedContents() {
		$formatted_contents = array();
		foreach ($this->contents as $index=>$cart_item) {
			
			//Get student name
			$student = new Student($cart_item['student_id'], $this->database);
			$student_name = $student->getName();
			//Get program
			$program = new Program($cart_item['program_id'], $this->database);
			$program_name = $program->getName();
			//Get cost
			if ($cart_item['bursary_id']!=-1) {
				$cost = $this->getBursaryCost($cart_item['bursary_id']);
			}
			else {
				$cost = $program->getCost();
			}
			//Bundle data
			$formatted_contents[$index] = array(
					'student_id' => $cart_item['student_id'],
					'student_name' => $student_name, 
					'program_id' => $cart_item['program_id'],
					'program_name' => $program_name,  
					'cost' => $cost
			);
		}
		return $formatted_contents;
	}

	public static function  getTotal($formattedContents) {
		$total = 0;
		foreach ($formattedContents as $item) {
			$total += $item['cost'];
		}
		return $total;
	}
	
	/* Retrieves bursary cost from database. */
	private function getBursaryCost($bursary_id) {
		$query = "SELECT price
	    		FROM bursaries
	    		WHERE bursary_id = :bursary_id";
		 
		$query_params = array(':bursary_id' => $bursary_id);
		
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		} catch ( PDOException $ex ) {
			error_log($ex->getMessage());
		}
		$row = $stmt->fetch();
		return $row['price'];
	}
    
	/* Adds programs and student to cart. */  
	public function addPrograms($student_id, $selectedPrograms) {
		foreach ($selectedPrograms as $program_id) {
			if (!$this->searchCart($student_id, $program_id)) {
				array_push($this->contents, array('student_id' => $student_id,
					'program_id' => $program_id,
					'bursary_id' => -1));
			}
		}
		$this->syncDatabase();  	
    		return true;
	}
	
	/* Returns true iff student-program id combo found in cart. */
	private function searchCart($student_id, $program_id) {
		foreach ($this->contents as $cart_item) {
			if ($cart_item['program_id']==$program_id && 
					$cart_item['student_id']==$student_id) {
						return true;
					}	
		}
		return false;
	}
   
	/* Deletes program in cart in position $index. */
	public function deletePrograms($selected_programs) {
		foreach ($selected_programs as $index) {
			unset($this->contents[$index]);
		}
		$this->syncDatabase();
	}
	
	/* Registers students in programs and empties cart.
	 * Called upon successful payment. */
	public function registerStudents($transaction_id) {
		
		foreach ($this->contents as $cart_item) {
			// Add student-program entry
			$query = "INSERT INTO students_programs 
					(transaction_id, program_id, student_id)
	   			VALUES
					(:transaction_id, :program_id, :student_id)";
			
			$query_params = array(
					':transaction_id' => $transactionId, 
					':program_id' => $cart_item['program_id'], 
					':student_id' => $cart_item['student_id']
			);
				
			try {
				$stmt = $this->database->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex) {
				error_log($ex->getMessage());
			}
			
			// Record usage of bursary
			if ($cart_item['bursary_id']!=-1) {
				$query = "UPDATE bursaries
	    		SET student_id = :student_id, transaction_id = :transaction_id
	    		WHERE bursary_id = :bursary_id";
				
				$query_params = array (
						':student_id' => $cart['student_id'],
						':transaction_id' => $transactionId
				);
				try {
					$stmt = $this->database->prepare ( $query );
					$result = $stmt->execute ( $query_params );
				} catch ( PDOException $ex ) {
					error_log($ex->getMessage());
				}
			}
		}
		$this->contents = array();
		$this->syncDatabase();
		return true;
	}
	
	/* Returns True iff this cart is empty.*/
	public function isEmpty() {
		return empty($this->contents);
	}
	
	/* Add bursary code. */
	public function applyBursary($bursary_id, $selected_programs) {
		$this->contents[$selected_programs[0]]['bursary_id'] = $bursary_id;
		$this->syncDatabase();
	}
	

	/* Checks whether bursary can be applied to program at $index. 
	 * Bursary may be already used or not exist, or may not be applicable
	 * to program. */
	public function validBursary($bursary_id, $index) {
		$program_id = $this->contents[$index]['program_id'];
		
		$query = "SELECT *
	    		FROM bursaries
	    		WHERE bursary_id = :bursary_id AND program_id = :program_id AND student_id = 0";
		 
		$query_params = array(
				':bursary_id' => $bursary_id, 
				':program_id' => $program_id
		);
		
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		} catch ( PDOException $ex ) {
			error_log($ex->getMessage());
		}
		$row = $stmt->fetch();
		if (empty($row)) {
			//Valid bursary with bursary does not exist.
			return false;
		}
		else {
			// Valid bursary exists
			return true;
		}
	}
	
	/* Updates database record of shopping cart contents. */
	private function syncDatabase() {
		$query = "UPDATE cart
	    		SET contents = :contents
	    		WHERE user_id = :user_id";
		
		$query_params = array (
				':contents' => 
					$this->cartArrayToString(),
				':user_id' => $this->user_id
		);	
		try {
			$stmt = $this->database->prepare ( $query );
			$result = $stmt->execute ( $query_params );
		} catch ( PDOException $ex ) {
			error_log($ex->getMessage());
		}
	}
	
	/* Stores transaction details. */
	public function saveTransaction($data) {		
		$value_array_string = "";
		$key_array_string = "";		
		$query_params = array();
	        $data['user_id'] = $this->user_id;	
		foreach ($data as $index=>$value) {
			$query_params[":".$index] = $value;
			if ($value_array_string != "") {
				$value_array_string .= ", ".$index;
				$key_array_string .= ", :".$index;
			}
			else {
				$value_array_string .= $index;
				$key_array_string .= ":".$index;
			}			
		}
		
		$query = "INSERT into transactions (".$value_array_string.")
				Values (".$key_array_string.")";
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		}		
		catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
			
		$transaction_id = $this->database->lastInsertId();
		return $transaction_id;
	}
	
	
 }


?>
