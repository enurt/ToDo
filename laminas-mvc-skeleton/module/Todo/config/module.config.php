<?php
namespace Todo;

use Laminas\Router\Http\Segment;

return [

      'router' => [
        'routes' => [
            'todo' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/todo[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\TodoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'todo' => __DIR__ . '/../view',
        ],
    ],
];

?>
