<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Interfaces\[ModelName]PolicyInterface;

class [ModelName]Policy extends BasePolicy implements [ModelName]PolicyInterface
{
    public function __construct()
    {
        parent::__construct('[modelName]');
    }
}
