<?php
namespace Todo\Form;

use Laminas\Form\Form;

class TodoForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('todo');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'To_Do_List',
            'type' => 'text',
            'options' => [
                'label' => 'todolist',
            ],
        ]);

        $this->add([
            'name' => 'status',
            'type' => 'text',
            'options' => [
                'label' => 'todolist',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
?>