<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\ProductModel;
use App\Models\CategorystatusModel;
use App\Models\CommanModel;

use App\Models\FooterModel;
use App\Models\FootersubModel;
use App\Models\OrdertempModal;
use App\Models\NavlistModel;
 use App\Models\PinModel;
 use App\Models\CartModel;
 use  App\Models\CategoryModel;
 use  App\Models\ServeModel;
 use  App\Models\MediaModel;
 use  App\Models\WishlistModel;
 use App\Models\CusModel;
 use App\Models\ProductratingModel;
 use App\Models\ProductcategoryModel;
 use App\Models\VarientModel;




class ProductHomeController extends BaseController
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

public function productlist($id, $sn)
{
    $product = new ProductModel();
    $wishlistModel = new WishlistModel();


    // Build base query
    if ($sn === 1) {
        $builder = $product->select('product.id as pro_id, product.*, pc.*, pv.*,pv.id as pv_id, tbl_type.product_type as type_name')
            ->veriants()->active()
            ->join('product_category pc', 'pc.product_id = product.id')
             ->join('tbl_type', 'product.product_type = tbl_type.id', 'left')
            ->where('pc.category_id', $id);
    } elseif ($sn === 2) {
        $builder = $product->select('product.id as pro_id, product.img as raw_img, product.*, pc.*, pv.*, pg.id,pv.id as pv_id, tbl_type.product_type as type_name')
            ->veriants()->active()
            ->join('product_category pc', 'pc.product_id = product.id')
            ->join('tbl_category pg', 'pg.id = pc.category_id')
            ->join('tbl_type', 'product.product_type = tbl_type.id', 'left')
            ->where('pg.main_id', $id);
    } elseif ($sn === 3) {
        $builder = $product->select('product.id as pro_id, product.*, pv.*,pv.id as pv_id, tbl_type.product_type as type_name')
        ->join('tbl_type', 'product.product_type = tbl_type.id', 'left')
            ->veriants()->active()
            ->where('product.categorystatus', $id);
    } else {
        $builder = $product->select('product.id as pro_id, product.*, pv.*,pv.id as pv_id, tbl_type.product_type as type_name')
        ->join('tbl_type', 'product.product_type = tbl_type.id', 'left')
            ->veriants()->active();
    }

    $data['productList'] = $builder->findAll();
    
    $userId = session()->get('user_id');


    // Category and subcategory data
    $nav = new NavlistModel();
    $data['category'] = $nav->where('main_id', 0)
        ->select('*, id as main_id')
        ->where('is_active', 'Y')
        ->findAll();

    $data['subcategory'] = $nav->where('main_id !=', 0)
        ->where('is_active', 'Y')
        ->findAll();

    return view('web/list', $data + $this->comman());
}

