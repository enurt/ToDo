<?php 
namespace Todo\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;

class Todo implements InputFilterAwareInterface
{
    public $id;
    public $To_Do_List;
    
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->To_Do_List = !empty($data['To_Do_List']) ? $data['To_Do_List'] : null;
    }

        public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'To_Do_List',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        // $inputFilter->add([
        //     'name' => 'title',
        //     'required' => true,
        //     'filters' => [
        //         ['name' => StripTags::class],
        //         ['name' => StringTrim::class],
        //     ],
        //     'validators' => [
        //         [
        //             'name' => StringLength::class,
        //             'options' => [
        //                 'encoding' => 'UTF-8',
        //                 'min' => 1,
        //                 'max' => 100,
        //             ],
        //         ],
        //     ],
        // ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}

 ?>