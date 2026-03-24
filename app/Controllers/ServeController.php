<?php

namespace App\Controllers;

use App\Controllers\BaseStaff;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\ServeModel;

class ServeController extends BaseStaff
{
    function getCommonData() : array {
        $data['main'] = 2;
        $data['sub'] = 9;
        return $data;
    }
    public function show()
    {
      
        $serve = new ServeModel();

        $this->data['raw'] = $serve->findAll();

        return view("flash/show",$this->data + $this->getCommonData());
    }
    public function edit($id)
    {
        $serve = new ServeModel();
        $this->data['raw'] = $serve->find($id);
        return view("serve/edit",$this->data + $this->getCommonData());
    }
   public function update($id)
{
    $serve = new ServeModel();
    $icon = $this->request->getFile('icon');

    // 1. Create base data
    $data_val = [
        'head'        => $this->request->getPost('head'),
        'description' => $this->request->getPost('description'),
    ];

    // 2. Process icon only if uploaded
    if (!empty($icon->getName())) {

        $rules_icon = [
            'icon' => [
                'label' => 'Icon',
                'rules' => 'uploaded[icon]|mime_in[icon,image/jpg,image/jpeg,image/png,image/gif,image/svg+xml]|max_size[icon,2048]',
            ]
        ];

        if (!$this->validate($rules_icon)) {
            $validationErrors = $this->validator->getErrors();
            $this->session->setFlashdata('error', $validationErrors);
            return redirect()->back()->withInput();
        }

        if ($icon->isValid()) {

            // get old icon
            $oldIcon = $serve->select('icon')->where('id', $id)->first();

            $PATH = getcwd();

            if (!empty($oldIcon->icon) && file_exists($PATH . '/web/images/' . $oldIcon->icon)) {
                unlink($PATH . '/web/images/' . $oldIcon->icon);
            }

            // upload new icon
            $iconName = date("Y-m-d") . '_icon_' . $icon->getRandomName();
            $icon->move($PATH . '/web/images/', $iconName);

            // add icon field to $data_val
            $data_val['icon'] = $iconName;
        }
    }

    // 3. Update database
    if ($serve->update($id, $data_val)) {
        session()->setFlashdata('success', 'Update successful.');
        return redirect()->to('/serve/show');
    }
}

}
