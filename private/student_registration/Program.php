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
	   			end_date, registration_date, grades, description
	   			FROM programs
	    		WHERE program_id = :program_id';
	   	
	   	$query_params = array(':program_id' => $this->program_id);
	   	 
	   	try {
	   		// Execute the query against the database
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

	public static function createProgram($name_0, $cost_0,
   		$cap, $s_date, $e_date, $r_date, $gr, $des, $db) {
   	    /*Creates program in database.*/
   		
		$query = "INSERT INTO programs (program_name, cost, 
				capacity, start_date, end_date, registration_deadline, 
				grades, description) 
	   			VALUES
				(:program_name, :cost, :capacity, :start_date, :end_date, 
				:registration_deadline, :grades, :description)";
	   	 
		$query_params = array(
				':program_name' => $name_0,
				':cost' => $cost_0;
				':capacity' => $cap,
				':start_date' => $s_date,
				':end_date' => $e_date,
				':registration_deadline' => r_date,
				':grades' => $gr, 
				':description' => $des,
	   	);
	   	
	   	try	{
	   		// Execute the query against the database
	   		$stmt = $db->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
	   	}
	   	return True;
   }
   
	public function getCost {
		return this->$cost;
	}
	
	/* Returns the remaining number of spots in this program. */
 	private function remainingSpots($program_id) {
 		return 0;
 	}
	
	/* Display program for program for programs.php if student is not
	 * registered in program.
	 */
	public function displayProgramForSelectionOne() {
		echo "
		<div class='contact'>
			<input class='accordion' type='checkbox' id='".$this->program_id."'/>
			<label for='".$this->program_id."'>
 				<input class='regular' name='program_group[]'
 					value='".$this->program_id."' type='checkbox'/>
 				".$programRow['program_name'].", (".$this->remainingSpots()."
		 		spots remaining) Fee: ".$this->cost."
		 	</label>
			<article>
			 	<ul>
			 		<li>Start date: ".$this->start_date."</li>
			 		<li>End date: ".$this->end_date."</li>
			 		<li>Registration deadline:
			 			".$this->registration_deadline."
					</li>
			 		<li>Grade levels: ".$this->grades."</li>
			 		<li>Description: ".$this->description"</li>
			 	</ul>
			 </article>
		 </div>";
	}
	
	/* Display program for program for programs.php if student is
	 * registered in program.
	 */
	public function displayProgramForSelectionTwo($status) {
		echo "
		<div class='contact'>
			<input class='accordion' type='checkbox' id='".$this->program_id."'/>
			<label for='".this->$program_id."'>
				".$this->program_name." (Status: ".$status.")
			</label>
			<article>
			 	<ul>
			 		<li>Start date: ".$this->start_date."</li>
			 		<li>End date: ".$this->end_date."</li>
			 		<li>Registration deadline:
			 			".$this->registration_deadline."
					</li>
			 		<li>Grade levels: ".$this->grades."</li>
			 		<li>Description: ".$this->description"</li>
			 	</ul>
			 </article>
		 </div>";
	}
	
	/* Displays shopping cart entry with removal option for cart.php */
	public function displayProgramForCart($studentName) {
		echo "
		<article>
			".$studentName." - ".$this->program_name." ".$this->cost."   
					     Remove:
			<input class='regular' name='delete_group[]'
				value='".$this->program_id."' type='checkbox'/>
		</article>
		";
	}
  
   
}