<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\FooterModel;
use App\Models\FootersubModel;
use App\Models\NavlistModel;
use App\Models\CartModel;
use App\Models\OrdertempModal;
use App\Models\CusModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\CommanModel;
use App\Models\CategorystatusModel;
use App\Models\CategoryModel;
use App\Models\ServeModel;
use App\Models\MediaModel;
use App\Models\AddressModel;
use App\Libraries\PaymentGate;

class CartController extends BaseController
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
public function cart()
{

    $id = $this->session->get('user_id');


    $car = new CartModel;
    $cus = new CusModel;
    $ordertemp = new OrdertempModal();

    // delete temp orders
    $ordertemp->where('cus_id', $id)->delete();

    $this->data['cusdata'] = $cusdata = $cus->where('id', $id)->where('is_active', 'Y')->first();

    $this->data['cartdata'] = $cartdata = $car->where('tbl_cart.cus_id', $id)->get_data_by()->findAll();

 
    if ($_POST) {

        $validation = \Config\Services::validation();
        $validation->setRule('one', 'Something Went Wrong', 'required');

            if (empty($id)) {

                $this->session->setFlashdata('login_error', 'Please login to continue shopping.');
                return redirect()->back();
            }

        if ($validation->withRequest($this->request)->run()) {

            $data_val = [];

            // Step1: get total required quantity per product-variant
            $totalQtyNeeded = [];
            foreach ($cartdata as $item) {
                $key = $item->product_id . '_' . $item->variant;
                $totalQtyNeeded[$key] = ($totalQtyNeeded[$key] ?? 0) + (int)$item->quantity;
            }

            // Step2: insert only items that have stock
            foreach ($cartdata as $item) {

                $key = $item->product_id . '_' . $item->variant;
                $required = $totalQtyNeeded[$key];
                $stock = (int)$item->qty;

                // ignore this product if no stock
                if ($required > $stock) {
                    continue;
                }

                // Add item to order temp
                $data_val[] = [
                    'variant'      => $item->variant,
                    'quantity'     => $item->quantity,
                    'cart_id'      => $item->cart_id,
                    'cus_id'       => $id,
                    'created_date' => date('Y-m-d H:i:s')
                ];
            }

            // If at least one product inserted -> go to placeorder
            if (!empty($data_val)) {
                $ordertemp->insertBatch($data_val);
                return redirect()->to('placeorder');
            }

            // If none inserted -> show message
            session()->setFlashdata('error', 'No items available in stock to place the order.');
            return redirect()->back();
        }

        session()->setFlashdata('errors', $validation->getErrors());
    }
        if (empty($id)) {
        return view('web/guest_cart', $this->comman());  // guest cart page
    }

            if (empty($id) && empty($cartdata)) {
        return view('web/guest_cart', $this->comman());  // guest cart page
    }

     if(empty($this->data['cartdata'])){
        return redirect()->to(base_url());
    }


    return view('web/cart', $this->data + $this->comman());
}
public function details()
{
    $variantId = $this->request->getPost('variant');

    if (empty($variantId)) {
        return $this->response->setJSON(['status' => 'error', 'msg' => 'No variant sent']);
    }

    $variantModel = new \App\Models\VarientModel();
    $productModel = new \App\Models\ProductModel();

    // Get variant row
    $variant = $variantModel
                ->select('id, product_id, veriant, price, if_offer_per_price, qty')
                ->find($variantId);

    if (!$variant) {
        return $this->response->setJSON(['status' => 'error', 'msg' => 'Variant not found']);
    }

    // Get product row
    $product = $productModel
                ->select('product.product_name, product.product_type, product.tax, product.img, product.shipping,tbl_type.product_type as type_name')
                ->join('tbl_type', 'tbl_type.id = product.product_type', 'left')
                ->where('product.id', $variant->product_id)
                ->first();

    if (!$product) {
        return $this->response->setJSON(['status' => 'error', 'msg' => 'Product not found']);
    }


    // PRICE CALCULATIONS
    $base_price     = (float)$variant->price;
    $offer_percent  = (float)$variant->if_offer_per_price;
    $tax_percentage = isset($product->tax) ? (float)$product->tax : 0;

    // After discount
    $offer_price = $offer_percent > 0 ? ($base_price - ($base_price * $offer_percent / 100)) : $base_price;

    // After tax
    $final_price = $offer_price + ($offer_price * $tax_percentage / 100);


    // Response
    return $this->response->setJSON([
        'status'            => 'success',
        'variant'           => $variantId,
        'product_id'        => $variant->product_id,
        'product_name'      => $product->product_name,
        'product_type'      => $product->product_type,
        'veriant'           => $variant->veriant,
        'price'             => $base_price,
        'type_name'         => $product->type_name,
        'if_offer_per_price'=> $offer_percent,
        'tax'               => $tax_percentage,
        'final_price'       => $final_price,
        'img'               => base_url('uploads/products/' . $product->img),
        'shipping'          => isset($product->shipping) ? (float)$product->shipping : 0,
    ]);
}



    public function update_cart_qty()
{
    $cart_id  = $this->request->getPost('cart_id');
    $quantity = $this->request->getPost('quantity');

    // Update only quantity
    $cart = new CartModel;
    $cart->update($cart_id, [
        'quantity' => $quantity
    ]);

    return $this->response->setJSON([
        'status' => 'success'
    ]);
}



   public function cart_order()
{

    $id = $this->session->get('user_id');
    $address_id = $this->request->getGet('address');
    if($address_id){
        $address = new AddressModel();
       $address->update($address_id, [
            'primary_address' =>  1
        ]);
    }

    $cus = new CusModel();
    $this->data['cusdata'] = $cus->where('id', $id)->where('is_active', 'Y')->first();

    $ordertemp = new OrdertempModal();
    $this->data['cartdata'] = $cartdata = $ordertemp
        ->where('tbl_order_temp.cus_id', $id)
        ->get_data_by()
        ->findAll();
     
    $address = new AddressModel();
    $this->data['address'] = $address->select('tbl_cus_add.*,cities.name as city_name')->where('cus_id', $id)->join('cities', 'tbl_cus_add.city = cities.id')->findAll();
    

        if ($_POST) {
        
        $validation = \Config\Services::validation();
        $validation->setRule('selected_address_id', 'Something Went Wrong', 'required');

        if ($validation->withRequest($this->request)->run() == TRUE) {
              $address = $address->where('id', $this->request->getPost('selected_address_id'))->first();
              $cus = new CusModel();
              $payment = new PaymentGate();
              $shipping_cost =0;

            if( array_sum(array_column($cartdata, 'quantity'))  == 1)
            {
                $shipping_cost = $address->state == 19 ? $cartdata[0]->shipping : $cartdata[0]->shipping_outside;
            }
            $grandTotal = array_sum(array_map(function($item){
                $price  = (float)$item->price;
    $offer  = (float)$item->if_offer_per_price;
    $tax    = isset($item->tax) ? (float)$item->tax : 0;

     

    $offer_price = $offer > 0 ? round($price - ($price * $offer / 100),2) : $price;
    $offer_price = $tax > 0 ? round($offer_price + ($offer_price * $tax / 100),2) : $offer_price;
    $offer_price = $offer_price * $item->quantity;
  
                return $offer_price;
              }, $cartdata));
              $orderID = 'OD' . date('YmdHis') . random_int(1000, 9999);
                
              $cus_id = $this->session->get('user_id');
                $post = [
                    'api_key'        =>  getenv('api_key'),
                    'return_url'     => getenv('appurl') . 'payment',
                    'mode'           =>  getenv('status'),
                    'order_id'       =>  $orderID,
                    'amount'         =>  $grandTotal + $shipping_cost,
                    'currency'       => "INR",
                    'description'    => "Payment for Order" ,
                    'name'           => $address->name,
                    'email'          => "info@cllit.com",
                    'phone'          => $address->mobile,
                    'address_line_1' => $address->address,
                    'address_line_2' => $address->address,
                    'city'           => $address->city,
                    'state'          => $address->state,
                    'zip_code'       => $address->pincode,
                    'country'        => "IND",
                    'udf1'           => $cus->encrypt($cus_id),
                    
                ];
                $payment_id = $payment->saveData($post);
                  
          
            $order = new OrderModel();
            $lastOrder = $order->orderBy('id', 'DESC')->first();
            $order_id = (isset($lastOrder->order_id) ? $lastOrder->order_id : 0) + 1;

            $data_val = [];
            foreach ($cartdata as $key => $cardata) {
                $data_val[] = [
                    'order_id'     => $order_id,
                    'cus_id'       => $id,
                    'ord_status'   => 'N',
                    'variant'      => $cardata->variant,
                    'add_id'       => $this->request->getPost('selected_address_id'),
                    'quantity'     => $cardata->quantity,
                    'status'       => 'N',
                    'payment_id'   => $payment_id,
                    'amount'       => $cardata->price,
                    'created_date' => date('Y-m-d')
                ];
            }
         
            // Insert the order
            if ($order->insertBatch($data_val)) {

              
                $productVariant = model('ProductveriantModel');

                foreach ($cartdata as $cardata) {
                    $variantId = $cardata->variant;    
                    $orderQty  = (int)$cardata->quantity;

                    // Get current stock
                    $variant = $productVariant->where('id', $variantId)->first();

                    if ($variant) {
                        $newQty = max(0, (int)$variant->qty - $orderQty); // Prevent negative stock
                        $productVariant->where('id', $variantId)->set(['qty' => $newQty])->update();
                    }
                }

                
               /* $car = new CartModel();
                foreach ($cartdata as $cardata) {
                   
                    $car->where('id', $cardata->cart_id)->delete();
                }*/
                $post['udf2'] = $payment_id;
                $hash = $payment->hashCalculate(getenv('salt'), $post);
                $post['hash'] = $hash;
                $this->data['post'] = $post;

               return view('web/payment', $this->data + $this->comman());
            } else {
                session()->setFlashdata('error', 'Failed to create order.');
            }
        } else {
            session()->setFlashdata('errors', $validation->getErrors());
        }
    }

    return view('web/placeorder', $this->data + $this->comman());
}

    
   
    public function addressedit()
    {
      
         $id = $this->session->get('user_id');
        $cus = new CusModel;
        $address = $cus->where('id', $id)->where('is_active', 'Y')->first();
        $this->data['address'] = $address;
    
        if ($_POST) {
            $post = $this->request->getPost();
            $validation = \Config\Services::validation();
        //  dd($post);
    
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
                return redirect()->to('/placeorder');
            } else {
             
                $this->data['errors'] = $this->validation->getErrors();   
                return view('web/addressedit', $this->data + $this->comman());
             
            }
    
            return redirect()->back();
        }
    
        return view('web/addressedit', $this->data + $this->comman());
    }
    public function proedit($cusid,$proid,$var)
    {

        $car = new CartModel;
        $this->data['cart'] =  $car->select('*')->where('cus_id', $cusid)->where('product_id', $proid)->where('variant', $var)->findAll();
     
        $data['productModel'] = $product = new ProductModel();
        $commanModel = new CommanModel();
        $data['product'] = $product->status()->active()->find($proid);
        $data['productImages'] = $commanModel->get_selected_data("*", "product_images", ["product_id" => $proid]);
        $data['productVeriant'] = $commanModel->get_selected_data("*", "product_veriant", ["product_id" => $proid]);
        if ($_POST) {
            $post = $this->request->getPost();
          
            $this->validation->setRule('selectedPrice', 'Please Select any Varient', 'required');
            $this->validation->setRule('selectedVarp', 'Please Select any Varient', 'required');
            $this->validation->setRule('att_2', 'Variant', 'required');
    
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
                $price = strip_tags($this->request->getPost('selectedPrice'));
                $varp = strip_tags($this->request->getPost('selectedVarp'));
                $varient = strip_tags($this->request->getPost('att_2'));
                $msg = strip_tags($this->request->getPost('att_3'));
                $pincode = strip_tags($this->request->getPost('pin'));
                $eggless = strip_tags($this->request->getPost('eggless'));
                $off = strip_tags($this->request->getPost('offerprice'));
                $off1 = strip_tags($this->request->getPost('offerper'));
    
                $offper = $eggless == "Y" ? $off1 : $off;
    
                $data_val = [
                    'product_id' => $proid,
                    'price' => $price,
                    'off_price' => $varp,
                    'off_per' => $offper,
                    'message_on' => $msg,
                    'pin' => $pincode,
                    'variant' => $varient,
                    'eggless' => $eggless,
                    
                   // 'cus_id' => "1",
                   'cus_id' => $cusid,
                    'updated_date' => date('Y-m-d H:i:s'),

                ];
                $car = new CartModel;
                if ($car->where('cus_id', $cusid)
                ->where('variant', $var)
                ->where('product_id', $proid)
                ->set($data_val)
                ->update()) {
                    $this->session->setFlashdata('success', 'Product added to cart successfully!');
                
                    return redirect()->to('cart');
                } else {
                    $this->session->setFlashdata('error', 'Failed to add product to cart.');
                    return redirect()->back()->withInput();
                }
            } else {
             
                $errors = $this->validation->getErrors();
                $this->session->setFlashdata('validation_errors', $errors);
                return redirect()->back()->withInput();
            }
        }

        return view('web/editpro',$this->data + $data + $this->comman());


    }
    public function deletepro($cusid,$proid)
    {
        $car = new CartModel;


        $car->where('cus_id', $cusid)->where('id', $proid)->delete();
       
                $this->session->setFlashdata('success', 'Product deleted from the cart successfully!');
        return redirect()->to('cart')->withInput();
    }
}    
