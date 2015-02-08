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
	
}
   
