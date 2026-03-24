<?php

use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */

 //Web Routes
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->match(['GET','POST'], 'user-cllit-management', 'Useradmin::login');
$routes->group('users', ['namespace' => 'App\Controllers',"filter" => "auth"], static function ($routes) {
    /* Used Routes */
    $routes->get('user-dashboard', 'Dashboard::index');
    $routes->get('banner-list', 'Dashboard::banner');
    $routes->get('middle-banner-list', 'Dashboard::mid_banner');
    $routes->match(['GET','POST'], 'mid_bnr_edit/(:num)', 'Dashboard::mid_banner_edit/$1');
    $routes->match(['GET','POST'], 'midcreate-banner', 'Dashboard::mid_banner_add');
    $routes->post("midbanner_dlt", "Dashboard::mid_bnr_dlt");

    $routes->get('other-banner-list', 'Dashboard::other_banner');
    $routes->match(['GET','POST'], 'other_bnr_edit/(:num)', 'Dashboard::other_banner_edit/$1');

    $routes->match(['GET','POST'], 'bnr_edit/(:num)', 'Dashboard::banner_edit/$1');
    $routes->match(['GET','POST'], 'create-banner', 'Dashboard::banner_add');
     
    $routes->post("banner_dlt", "Dashboard::bnr_dlt");
    $routes->match(['GET','POST'], 'user-profile', 'Dashboard::profile');
    $routes->match(['GET','POST'], 'ck-editor', 'Dashboard::upload_ckeditor');
    $routes->match(['GET','POST'], 'ck-browser', 'Dashboard::file_browser');
    $routes->match(['GET','POST'], 'about/(:num)', 'Dashboard::about_edit/$1');
    $routes->match(['GET','POST'], 'company-details/(:num)', 'Dashboard::company_details_edit/$1');
   // "users/activity-list as cate"
   
    /* Used Routes */
   
    
  
   
   
  
    
    $routes->get('proposal', 'Dashboard::proposal');
    $routes->post("proposal-itm-dlt", "Dashboard::pro_dlt");
    $routes->get('enq-list', 'Dashboard::enq');
    $routes->post("enq-itm-dlt", "Dashboard::enq_dlt");
    
    $routes->get('news-list', 'Dashboard::news_list');
    $routes->match(['GET','POST'], 'create-news', 'Dashboard::news_add');
    $routes->match(['GET','POST'], 'news-edit/(:num)', 'Dashboard::news_edit/$1');
    $routes->match(['GET','POST'], 'news-itm-edit/(:num)', 'Dashboard::news_edit/$1');
    $routes->post("news-dlt", "Dashboard::news_dlt");
    $routes->post("news-dlt", "Dashboard::news_dlt");


  
    
    
    
    $routes->get('announce-list', 'Dashboard::announce_list');
    $routes->match(['GET','POST'], 'create-announce', 'Dashboard::announce_add');
    $routes->match(['GET','POST'], 'announce-edit/(:num)', 'Dashboard::announce_edit/$1');
    $routes->match(['GET','POST'], 'member-edit/(:num)', 'Dashboard::wardmember_edit/$1');
    $routes->post("announce-dlt", "Dashboard::announce_dlt");
    
    $routes->get('activity-list', 'Dashboard::activity_list');
    $routes->match(['GET','POST'], 'create-activity', 'Dashboard::activity_add');
    $routes->match(['GET','POST'], 'activity-edit/(:num)', 'Dashboard::activity_edit/$1');
    $routes->match(['GET','POST'], 'member-edit/(:num)', 'Dashboard::activity_edit/$1');
    $routes->post("activity-dlt", "Dashboard::activity_dlt");
    
    $routes->get('tourist-list', 'Dashboard::tourist_list');
    $routes->match(['GET','POST'], 'create-tourist', 'Dashboard::tourisam_add');
    $routes->match(['GET','POST'], 'tourist-edit/(:num)', 'Dashboard::tourist_edit/$1');
    $routes->match(['GET','POST'], 'member-edit/(:num)', 'Dashboard::activity_edit/$1');
    $routes->post("tourist-dlt", "Dashboard::tourist_dlt");
    
    
    
    
    
    
    $routes->get('footermenu-list', 'Dashboard::footerlinksub_list');
    $routes->match(['GET','POST'], 'create-footermenu', 'Dashboard::footermenu_add');
    $routes->match(['GET','POST'], 'footermenu-edit/(:num)', 'Dashboard::footermenu_edit/$1');
    //$routes->match(['GET','POST'], 'footerlink-itm-edit/(:num)', 'Dashboard::footerlink_edit/$1');
    $routes->post("footermenu-dlt", "Dashboard::footermenu_dlt");
    
    
    $routes->get('footerlink-list', 'Dashboard::footerlink_list');
    $routes->match(['GET','POST'], 'create-footerlink', 'Dashboard::footerlink_add');
    $routes->match(['GET','POST'], 'footerlink-edit/(:num)', 'Dashboard::footerlink_edit/$1');
    //$routes->match(['GET','POST'], 'footerlink-itm-edit/(:num)', 'Dashboard::footerlink_edit/$1');
    $routes->post("footerlink-dlt", "Dashboard::footerlink_dlt");

    //pincode 
    $routes->match(['GET','POST'], 'pincode', 'PincodeController::index');
    $routes->match(['GET','POST'], 'pinedit/(:num)', 'PincodeController::pinedit/$1');
    $routes->post("pindlt", "PincodeController::pindlt");

    
  
    
    
    
    $routes->get('client-list', 'Dashboard::trusted_client_list');
    $routes->match(['GET','POST'], 'create-client', 'Dashboard::client_add');
    $routes->post("client_dlt", "Dashboard::client_dlt");
    
    $routes->match(['GET','POST'], 'client-edit/(:num)', 'Dashboard::client_edit/$1');
    $routes->match(['GET','POST'], 'news-itm-edit/(:num)', 'Dashboard::client_edit/$1');

    // Vendor Management (Admin only)
    $routes->get('vendor-list', 'VendorController::index');
    $routes->match(['GET','POST'], 'vendor-add', 'VendorController::create');
    $routes->match(['GET','POST'], 'vendor-edit/(:num)', 'VendorController::edit/$1');
    $routes->get('vendor-toggle/(:num)', 'VendorController::toggle/$1');

    // Customers & Promotional Emails (Admin only)
    $routes->get('customers', 'Dashboard::customers');
    $routes->post('send-promo-email', 'Dashboard::sendPromoEmail');

    // Sales Report (Admin only)
    $routes->get('sales-report', 'Dashboard::salesReport');

    });
    //added by winni
    $routes->group('users', ['namespace' => 'App\Controllers',"filter" => "auth"], static function ($routes) {
        //new 
        $routes->match(['GET','POST'], 'menu', 'MenuContoller::index');
           $routes->match(['GET','POST'], 'submain', 'MenuContoller::subindex');
         
         $routes->match(['GET','POST'], 'menucreate', 'MenuContoller::create');
        $routes->match(['GET','POST'], 'menu_edit/(:num)', 'MenuContoller::main_edit/$1');
        $routes->post("menudlt", "MenuContoller::menudlt");
        $routes->match(['GET','POST'], 'sub', 'MenuContoller::sub');
        $routes->match(['GET','POST'], 'sub_edit/(:num)', 'MenuContoller::sub_edit/$1');
        $routes->post("subdlt", "MenuContoller::subdlt");
        $routes->match(['GET','POST'], 'create', 'MenuContoller::create');
        $routes->match(['GET','POST'], 'edit/(:num)', 'MenuContoller::edit/$1');
        $routes->post("dlt", "MenuContoller::dlt");
        $routes->post("getsub", "MenuContoller::getsub");
        $routes->get("list", "MenuContoller::list");
        $routes->post("upload", "MenuContoller::upload_ckeditor");
        $routes->get("file", "MenuContoller::listfile");
        $routes->post("file", "MenuContoller::listfile");
        $routes->post("savephotos", "ProductContoller::savephotos");
        $routes->post('getsubhead', 'ProductContoller::getsubhead');

        $routes->post("productdelete", "ProductContoller::delete");
        $routes->post("proimgdelete", "ProductContoller::productdelete");
    
   $routes->get("logoutadm", "Useradmin::logout", ['filter' => 'check']);
       

        
       });
          $routes->match(['GET','POST'], 'qa/(:num)', 'ProductContoller::qa/$1',['filter' => 'auth']);
            $routes->match(['GET','POST'], 'highlight/(:num)', 'ProductContoller::highlight/$1',['filter' => 'auth']);
       $routes->post('products/getFieldsByType', 'ProductContoller::getFieldsByType');
       //$routes->get('product/details/(:num)', 'ProductController::details/$1');
       $routes->get('search/products', 'ProductContoller::details');
              
              $routes->post('QtyController/update_quantity', 'QtyController::update_quantity');
                     $routes->post('type/edit/(:num)', 'TypeController::edit/$1');
                     $routes->post('type-dlt', 'TypeController::delete');




        //added by winni
       $routes->presenter('category', ['controller' => 'CategoryContoller','filter' => 'auth']);
        $routes->presenter('qty', ['controller' => 'QtyController','filter' => 'auth']);
        $routes->presenter('type', ['controller' => 'TypeController','filter' => 'auth']);
       $routes->presenter('content', ['controller' => 'ContentController','filter' => 'auth']);
       $routes->presenter('products', ['controller' => 'ProductContoller','filter' => 'auth']);
       $routes->presenter('serve', ['controller' => 'ServeController','filter' => 'auth']);
             
     
       $routes->presenter('media', ['controller' => 'MediaController', 'filter' => 'auth']);
       $routes->post("savephotos", "ProductContoller::savephotos");
       $routes->presenter('alumini', ['controller' => 'AluminiController']);
       $routes->presenter('footview', ['controller' => 'FooterController']);
      //product List start
       $routes->get("product-category/(:num)/(:num)", "ProductHomeController::productlist/$1/$2'");
              $routes->get("product-filter/(:num)/(:num)", "ProductHomeController::productlistfilter/$1/$2'");
        $routes->get("wishlist", "OrderController::wishlist");
              $routes->post("product-category/(:num)/(:num)", "ProductHomeController::productlist/$1/$2'");
       $routes->match(['GET','POST'], "product-details/(:num)/(:any)", "ProductHomeController::productdetails/$1/$2");
        $routes->match(['GET','POST'], "product-details/(:num)", "ProductHomeController::productdetails/$1");
       $routes->post('update-cart-qty', 'CartController::update_cart_qty');
       $routes->match(['GET','POST'], 'cart/cartadd', 'ProductHomeController::cartadd');
              $routes->match(['GET','POST'], 'cart/sync_cart', 'ProductHomeController::sync_cart');




       $routes->get("checkout", "LoginHomeController::checkout");
       $routes->get("my-order", "LoginHomeController::myorder");
               $routes->match(['GET','POST'], "terms/(:num)", "OtherController::policy/$1");
                $routes->match(['GET','POST'], "footlink_view/(:num)", "ProductHomeController::footlink_view/$1");
               $routes->match(['GET','POST'], "privacy/(:num)", "OtherController::policy/$1");
               $routes->match(['GET','POST'], "cancellation/(:num)", "OtherController::policy/$1");
               $routes->match(['GET','POST'], "pur-pro", "OtherController::purchased_products", ['filter' => 'auth']);
               $routes->match(['GET','POST'], "pending_orders", "OtherController::pending_orders", ['filter' => 'auth']);
               $routes->get("order_status/(:num)", "OtherController::order_status/$1", ['filter' => 'auth']);
                    $routes->match(['GET','POST'], "adminvoice/(:num)", "OtherController::invoice/$1", ['filter' => 'auth']);
                   $routes->match(['GET','POST'], "ship-pro", "OtherController::shipped_products", ['filter' => 'auth']);
                    $routes->match(['GET','POST'], "ship/(:num)", "OtherController::ship/$1", ['filter' => 'auth']);
                    $routes->get('deliver/(:num)', 'OtherController::deliver/$1', ['filter' => 'auth']);
                    $routes->get('del-pro', 'OtherController::delivered_products', ['filter' => 'auth']);
                    $routes->get('can-pro', 'OtherController::cancelled_products', ['filter' => 'auth']);




       //product List end
         //registration start
         $routes->match(['GET','POST'],'login', "LoginHomeController::login");
         $routes->match(['GET','POST'], 'auth/signup', 'LoginHomeController::register');
         $routes->match(['GET','POST'], 'auth/loginOtp', 'LoginHomeController::loginOtp');
         $routes->match(['GET','POST'], 'auth/forgotOtp', 'LoginHomeController::forgotOtp');
         $routes->match(['GET','POST'], 'proedit/(:num)', 'LoginHomeController::proedit/$1', ['filter' => 'check']);
         $routes->match(['GET','POST'], 'passedit', 'LoginHomeController::passedit', ['filter' => 'check']);
         $routes->get("logout", "LoginHomeController::logout", ['filter' => 'check']);
         
         $routes->get("delacc/(:num)", "LoginHomeController::delacc/$1", ['filter' => 'check']);


                  //registration end

                  //j_add start

         $routes->match(['GET','POST'], 'cart', 'CartController::cart');
          $routes->match(['GET','POST'], 'product/get_variant_details', 'CartController::details');
         $routes->get("pin/pindata", "ProductHomeController::getdata");
          $routes->get("termspolicy/(:num)", "ProductHomeController::termspolicy/$1");
         $routes->post("wishlist/toggle", "ProductHomeController::toggle");

     
         $routes->match(['GET','POST'], "regedit", "LoginHomeController::proedit", ['filter' => 'check']);
         $routes->match(['GET','POST'], "address", "CartController::addressedit", ['filter' => 'check']);
         $routes->match(['GET','POST'], "placeorder", "CartController::cart_order", ['filter' => 'check']);
         $routes->match(['GET','POST'], "proedit", "CartController::proedit", ['filter' => 'check']);
         $routes->match(['GET','POST'], 'proedit/(:num)/(:num)/(:segment)', 'CartController::proedit/$1/$2/$3', ['filter' => 'check']);
         $routes->match(['GET','POST'], "order", "OrderController::order", ['filter' => 'check']);
         $routes->match(['GET','POST'], "invoice/(:num)", "OrderController::invoice/$1", ['filter' => 'check']);

         $routes->get('deletepro/(:num)/(:num)', 'CartController::deletepro/$1/$2', ['filter' => 'check']);
         $routes->post('cancelOrder', 'OrderController::cancel_order', ['filter' => 'check']);
         $routes->match(['GET','POST'], 'new_address', 'OrderController::new_address', ['filter' => 'check']);
                  $routes->match(['GET','POST'], 'edit_address/(:num)', 'OrderController::edit_address/$1', ['filter' => 'check']);
             $routes->match(['GET','POST'], 'prime_add/(:num)', 'OrderController::prime_add/$1', ['filter' => 'check']);
            $routes->match(['GET','POST'], 'show_address', 'OrderController::address', ['filter' => 'check']);
                        $routes->match(['GET','POST'], 'profile', 'OrderController::profile', ['filter' => 'check']);
                            $routes->match(['GET','POST'], 'cha_pass', 'LoginHomeController::password', ['filter' => 'check']);
            $routes->match(['GET','POST'], 'password-change', 'OrderController::password_change', ['filter' => 'check']);
              $routes->match(['GET','POST'], 'review', 'OrderController::review', ['filter' => 'check']);
          $routes->post('get_state', 'OrderController::get_state', ['filter' => 'check']);
          $routes->post('get_district', 'OrderController::get_district', ['filter' => 'check']);
          $routes->post("cat-dlt", "CategoryContoller::delete");
          $routes->post("payment", "CronContoller::payment");
          $routes->get("paymentstatus", "CronContoller::paymentstatus");


