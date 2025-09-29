<?php

namespace App\Http\Repository\api\[apiVersion]\Eloquent;

use App\Http\Repository\api\[apiVersion]\[ModelName]RepositoryInterface;
use App\Models\[ModelName];
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class [ModelName]Repository extends BaseRepository implements [ModelName]RepositoryInterface
{
    public function __construct([ModelName] $model)
    {
        parent::__construct($model);
    }
}