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
            'todolists' => $this->table->fetchAll(),
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
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('todo', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $todo = $this->table->getTodo($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('todo', ['action' => 'index']);
        }

        $form = new TodoForm();
        $form->bind($todo);
        $form->get('submit')->setAttribute('value', 'Update');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($todo->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveTodo($todo);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('todo', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('todo');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteTodo($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('todo');
        }

        return [
            'id'    => $id,
            'todo' => $this->table->getTodo($id),
        ];
    }

    public function doneAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('todo');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('stat', 'No');

            if($del == 'Yes'){
                $id = (int) $request->getPost('id');
                $this->table->saveTodo($id);
           } 
        

            // Redirect to list of ToDo
            return $this->redirect()->toRoute('todo');
        }

        return [
            'id'    => $id,
            'todo' => $this->table->getTodo($id),
        ];
    }

}
 ?>