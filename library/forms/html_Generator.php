<?php

class html_Generator {

	public function bootstrapAccordion($id, $label, $article) {
		return "
				<div class='accordion-group'>
				    <div class='accordion-heading'>
				      <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse-".$id."'>
				        ".$label."
				      </a>
				    </div>
				    <div id='collapse-".$id."' class='accordion-body collapse'>
				      <div class='accordion-inner'>
				        ".$article."
				      </div>
				    </div>
			 	</div>
				";
	}
	
	public function errorMessage($message) {
		if ($message=="") {
			return "";
		}
		else {
			return "
				<div class='alert alert-danger' role='alert'>
					$message
				</div>";
		}	
	}

	public function successMessage($message) {
		if ($message=="") {
			return "";
		}
		else {
			return "
			<div class='alert alert-success' role='alert'>
				$message
			</div>";
		}
	}
	
}
