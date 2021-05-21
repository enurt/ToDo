<?php
namespace TodoTest\Model;

use Todo\Model\Todo;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase{
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $todo = new Todo();
        $data  = [
            'id' => 5,
            'To_Do_List' => 'test',
            'status'  => 'new'
        ];

        $todo->exchangeArray($data);

        $this->assertSame(
            $data['id'],
            $todo->id,
            '"id" was not set correctly'
        );

        $this->assertSame(
            $data['To_Do_List'],
            $todo->To_Do_List,
            '"To_Do_List" was not set correctly'
        );

        $this->assertSame(
            $data['status'],
            $todo->status,
            '"status" was not set correctly'
        );
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $todo = new Todo();
        $data  = [
            'id' => 5,
            'To_Do_List' => 'test',
            'status'  => 'new'
        ];

        $todo->exchangeArray($data);
        $copyArray = $todo->getArrayCopy();

        $this->assertSame($data['id'], $copyArray['id'], '"id" was not set correctly');
        $this->assertSame($data['To_Do_List'], $copyArray['To_Do_List'], '"To_Do_List" was not set correctly');
        $this->assertSame($data['status'], $copyArray['status'], '"status" was not set correctly');
    }
}