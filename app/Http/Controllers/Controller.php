<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // message helper
    public string $errNotFound; // from database
    public string $errBadRequest;
    public string $errPayloadNotValid;
    public string $errIDNotValid;
    public string $errInternalServer;
    public string $errDuplicateData; // from database

    public array $exampleReturn;

    public function __construct()
    {
        $this->errNotFound        = "data isn't found.";
        $this->errBadRequest      = "data isn't valid.";
        $this->errPayloadNotValid = "payload isn't valid.";
        $this->errIDNotValid      = "id isn't valid.";
        $this->errInternalServer  = "internal server error.";
        $this->errDuplicateData   = "data has duplicate in storage.";

        $this->exampleReturn = [
            'success' => [
                'status' => 'success',
                'code' => 200,
                'data' => [
                    'id' => 69
                ],
            ],
            'errNotFound' => [
                'messsage' => $this->errNotFound,
                'status' => 'error',
                'code' => 404,
            ],
            'errBadRequest' => [
                'messsage' => $this->errBadRequest,
                'status' => 'error',
                'code' => 400,
            ],
            'errPayloadNotValid' => [
                'messsage' => $this->errPayloadNotValid,
                'status' => 'error',
                'code' => 400,
            ],
            'errIDNotValid' => [
                'messsage' => $this->errIDNotValid,
                'status' => 'error',
                'code' => 400,
            ],
            'errInternalServer' => [
                'messsage' => $this->errInternalServer,
                'status' => 'error',
                'code' => 500,
            ],
            'errDuplicateData' => [
                'messsage' => $this->errDuplicateData,
                'status' => 'error',
                'code' => 500,
            ],
        ];
    }

    private array $susChars = ['`', '%', '/', '*'];

    private function addSusChars(array $data)
    {
        $this->susChars = array_push($this->susChars, $data);
    }

    // should use this after run addSusChars()
    private function setSusCharsAsDefaultValue()
    {
        $this->susChars = ['`', '%', '/', '*'];
    }

    public function payloadChecker(string $data): bool
    {
        foreach ($this->susChars as $key => $value) {
            if (str_contains($data, $value)) {
                return false;
            }
        }

        return true;
    }

    public function filterString(string $data): string
    {
        $filteredString = '';

        for ($i = 0; $i < strlen($data); $i++) {
            $char = $data[$i];

            if ($this->payloadChecker($char)) {
                $filteredString .= $char;
            }
        }

        return $filteredString;
    }
}
