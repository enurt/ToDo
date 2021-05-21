<?php
namespace TodoTest\Model;

use Todo\Model\TodoTable;
use Todo\Model\Todo;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;

class TodoTableTest extends TestCase
{
    protected function setUp() : void
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
        $this->todoTable = new TodoTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllTodos()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->todoTable->fetchAll());
    }

    public function testCanDeletTodoByItsId()
    {
        $this->tableGateway->delete(['id' => 5])->shouldBeCalled();
        $this->todoTable->deleteTodo(5);
    }

    public function testSaveTodoWillInsertNewTodoIfTheyDontAlreadyHaveAnId()
    {
        $todoData = [
            'To_Do_List' => 'test',
            'status'  => 'new'
        ];
        $todo = new Todo();
        $todo->exchangeArray($todoData);

        $this->tableGateway->insert($todoData)->shouldBeCalled();
        $this->todoTable->saveTodo($todo);
    }

    public function testSaveTodoWillUpdateExistingTodoIfTheyAlreadyHaveAnId()
    {
        $todo = new Todo();
        $todoData  = [
            'id' => 5,
            'To_Do_List' => 'test',
            'status'  => 'new'
        ];
        $todo->exchangeArray($todoData);

        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn($todo);

        $this->tableGateway
            ->select(['id' => 5])
            ->willReturn($resultSet->reveal());
        $this->tableGateway
            ->update(
                array_filter($todoData, function ($key) {
                    return in_array($key, ['To_Do_List', 'status']);
                }, ARRAY_FILTER_USE_KEY),
                ['id' => 5]
            )->shouldBeCalled();

        $this->todoTable->saveTodo($todo);
    }
}
