<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \App\Response\ApiResponse setMessage(string $message)
 * @method static \App\Response\ApiResponse setData(array $data)
 * @method static array getData()
 * @method static string getMessage()
 * @method static \App\Response\ApiResponse setRedirectCode(string $code)
 * @method static \App\Response\ApiResponse setStatusCode(int $statusCode)
 * @method static string getRedirectCode()
 * @method static array toArray(?array $data = null, ?string $message = null, ?int $status = null)
 * @method static \Illuminate\Http\JsonResponse json(?array $data = null, ?string $message = null, ?int $status = null)
 *
 * @see \App\Response\ApiResponse
 */

class ApiResponse extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'apiResponse';
    }
}
