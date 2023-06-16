<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    public function AnySuccess()
    {
        return response(
            [
                'method' => request()->method(),
                'status' => 'success',
                'code' => 200,
            ],
            200,
        );
    }

    public function Success()
    {
        return response(
            $this->exampleReturn['success'],
            200,
        );
    }

    public function ErrNotFound()
    {
        return response(
            $this->exampleReturn['errNotFound'],
            $this->exampleReturn['errNotFound']['code'],
        );
    }

    public function ErrBadRequest()
    {
        return response(
            $this->exampleReturn['errBadRequest'],
            $this->exampleReturn['errBadRequest']['code'],
        );
    }

    public function ErrPayloadNotValid()
    {
        return response(
            $this->exampleReturn['errPayloadNotValid'],
            $this->exampleReturn['errPayloadNotValid']['code'],
        );
    }

    public function ErrIDNotValid()
    {
        return response(
            $this->exampleReturn['errIDNotValid'],
            $this->exampleReturn['errIDNotValid']['code'],
        );
    }

    public function ErrInternalServer()
    {
        return response(
            $this->exampleReturn['errInternalServer'],
            $this->exampleReturn['errInternalServer']['code'],
        );
    }

    public function ErrDuplicateData()
    {
        return response(
            $this->exampleReturn['errDuplicateData'],
            $this->exampleReturn['errDuplicateData']['code'],
        );
    }
}
