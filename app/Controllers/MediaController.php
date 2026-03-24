<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Config\Factories;

class MediaController extends BaseController
{
    public function index()
    {
        $this->data['main'] = 15;
        $this->data['sub'] = 0;
        $med = Factories::models('MediaModel');
        $this->data['result'] = $med->first();
  
       
        return view('admin/social',$this->data);
    }
    
    public function update()
    {
        $med = Factories::models('MediaModel');
       $id = 1;
     
        $postdata = $this->request->getPost();
    
        if ($med->update($id, $postdata)) {
            $this->session->setFlashdata('success', 'Data Updated successfully!!');
        } else {
            $this->session->setFlashdata('error', 'Update Failed!!');
        }
        
        return redirect()->to('media');
    }
}