public function productlistfilter($id, $type)
{
    $type = trim($type, " '\"");
    $flag = $this->request->getGet('flag');

    $product = new ProductModel();
    $nav = new NavlistModel();

    // Type 1 → Filter using subcategory logic
    if ($type == '1') {

        // Fetch subcategories
        $subcats = $nav->select('*')
            ->where('main_id', $id)
            ->where('is_active', 'Y')
            ->findAll();

        // Handle no subcategories
        if (empty($subcats)) {
            return ($flag == '1') ? redirect()->to(base_url()) : redirect()->back();
        }

        // Extract subcategory IDs
        $subcatIds = array_column($subcats, 'id');

        // Get product_category links for subcategories
        $cats = (new ProductcategoryModel())
            ->select('category_id')
            ->whereIn('category_id', $subcatIds)
            ->findAll();

        // No matching product categories found → redirect
        if (empty($cats)) {
            return redirect()->to(base_url());
        }

        $catIds = array_column($cats, 'category_id');

        // Base product query
        $builder = $product->select('product.id as pro_id, product.*, pc.*, pv.*, pv.id as pv_id,tbl_type.product_type as type_name')
            ->veriants()
            ->active()
            ->join('tbl_type', 'product.product_type = tbl_type.id', 'left')
            ->join('product_category pc', 'pc.product_id = product.id')
            ->whereIn('pc.category_id', $catIds);
    }

    // Type 2 → Filter directly by category ID
    elseif ($type == '2') {

        $builder = $product->select('product.id as pro_id, product.*, pc.*, pv.*, pv.id as pv_id,tbl_type.product_type as type_name')
            ->veriants()
            ->active()
            ->join('tbl_type', 'product.product_type = tbl_type.id', 'left')
            ->join('product_category pc', 'pc.product_id = product.id')
            ->where('pc.category_id', $id);
    }

    // Any other type → redirect to homepage
    else {
        return redirect()->to(base_url());
    }

    // Fetch product list
    $data['productList'] = $builder->findAll();

    // ID from session
    $userId = session()->get('user_id');

    // Main categories
    $data['category'] = $nav->where('main_id', 0)
        ->select('*, id as main_id')
        ->where('is_active', 'Y')
        ->findAll();

    // Subcategories
    $data['subcategory'] = $nav->where('main_id !=', 0)
        ->where('is_active', 'Y')
        ->findAll();

    // Return view
    return view('web/list', $data + $this->comman());
}

    
    function productdetails($id,$title = "") {
    
      $commanModel = new CommanModel();
      $data['productModel'] = $product = new ProductModel();
      $data['product'] = $product->active()->find($id);
     
        $data['title'] = $title;
        $data['productImages'] = $commanModel->get_selected_data("*","product_images",["product_id" => $id],["order" => ["id", "asc"]]);

    
      $data['productVeriant'] = $commanModel->get_selected_data("*", "product_veriant", ["product_id" => $id]);

  

      $data['qa'] = $commanModel->get_selected_data("*", "tbl_qa", ["product_id" => $id]);
      $data['highlight']= $commanModel->get_selected_data("*", "tbl_highlight", ["product_id" => $id]);
     
      $category = $commanModel->get_selected_data("category_id", "product_category", ["product_id" => $id]);
      $rating = new ProductratingModel();
      $data['rating'] = $rating->select('product_ratings.*, tbl_cus.name')->join('tbl_cus', 'tbl_cus.id = product_ratings.user_id')->where('product_id', $id)->findAll();
    



      $categoryIds = array_column($category, 'category_id');

     $data['simproduct'] = $product->active()
    ->join('product_category', 'product_category.product_id = product.id')
    ->join('product_veriant', 'product_veriant.product_id = product.id')
    ->select('product.*, product_category.*, product_veriant.*')
    ->whereIn('product_category.category_id', $categoryIds)
    ->groupBy('product.id')   // ← fetch only one per product
    ->findAll();
                      

      if ($_POST) {
       
          $post = $this->request->getPost();
      
          if ($_POST['target'] == 'cart') {
          } else if ($_POST['target'] == 'buy') {
              return $this->direct_order();
          }
  
          $this->validation->setRule('variant', 'Please Select any Varient', 'required');
  
          if ($this->validation->withRequest($this->request)->run() == TRUE) {
       
              $varient = strip_tags($this->request->getPost('variant'));
           
              $quantity = '1';
              
           
         
              $user = $this->user_id;
       
              if(empty($user)){
           
                return redirect()->to('/')->with('login_error', 'Login to move further');
              }

              $data_val = [
                  'variant' => $varient,
                  'cus_id' => $this->user_id,
                  'quantity' => $quantity,
                  'created_date' => date('Y-m-d H:i:s'),
              ];
          
              
              $car = new CartModel;
              if ($car->insert($data_val)) {
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
  
      return view('web/productdetals', $data + $this->comman());
  }


public function cartadd()
{
    $productId = strip_tags($this->request->getPost('product_id'));
    $variant   = new VarientModel();
    $variants   = $variant->where('product_id', $productId)->first();
   
    $quantity  = 1;
    $user      = $this->user_id;

    if (empty($user)) {
        return $this->response->setJSON(['status' => 'login_required']);
    }

    $car = new CartModel;


    $data_val = [
        
        'variant'      => $variants->id,
        'cus_id'       => $user,
        'quantity'     => $quantity,
        'created_date' => date('Y-m-d H:i:s'),
    ];

    if ($car->insert($data_val)) {
        return $this->response->setJSON(['status' => 'success']);
    } else {
        return $this->response->setJSON(['status' => 'error']);
    }
}
public function sync_cart()
{
    $userId = session()->get('user_id');
    $items = $this->request->getPost('guest_cart');

    if (!$items || !$userId) {
        return $this->response->setJSON(['status' => 'no_items']);
    }

    $car = new CartModel();

    foreach ($items as $item) {

        // Check if variant already exists in cart
        $exists = $car->where('cus_id', $userId)
                      ->where('variant', $item['variant'])
                      ->first();

        if ($exists) {
            // update quantity
            $car->update($exists->id, [
                'quantity' => $exists->quantity + $item['quantity']
            ]);
        } else {
            // insert new item
            $car->insert([
                'variant'      => $item['variant'],
                'cus_id'       => $userId,
                'quantity'     => $item['quantity'],
                'created_date' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    return $this->response->setJSON(['status' => 'synced']);
}



public function direct_order()
{
    // -------------------------------
    // VALIDATION
    // -------------------------------
    $this->validation->setRule('variant', 'Please Select any Variant', 'required');

    if (!$this->validation->withRequest($this->request)->run()) {
        $errors = $this->validation->getErrors();
        $this->session->setFlashdata('validation_errors', $errors);
        return redirect()->back()->withInput();
    }

    // -------------------------------
    // LOGIN CHECK
    // -------------------------------
    if (empty($this->user_id)) {
        return redirect()->back()->with('login_error', 'Login to move further');
    }

    // -------------------------------
    // CLEAR PREVIOUS TEMP ORDER
    // -------------------------------
    $ordertemp = new OrdertempModal();
    $ordertemp->where('cus_id', $this->user_id)->delete();

    // -------------------------------
    // POST DATA
    // -------------------------------
    $variant  = strip_tags($this->request->getPost('variant'));
    $color_id = strip_tags($this->request->getPost('color_id')); // optional
    $size_id  = strip_tags($this->request->getPost('size_id'));  // optional
    $quantity = strip_tags($this->request->getPost('quantity')) ?: 1;

    // -------------------------------
    // INSERT DATA
    // -------------------------------
    $data_val = [
        'variant'      => $variant,
        'cus_id'       => $this->user_id,
        'quantity'     => $quantity,
        'created_date' => date('Y-m-d H:i:s'),
    ];

    // Optional fields (won’t break old products)
    if (!empty($color_id)) {
        $data_val['color_id'] = $color_id;
    }

    if (!empty($size_id)) {
        $data_val['size_id'] = $size_id;
    }

    // -------------------------------
    // SAVE
    // -------------------------------
    if ($ordertemp->insert($data_val)) {
        $this->session->setFlashdata('success', 'Product added successfully!');
        return redirect()->to('placeorder');
    }

    $this->session->setFlashdata('error', 'Failed to add product.');
    return redirect()->back()->withInput();
}

    public function getdata()
    {
        $pin = new PinModel();
        $pincode = $this->request->getGet('pincode');
        $data = $pin->select("pincode")->where("pincode",$pincode)->where("status","Y")->findAll();
        if ($data) {
          return $this->response->setJSON(['success' => true]);
      } else {
          return $this->response->setJSON(['success' => false]);
      }
     
    }

 public function toggle()
{
    // ✅ Check session
    if (!session()->get('user_id')) {
        return $this->response->setJSON([
            'status' => 'login_required',
            'msg' => 'Please login to add items to your wishlist.'
        ]);
    }

    $userId = session()->get('user_id');
    $productId = $this->request->getPost('productid'); // updated key
    $active = $this->request->getPost('active'); // 1 or 0
    $wishlistModel = new WishlistModel();

   
    $existing = $wishlistModel
        ->where('user_id', $userId)
        ->where('product_id', $productId)
        ->first();

    if ($active == 1) {
       
        if (!$existing) {
            $wishlistModel->insert([
                'user_id'    => $userId,
                'product_id' => $productId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'status' => 'added',
            'msg'    => 'Added to your wishlist!'
        ]);
    } else {
      
        if ($existing) {
            $wishlistModel->delete($existing->id);
        }

        return $this->response->setJSON([
            'status' => 'removed',
            'msg'    => 'Removed from your wishlist.'
        ]);
    }
}

public function wishlist()
{
    $userId = session()->get('user_id');

    if (!$userId) {
        // Redirect if not logged in
        return redirect()->to(base_url('login'))->with('error', 'Please log in to view your wishlist.');
    }

    $wishlistModel = new WishlistModel();
    $productModel = new ProductModel();

    // 1️⃣ Get all product IDs from wishlist for this user
    $wishlistItems = $wishlistModel
        ->select('product_id')
        ->where('user_id', $userId)
        ->findAll();

    if (empty($wishlistItems)) {
        $data['wishlistProducts'] = [];
    } else {
        // 2️⃣ Extract product IDs into an array
        $productIds = array_column($wishlistItems, 'product_id');

        // 3️⃣ Fetch those products
        $data['wishlistProducts'] = $productModel
            ->select('product.*, pv.*')
            ->veriants()        // joins product_veriant (if you use this trait)
            ->active()          // filters active products
            ->whereIn('product.id', $productIds)
            ->findAll();
    }
//dd($data['wishlistProducts']);
    // 4️⃣ Load category and subcategory data (like in productlist)
    $nav = new NavlistModel();
    $data['category'] = $nav->where('main_id', 0)
        ->select('*, id as main_id')
        ->where('is_active', 'Y')
        ->findAll();
    $data['subcategory'] = $nav->where('main_id !=', 0)
        ->where('is_active', 'Y')
        ->findAll();

    // 5️⃣ Return the view
    return view('web/wishlist', $data + $this->comman());
}

public function termspolicy($id){
  
    $data['policy'] = model('OtherModel')->where('id',$id)->first();
    return view('web/policy', $data + $this->comman());
}
public function footlink_view($id){
  
    $data['policy'] = model('FootersubModel')->where('id',$id)->first();

    return view('web/footview', $data + $this->comman());
}


   
}
