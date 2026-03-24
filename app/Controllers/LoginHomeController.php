<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\NavlistModel;
use App\Models\FooterModel;
use App\Models\FootersubModel;
use App\Models\CommanModel;
use App\Models\CusModel;
use App\Models\CategorystatusModel;
use App\Models\CategoryModel;
use App\Models\CartModel;
use App\Models\ServeModel;
use App\Models\MediaModel;





class LoginHomeController extends BaseController
{
    public function comman():array
  {
    $this->data['errors'] = $this->session->get('validation');
    $nav = new NavlistModel();
    $foot = new FooterModel();
    $foo = new FootersubModel();
    $social = new MediaModel();
    $data['social']  = $social->first();
    $data['result'] = array_map(function($a) {
        if($a['main_id'] == 0) {
          $nav = new NavlistModel();
          $a['sub_category'] = $nav->where('main_id', $a['id'])->findAll();
          return $a;
        } else {
          return $a;
        }
      }, $nav->findAll());
      $cat = new CategorystatusModel();
      $data['catstatus'] = $cat->findAll();
      $cata = new CategoryModel();
      $data['adsban'] = $cata->select('*')->where('type_id', '6')->findAll();
      $ra =  new ServeModel();
      $data['raw'] = $ra->findAll();
      $cart = new CartModel();
      if($this->session->has('user_id')){
        $data['cartcount'] = $cart->where('cus_id', $this->session->get('user_id'))->countAllResults();
      }
    $data['main'] = $foot->where('status', 'view')->orderBy('id', 'DESC')->findAll();
    $data['sub'] = $foo->findAll();
             $cus = new CusModel();
          $car = new CartModel();
          $id = $this->session->get('user_id');
    
        $data['cusdata'] = $id ? $cus->where('is_active', 'Y')->first() : '';
        $data['cartdata'] = $id ? $car->where('tbl_cart.cus_id', $id)->get_data_by()->findAll() : [];
  

    return $data;
     
  }
  public function login_old()
  {
      $data = [];
      if ($_POST) {
      
          $cus = new CusModel();
      
          $validation = \Config\Services::validation();
          $validation->setRules([
              'mobile' => 'required',
              'password' => 'required'
          ]);
          $postData = [
              'mobile' => $this->request->getPost('mobile'),
              'password' => $this->request->getPost('password')
          ];
    
          if (!$validation->run($postData)) {
           
              $data['errors'] = $validation->getErrors();
          } else {
          
              $password = $cus->encrypt($postData['password']);
            
              $cus = $cus->where('mobile', $postData['mobile'])
                          ->where('is_active', 'Y')
                          ->where('password', $password)
                          ->first();
                      
              if ($cus) {
            
                  $sess_data = ['user_id' => $cus['id']];
                  session()->set($sess_data);
         
                  return redirect()->to(base_url('/'));
              } else {

                  $data['errors']['password'] = "Password is incorrect. Please try again.";
              }
          }
      }
      if(isset($data['errors']))
      {
          $this->session->setFlashdata('errors', $data['errors']);
          return redirect()->back()->withInput();
      }
     
  }

