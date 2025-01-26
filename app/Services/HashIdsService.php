<?php

namespace App\Services;

use App\Exceptions\EncodeDecodeFailureException;
use Exception;
use Vinkla\Hashids\Facades\Hashids;

class HashIdsService
{
    public function createAHashShortCode(string $linkId)
    {
        try {
            return Hashids::encode($linkId);
        } catch (Exception $th) {
            // @todo add a different exception error
            throw new EncodeDecodeFailureException('The encode service failed');
        }
    }


    public function decodeAHashShortCode(string $linkId)
    {
        try {
            return Hashids::decode($linkId)[0];
        } catch (Exception $exception) {
            // @todo add a different exception error
            throw new EncodeDecodeFailureException('The decode service failed' . $exception);
        }
    }
}
