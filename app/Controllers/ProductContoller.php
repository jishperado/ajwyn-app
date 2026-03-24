<?php

namespace App\Controllers;

use App\Controllers\BaseStaff;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CategoryModel;
use App\Models\ProductModel;

class ProductContoller extends BaseStaff
{
    function getCommonData() : array {
        $data['main'] = 10;
        $data['sub'] = 10;
        return $data;
    }
    public function show($id)  {
        $this->data['id'] =   $id;
        $this->data['err'] = $this->session->get('validationerror');
        $this->data['result'] =   $this->om->get_selected_data("id,product_image,product_image_s","product_images",["product_id"=>$id]);
        return view('product/photos',$this->data + $this->getCommonData());
    
    }
    public function savephotos()  {

        $catemodel = new CategoryModel();
       
         $this->validation->setRules([
            'photo' => 'uploaded[photo]|ext_in[photo,jpg,jpeg,png,gif]'
        ]);

        if ($this->validation->withRequest($this->request)->run() == TRUE) {
           
            $PATH = getcwd();
            $files = $this->request->getFiles();
            $id = $this->request->getPost('id');
            foreach ($files['photo'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
               
                    $newName = date('Y-m-d-H-i-s') . '-' . $file->getRandomName();
                    $fileName = date('Y-m-d-H-i-s') . '-' . $file->getRandomName();
   
                    if ($file->move($PATH . '/uploads/products/', $newName)) {

                $img = \Config\Services::image()
        ->withFile($PATH . '/uploads/products/' . $newName)
        ->resize(80, 80, true)   // true = maintain aspect ratio
        ->save($PATH . '/uploads/products/' . $fileName);


                        $this->om->insert_data("product_images", [
                            "product_id" => $id,
                            "product_image" => $newName, 
                            "product_image_s" => $fileName 
                        ]);
                    } else {
                        echo "Failed to move the uploaded file.";
                    }
                } else {
                    echo "Invalid file or file already moved.";
                }
            }
                 
           
            $this->session->setFlashdata('success', 'Data Saved successfully!!');
            return  redirect()->to('products/show/'.$this->request->getPost('id'));   
         
        } else {
          
           
        return redirect()->back()->withInput()->with('validationerror',  $this->validation->getErrors());

           
       }
   
        
    }
    public function index()
    {

       $product = new ProductModel();
       $role = $this->data['user_role'] ?? 'admin';

       if ($role === 'vendor') {
           $this->data['result'] = $product->select('*')->where('vendor_id', $this->user_id)->findAll();
       } else {
           $this->data['result'] = $product->select('*')->findAll();
           // Load vendor list for admin to see vendor names
           $this->data['vendors'] = $this->om->get_selected_data('id, name, shop_name', 'admin_log', ['role' => 'vendor']);
       }

        return view('product/index',$this->data + $this->getCommonData());
    }
    public function new()
    {
   //$this->data['mainmenu'] =  $this->om->jointbl("third.id,CONCAT(first.name,'/',second.name,'/',third.name) as name","tbl_category third",["tbl_category second,second.id=third.sub_id","tbl_category first,first.id=second.main_id"],["third.main_id !="=>0,"third.sub_id !="=>0],["order"=>["third.id","desc"]]); 
      $this->data['mainmenu'] = $this->om->jointbl(
        "second.id, CONCAT(first.name, '/', second.name) as name",  
        "tbl_category second", 
        [
            "tbl_category first, first.id = second.main_id"  
        ],
        [
            "second.main_id !=" => 0, 
            "second.sub_id " => 0
        ],
        [
            "order" => ["second.id", "desc"]  
        ]
    );   
    
       $this->data['status'] = $this->om->get_selected_data("id,name","categorystatus");
     

       $this->data['post'] = $this->session->get('post');
       $this->data['type'] = model('TypeModel')->findAll();
       $this->data['errors'] = session()->get('errors');

       
 
       $product = new ProductModel();
       $this->data['orderlist'] =   $product->select('max(orderlist) as cnt')->findAll();

       // Load vendor list for admin to assign products to vendors
       if (($this->data['user_role'] ?? 'admin') === 'admin') {
           $this->data['vendors'] = $this->om->get_selected_data('id, name, shop_name', 'admin_log', ['role' => 'vendor']);
       }

        return view('product/create',$this->data + $this->getCommonData());
    }
 
