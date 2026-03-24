<?php

namespace App\Controllers;

use App\Models\CommanModel;

class VendorController extends BaseStaff
{
    public function index()
    {
        // Only admin can manage vendors
        if (($this->data['user_role'] ?? 'admin') !== 'admin') {
            return redirect()->to(base_url('users/user-dashboard'));
        }

        $om = new CommanModel();
        $this->data['vendors'] = $om->get_selected_data('*', 'admin_log', ['role' => 'vendor']);
        $this->data['main'] = 0;
        $this->data['sub'] = 0;
        $this->data['title'] = 'Vendor Management';

        return view('admin/vendor_list', $this->data);
    }

    public function create()
    {
        if (($this->data['user_role'] ?? 'admin') !== 'admin') {
            return redirect()->to(base_url('users/user-dashboard'));
        }

        $om = new CommanModel();

        if ($this->request->getMethod() === 'post') {
            $this->validation->setRules([
                'username'  => 'required|min_length[3]',
                'password'  => 'required|min_length[4]',
                'name'      => 'required',
                'shop_name' => 'required',
                'phone'     => 'required|numeric|min_length[10]',
            ]);

            if ($this->validation->withRequest($this->request)->run()) {
                $username = $this->request->getPost('username');

                // Check if username already exists
                $exists = $om->get_selected_data('id', 'admin_log', ['username' => $username]);
                if (!empty($exists)) {
                    $this->data['errors']['username'] = 'Username already exists!';
                } else {
                    $password = $om->encrypt($this->request->getPost('password'));

                    $insertData = [
                        'username'   => $username,
                        'password'   => $password,
                        'name'       => $this->request->getPost('name'),
                        'role'       => 'vendor',
                        'shop_name'  => $this->request->getPost('shop_name'),
                        'phone'      => $this->request->getPost('phone'),
                        'email'      => $this->request->getPost('email'),
                        'is_active'  => 'Y',
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    $om->insert_data('admin_log', $insertData);
                    return redirect()->to(base_url('users/vendor-list'))->with('success', 'Vendor created successfully!');
                }
            } else {
                $this->data['errors'] = $this->validation->getErrors();
            }
        }

        $this->data['main'] = 0;
        $this->data['sub'] = 0;
        $this->data['title'] = 'Add Vendor';
        $this->data['edit_mode'] = false;
        $this->data['vendor'] = null;

        return view('admin/vendor_form', $this->data);
    }

    public function edit($id)
    {
        if (($this->data['user_role'] ?? 'admin') !== 'admin') {
            return redirect()->to(base_url('users/user-dashboard'));
        }

        $om = new CommanModel();
        $vendor = $om->get_selected_data('*', 'admin_log', ['id' => $id, 'role' => 'vendor']);

        if (empty($vendor)) {
            return redirect()->to(base_url('users/vendor-list'))->with('error', 'Vendor not found!');
        }

        if ($this->request->getMethod() === 'post') {
            $this->validation->setRules([
                'name'      => 'required',
                'shop_name' => 'required',
                'phone'     => 'required|numeric|min_length[10]',
            ]);

            if ($this->validation->withRequest($this->request)->run()) {
                $updateData = [
                    'name'      => $this->request->getPost('name'),
                    'shop_name' => $this->request->getPost('shop_name'),
                    'phone'     => $this->request->getPost('phone'),
                    'email'     => $this->request->getPost('email'),
                ];

                // Update password only if provided
                $newPassword = $this->request->getPost('password');
                if (!empty($newPassword)) {
                    $updateData['password'] = $om->encrypt($newPassword);
                }

                $om->update_data('admin_log', ['id' => $id], $updateData);
                return redirect()->to(base_url('users/vendor-list'))->with('success', 'Vendor updated successfully!');
            } else {
                $this->data['errors'] = $this->validation->getErrors();
            }
        }

        $this->data['main'] = 0;
        $this->data['sub'] = 0;
        $this->data['title'] = 'Edit Vendor';
        $this->data['edit_mode'] = true;
        $this->data['vendor'] = $vendor[0];

        return view('admin/vendor_form', $this->data);
    }

    public function toggle($id)
    {
        if (($this->data['user_role'] ?? 'admin') !== 'admin') {
            return redirect()->to(base_url('users/user-dashboard'));
        }

        $om = new CommanModel();
        $vendor = $om->get_selected_data('id, is_active', 'admin_log', ['id' => $id, 'role' => 'vendor']);

        if (!empty($vendor)) {
            $newStatus = ($vendor[0]->is_active === 'Y') ? 'N' : 'Y';
            $om->update_data('admin_log', ['id' => $id], ['is_active' => $newStatus]);
        }

        return redirect()->to(base_url('users/vendor-list'));
    }
}
