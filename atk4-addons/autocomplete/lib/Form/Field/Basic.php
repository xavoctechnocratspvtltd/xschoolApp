<?php
/**
 * Addon  for converting hasOne field into auto-complete
 */
namespace autocomplete;

class Form_Field_Basic extends \Form_Field_Hidden
{
    // You can find all available options here: http://jqueryui.com/demos/autocomplete/
    public $options = array('mustMatch'=>true);

    // Limits resultset
    public $limit_rows = 20;

    // Minimum characters you have to enter to make autocomplete ajax call
    public $min_length = 1;

    // Hint text. If empty/null, then hint will not be shown.
    public $hint = 'Please enter at least %s symbols. Search results will be limited to %s records.';
    
    // show as hint or placeholder
    public $hint_show_as = 'placeholder'; // hint|placeholder

    // Text input field object
    public $other_field;

    // Model ID field and title field names
    public $id_field;
    public $title_field;



    function init()
    {
        parent::init();

        // add add-on locations to pathfinder
        $l = $this->api->locate('addons', __NAMESPACE__, 'location');
        $addon_location = $this->api->locate('addons', __NAMESPACE__);
        $this->api->pathfinder->addLocation($addon_location, array(
            'js'  => 'js',
            'css' => 'templates/css',
        ))->setParent($l);

        // add additional form field
        $name = preg_replace('/_id$/', '', $this->short_name);
        $caption = null;
        if ($this->owner->model) {
            if ($f = $this->owner->model->getField($this->short_name)) {
                $caption = $f->caption();
            }
        }
        $this->other_field = $this->owner->addField('line', $name, $caption);
        if ($this->hint) {
            $text = sprintf($this->hint, $this->min_length, $this->limit_rows);
            if ($this->hint_show_as=='placeholder') {
                $this->other_field->setAttr('placeholder', $text);
            } elseif($this->hint_show_as=='hint') {
                $this->other_field->setFieldHint($text);
            }
        }

        // move hidden ID field after other field. Otherwise it breaks :first->child CSS in forms
        $this->js(true)->appendTo($this->other_field->js()->parent());

        // Set default options
        if ($this->min_length) {
            $this->options['minLength'] = $this->min_length;
        }
    }

    function setCaption($_caption)
    {
        $this->caption = $this->other_field->caption = $this->api->_($_caption);
        return $this;
    }

    function mustMatch()
    {
        $this->options = array_merge($this->options, array('mustMatch'=>'true'));
        return $this;
    }

    function validateNotNULL($msg = null)
    {
        $this->other_field->validateNotNULL($msg);
        return $this;
    }

    function addCondition($q)
    {
        $this->model->addCondition($this->title_field, 'like', '%'.$q.'%'); // add condition
        /*
        $this->model->addCondition(
            $this->model->dsql()->orExpr()
                ->where($this->model->getElement( $this->title_field), 'like', '%'.$q.'%')
                ->where($this->model->getElement( $this->id_field), 'like', $this->model->dsql()->getField('id','test'))
        )->debug();
        */
        // $this->model->setOrder($this->title_field); // order ascending by title field
        $this->model->_dsql()->order('length('.$this->title_field.')'); // order ascending by title field
        if ($this->limit_rows) {
            $this->model->_dsql()->limit($this->limit_rows); // limit resultset
        }

        return $this;
    }

    function setOptions($options = array())
    {
        $this->options = $options;
        return $this; //maintain chain
    }

    function getData() {
        return $this->model->getRows(array($this->id_field, $this->title_field));
    }

    function setModel($m, $id_field = null, $title_field = null)
    {
        parent::setModel($m);

        $this->id_field = $id_field ?: $this->model->id_field;
        $this->title_field = $title_field ?: $this->model->title_field;

        if ($_GET[$this->name]) {

            if ($_GET['term']) {
                $this->addCondition($_GET['term']);
            }

            $data = $this->getData();

            echo json_encode($data);
            exit;
        }
    }

    function render()
    {
        $url = $this->api->url(null, array($this->name => 'ajax'));
        if ($this->value) { // on add new and inserting allow empty start value
            $this->model->tryLoad($this->value);
            $name = $this->model->get($this->title_field);
            $this->other_field->set($name);
        }

        $this->other_field->js(true)
            ->_load('autocomplete_univ')
            ->_css('autocomplete')
            ->univ()
            ->myautocomplete($url, $this, $this->options, $this->id_field, $this->title_field);

        return parent::render();
    }
}
