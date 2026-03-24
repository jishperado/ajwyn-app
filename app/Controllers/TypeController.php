<?php

namespace App\Controllers;

use App\Controllers\BaseStaff;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Config\Factories;

class TypeController extends BaseStaff
{
            function getCommonData() : array {
        $data['main'] = 30;
        $data['sub'] = 0;
        return $data;
    }
    public function index()
    {
      
        $type = Factories::models('TypeModel');
        $this->data['result'] = $type->findAll();
        $this->data['err'] = $this->session->get('validationerror');
   
        return view('type/type',$this->data + $this->getCommonData());
     
    }

    public function create()
    {
        $pro_type= $this->request->getPost('pro_type');

        $validation = \Config\Services::validation();
        $validation->setRule('pro_type', 'Type', 'required');
        if (!$this->validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('validationerror',  $this->validation->getErrors());
        }

        $data_val = [
            'product_type' => $pro_type,
            'created_at' => date('Y-m-d H:i'),
            'status ' => 'Y',
            'created_id' => $this->user_id,
        ];

        $type = Factories::models('TypeModel');
        $type->insert($data_val);

        return redirect()->back()->with('success', 'Data Saved successfully!!');
    }
  
    public function edit($id)
    {
        $type = Factories::models('TypeModel');


        $data_val = [
            'product_type' => $this->request->getPost('product_type'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i'),
            'updated_by' => $this->user_id,
        ];
    
        $type->update($id, $data_val);
        return redirect()->back()->with('success', 'Data Updated successfully!!');
    }
    public function delete()
    {
        $id = $this->request->getPost('deleteclii');
        $type = Factories::models('TypeModel');
        foreach ($id as $val) {
            $type->delete($val);
        }
        return redirect()->back()->with('success', 'Data Deleted successfully!!');
    }
}
