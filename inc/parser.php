<?php
// Parser
// Basic formatting
$rules = array(
	new SimpleReplace("\r\n----\r\n", "\r\n<hr/>"),

	new LineFormattingSyntax( "======" , "h6" ),
	new LineFormattingSyntax( "=====" , "h5" ),
	new LineFormattingSyntax( "====" , "h4" ),
	new LineFormattingSyntax( "===" , "h3" ),
	new LineFormattingSyntax( "==" , "h2" ),

	new FormattingSyntax( "**", "strong" ),
	new FormattingSyntax( "__", "u" ),
	new FormattingSyntax( "//", "i" ),
	new FormattingSyntax( "--", "del" ),

	new SimpleReplace("\n", "<br/>")
);

function parse_code($input){
	global $rules;

	foreach($rules as $rule){
		$input = $rule->render($input);
	}

	return $input;
}

class Syntax{
	public $regex = "%{delimiter1}(()|[^'].*){delimiter2}%U";

	public function __construct($delimiter, $delimiter2, $tag){
		$this->delimiter = $delimiter;
		$this->regex = str_replace("{delimiter1}", preg_quote($delimiter), $this->regex);
		$this->regex = str_replace("{delimiter2}", preg_quote($delimiter2), $this->regex);
		$this->tag = $tag;
	}

	public function _cbac($matches){
		return "<" . $this->tag . ">". $matches[1] . "</" . $this->tag . ">";
	}

	public function render($input){
		return preg_replace_callback($this->regex, array(&$this, "_cbac"), $input);
	}
}

class FormattingSyntax extends Syntax{
	public function __construct($delimiter, $tag){
		parent::__construct($delimiter, $delimiter, $tag);
	}
}

class LineFormattingSyntax extends FormattingSyntax{
	public $regex = "/{delimiter1}(()|[^'].*){delimiter2}\r\n\r\n/";
}

class SimpleReplace{
	public function __construct($needle, $replacement){
		$this->needle = $needle;
		$this->replacement = $replacement;
	}
	public function render($input){
		return str_replace($this->needle, $this->replacement, $input);
	}
}