<?php
include 'Student.php';

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
	
	/* Displays cart for site/cart.php and returns the cost of this program
	 * with bursaries tooken into account.*/
	public function displayCart() {
		$cart_total = 0;
		foreach ($this->contents as $index=>$cart_item) {
			//Get student name
			$student = new Student($cart_item['student_id'], $this->database);
			$student_name = $student->getName();
			//Get program name and cost
			$program = new Program($cart_item['program_id'], $this->database);
			$program_name = $program->getName();
			if ($cart_item['bursary_id']!=-1) {
				$cost = $this->getBursaryCost($cart_item['bursary_id'])." (Bursary applied.)";
			}
			else {
				$cost = $program->getCost();
			}
			
			echo "
			<li>
				<section id='accordion'>
					<div class='contact'>
	   					<label>
	   						<input class='regular' name='selected_programs[]'
								value='".$index."' type='checkbox'/>
							".$student_name." - ".$program_name."
	   						- ".$cost."
	   					</label>
					</div>
				</section>
			</li>
			";
			$cart_total += $cost;
		}
		return $cart_total;
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
			array_push($this->contents, 
    		array('student_id' => $student_id,
    		'program_id' => $program_id, 
    		'bursary_id' => -1));
		}
		$this->syncDatabase();  	
    		return true;
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
	public function registerStudents($transactionId, $orderTime, $amt) {
		// Create transaction
		$query = "INSERT INTO transactions (transaction_id, user_id, date, amount)
	   			VALUES
				(:transaction_id, :user_id, :date, :amount)";
			
		$query_params = array(
				':transaction_id' => $transactionId, 
				':user_id' => $this->user_id,
				':date' => $orderTime,
				':amount' => $amt
		);
		
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
		
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
	
 }


?>
