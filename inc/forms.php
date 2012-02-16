<?php
// MyCloud forms implementation
// used to make forms work

// our script
register_script("forms", ROOT . "inc/templates/js/forms.js", array("jquery"));

function render_fields($fields, $labels = true, $formclass=array()){
	$t = 0;
	foreach($fields as $field){
		if(is_a($field, "Field")){
			if($field->label_enabled == true && $labels == true){
				echo '<' . $field->label_tag . ' for="'.$field->name.'">';
				echo $field->label;
				echo '</' . $field->label_tag . '>';
			}
			$field->render();
		} elseif(is_a($field, "Section")){
			if(in_array("tabs", $formclass)){
				if($t >= 2){
					$formclass = array_diff( $formclass, array("active") );
				} else{
					$formclass[] = "active";
					$t += 1;
				}
			}
			$field->render($formclass);
		}
	}
}

// Base class
class Form{
	public $class = array();
	private $section = "default";
	public $submit_button = "Submit";
	public $submitting_button = "Submiting...";

	public $updated = false;

	public function __construct($formname = "form", $class = array()){
		$this->submit_button = _("Submit");
		$this->submitting_button = _("Submiting...");
		$this->fields[$this->section] = new DefaultSection();

		$this->class = $class;
		$this->class[] = "nice";
		// Identify our form
		$this->formname = $formname;
		$this->add_field(new HiddenField("formname", $formname));

		if($_GET['updated']){
			$this->updated = true;
		}
	}
	public $fields = array();

	function add_field($field){
		$this->fields[$this->section]->fields[] = $field;
	}

	function handle_posting(){
		echo "Stub: Form subclass should implement handle_posting();<br/>";
	}

	function section($section){
		$this->section = $section;
		$s = new Section();
		$s->name = $section;
		$this->fields[$section] = $s;
	}

	function section_property($property, $value){
		$this->fields[$this->section]->setprop($property, $value, $this);
	}

	public function render(){
		if($this->updated == true){
			echo '<div class="alert-box success">';
			L("Updated!");
			echo '</div>';
		}

		request_script("forms");
		$post_url = curPageURL();
		$class = $this->class;
		$class = array_diff( $class, array("tabs") );
		echo '<form method="post" class="'.implode(" ", $class).'" action="'.$post_url.'"><div class="row">';

		if(in_array("tabs", $this->class)){
			echo '<dl class="tabs contained">';
			$f = true;
			foreach($this->fields as $section){
				if($section->name){
					echo '<dd><a';
					if($f == true){
						echo ' class="active"';
						$f = false;
					}
					echo' href="#'.
						str_replace(" ", "_", $section->name).'">'.$section->name.'</a></dd>';
				}
			}
			echo '</dl>';
			echo '<ul class="tabs-content contained">';
		}

		render_fields($this->fields, true, $this->class);

		// Submit
		echo '</div>';

		if(in_array("tabs", $this->class)){
			echo '</ul>';
		}

		echo '<button class="button large radius" data-submiting="'.$this->submitting_button.'" type="submit">' . $this->submit_button . "</button>";
		echo '</form>';
	}

	/**
	 * Call when you've done making your form
	 */
	public function done(){
		if($_POST['formname'] == $this->formname){
			$this->handle_posting();
		}
	}

}

class Section{
	public $fields = array();
	public $name = "";
	public $properties = array();

	public function render($formclass){
		$fs = true;
		if(in_array("tabs", $formclass)){
			$fs = false;
			echo '<li';
			if(in_array("active", $formclass)){
				echo ' class="active"';
			}
			echo ' id="'.str_replace(" ", "_", $this->name).'Tab">';
		} elseif($this->properties['display'] == "sidebar"){
			echo "<div class='four columns'>";
		}

		if($fs)
			echo "<fieldset>";
		echo "<h5>".$this->name."</h5>";

		render_fields($this->fields);

		if($fs)
			echo "</fieldset>";

		if(in_array("tabs", $formclass)){
			echo '</li>';
		} elseif($this->properties['display'] == "sidebar"){
			echo "</div>";
		}
	}

	function setprop($property, $value, $form){
		$this->properties[$property] = $value;
		if($property == "display" && $value == "sidebar"){
			$form->fields['default']->setprop("display", "msidebar", $form);
		}
	}
}

