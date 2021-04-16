<?php 
namespace Todo;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\TodoTable::class => function($container) {
                    $tableGateway = $container->get(Model\TodoTableGateway::class);
                    return new Model\TodoTable($tableGateway);
                },
                Model\TodoTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Todo());
                    return new TableGateway('todolist', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\TodoController::class => function($container) {
                    return new Controller\TodoController(
                        $container->get(Model\TodoTable::class)
                    );
                },
            ],
        ];
    }
}

 ?>