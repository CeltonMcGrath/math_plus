<?php
class Program {
	
	public $program_id;
	private $program_name;
	private $cost;
	private $capacity;	
	private $start_date;	
	private $end_date;
	private $registration_deadline;
	private $grades;
	private $description;
	private $database;    
 
	public function __construct($p_id, $db) {
   		/* Returns program object with progrma-id p_id */
   		$this->program_id = $p_id;
   		$this->database = $db;
   		
   		/*Retrieves student data from db.*/
	   	$query = 'SELECT program_name, cost, capacity, start_date, 
	   			end_date, registration_deadline, grades, description
	   			FROM programs
	    		WHERE program_id = :program_id';
	   	
	   	$query_params = array(':program_id' => $this->program_id);
	   	 
	   	try {
	   		$stmt = $this->database->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
	   	}
	   	$row = $stmt->fetch();
	   	
	   	$this->program_name = $row['program_name']; 
	   	$this->cost = $row['cost']; 
	   	$this->capacity = $row['capacity']; 
	   	$this->start_date = $row['start_date']; 
	   	$this->end_date = $row['end_date']; 
	   	$this->registration_deadline = $row['registration_deadline'];
	   	$this->grades = $row['grades'];
	   	$this->description = $row['description'];
   }
   
  	/* Returns undiscounted cost of program. */
	public function getCost() {
		return $this->cost;
	}
	
	/* Returns the remaining number of spots in this program. */
 	private function remainingSpots() {
 		$query = 'SELECT COUNT(*)
	   			FROM programs_students
	    		WHERE program_id = :program_id';
	   	
	   	$query_params = array(':program_id' => $this->program_id);
	   	 
	   	try {
	   		$stmt = $this->database->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
	   	}
	   	
	   	return $this->capacity - $result;
 	}
	
 	/* Displays program article for programs.php */
 	public function displayArticle() {
 		echo "<article>
		 			<ul>
			 			<li>Start date: ".$this->start_date."</li>
			 			<li>End date: ".$this->end_date."</li>
			 			<li>Registration deadline:
			 			".$this->registration_deadline."
			 			</li>
			 			<li>Grade levels: ".$this->grades."</li>
			 			<li>Description: ".$this->description."</li>
		 			</ul>
 				</article>
 			</div>";
 	}
 	
	/* Display program label for programs.php if student is not
	 * registered in program. */
	public function displayLabelForSelectionOne() {
		echo "
			<label for='".$this->program_id."'>
 				<input class='regular' name='program_group[]'
 					value='".$this->program_id."' type='checkbox'/>
 				".$this->program_name.", (".$this->remainingSpots()."
		 		spots remaining) Fee: ".$this->cost."
		 	</label>";
	}
	
	/* Display program label for programs.php if student is
	 * registered in program. */
	public function displayLabelForSelectionTwo() {
		echo "<label for='".$this->program_id."'>
				".$this->program_name." (<i>Registered</i>)
			</label>";		 
	}
	
	/* Displays program label for program.php is program has full capacity.*/
	public function displayLabelForSelectionThree() {
		echo "<label for='".$this->program_id."'>
				".$this->program_name." (Sorry - full capacity)
			</label>";
	}
	
	/* Displays shopping cart entry with removal option for cart.php */
	public function displayForCart($studentName, $counter) {
		echo "
		<li>
			<section id="accordion">
				<div class="contact">
	   				<label>
	   					".$studentName." - ".$this->program_name." 
	   					- ".$this->cost." (Remove:
						<input class='regular' name='delete_group[]'
							value='".$counter."' type='checkbox'/>)
	   				</label>
				</div>
			</section>
		</li>
		";
	}
  
   
}