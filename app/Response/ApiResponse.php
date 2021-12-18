<?php

namespace App\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse extends Response
{

    /**
     * Store response message
     *
     * @var string $message
     */
    private string $message = '';

    /**
     * Store response data
     *
     * @var array $data
     */
    private array $data = [];


    /**
     * Store redirection code jump one screen to another
     *
     * @var string $message
     */
    private string $redirect_code = '';


    /**
     * Send Json Response
     *
     * @param array|null $data default value is []
     * @param string|null $message default value is null
     * @param int|null $status default value is null
     *
     * @return JsonResponse
     */
    public function json(?array $data = null, ?string $message = null, ?int $status = null): JsonResponse
    {
        $responseData = $this->toArray($data, $message, $status);
        return response()->json($responseData, $this->getStatusCode());
    }

    /**
     * Build Response Data
     *
     * @param array|null $data default value is []
     * @param string|null $message default value is null
     * @param int|null $status default value is null
     *
     * @return array
     */
    public function toArray(?array $data = null, ?string $message = null, ?int $status = null): array
    {
        if($data){
            $this->setData($data);
        }
        if($message){
            $this->setMessage($message);
        }
        if($status){
            $this->setStatusCode($status);
        }
        return [
            'status' => $this->getStatusCode(),
            'redirect_code' => $this->getRedirectCode(),
            'message' => $this->getMessage(),
            'data' => (object)$this->getData(),
        ];
    }


    /**
     * Set message
     *
     * @param string $message
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }


    /**
     * Get message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }


    /**
     * Set RedirectCode
     *
     * @param string $code
     * @return self
     */
    public function setRedirectCode(string $code): self
    {
        $this->redirect_code = $code;
        return $this;
    }

     /**
     * get RedirectCode
     *
     * @param string $code
     * @return self
     */
    public function getRedirectCode(): string
    {
        return $this->redirect_code;
    }

    /**
     * Set Data
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get Data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
