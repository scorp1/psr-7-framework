<?php
namespace Framework\Counting;

use phpDocumentor\Reflection\Types\Integer;

class Counting
{

    public $length;

    public $height;

    public $type;

    /**
     * Counting constructor.
     * Передаются параметры по которым считать количество рулонов обоев в мм
     *
     * @param float $height
     * @param float $length
     * @param float $type
     */
    public function __construct(float $height,float $length, $type = 0.5)
    {
        $this->length = $length;
        $this->height = $height;
        $this->type = $this->getType($type);
    }

    public function getType($type)
    {
        if ($type == 1) {
            return   $this->type = 1000;
        } else {
            return $this->type = 500;
        }
    }

    public function getCountRoll()
    {
        $resultRulon = (10000 / $this->height) * $this->type;

        return $this->length / $resultRulon;
    }
}