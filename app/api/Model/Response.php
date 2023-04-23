<?php

namespace Koupon\Model;

final class Response
{
    public function __construct()
    {
        $this->setHeaders();
    }

    /**
     * @return [type]
     */
    public function setHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Content-Type: application/json');
    }

    /**
     * @param array $data
     *
     * @return [type]
     */
    public function send(array $data)
    {
        die(json_encode($data));
    }

    public function sendMessage(string $message)
    {
        $this->send(['message' => $message]);
    }

    /**
     * @param string $message
     * @param int $code
     *
     * @return [type]
     */
    public function sendError(string $message, int $code = 500)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param string $message
     * @param int $code
     *
     * @return [type]
     */
    public function sendSuccess(string $message, int $code = 200)
    {
        http_response_code($code);
        $this->send(['success' => $message]);
    }

    /**
     * @param mixed string
     * @param int $code
     *
     * @return [type]
     */
    public function sendNotFound(string $message = 'Not Found', int $code = 404)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param string $message
     * @param int $code
     *
     * @return [type]
     */
    public function sendUnauthorized(string $message = 'Unauthorized', int $code = 401)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param string $message
     * @param int $code
     *
     * @return [type]
     */
    public function sendForbidden(string $message = 'Forbidden', int $code = 403)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param mixed string
     * @param int $code
     *
     * @return [type]
     */
    public function sendBadRequest(string $message = 'Bad Request', int $code = 400)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param string $message
     * @param int $code
     *
     * @return [type]
     */
    public function sendConflict(string $message = 'Conflict', int $code = 409)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param mixed string
     * @param int $code
     *
     * @return [type]
     */
    public function sendUnprocessableEntity(string $message = 'Unprocessable Entity', int $code = 422)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param mixed string
     * @param int $code
     *
     * @return [type]
     */
    public function sendMethodNotAllowed(string $message = 'Method Not Allowed', int $code = 405)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param mixed string
     * @param int $code
     *
     * @return [type]
     */
    public function sendNotImplemented(string $message = 'Not Implemented', int $code = 501)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param mixed string
     * @param int $code
     *
     * @return [type]
     */
    public function sendServiceUnavailable(string $message = 'Service Unavailable', int $code = 503)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param mixed string
     * @param int $code
     *
     * @return [type]
     */
    public function sendGatewayTimeout(string $message = 'Gateway Timeout', int $code = 504)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }

    /**
     * @param mixed string
     * @param int $code
     *
     * @return [type]
     */
    public function sendInternalServerError(string $message = 'Internal Server Error', int $code = 500)
    {
        http_response_code($code);
        $this->send(['error' => $message]);
    }
}