    public function edit($id)
    {
      // Vendor can only edit their own products
      $role = $this->data['user_role'] ?? 'admin';
      if ($role === 'vendor') {
          $check = $this->om->get_selected_data('id', 'product', ['id' => $id, 'vendor_id' => $this->user_id]);
          if (empty($check)) {
              return redirect()->to(base_url('products'))->with('error', 'Access denied!');
          }
      }

      $this->data['mainmenu'] = $this->om->jointbl(
        "second.id, CONCAT(first.name, '/', second.name) as name",  
        "tbl_category second", 
        [
            "tbl_category first, first.id = second.main_id"  
        ],
        [
            "second.main_id !=" => 0, 
            "second.sub_id " => 0
        ],
        [
            "order" => ["second.id", "desc"]  
        ]
    ); 
      
      $this->data['status'] = $this->om->get_selected_data("id,name","categorystatus");
       $this->data['errors'] = $this->session->get('validation');
       $this->data['post'] = $this->session->get('post');
          $this->data['type'] = model('TypeModel')->findAll();
       $product = new ProductModel();  
        $this->data['result'] =   $product->select('*')->find($id);
       
        $this->data['veriantitem'] =   $this->om->get_selected_data("*","product_veriant",["product_id"=>$id]);
      
        $this->data['category'] =   $this->om->get_selected_data("*","product_category",["product_id"=>$id]);

        // Load vendor list for admin
        if (($this->data['user_role'] ?? 'admin') === 'admin') {
            $this->data['vendors'] = $this->om->get_selected_data('id, name, shop_name', 'admin_log', ['role' => 'vendor']);
        }

        return view('product/edit',$this->data + $this->getCommonData());
    }

   public function update($id)
{
    $productModel = new ProductModel();

    // Vendor can only update their own products
    $role = $this->data['user_role'] ?? 'admin';
    if ($role === 'vendor') {
        $check = $this->om->get_selected_data('id', 'product', ['id' => $id, 'vendor_id' => $this->user_id]);
        if (empty($check)) {
            return redirect()->to(base_url('products'))->with('error', 'Access denied!');
        }
    }

    // Validation rules
    $this->validation->setRules([
        'product_name'      => 'required',
        'variant.*'         => 'required',
        'is_active'         => 'required',
    //    'product_type'      => 'required',
        'shipping'          => 'required|numeric',
        'shipping_outside'  => 'required|numeric',
    ]);

    if (!$this->validation->withRequest($this->request)->run()) {
    
        return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
    }

    // Check duplicate name (except current product)
    $productName = $this->request->getPost('product_name');
    $exists = $productModel
        ->where('product_name', $productName)
        ->where('id !=', $id)
        ->countAllResults();

    if ($exists) {
        return redirect()->back()->withInput()->with('errors', ['product_name' => 'This product already exists']);
    }

    // Updated product fields
    $data_val = [
        'product_name'     => $productName,
        'product_type'     => $this->request->getPost('product_type'),
        'offers'           => $this->request->getPost('offers'),
        'description'      => $this->request->getPost('description'),
        'is_active'        => $this->request->getPost('is_active'),
        'tax'              => $this->request->getPost('tax'),
        'shipping'         => $this->request->getPost('shipping'),
        'shipping_outside' => $this->request->getPost('shipping_outside'),
        'categorystatus'   => $this->request->getPost('category_status'),
        'orderlist'        => $this->request->getPost('order'),
        'updated_at'       => date('Y-m-d H:i'),
        'updated_by'       => $this->user_id,
    ];

    // Set vendor_id: admin can reassign, vendor keeps their own
    if ($role === 'admin') {
        $vendorId = $this->request->getPost('vendor_id');
        $data_val['vendor_id'] = !empty($vendorId) ? $vendorId : null;
    }

    $productModel->update($id, $data_val);

    // Remove old variants & categories
    $this->om->delete_data("product_veriant", ["product_id" => $id]);
    $this->om->delete_data("product_category", ["product_id" => $id]);

    // Variant input
    $variant       = $this->request->getPost('variant');
    $price         = $this->request->getPost('price');
    $offerPerPrice = $this->request->getPost('offerPerPrice');

    // Insert updated variants (universal)
    foreach ($variant as $key => $val) {
        $variantData = [
            'veriant'            => $val,
            'price'              => $price[$key] ?? 0,
            'if_offer_per_price' => $offerPerPrice[$key] ?? 0,
            'product_id'         => $id,
        ];
        $this->om->insert_data("product_veriant", $variantData);
    }

    // Handle image upload
    $file = $this->request->getFile('photo');

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = date('YmdHis') . '_' . $file->getRandomName();
        $file->move(FCPATH . 'uploads/products/', $newName);

        if (extension_loaded('gd')) {
            \Config\Services::image()
                ->withFile(FCPATH . 'uploads/products/' . $newName)
                ->fit(300, 300, 'center')
                ->save(FCPATH . 'uploads/products/' . $newName);
        }

        $this->om->update_data("product", ["id" => $id], ["img" => $newName]);
    }

