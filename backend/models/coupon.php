<?php

class Coupon {
    public int $id;
    public string $code;
    public float $amount;
    public float $residual_value;
    public string $date;
    public bool $expired;

    /**
     * @param int $id
     * @param string $code
     * @param float $amount
     * @param float $residual_value
     * @param string $date
     */
    public function __construct(int $id, string $code, float $amount, float $residual_value,string $date, bool $expired)
    {
        $this->id = $id;
        $this->code = $code;
        $this->amount = $amount;
        $this->residual_value = $residual_value;
        $this->date = $date;
        $this->expired = $expired;
    }


}