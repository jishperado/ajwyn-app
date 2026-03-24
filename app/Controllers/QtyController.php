<?php

namespace App\Controllers;

use App\Controllers\BaseStaff;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Config\Factories;

class QtyController extends BaseStaff
{
        function getCommonData() : array {
        $data['main'] = 16;
        $data['sub'] = 16;
        return $data;
    }
    public function index()
    {
        
       $pro = model("ProductModel");
       $this->data['result'] = $pro->select('product.id, product.product_name, product_veriant.id as variant_id,product_veriant.qty, product_veriant.veriant, product_veriant.price, product_veriant.if_offer_per_price, product_veriant.if_offer_per_eggless, product_veriant.eggless_price')
                        ->join('product_veriant','product_veriant.product_id = product.id')
                        ->where('product.is_active','Y')
                        ->findAll();
                       

        return view('qty/qty',$this->data + $this->getCommonData());
       
    }

public function update_quantity()
{
    $variant_ids = $this->request->getPost('variant_id');
    $quantities  = $this->request->getPost('quantity');
 


    if (!empty($variant_ids) && !empty($quantities)) {
        $productVariantModel = model('ProductveriantModel'); 
        foreach ($variant_ids as $key => $variant_id) {
            $qty = $quantities[$key];

            // Update quantity safely
            $productVariantModel
                ->where('id', $variant_id)
                ->set(['qty' => $qty])
                ->update();
        }
    }

    return redirect()->back()->with('success', 'Quantities updated successfully!');
}


    
}