    // Reinsert categories
    $cate = $this->request->getPost('cate');
    if (!empty($cate)) {
        foreach ($cate as $catId) {
            $this->om->insert_data("product_category", [
                'category_id' => $catId,
                'product_id'  => $id,
            ]);
        }
    }

    $this->session->setFlashdata('success', 'Product updated successfully!');
    return redirect()->to(base_url('products'));
}


    
  public function create()
{
    $productModel = new ProductModel();


    // Validation rules
    $this->validation->setRules([
        'product_name'      => 'required',
        'is_active'         => 'required',
        'variant.*'         => 'required',                     // updated
        'shipping'          => 'required|numeric',
        'shipping_outside'  => 'required|numeric',
        'photo'             => 'uploaded[photo]|ext_in[photo,jpg,jpeg,png,gif]'
    ], [
        'product_name'     => ['required' => 'Product name is required.'],
        'is_active'        => ['required' => 'Product status is required.'],
        'variant.*'        => ['required' => 'Add at least one variant.'],
        'shipping'         => ['required' => 'Shipping price is required.'],
        'shipping_outside' => ['required' => 'Shipping price outside Kerala is required.'],
        'photo'            => ['uploaded' => 'Image is required.'],
    ]);

    // Run validation
    if (!$this->validation->withRequest($this->request)->run()) {
        return redirect()
            ->to(base_url('products/new'))
            ->withInput()
            ->with('errors', $this->validation->getErrors());
    }

    // Duplicate product check
    $productName = $this->request->getPost('product_name');
    if ($productModel->where('product_name', $productName)->countAllResults() > 0) {
        return redirect()->back()
            ->withInput()
            ->with('errors', ['product_name' => 'This product already exists']);
    }

    // Insert into product table
    $role = $this->data['user_role'] ?? 'admin';
    $data_val = [
        'product_name'     => $productName,
        'offers'           => $this->request->getPost('offers'),
        'description'      => $this->request->getPost('description'),
        'is_active'        => $this->request->getPost('is_active'),
        'tax'              => $this->request->getPost('tax'),
        'shipping'         => $this->request->getPost('shipping'),
        'shipping_outside' => $this->request->getPost('shipping_outside'),
        'categorystatus'   => $this->request->getPost('category_status'),
        'orderlist'        => $this->request->getPost('order'),
        'product_type'     => $this->request->getPost('product_type'),
        'created_at'       => date('Y-m-d H:i'),
        'created_by'       => $this->user_id,
    ];

    // Set vendor_id: auto-assign for vendors, admin can choose
    if ($role === 'vendor') {
        $data_val['vendor_id'] = $this->user_id;
    } else {
        $vendorId = $this->request->getPost('vendor_id');
        $data_val['vendor_id'] = !empty($vendorId) ? $vendorId : null;
    }

    $productModel->insert($data_val);
    $lastInsertedID = $productModel->insertID();

    // Insert variants (universal logic)
    $variant       = $this->request->getPost('variant');
    $price         = $this->request->getPost('price');
    $offerPerPrice = $this->request->getPost('offerPerPrice');

    foreach ($variant as $key => $val) {
        $variantData = [
            'veriant'            => $val,
            'price'              => $price[$key] ?? 0,
            'if_offer_per_price' => $offerPerPrice[$key] ?? 0,
            'product_id'         => $lastInsertedID,
        ];

        $this->om->insert_data("product_veriant", $variantData);
    }

    // Image Upload
    $file = $this->request->getFile('photo');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = date('YmdHis') . '_' . $file->getRandomName();
        $file->move(FCPATH . 'uploads/products/', $newName);

        // Crop/resize
        \Config\Services::image()
            ->withFile(FCPATH . 'uploads/products/' . $newName)
            ->fit(300, 300, 'center')
            ->save(FCPATH . 'uploads/products/' . $newName);

        // Update product table with image
        $this->om->update_data("product", ["id" => $lastInsertedID], ["img" => $newName]);
    }

    // Category mapping
    $cate = $this->request->getPost('cate');
    if (!empty($cate)) {
        foreach ($cate as $catId) {
            $this->om->insert_data("product_category", [
                'category_id' => $catId,
                'product_id'  => $lastInsertedID,
            ]);
        }
    }

    // Success message
    $this->session->setFlashdata('success', 'Product added successfully!');
    return redirect()->to(base_url('products'));
}


    public function qa($id){

      
         $this->data['result'] =   $this->om->get_selected_data("*","product",["id"=>$id]);
         $this->data['qa'] =   model('QaModel')->where('product_id',$id)->findAll();
         if($_POST){

            // Delete all existing Q&A for this product
            model('QaModel')->where('product_id', $id)->delete();

            $questions = $this->request->getPost('question');
            $answers = $this->request->getPost('answer');

            foreach ($questions as $key => $question) {
                $data = [
                    'product_id' => $id,
                    'question' => $question,
                    'answer' => $answers[$key],
                    'created_at' => date('Y-m-d H:i'),
                    'created_id' => $this->user_id,
                ];

                model('QaModel')->insert($data);
            }

            return redirect()->to(base_url('qa/'.$id));
         }

         return view('product/qa',$this->data + $this->getCommonData());


    }
    public function highlight($id){

      
         $this->data['result'] =   $this->om->get_selected_data("*","product",["id"=>$id]);
         $this->data['qa'] =   model('HighModel')->where('product_id',$id)->findAll();
         if($_POST){

            // Delete all existing Q&A for this product
            model('HighModel')->where('product_id', $id)->delete();

            $questions = $this->request->getPost('question');
            $answers = $this->request->getPost('answer');

            foreach ($questions as $key => $question) {
                $data = [
                    'product_id' => $id,
                    'question' => $question,
                    'answer' => $answers[$key],
                    'created_at' => date('Y-m-d H:i'),
                    'created_id' => $this->user_id,
                ];

                model('HighModel')->insert($data);
            }

            return redirect()->to(base_url('highlight/'.$id));
         }

         return view('product/highlight',$this->data + $this->getCommonData());


    }



