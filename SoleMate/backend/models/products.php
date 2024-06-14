<?php

class products{
    public $id;
    public $name;
    public $price;
    public $category;
    public $image;

    function __construct(int $id, string $name, float $price, string $category, string $image) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->image = $image;
    }
}