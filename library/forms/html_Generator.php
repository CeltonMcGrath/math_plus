<?php

class html_Generator {

	/* Returns an html contact for accordion */
	public function accordionBox($id, $label, $article) {
		return "<div class='contact'>
		   	<input class='accordion' type='checkbox' id=".$id." />
		   	<label for=".$id.">
		   		".$label."
		   	</label>
		   	<article>
		   		".$article."
		   	</article>
	   		</div>";
	}
	
}
   
