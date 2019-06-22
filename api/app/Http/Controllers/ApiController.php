<?php

namespace App\Http\Controllers;
use Response;
use \Illuminate\Http\Response as Res;

/**
 * Class ApiController
 * @package etymology\api\Http\Auth\Controllers
 */
class ApiController extends Controller {
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->beforeFilter('auth', ['on' => 'post']);
    }

    /**
     * @var int
     */
    protected $statusCode = Res::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return ApiController response
     */
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $message
     * @param $data
     * @return mixed respond
     */
    public function respondCreated($message, $data = null) {
        return $this->respond([
            STATUS => SUCCESS,
            STATUS_CODE => Res::HTTP_CREATED,
            MESSAGE => $message,
            DATA => $data
        ]);
    }

    /**
     * @param $message
     * @return mixed respond
     */
    public function respondNotFound($message = 'Not Found!') {
        return $this->respond([
            STATUS => ERROR,
            STATUS_CODE => Res::HTTP_NOT_FOUND,
            MESSAGE => $message,
        ]);
    }

    /**
     * @param $message
     * @param $errors
     * @return mixed respond
     */
    public function respondValidationError($message, $errors) {
        return $this->respond([
            STATUS => ERROR,
            STATUS_CODE => Res::HTTP_UNPROCESSABLE_ENTITY,
            MESSAGE => $message,
            DATA => $errors
        ]);
    }

    /**
     * @param $message
     * @return mixed respond
     */
    public function respondWithError($message) {
        return $this->respond([
            STATUS => ERROR,
            STATUS_CODE => Res::HTTP_UNAUTHORIZED,
            MESSAGE => $message,
        ]);
    }

    /**
     * @param $data
     * @param $headers
     * @return mixed Response
     */
    public function respond($data, $headers = []) {
        return Response::json($data, $this->getStatusCode(), $headers);
    }
}
