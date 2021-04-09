<?php 
namespace Todo\Model;

class Todo
{
    public $id;
    public $To_Do_List;
    

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->To_Do_List = !empty($data['To_Do_List']) ? $data['id'] : null;
    }
}

 ?>