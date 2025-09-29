<?php

namespace App\Http\Resources\api\[apiVersion];

class [ModelName]Resource extends BaseResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $coreModel = [
            'id' => $this->id,
            [ModelFields],
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'deleted_at' => $this->deleted_at,
        ];

        $relations = [];

        return $this->composeResourceReturnArray($coreModel, $relations);
    }
}
