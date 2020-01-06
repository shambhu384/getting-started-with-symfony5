<?php

namespace App\Message;

use App\Entity\Customer;

class CreateCustomer
{
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }
}
