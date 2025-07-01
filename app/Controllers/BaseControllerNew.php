<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['form', 'url'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    /**
     * Return JSON response
     */
    protected function respondWithJson($data, $statusCode = 200, $message = 'Success')
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON([
                'status' => $statusCode < 400 ? 'success' : 'error',
                'message' => $message,
                'data' => $data
            ]);
    }

    /**
     * Return success response
     */
    protected function respondWithSuccess($data = null, $message = 'Operation successful')
    {
        return $this->respondWithJson($data, 200, $message);
    }

    /**
     * Return error response
     */
    protected function respondWithError($message = 'An error occurred', $statusCode = 400, $data = null)
    {
        return $this->respondWithJson($data, $statusCode, $message);
    }

    /**
     * Return validation error response
     */
    protected function respondWithValidationError($errors)
    {
        return $this->respondWithJson($errors, 422, 'Validation failed');
    }

    /**
     * Return not found response
     */
    protected function respondWithNotFound($message = 'Resource not found')
    {
        return $this->respondWithError($message, 404);
    }

    /**
     * Return unauthorized response
     */
    protected function respondWithUnauthorized($message = 'Unauthorized access')
    {
        return $this->respondWithError($message, 401);
    }
}
