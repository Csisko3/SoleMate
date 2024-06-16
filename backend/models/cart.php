<?php

class cart{
    public $id;
    public $user_id;
    public $product_id;
    public $quantity;

    function __construct(int $id, int $user_id, int $product_id, int $quantity) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
    }
}