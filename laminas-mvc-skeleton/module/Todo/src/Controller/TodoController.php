<?php 
namespace Todo\Controller;

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
            'albums' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
    }

    public function updateAction()
    {
    }

    public function deleteAction()
    {
    }
}
 ?>