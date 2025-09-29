<?php

namespace App\Http\Controllers\api\[apiVersion];

use App\Http\Resources\api\[apiVersion]\[ModelName]Resource;

use App\Http\Requests\[ModelName]StoreRequest;
use App\Http\Requests\[ModelName]UpdateRequest;

class [ModelName]Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct('[ModelName]');
        $this->setModelAndRepository();
    }

    /**
     * @param [ModelName]StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store([ModelName]StoreRequest $request)
    {
        $response = $this->repository->store($request);

        if (!empty($response)) {
            $collection = [ModelName]Resource::collection($response);
            return response()->json($this->ok(__(
                $this->language . '.store.success'
            ), $collection));
        }

        // @codeCoverageIgnoreStart
        $responseMessage = $this->error(__(
            $this->language . '.store.failed'
        ));
        return response()->json($responseMessage, $responseMessage['code']);
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param [ModelName]UpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update([ModelName]UpdateRequest $request, $id)
    {
        $response = $this->repository->update($request, $id);

        if (!empty($response)) {
            $collection = [ModelName]Resource::collection($response);
            return response()->json($this->ok(__(
                $this->language . '.update.success',
            ), $collection));
        }

        // @codeCoverageIgnoreStart
        return response()->json($this->error(__(
            $this->language . '.update.failed',
        )));
        // @codeCoverageIgnoreEnd
    }
}
