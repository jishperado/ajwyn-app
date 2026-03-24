<?php

namespace App\Controllers;
use App\Models\CommanModel;
use CodeIgniter\Controller;

class Useradmin extends BaseStaff
{
    public function login()
    {
        $this->data['obj'] = $om = new CommanModel() ;
        

        if ($_POST) {
            $this->validation->setRule('name', 'Name', 'required');
            $this->validation->setRule('pwd', 'Password', 'required');

            if ( $this->validation->withRequest($this->request)->run() == TRUE) {
                $name = $this->request->getPost('name');
                $password = $this->request->getPost('pwd');
                $password = $om->encrypt($password);
                
                $result = $om->get_selected_data("id, role, is_active", "admin_log", ["username" => $name, "password" => $password]);


                if (!empty($result)) {
                    // Check if account is active
                    if (isset($result[0]->is_active) && $result[0]->is_active === 'N') {
                        $this->data['errors']['name'] = "Your account has been deactivated. Please contact admin.";
                    } else {
                        $sess_data = array(
                            'usar_id' => $result[0]->id,
                            'usar_role' => !empty($result[0]->role) ? $result[0]->role : 'admin',
                        );
                        $this->session->set($sess_data);
                        return redirect()->to(base_url() . 'users/user-dashboard');
                    }
                }else{
                    $this->data['errors']['name'] = "Wrong User-ID or Password!";
                }
                 
                } 
             else {
                $this->data['errors'] = $this->validation->getErrors();
            }
        }

        return view('admin/login',$this->data); //<-$this->data from BaseController protected data (using session)

    }

    function logout()
    {
       
        $session=  \Config\Services::session();
        $session->stop();
		$session->destroy();
        return redirect()->to(base_url() . 'user-cllit-management');
    }

}
