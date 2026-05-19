<?php

namespace App\Concerns;

trait PayloadTrait
{
    private function payload(String $message, $data = null, $code = 200)
    {
        $status = true;

        if($message == TOAST_FAILED)
        {
            $status = false;
            $code = 500;
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }
}
