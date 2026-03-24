<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\CommanModel;

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
abstract class BaseStaff extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $session;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form', 'url','text','date'];
    protected $user_id;
    protected $data;
    protected $userdetails;
    protected $om;
    protected $validation;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->data['om'] = $this->om =  new CommanModel();
        $this->session = \Config\Services::session();
        $this->data['session'] = 	$this->session = \Config\Services::session();
        $this->data['userid'] = $this->user_id = $this->session->get('usar_id');
        $sessionRole = $this->session->get('usar_role');
        $this->data['user_role'] = !empty($sessionRole) ? $sessionRole : 'admin';


        $this->data['menus'] =  $this->om->get_selected_data('*', "menu", );



        $this->validation =  \Config\Services::validation();
        helper($this->helpers);

        $this->data['userdetails'] = $this->userdetails = $this->om->get_selected_data('*', "admin_log", ["id" => $this->user_id]);

    }

    


}
