<?php
/**
 * Nama Class: Form
 * Deskripsi: Class untuk membuat form inputan text sederhana
 **/

class Form
{
    private $fields = array();
    private $action;
    private $submit = "Submit Form";
    private $jumField = 0;

    public function __construct($action, $submit)
    {
        $this->action = $action;
        $this->submit = $submit;
    }

    public function displayForm()
    {
        echo "<form action='" . $this->action . "' method='POST'>";
        echo "<table class='table table-borderless align-middle mb-2' style='max-width: 760px;'>";

        for ($j = 0; $j < count($this->fields); $j++) {
            $field = $this->fields[$j];
            $type = isset($field['type']) ? $field['type'] : 'text';
            $options = isset($field['options']) ? $field['options'] : array();

            echo "<tr><td width='180' class='text-end fw-semibold'>" . $field['label'] . ":</td><td>";

            switch ($type) {
                case 'password':
                    echo "<input type='password' name='" . $field['name'] . "' class='form-control form-control-sm app-input'>";
                    break;

                case 'radio':
                    foreach ($options as $val => $label) {
                        echo "<label class='me-3'><input class='form-check-input me-1' type='radio' name='" . $field['name'] . "' value='" . $val . "'>" . $label . "</label>";
                    }
                    break;

                case 'select':
                    echo "<select name='" . $field['name'] . "' class='form-select form-select-sm'>";
                    foreach ($options as $val => $label) {
                        echo "<option value='" . $val . "'>" . $label . "</option>";
                    }
                    echo "</select>";
                    break;

                case 'checkbox':
                    foreach ($options as $val => $label) {
                        echo "<label class='me-3'><input class='form-check-input me-1' type='checkbox' name='" . $field['name'] . "[]' value='" . $val . "'>" . $label . "</label>";
                    }
                    break;

                case 'textarea':
                    echo "<textarea name='" . $field['name'] . "' rows='3' class='form-control form-control-sm app-input'></textarea>";
                    break;

                default: // text
                    echo "<input type='text' name='" . $field['name'] . "' class='form-control form-control-sm app-input'>";
                    break;
            }

            echo "</td></tr>";
        }

        echo "<tr><td colspan='2'>";
        echo "<button type='submit' class='btn btn-success btn-sm px-3'>" . $this->submit . "</button></td></tr>";
        echo "</table>";
        echo "</form>";
    }

    public function addField($name, $label, $type = 'text', $options = array())
    {
        $this->fields[$this->jumField]['name']    = $name;
        $this->fields[$this->jumField]['label']   = $label;
        $this->fields[$this->jumField]['type']    = $type;
        $this->fields[$this->jumField]['options'] = $options;
        $this->jumField++;
    }
}

?>
