<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class BaseApiController extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';
    protected $modelName = '';
    protected $model;

    public function __construct()
    {
        if (!empty($this->modelName)) {
            $this->model = model($this->modelName);
        }
    }

    /**
     * Return success response
     */
    protected function success($data = null, $message = 'Success', $code = 200)
    {
        return $this->respond([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return error response
     */
    protected function error($message = 'Error', $code = 400, $data = null)
    {
        return $this->respond([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return validation error response
     */
    protected function validationError($errors, $message = 'Validation failed')
    {
        return $this->respond([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], 422);
    }

    /**
     * Return paginated response
     */
    protected function paginated($data, $pager, $message = 'Success')
    {
        return $this->respond([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'current_page' => $pager->getCurrentPage(),
                'per_page' => $pager->getPerPage(),
                'total' => $pager->getTotal(),
                'last_page' => $pager->getLastPage(),
                'first_page' => $pager->getFirstPage(),
                'next_page' => $pager->getNextPage(),
                'previous_page' => $pager->getPreviousPage(),
            ]
        ]);
    }
}
