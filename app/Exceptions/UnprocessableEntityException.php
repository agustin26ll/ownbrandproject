<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Throwable;

class UnprocessableEntityException extends  Exception implements Responsable
{
    const DEFAULT_ERROR_MESSAGE = ['error' => 'Algo salió mal'];
    
    public function __construct($message = self::DEFAULT_ERROR_MESSAGE, $code = Response::HTTP_UNPROCESSABLE_ENTITY, ?Throwable $previous = null)
    {
        parent::__construct(json_encode($message), $code, $previous);
    }

    public function toResponse($request)
    {
        return response()->json(json_decode($this->getMessage()), $this->getCode());
    }
}
