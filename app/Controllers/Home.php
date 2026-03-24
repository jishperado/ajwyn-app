<?php

namespace App\Controllers;

use App\Models\NavlistModel;
use App\Models\BannerModel;
use App\Models\CategoryModel;
use App\Models\CartModel;
use App\Models\ProductModel;
use App\Models\FooterModel;
use App\Models\CategorystatusModel;
use App\Models\FootersubModel;
use App\Models\ServeModel;
use App\Models\MediaModel;
use App\Models\CusModel;
use App\Models\MidbannerModel;
use App\Models\TypeModel;




class Home extends BaseController
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
    public function index()
    { 
      $ban = new BannerModel();
      $cat = new CategoryModel();
      $pro = new ProductModel();
      $nav = new NavlistModel();
      $mid = new MidbannerModel();
      $type = new TypeModel();
      helper('slug');


   //   $this->data['products'] = $pro->getList();
      
   $result = $pro->get_product_list();
     $final = [];
        foreach ($result as $row) {
              $pid = $row->pro_id;
              if (!isset($final[$pid])) {
               $final[$pid] = $row;
               }
            if ($row->qty > 0) {
           $final[$pid] = $row;
          }
       }
      $this->data['products'] = array_values($final);


     
      $this->data['banner'] = $ban->select('id,title,desk_banner,banner_title,mobile_banner')->where('status', 'view')->findAll();
      $this->data['midbanner'] = $mid->select('id,title,desk_banner,banner_title')->where('status', 'view')->findAll();  
      $this->data['category'] =  $this->om->get_selected_data("*","tbl_category",["main_id"=>0],["order"=>["id","desc"]]);
 
     

    return view('web/frontpage',$this->data + $this->comman());
    //return view('web/index',$this->data + $this->comman());
    }

   
    
}
