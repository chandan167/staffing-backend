<?php

namespace App\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse extends Response
{

    /**
     * Hold response data
     * @var array $data
     */
    private array $data;


    /**
     * Hold response message
     * @var string $message
     */
    private string $message;


    /**
     * Hold redirect_code code
     * @var string $redirect_code
     */
    private string $redirect_code;



    public function __construct()
    {
        $this->data = [];
        $this->message = "";
        $this->redirect_code = "";
        $this->setStatusCode(self::HTTP_OK);
    }


    /**
     * set message
     * @param string $message
     * @return self|$this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }


    /**
     * set data
     * @param array $data
     * @return self|$this
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }


    /**
     * set redirect code
     * @param string $redirect_code
     * @return self|$this
     */
    public function setRedirectCode(string $redirect_code): self
    {
        $this->redirect_code = $redirect_code;
        return $this;
    }



    /**
     * get message
     * @return self|$this
     */
    public function getMessage(): string
    {
        return $this->message;
    }



    /**
     * get message
     * @return self|$this
     */
    public function getData(): array
    {
        return $this->data;
    }


    /**
     * get redirect code
     * @return self|$this
     */
    public function getRedirectCode(): string
    {
        return $this->redirect_code;
    }


    /**
     * return json response
     * @return JsonResponse
     */
    public function json(?int $statusCode = null, ?array $data = null, ?string $message = null)
    {
        $this->setResponse($statusCode, $data, $message);
        return response()->json($this->buildResponse(), $this->getStatusCode());
    }




    /**
     * set response data
     * @param int|null $statusCode
     * @param array|null $data
     * @param string|null $message
     * @return self|$this
     */
    private function setResponse(?int $statusCode = null, ?array $data = null, ?string $message = null): self
    {
        if ($statusCode) {
            $this->setStatusCode($statusCode);
        }
        if ($data) {
            $this->setData($data);
        }
        if ($message) {
            $this->setMessage($message);
        }

        return $this;
    }



    /**
     * build response data
     * @param int|null $statusCode
     * @param array|null $data
     * @param string|null $message
     * @return array
     */
    private function buildResponse(?int $statusCode = null, ?array $data = null, ?string $message = null): array
    {
        return [
            'status' => $this->statusCode,
            'message' => $this->message,
            'redirect_code' => $this->redirect_code,
            'data' => (object) $this->data
        ];
    }
}
