<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\FooterModel;
use App\Models\FootersubModel;
use App\Models\NavlistModel;
use App\Models\CusModel;
use App\Models\OrderModel;
use App\Models\CategoryModel;
use App\Models\CategorystatusModel;
use App\Models\CartModel;
use App\Models\ServeModel;
use App\Models\MediaModel;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\AddressModel;
use App\Models\WishlistModel;
use App\Models\ProductModel;
use App\Models\ProductratingModel;
use App\Services\EmailNotificationService;

class OrderController extends BaseController
{
    public function comman():array
  {
   $data['errors'] = $this->session->get('validation');
   $data['login_error'] = $this->session->get('validation');
   $data['sign_error'] = $this->session->get('validation');

    $nav = new NavlistModel();
    $foot = new FooterModel();
    $foo = new FootersubModel();

    
    $data['result'] = array_map(function($a) {
      if($a['main_id'] == 0) {
         $nav = new NavlistModel();
        $a['sub_category'] = $nav->where('main_id', $a['id'])->findAll();
        return $a;} else {return $a;  } }, $nav->findAll());
        $raw = $data['result'];
        $menu = [];

foreach ($raw as $item) {

    if ($item['main_id'] == 0 && $item['sub_id'] == 0) {
        $menu[$item['id']] = $item;
        $menu[$item['id']]['children'] = [];
        continue;
    }

    if ($item['main_id'] != 0 && $item['sub_id'] == 0) {
        $menu[$item['main_id']]['children'][$item['id']] = $item;
        $menu[$item['main_id']]['children'][$item['id']]['children'] = [];
        continue;
    }

    if ($item['main_id'] != 0 && $item['sub_id'] != 0) {
        $menu[$item['main_id']]['children'][$item['sub_id']]['children'][] = $item;
        continue;
    }
}

     $data['menu'] = $menu;

      
 
     $cat = new CategorystatusModel();
   
    $data['catstatus'] = $cat->findAll();
    $cata = new CategoryModel();
    $ra =  new ServeModel();
    $data['raw'] = $ra->findAll();
    
    $cart = new CartModel();
    if ($this->session->has('user_id')) {
    $data['user_id'] = $user_id = $this->session->get('user_id');
    $data['cartcount'] = $cart->where('cus_id', $user_id)->countAllResults();
    $data['totalPrice']  = $cart->getTotalCartPrice($user_id);
    }

    $data['adsban'] = $cata->select('*')->where('type_id', '6')->findAll();
    $data['mainfoot'] = $foot->where('status', 'view')->orderBy('id', 'DESC')->findAll();
    
    
  
    $data['sub'] = $foo->findAll();
          $groupedSub = [];
            foreach ($data['sub'] as $s) {
             $groupedSub[$s['title_footlink_id']][] = $s;
           }

    $data['subGrouped'] = $groupedSub;




    $social = new MediaModel();
    $data['social']  = $social->first();

          $cus = new CusModel();
          $car = new CartModel();
          $id = $this->session->get('user_id');
    
       if(!empty($id)){
          $data['cusdata']  = $cus->where('id', $id)->where('is_active', 'Y')->first();
        }
      
      
     
       
        $data['cartdata'] = $id ? $car->where('tbl_cart.cus_id', $id)->get_data_by()->findAll() : [];

    return $data;
     
  }

