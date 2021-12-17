<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static \App\Response\ApiResponse setMessage(string $message)
 * @method static \App\Response\ApiResponse setData(array $data)
 * @method static \App\Response\ApiResponse setStatusCode(int $statusCode)
 * @method static \App\Response\ApiResponse setRedirectCode(string $redirect_code)
 * @method static string getMessage()
 * @method static string getRedirectCode()
 * @method static array getData()
 * @method static int getStatusCode()
 * @method static \Illuminate\Http\JsonResponse json(?int $statusCode = null, ?array $data = null, ?string $message = null)
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