public function getFieldsByType()
{
    $type = $this->request->getPost('type');

    // Define product-type variant structures only
    $fieldSets = [
        'cake' => [
            'variants' => [
                ['name' => 'variant', 'label' => 'Variant (e.g. 1kg, 500g)', 'type' => 'text'],
                ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'offerPerPrice', 'label' => 'Offer % (Price)', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'egglessPrice', 'label' => 'Eggless Price', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'offerPerEggless', 'label' => 'Offer % (Eggless)', 'type' => 'number', 'step' => '0.01']
            ]
        ],

        'cream' => [
            'variants' => [
                ['name' => 'variant', 'label' => 'Variant (e.g. 100ml, 250ml)', 'type' => 'text'],
                ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'offerPerPrice', 'label' => 'Offer % (Price)', 'type' => 'number', 'step' => '0.01']
            ]
        ],

        'oil' => [
            'variants' => [
                ['name' => 'variant', 'label' => 'Variant (e.g. 100ml, 500ml)', 'type' => 'text'],
                ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'offerPerPrice', 'label' => 'Offer % (Price)', 'type' => 'number', 'step' => '0.01']
            ]
        ]
    ];

    // Stop if invalid type
    if (!isset($fieldSets[$type])) {
        return '';
    }

    $config = $fieldSets[$type];
    $html = '';

    // Variant table only
    if (!empty($config['variants'])) {
        $html .= '
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary" type="button" id="addVariant">Add New Row</button><br><br>
                <table class="table table-bordered" id="productVariants">
                    <thead><tr>';
                    foreach ($config['variants'] as $v) {
                        $html .= '<th>'.$v['label'].'</th>';
                    }
                    $html .= '<th></th></tr></thead><tbody><tr>';
                    foreach ($config['variants'] as $v) {
                        $step = isset($v['step']) ? 'step="'.$v['step'].'"' : '';
                        $html .= '<td><input type="'.$v['type'].'" '.$step.' name="'.$v['name'].'[]" class="form-control" required></td>';
                    }
                    $html .= '<td><button class="btn btn-danger deleteRow">Remove</button></td></tr></tbody>
                </table>
            </div>
        </div>';
    }

    // JS for adding/removing variant rows
    $html .= "
    <script>
    $(document).on('click', '#addVariant', function() {
        let newRow = `<tr>";
        foreach ($config['variants'] as $v) {
            $step = isset($v['step']) ? 'step=\"'.$v['step'].'\"' : '';
            $html .= "<td><input type='{$v['type']}' {$step} name='{$v['name']}[]' class='form-control' required></td>";
        }
        $html .= "<td><button class='btn btn-danger deleteRow'>Remove</button></td></tr>`;
        $('#productVariants tbody').append(newRow);
    });

    $(document).on('click', '.deleteRow', function() {
        if($('#productVariants tbody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('At least one variant is required!');
        }
    });
    </script>
    ";

    return $html;
}









    
    function productdelete()
    {
        $id = $this->request->getPost('deleteclii');
        $last = $this->request->getPost('id');
       
       
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . 'products/show/'.$last); 
        } else {
            foreach($id as $val)
            {
                $result =   $this->om->get_selected_data("id,product_image","product_images",["id"=>$val]);
          
            $PATH = getcwd();
            if(file_exists($PATH . '/uploads/products/'.$result[0]->product_image))
            {
                unlink($PATH . '/uploads/products/'.$result[0]->product_image);
            }
            $this->om->delete_data("product_images",["id"=>$val]);
            }
           
        }
        $this->session->setFlashdata('success', 'Data Deleted !!');
        return  redirect()->to(base_url() . 'products/show/'.$last);  
    }
   public function delete()
{
    $ids = $this->request->getPost('deleteclii');
    $role = $this->data['user_role'] ?? 'admin';

    if (empty($ids)) {
        $this->session->setFlashdata('error', 'Please select at least one!!');
        return redirect()->to(base_url('products'));
    }

    foreach ($ids as $productId) {

        // Vendor can only delete their own products
        if ($role === 'vendor') {
            $check = $this->om->get_selected_data('id', 'product', ['id' => $productId, 'vendor_id' => $this->user_id]);
            if (empty($check)) {
                continue; // skip products not owned by this vendor
            }
        }

        //Get variant list for this product
        $variants = $this->om->get_selected_data("id", "product_veriant", ["product_id" => $productId]);

        if (!empty($variants)) {
            foreach ($variants as $v) {

                //Delete cart rows connected to this variant
                $this->om->delete_data("tbl_cart", ["variant" => $v->id]);

                // If this variant exists in orders → do NOT delete product
                $orderExist = $this->om->get_selected_data("id", "tbl_order", ["variant" => $v->id]);

                if (!empty($orderExist)) {
                    // Skip deletion and go to next product
                    $this->session->setFlashdata(
                        'error',
                        'Product ID ' . $productId . ' cannot be deleted because orders exist!'
                    );
                    continue 2; // skip to next $productId in loop
                }
            }
        }

        // 4Delete product images & records
        $images = $this->om->get_selected_data("id, product_image", "product_images", ["product_id" => $productId]);
        if (!empty($images)) {
            foreach ($images as $img) {
                $PATH = getcwd();
                if (!empty($img->product_image) && file_exists($PATH . '/uploads/products/' . $img->product_image)) {
                    unlink($PATH . '/uploads/products/' . $img->product_image);
                }
                $this->om->delete_data("product_images", ["id" => $img->id]);
            }
        }

        // 5 Delete product and mapping tables
        $this->om->delete_data("product_veriant", ["product_id" => $productId]);
        $this->om->delete_data("product_category", ["product_id" => $productId]);
        $this->om->delete_data("product", ["id" => $productId]);
    }

    $this->session->setFlashdata('success', 'Process completed!');
    return redirect()->to(base_url('products'));
}


        public function details()
    {
        $query = $this->request->getGet('q');
        $productModel = new ProductModel();

        $results = $productModel
            ->like('product_name', $query)
            ->where('is_active', 'Y')
            ->orderBy('product_name', 'ASC')
            ->findAll(8); // limit 8 results

        if (!empty($results)) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $results
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'data' => []
            ]);
        }
    }



    
}