    public function order(){


        $id = $this->session->get('user_id');
        $car = new OrderModel;
        $cus = new CusModel;
        $this->data['cusdata']  = $cus->where('id', $id)->where('is_active', 'Y')->first();
       
        $this->data['orderdata'] = $orderdata = $car->get_data_by2()->payment()->where('cus_id', $id)->where('ord_status !=', 'N')->orderBy('created_date', 'DESC')->findAll();

        if($_POST){

            $validation = \Config\Services::validation();
            $validation->setRule('ord_id', 'Something Went Wrong', 'required');
            
            if ($_POST) {
                $order = new OrderModel();
              $data_val =[
                'reason' => $this->request->getPost('reason'),
                'status' => 'N',

              ];
              $order->update($this->request->getPost('ord_id'), $data_val);
                $this->session->setFlashdata('success', 'Order cancelled successfully');
                return redirect()->back();
            }
          }
        return view('web/myorders', $this->data + $this->comman());
        

    }
    public function invoice($id){
    $social = new MediaModel();
    $data['social']  = $social->first();


    $car = new OrderModel;
    $this->data['orderdata'] = $orderdata = $car->get_data_by()->where('status', 'Y')->where('order_id', $id)->findAll();

    $user_id = $this->session->get('user_id');
    $address = new AddressModel;
    $this->data['address'] = $address = $address->select('tbl_cus_add.*,countries.name as country_name,states.name as state_name,cities.name as city_name')->where('tbl_cus_add.id', $orderdata[0]->add_id)->where('cus_id', $user_id)->country()->state()->city()->first();


       
        return view('web/invoice', $this->data + $this->comman());
    }

public function review()
{
    $id = $this->session->get('user_id');

    $car = new OrderModel;
    $cus = new CusModel;
    $ratingModel = new ProductratingModel();

    // Get customer
    $this->data['cusdata'] = $cus->where('id', $id)
                                ->where('is_active', 'Y')
                                ->first();

    // Get orders
    $orderdata = $car->get_data_by()
                     ->where('cus_id', $id)
                     ->where('status', 'Y')
                     ->orderBy('created_date', 'DESC')
                     ->findAll();

    // Attach existing rating for each order
    foreach ($orderdata as &$order) {
        $order->rating_info = $ratingModel
            ->where('user_id', $id)
            ->where('product_id', $order->product_id)
            ->first();
    }

    // Pass order data to view
    $this->data['orderdata'] = $orderdata;

    // PROCESS FORM SUBMISSION
    if ($_POST) {

       $product_id = $this->request->getPost('product_id');
    $rating     = $this->request->getPost('rating');
    $review     = $this->request->getPost('review');

    // Check if already rated
    $exists = $ratingModel->where('user_id', $id)
                          ->where('product_id', $product_id)
                          ->first();

    if ($exists) {
        // UPDATE existing review
        $ratingModel->update($exists->id, [
            'rating' => $rating,
            'review' => $review,
        ]);

        session()->setFlashdata('success', 'Your review has been updated!');
        return redirect()->back();
    }

    // INSERT new review
    $ratingModel->insert([
        'user_id'    => $id,
        'product_id' => $product_id,
        'rating'     => $rating,
        'review'     => $review,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    session()->setFlashdata('success', 'Thank you for your review!');
    return redirect()->back();
    }

    return view('web/review', $this->data + $this->comman());
}


        public function new_address(){


        $id = $this->session->get('user_id');
              $country = new CountryModel();
              $this->data['country'] = $country->findAll();
              $place = $this->request->getget('place');
       
        if($_POST){
   

            $validation = \Config\Services::validation();
            $validation->setRule('name', 'name', 'required');
            $validation->setRule('address', 'address', 'required');
            $validation->setRule('country', 'country', 'required');
            $validation->setRule('state', 'state', 'required');
            $validation->setRule('pincode', 'pin', 'required');
            $validation->setRule('mobile', 'mobile', 'required');
 
            
            if ($validation->withRequest($this->request)->run() == TRUE) {
                $order = new AddressModel();
              $data_val =[
                'cus_id' => $id,
                'name' => $this->request->getPost('name'),
                'address' => $this->request->getPost('address'),
                'country' => $this->request->getPost('country'),
                'state' => $this->request->getPost('state'),
                'city' => $this->request->getPost('city'),
                'pincode' => $this->request->getPost('pincode'),
                'mobile' => $this->request->getPost('mobile'),
                'landmark' => $this->request->getPost('landmark'),
                'created_at' => date('Y-m-d H:i:s')
              ];
              $order->insert($data_val);
                $this->session->setFlashdata('success', 'Address added successfully');
                if($place=='1'){
                    return redirect()->to(base_url('placeorder'));
                }
                return redirect()->back();
            }
          }
        return view('web/newship', $this->data + $this->comman());
        

    }
    public function edit_address($id)
{
    $user_id = $this->session->get('user_id');

    $addressModel = new AddressModel();
    $countryModel = new CountryModel();

    // Get existing address data
    $address = $addressModel->where('cus_id', $user_id)->find($id);

    // If no record found or belongs to another user
    if (!$address) {
        $this->session->setFlashdata('error', 'Invalid address selected');
        return redirect()->back();
    }

    $this->data['country'] = $countryModel->findAll();
    $this->data['address'] = $address; // send old data to view

    if ($_POST)  {

        $validation = \Config\Services::validation();
        $validation->setRule('name', 'name', 'required');
        $validation->setRule('address', 'address', 'required');
        $validation->setRule('country', 'country', 'required');
        $validation->setRule('state', 'state', 'required');
        $validation->setRule('pincode', 'pin', 'required');
        $validation->setRule('mobile', 'mobile', 'required');

        if ($validation->withRequest($this->request)->run() == TRUE) {

            $updateData = [
                'name'      => $this->request->getPost('name'),
                'address'   => $this->request->getPost('address'),
                'country'   => $this->request->getPost('country'),
                'state'     => $this->request->getPost('state'),
                'city'      => $this->request->getPost('city'),
                'pincode'   => $this->request->getPost('pincode'),
                'mobile'    => $this->request->getPost('mobile'),
                'landmark'  => $this->request->getPost('landmark'),
              
            ];

            $addressModel->update($id, $updateData);

            $this->session->setFlashdata('success', 'Address updated successfully');
            return redirect()->to(base_url('show_address'));
        }
    }

    return view('web/editship', $this->data + $this->comman());
}
public function profile()
{
    $user_id = $this->session->get('user_id');
    $this->data['validation'] = \Config\Services::validation();
    $this->data['error'] = $this->session->getFlashdata('error'); // duplicate mobile / email errors


    $CusModel = new CusModel();
    $this->data['a'] = $address = $CusModel->where('id', $user_id)->first();

    if ($_POST) {

        $validation = \Config\Services::validation();
        $validation->setRule('name', 'name', 'required');
        $validation->setRule('mobile', 'mobile', 'required');
        $validation->setRule('email', 'email', 'required');

        if ($validation->withRequest($this->request)->run() == TRUE) {

            $mobile = $this->request->getPost('mobile');
            $email  = $this->request->getPost('email');

            /* 🔍 Check if another user already uses this mobile */
            $exists_mobile = $CusModel
                ->where('mobile', $mobile)
                ->where('id !=', $user_id)
                ->first();

            if (!empty($exists_mobile)) {
                $this->session->setFlashdata('error', 'Mobile Number already exists!');
                return redirect()->back()->withInput();
            }

            /* 🔍 Check if another user already uses this email */
            $exists_email = $CusModel
                ->where('email', $email)
                ->where('id !=', $user_id)
                ->first();

            if (!empty($exists_email)) {
                $this->session->setFlashdata('error', 'Email already exists!');
                return redirect()->back()->withInput();
            }

            /* ✔ Passed all checks — safe to update */
            $updateData = [
                'name'   => $this->request->getPost('name'),
                'mobile' => $mobile,
                'email'  => $email,
            ];

            $CusModel->update($user_id, $updateData);

            $this->session->setFlashdata('success', 'Profile updated successfully');
            return redirect()->to(base_url('profile'));
        }
    }

    return view('web/profile', $this->data + $this->comman());
}

   

    public function address(){

        $id = $this->session->get('user_id');
        $car = new AddressModel;
        $cus = new CusModel;
        $this->data['cusdata']  = $cus->where('id', $id)->where('is_active', 'Y')->first();
        $this->data['address'] = $address = $car->select('tbl_cus_add.*,countries.name as country_name,states.name as state_name,cities.name as city_name')->where('cus_id', $id)->country()->state()->city()->findAll();
 
      
        return view('web/address', $this->data + $this->comman());
    }

public function prime_add($address_id)
{
    $user_id = $this->session->get('user_id');
    $model = new AddressModel();
    $model->where('cus_id', $user_id)->set(['primary_address' => 0])->update();
    $model->update($address_id, ['primary_address' => 1]);

    return redirect()->back()->with('success', 'Primary address updated!');
}

public function wishlist()
{
    $userId = session()->get('user_id');

    if (!$userId) {
        return redirect()->back()->with('login_error', 'Login to move further');
    }

    $wishlistModel = new WishlistModel();
    $productModel = new ProductModel();
    $wishlistItems = $wishlistModel
        ->select('product_id')
        ->where('user_id', $userId)
        ->findAll();

    if (empty($wishlistItems)) {
        $data['wishlistProducts'] = [];
    } else {
        $productIds = array_column($wishlistItems, 'product_id');
        $data['wishlistProducts'] = $productModel
            ->select('product.*, pv.*')
            ->veriants()        // joins product_veriant (if you use this trait)
            ->active()          // filters active products
            ->whereIn('product.id', $productIds)
            ->findAll();
    }

    return view('web/wishlist', $data + $this->comman());
}


public function get_state() {
    $id = $this->request->getPost('id'); // <-- match AJAX
    $state = new StateModel();
    $data = $state->where('country_id', $id)->findAll();

    return $this->response->setJSON($data);
}
public function get_district() {
    $state_id = $this->request->getPost('state_id'); // match AJAX
    $district = new CityModel();
    $data = $district->where('state_id', $state_id)->findAll();

    return $this->response->setJSON($data);
}

public function cancel_order()
{
    $order_id   = $this->request->getPost('order_id');
    $product_id = $this->request->getPost('product_id');
   
    $reason     = $this->request->getPost('reason');

    if (empty($reason)) {
        $this->session->setFlashdata('errors', 'Reason is required');
        return redirect()->back();
    }
    $remarks    = $this->request->getPost('remarks');
  

    $data = [
        'status' => 'N',    
        'ord_status' => 'C',                   // cancelled
        'cancel_reason' => $reason,            // numeric 1–10
        'cancel_note' => $remarks,
        'cancel_date' => date('Y-m-d H:i:s')
    ];

    $car = new OrderModel;


    // update using both conditions
    $result = $car->where('order_id', $order_id)
                  ->where('variant', $product_id)
                  ->set($data)
                  ->update();

    if ($result) {
        $cusData = (new CusModel())->find($this->session->get('user_id'));
        $email = $cusData['email'] ?? '';
        $cusName = $cusData['name'] ?? 'Customer';
        if($email){
            $this->sendMail($email, $order_id);
        }

        // Notify admin and vendors about cancellation
        $notifier = new EmailNotificationService();
        $notifier->orderCancelledAdmin($order_id, $cusName);
        $notifier->orderCancelledVendors($order_id, $cusName);

        session()->setFlashdata('success', 'Order cancelled successfully');
    } else {
        session()->setFlashdata('error', 'Failed to cancel order');
    }

    return redirect()->back();
}
public function sendMail($emailId, $orderId)
{
    $htmlBody = '
    <div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h2 style="color:#e63946;">Order Cancelled</h2>

        <p style="font-size: 15px; color:#333;">
            Hello,
        </p>

        <p style="font-size: 15px; color:#333;">
            This is to inform you that your order <strong>#' . $orderId . '</strong> has been <strong>cancelled</strong>.
        </p>

        <p style="background:#ffe5e5; padding:10px 15px; border-left:4px solid #e63946; color:#b30000;">
            If this cancellation was not done by you, please contact our support team immediately.
        </p>

        <p style="margin-top: 20px; font-size: 14px; color:#555;">
            Thank you,<br>
            <strong>Team '.getenv('APP_NAME').'</strong>
        </p>
    </div>';

    helper('smtp');
    $appName = getenv('APP_NAME') ?: 'AJWYN';
    if (smtp_send($emailId, 'Order Cancelled - ' . $appName, $htmlBody)) {
        return "Email sent successfully!";
    } else {
        return "Email sending failed";
    }
}

function password_change(){
    $id = $this->session->get('user_id');
    $cus = new CusModel;
    if (!$id) {
        return redirect()->back()->with('login_error', 'Login to move further');
    }
    if(!empty($_POST)){
          $validation = \Config\Services::validation();
    $validation->setRule('new_password', 'new password', 'required|min_length[6]');
    $validation->setRule('confirm_password', 'confirm password', 'required|matches[new_password]|min_length[6]');
    if ($validation->withRequest($this->request)->run() == TRUE) {
        $new_password = $this->request->getPost('new_password');
      
        $cus->update($id, ['password' =>  $cus->encrypt($new_password)]);
        $this->session->setFlashdata('success', 'Password changed successfully');
        return redirect()->back();
    }else{
       $this->data['errors'] = $validation->getErrors();
        
    }
    }
  
    $this->data['cusdata']  = $cus->where('id', $id)->where('is_active', 'Y')->first();
    return view('web/password_change', $this->data + $this->comman());
}
    




    
}