class DefaultSection extends Section{
	public function render($formclass){
		if(in_array("tabs", $formclass)){
			echo '<li>';
		} elseif($this->properties['display'] == "msidebar"){
			echo "<div class='eight columns'>";
		}
		render_fields($this->fields);
		if(in_array("tabs", $formclass)){
			echo "</li>";
		} elseif($this->properties['display'] == "msidebar"){
			echo "</div>";
		}
	}
}

// Links with readbean! ^___^
class BeanForm extends Form{
	public function __construct($bean){
		$this->bean = $bean;
		parent::__construct();
	}
	public function add_field($field){
		$field->set_bean($this->bean);
		parent::add_field($field);
	}

	public function handle_posting(){
		$err = false;
		foreach($this->fields as $section){
			foreach($section->fields as $field){
				if($field->validate() == true){
					$field->save_to_bean($this->bean);
				} else{
					$err = true;
				}
			}
		}
		if($err == false){
			R::store($this->bean);
			foreach($this->fields as $section){
				foreach($section->fields as $field){
					$field->stored_bean($this->bean);
				}
			}
			$_GET['id'] = $this->bean->id;
			$_GET['updated'] = "true";
			
			header("Location: " . curPageURL());
			die("Form has saved data. You are being redirected");
		}
	}
}

// Base
class Field{
	public $label_enabled = true;
	public $label_tag = "label";

	public $type = "text";
	public $required = true;
	public $error = "";

	public function __construct($name, $label, $type="text", $class=array()){
		$this->name = $name;
		$this->label = $label;
		$this->type = $type;
		$this->class = $class;
	}

	public function set_bean($bean){
		$v = $bean->{$this->name};
		if($v){
			$this->value = $v;
		}
	}
	public function set_value($val){
		$this->value = $val;
		return $this;
	}

	public function render(){
		$class = $this->class;
		if($this->type == "text"){
			$class[] ='input-text';
		}
		if(!empty($class)){
			$class = ' class="' . implode(' ', $class) . '"';
		}
		if($this->type == "checkbox" && (($this->value == "on") || ($this->value == true))){
			$class .= ' checked="checked"';
		}
		echo '<input'.$class.' type="' . $this->type . '"id="'. $this->name .
					'" name="'. $this->name .'" value="' . $this->value . '" placeholder="'.$this->label.'" />';

		$this->render_error();
	}
	public function save_to_bean($bean){
		$bean->{$this->name} = $this->value;
	}
	public function render_error(){
		if($this->error != ''){
			echo '<small class="error">' . $this->error . "</small>";
		}
	}

	public function validate(){
		$this->value = $_POST[$this->name];

		if($this->required == true && trim($this->value) == ''){
			$this->error = _("This field is required");
			return false;
		}
		return true;
	}
	public function stored_bean($bean){}
}

class CheckboxArray extends Field{
	public $type = "checkbox";
	public $label_tag = "span";

	public function __construct($name, $label, $values){
		$this->name = $name;
		$this->label = $label;
		$this->values = $values;
	}

	public function render(){
		$name = $this->name;
		$value = $this->value;

		foreach($this->values as $k => $v){
			echo '<label for="'.$name.'['.$k.']">';
			$this->value = (in_array($k, $value)) ? "on" : "off";
			$this->name = $name . "[" . $k . "]";
			parent::render();
			echo $v . '</label>';
		}

		parent::render_error();
		$this->name = $name;

	}
}

class HiddenField extends Field{
	public $label_enabled = false;
	public $type = "hidden";

	public function __construct($name, $value = ''){
		$this->name = $name;
		$this->value = $value;
	}
	public function render_error(){}
}

class SelectBox extends Field{
	public function __construct($name, $label, $options, $class=array()){
		$this->name = $name;
		$this->label = $label;
		$this->class = $class;
		$this->options = $options;
	}
	public function render(){
		echo '<select name="'.$this->name.'" class="'.implode(' ', $this->class).'">';
		foreach($this->options as $k => $v){
			$d = '';
			if($this->value == $k){
				$d = ' selected="selected"';
			}
			echo '<option'.$d.' value="'.$k.'">'.$v.'</option>';
		}
		echo '</select>';
		$this->render_error();
	}
}

