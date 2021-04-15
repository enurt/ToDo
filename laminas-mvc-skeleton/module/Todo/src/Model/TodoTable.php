<?php 
namespace Todo\Model;

use Todo\Form\TodoForm;
use Todo\Model\Todo;
use Todo\Model\TodoTable;
use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class TodoTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getTodo($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveTodo(Todo $To_Do_List)
    {
        $data = [
            'To_Do_List' => $To_Do_List->To_Do_List,
            'status' => $To_Do_List->status,
        ];

        $id = (int) $To_Do_List->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getTodo($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update to do list with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }


    public function deleteTodo($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
 ?>