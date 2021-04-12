<?php 
namespace Todo\Controller;

use Todo\Form\TodoForm;
use Todo\Model\Todo;
use Todo\Model\TodoTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class TodoController extends AbstractActionController
{
    private $table;

    public function __construct(TodoTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'todolist' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new TodoForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $todo = new Todo();
        $form->setInputFilter($todo->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $todo->exchangeArray($form->getData());
        $this->table->saveTodo($todo);
        return $this->redirect()->toRoute('todo');
    }

    public function updateAction()
    {
    }

    public function deleteAction()
    {
    }
}
 ?>