class TextArea extends Field{
	public function __construct($name, $label, $rows=4, $class=array()){
		$this->name = $name;
		$this->label = $label;
		$this->rows = $rows;
		$this->class = $class;
		$this->class[] = "full-width";
	}

	public function render(){
		echo '<textarea rows="'.$this->rows.'" id="' . $this->name . 
				'" name="' . $this->name . '" class="' . implode(' ', $this->class) . '">';
		echo $this->value;
		echo '</textarea>';
		$this->render_error();
	}
}

class TagField extends Field{
	public $required = false;
	public $value = array();

	public function __construct($name, $label, $class=array()){
		$this->name = $name;
		$this->label = $label;
		$this->class = $class;
		$this->class[] = "datefield";
	}

	public function set_bean($bean){
		if($bean)
			$this->value = R::tag($bean);
	}

	public function render(){
		echo '<dl class="sub-nav tagfield">';

		echo '<dt>';
		$this->value=implode(", ", $this->value);
		parent::render();
		echo '</dt>';

		echo '</dl>';
	}

	// We do it on another hook
	public function save_to_bean($bean){ }

	// This is the hook, after we've stored it
	public function stored_bean($bean){
		R::clearRelations( $bean, 'tag' );
		R::tag($bean, $this->value);
	}

	function validate(){
		$o = parent::validate();
		$this->value = explode(", ", $this->value);
		return $o;
	}
}

class DateTimeField extends Field{
	public $label_tag = "h6";

	public function __construct($name, $label, $class=array()){
		$this->name = $name;
		$this->label = $label;
		$this->class = $class;
		$this->class[] = "datefield";
		$this->value = "b";
	}

	public function render(){
		echo '<div class="' . implode(' ', $this->class) . '">';

		// Right now
		echo '<label for="'.$this->name.'[rightnow]">';
		if($this->value == "b")
			$c = ' checked="checked"';

		echo '<input type="checkbox"'.$c.' name="'.$this->name.'[rightnow]" id="'.$this->name.'[rightnow]" /> ';
		echo _("Right now").'</label>';

		// Other boxes
		echo '<div data-depends-off="'.$this->name.'[rightnow]">';
		$name = $this->name;
		$months = array(
			_("01 - January"),
			_("02 - February"),
			_("03 - March"),
			_("04 - April"),
			_("05 - May"),
			_("06 - June"),
			_("07 - July"),
			_("08 - August"),
			_("09 - September"),
			_("10 - October"),
			_("11 - November"),
			_("12 - December")
		);
		$field = new Field( $this->name . "[day]", _("Day"), "text", array("inline", "supertiny") );
		$field->value = $this->value->day;
		$field->render();

		$field = new SelectBox( $this->name . "[month]", _("Month"), $months, array("inline") );
		$field->value = $this->value->month;
		$field->render();

		$field = new Field( $this->name . "[year]", _("Year"), "text", array("inline", "tiny") );
		$field->value = $this->value->year;
		$field->render();

		echo "<br/>";

		$field = new Field( $this->name . "[hour]", _("Hours"), "text", array("inline", "supertiny") );
		$field->value = $this->value->hour;
		$field->render();

		echo ":";

		$field = new Field( $this->name . "[minute]", _("Mins"), "text", array("inline", "supertiny") );
		$field->value = $this->value->minute;
		$field->render();

		echo '</div>';

		echo '</div>';
		$this->render_error();
	}

	public function save_to_bean($bean){
		$bean->{$this->name} = $this->value->timestamp;
	}
	public function set_bean($bean){
		if($bean)
			$this->value = new Date($bean->{$this->name});
	}

	public function setval($value, $v){
		if(trim($value[$v]) == ""){
			throw new Exception("You must enter a value", 1);
		}
		$this->value->$v = ($value[$v] * 1);
	}

	public function validate(){
		$value = $_POST[$this->name];
		if($value['rightnow'] == "on"){
			$this->value = new Date();
			return true;
		} else{
			try {
				$this->setval($value, "day");
				$this->setval($value, "month");
				$this->setval($value, "year");
				$this->setval($value, "hour");
				$this->setval($value, "minute");
			} catch (Exception $e) {
				$this->error = $e;
				return false;
			}
			return true;
		}
		return false;
	}
}
