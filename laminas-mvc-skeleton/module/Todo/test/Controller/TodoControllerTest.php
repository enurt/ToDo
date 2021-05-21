<?php
namespace TodoTest\Controller;

use Todo\Controller\TodoController;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

use Todo\Model\TodoTable;
use Laminas\ServiceManager\ServiceManager;

use Todo\Model\Todo;
use Prophecy\Argument;

class TodoControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    protected $todoTable;

    protected function setUp() : void
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            // Grabbing the full application configuration:
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));
        parent::setUp();

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);

        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(TodoTable::class, $this->mockTodoTable()->reveal());

        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config['db'] = [];
        return $config;
    }

    protected function mockTodoTable()
    {
        $this->todoTable = $this->prophesize(TodoTable::class);
        return $this->todoTable;
    }


    public function testIndexActionCanBeAccessed()
    {
        $this->todoTable->fetchAll()->willReturn([]);

        $this->dispatch('/todo');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Todo');
        $this->assertControllerName(TodoController::class);
        $this->assertControllerClass('TodoController');
        $this->assertMatchedRouteName('todo');
    }

    /**
    * @group action
    */

    public function testUpdateAction()
    {
        $todo = new Todo();
        $this->todoTable->getTodo(5)->willReturn($todo);
        $this->todoTable->saveTodo($todo)->willReturn();

        $updateData  = [
            'id' => 1,
            'To_Do_List' => 'test',
            'status'  => 'new'
        ];

        $this->dispatch('/todo/update/5', 'POST', $updateData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/todo');
    }

    public function testDeleteAction()
    {

        $todo = new Todo();
        $deleteData  = [
            'id' => 5,
            'To_Do_List' => 'test',
            'status'  => 'new'
        ];
        
        $this->todoTable->deleteTodo(['id' => 5 ])->willReturn($todo);

        $this->dispatch('/todo/delete', 'DELETE', $deleteData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/todo');
    
    }

    public function testAddActionRedirectAfterValidPost(){
        $this->todoTable
            ->saveTodo(Argument::type(Todo::class))
            ->shouldBeCalled();

        $postData = [
            'id' => '',
            'To_Do_List' => 'test',
            'status'  => 'new',
        ];

        $this->dispatch('/todo/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/todo');
    }
        
}