  public function login()
{
      $otpService = service('smsService');
    
    if ($_POST) {
        
        $cus = new CusModel();
        $validation = \Config\Services::validation();
       session()->setFlashdata('login_post', $this->request->getPost('loginbtn'));
       session()->remove('login_user_id');
       $btn = $this->request->getPost('loginbtn');
     
       if($btn == 2 || $btn == 3)
       {
         $validation->setRules([
            'mobile'   => 'required|min_length[10]|max_length[10]',
        ]); 
        $postData = [
            'mobile'   => $this->request->getPost('mobile'),
        ];

        if (!$validation->run($postData)) {

            session()->setFlashdata('login_error', $validation->getErrors());
            return redirect()->back()->withInput();

        } else {
            $customer = $cus->where('mobile', $postData['mobile'])
                       ->where('is_active', 'Y')
                       ->first();
                 $otpService = service('smsService');

                $otp = rand(100000,999999);       
               
            if($customer == null)
            {
              $id =  $cus->insert([
                    'mobile' => $postData['mobile'],
                    'is_active' => 'Y',
                    'otp' => $otp,
                    'otpattempt' => 1,
                    'otpdatetime' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                  session()->set(['login_user_id' => $id, 'login_post_btn' => $btn]);
                 $otpService->otp($otp, $postData['mobile']);
            }else{
                $currentDateTime = new \DateTime();
                $otpDateTime = new \DateTime($customer['otpdatetime']);
                $interval = $currentDateTime->diff($otpDateTime);
                $minutesDiff = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
                $attempt = date('Y-m-d',strtotime($customer['otpdatetime'])) == date('Y-m-d') ? $customer['otpattempt'] : 0;
               
               
                if ($minutesDiff < 5 || $attempt >= 3) {
                    
                    session()->setFlashdata('login_error', ['mobile' => 'You have exceeded the OTP attempt limit. Please try again after 5 minutes.']);
                    return redirect()->back()->withInput();
                }  else {
                   
                $cus->update($customer['id'], [
                    'otp' => $otp,
                    'otpattempt' => $attempt + 1,
                    'otpdatetime' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                 session()->set(['login_user_id' => $customer['id'], 'login_post_btn' => $btn]);
                 $otpService->otp($otp, $postData['mobile']);
                 $email = $customer['email'];
                 if(!empty($email)){
                     $this->sendMail($email,$otp);
                 }
               }

            }
          
             return redirect()->back()->withInput();
           
           
        }
        
       }else{
         $validation->setRules([
            'mobile'   => 'required|min_length[10]|max_length[10]',
            'password' => 'required'
        ]);

        $postData = [
            'mobile'   => $this->request->getPost('mobile'),
            'password' => $this->request->getPost('password')
        ];

        if (!$validation->run($postData)) {

            session()->setFlashdata('login_error', $validation->getErrors());
            return redirect()->back()->withInput();

        } else {
               $password = $cus->encrypt($postData['password']);

            $cus = $cus->where('mobile', $postData['mobile'])
                       ->where('is_active', 'Y')
                       ->where('password', $password)
                       ->first();

         if ($cus) {
    session()->set(['user_id' => $cus['id']]);

    $previous = previous_url(); 

    if (strpos($previous, 'cart') !== false) {
       
        return redirect()->to(base_url('cart'));
    }

    return redirect()->back();
}
 else {
                session()->setFlashdata('login_error', ['password' => 'Password is incorrect. Please try again.']);
           
                return redirect()->back()->withInput();
            }
        }

       }
       
    }
}

    // Send OTP email via direct SMTP
    public function sendMail($emailId, $otp)
{
    $body = '
        <p style="font-family: Arial, sans-serif; font-size: 15px; color: #333;">
            Hi,
        </p>

        <p style="font-family: Arial, sans-serif; font-size: 15px; color: #333;">
            Your One-Time Password (OTP) is:
        </p>

        <div style="
            font-family: Arial, sans-serif;
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 4px;
            background: #f5f5f5;
            padding: 12px 20px;
            border-radius: 8px;
            display: inline-block;
            margin: 10px 0;
        ">
            '. $otp .'
        </div>

        <p style="font-family: Arial, sans-serif; font-size: 14px; color: #666;">
            This code is valid for 10 minutes.
        </p>

        <p style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
            Regards,<br>Ajwyn
        </p>
    ';

    helper('smtp');
    if (smtp_send($emailId, 'Login OTP - AJWYN', $body)) {
        return "Email sent successfully!";
    } else {
        return "Email sending failed";
    }
}

    
public function register()
{
    $validation = \Config\Services::validation();
    $cus = new CusModel();

    if ($_POST) {

        // FORM VALIDATION RULES
  $validation->setRules([
    'signname'  => 'required|min_length[3]|max_length[50]',
    'signemail' => 'required|valid_email|max_length[100]',
    'country'   => 'required|max_length[3]',
    'signmob'   => 'required|min_length[10]|max_length[10]',
    'signpsw1'  => 'required|min_length[6]|max_length[20]',
    'signpsw2'  => [
        'rules'  => 'required|matches[signpsw1]',
        'errors' => [
            'matches' => 'Password doesn\'t match'
        ]
    ]
]);

        if (!$validation->withRequest($this->request)->run()) {
           
              session()->setFlashdata('sign_error', $validation->getErrors());
            return redirect()->back()->withInput();
        }

        $postData = $this->request->getPost();
      

        // CHECK MOBILE ALREADY EXISTS
        $check_mobile = $cus->where('mobile', $postData['signmob'])
                            ->where('is_active', 'Y')
                            ->first();

        if ($check_mobile) {
           
        
            session()->setFlashdata('sign_error', ['signmob' => 'This mobile number already exists']);
             return redirect()->back()->withInput();
        }

 

        // SAVE USER
       $cus->save([
            'name'      => $postData['signname'],
            'email'     => $postData['signemail'],
            'mobile'    => $postData['signmob'],
            'country'   => $postData['country'],
            'password'  => $cus->encrypt($postData['signpsw1']),
            'otpdatetime' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'is_active' => 'Y'
        ]);
        
        $last_id = $cus->insertID();

    if (!empty($last_id)) {
    session()->set(['user_id' => $last_id]);

    $previous = previous_url(); 

    if (strpos($previous, 'cart') !== false) {
       
        return redirect()->to(base_url('cart'));
    }

    }
        return redirect()->to('/')
            ->with('success', 'Registration Successful. Please Login.');

    }

    return redirect()->back()->withInput();
}

public function password()
{
    $user_id = $this->session->get('user_id');
    $CusModel = new CusModel();

    // Fetch current user data
    $user = $CusModel->where('id', $user_id)->first();

    if ($_POST) {

        $old_password     = $this->request->getPost('old_password');
        $oldenc = $CusModel->encrypt($old_password);
        $new_password     = $this->request->getPost('new_password');
        $confirm_password = $this->request->getPost('confirm_password');

        if (($oldenc !== $user['password'])) {
              $this->session->setFlashdata('old_password_error', 'Current password is incorrect!');
            return redirect()->back()->withInput();
        }

        if ($new_password !== $confirm_password) {
            $this->session->setFlashdata('confirm_password_error', 'Passwords do not match!');
            return redirect()->back()->withInput();
        }

        $CusModel->update($user_id, [
            'password' => $CusModel->encrypt($new_password)
        ]);

        $this->session->setFlashdata('success', 'Password changed successfully!');
        return redirect()->back();
    }
}

      
    function logout()
    {
        $session=  \Config\Services::session();
        $session->stop();
		$session->remove('user_id');
        return redirect()->to(base_url() . '/');
    }

    public function proedit()
    {
      
         $id = $this->session->get('user_id');
        $cus = new CusModel;
        $address = $cus->where('id', $id)->where('is_active', 'Y')->first();
      
        $this->data['address'] = $address;
    
        if ($_POST) {
            $post = $this->request->getPost();
            $validation = \Config\Services::validation();
    
    
            $validation->setRules([
                'name' => 'required',
                'address' => 'required',
                'city' => 'required',
                'pin' => 'required',
                'mobile' => 'required',
               'location' => 'required'
            ], [
                'name' => ['required' => 'Name is required'],
                'address' => ['required' => 'Please enter address'],
                'city' => ['required' => 'City is required'],
                'pin' => ['required' => 'Pincode is required'],
                'mobile' => ['required' => 'Mobile number is required'],
                'location' => ['required' => 'Please select address type']
            ]);
    
      
    
            if ($validation->withRequest($this->request)->run()) {
                $mob = $this->request->getPost('mobile');

                $check_mobile = $cus->select('*')->where('mobile', $mob)->where('id !=', $id)->where('is_active', 'Y')->findAll();
                if (!empty($check_mobile)) {
                    $this->data['errors']['mobile'] = "This mobile number already exists";
                    return view('web/addressedit', $this->data + $this->comman());
                }
             
                $data = [
                    'name' => strip_tags($this->request->getPost('name')),
                    'address' => strip_tags($this->request->getPost('address')),
                    'city' => strip_tags($this->request->getPost('city')),
                    'landmark' => strip_tags($this->request->getPost('landmark')),
                    'pincode' => strip_tags($this->request->getPost('pin')),
                    'mobile' => strip_tags($this->request->getPost('mobile')),
                    'alt_mobile' => strip_tags($this->request->getPost('mobile2')),
                    'add_type' => strip_tags($this->request->getPost('location')),
                    'updated_date' => date('Y-m-d H:i:s')
                ];
          

    
                $cus->update($id, $data);
                session()->setFlashdata('success', 'Address updated successfully');
                return redirect()->to('/');
            } else {
             
                $this->data['errors'] = $this->validation->getErrors();   
                return view('web/addressedit', $this->data + $this->comman());
             
            }
    
            return redirect()->back();
        }
    
        return view('web/addressedit', $this->data + $this->comman());
    }

    public function passedit()
    {
        $id = $this->user_id;
        $cus = new CusModel;
    
        if ($_POST) {
            $post = $this->request->getPost();
            $validation = \Config\Services::validation();
    
            $validation->setRules([
                'old_password' => 'required',
                'password' => 'required',
                'con_password' => 'required|matches[password]'
            ], [
                'old_password' => ['required' => 'Old password is required'],
                'password' => ['required' => 'Password is required'],
                'con_password' => [
                    'required' => 'Please enter confirm password', 
                    'matches' => 'Password and confirm password do not match'
                ]
            ]);
    
            if ($validation->withRequest($this->request)->run()) {
                $old_password = $this->request->getPost('old_password');
                if (!empty($old_password)) {
                    $pass = $cus->select('password')->where('id', $id)->first();
                    $pass = $pass['password'];
              
                    $pass1 = $cus->encrypt($old_password);
    
                    if ($pass == $pass1) {
                        $new_password = $this->request->getPost('password');
                        $new_pass = $cus->encrypt($new_password);
                        $data = [
                            'password' => $new_pass
                        ];
                        $cus->update($id, $data);
                        session()->setFlashdata('success', 'Password updated successfully');
                        return redirect()->to('/');
                    } else {
                        $this->data['errors']['old_password'] = "Old password does not match";
                        return view('web/customer/passedit_new', $this->data + $this->comman());
                    }
                }
            } else {
                $this->data['errors'] = $validation->getErrors();
                return view('web/customer/passedit_new', $this->data + $this->comman());
            }
        }
        return view('web/customer/passedit_new', $this->data + $this->comman());
    }
    public function delacc()
    {
     
        $id = $this->user_id;
        $cus = new CusModel;
        $data = [
            'is_active' => 'N'
        ];
        $cus->update($id, $data);
        $session=  \Config\Services::session();
        $session->stop();
		$session->remove('user_id');
       
        return redirect()->to('/');
    }
    

    

    public function checkout()
    {
        $data = [];
          return view('web/checkout',$data + $this->comman());
 
    }
    public function loginOtp()
    {
      $otp = $this->request->getPost('userotp');
      $user_id = session()->get('login_user_id');
      $cus = new CusModel;
      $validation = \Config\Services::validation();
      $validation->setRules([
          'userotp' => 'required|numeric|exact_length[6]'
      ], [
          'userotp' => [
              'required' => 'OTP is required',
              'numeric' => 'OTP must be a number',
              'exact_length' => 'OTP must be 6 digits long'
          ]
      ]);

      if (!$validation->withRequest($this->request)->run()) {
           return redirect()->back()->withInput()->with('errors', $validation->getErrors());
      }

     $check_otp = $cus->select('id')->where('id', $user_id)->where('otp', $otp)->first();
     if (!empty($check_otp)) {
        session()->remove('login_user_id');
        session()->remove('login_post_btn');
        session()->set(['user_id' => $user_id]);

        $previous = previous_url();
        if (strpos($previous, 'cart') !== false) {
            return redirect()->to(base_url('cart'));
        }

        return redirect()->to('/');
     } else {
        session()->setFlashdata('error', 'Invalid OTP');
        return redirect()->back()->withInput()->with('errors', ['userotp' => 'Invalid OTP']);
     }

    }
    public function forgotOtp()
    {
      
      $otp = $this->request->getPost('userotp');
      $user_id = session()->get('login_user_id');
      $cus = new CusModel;
      $validation = \Config\Services::validation();
      $validation->setRules([
          'userotp' => 'required|numeric|exact_length[6]'
      ], [
          'userotp' => [
              'required' => 'OTP is required',
              'numeric' => 'OTP must be a number',
              'exact_length' => 'OTP must be 6 digits long'
          ]
      ]);

      if (!$validation->withRequest($this->request)->run()) {
           return redirect()->back()->withInput()->with('otperrors', $validation->getErrors());
      }

     $check_otp = $cus->select('id')->where('id', $user_id)->where('otp', $otp)->first();
     if (!empty($check_otp)) {
     $btn = session()->get('login_post_btn');
     session()->remove('login_user_id');
     session()->remove('login_post_btn');
     session()->set(['user_id' => $user_id]);
        if($btn == 2)
        {
          return redirect()->to('/');
        }else{
            return redirect()->to('/password-change');
        }
     
     }else{
      session()->setFlashdata('error', 'Invalid OTP');
      return redirect()->back()->withInput()->with('otperrors', ['userotp' => 'Invalid OTP']);
     }
 
    }
    
}
