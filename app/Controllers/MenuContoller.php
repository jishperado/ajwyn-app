<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MenuCategoryModel;

class MenuContoller extends BaseController
{
    public function index()
    {
        $this->data['main'] = 4;
        $this->data['sub'] = 5;
         if ($_POST) {
           
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('main', 'main', 'required');
            $this->validation->setRule('order', 'order', 'required');
            $this->validation->setRule('status', 'status', 'required');
       
         
            
           
          
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                
                 $main = strip_tags($this->request->getPost('main'));
                 $check_mobile = $this->om->get_selected_data("id","tbl_category", ["name"=>$main,"main_id"=>0]);
                 if(!empty( $check_mobile))
                 {
                    $this->data['errors']['main'] = "This main already exists";
                 }else{
                   
                    
                                     $data_val =[
                        "name"=>strip_tags($this->request->getPost('main')),
                        "main_id"=>0,
                        "sub_id"=>0,
                        "orderlist" =>strip_tags($this->request->getPost('order')),
                        "is_active" =>strip_tags($this->request->getPost('status')),
                        "icon"=>'',
                      
                    ];
                        $this->om->insert_data('tbl_category', $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');

                        return  redirect()->to(base_url() . 'users/menu');     
                 }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
        $this->data['result'] =  $this->om->get_selected_data("*","tbl_category",["main_id"=>0],["order"=>["id","desc"]]);
       // $this->data['icons'] = $this->om->icons();       
      
        return view('menu/main',$this->data);
    }

     public function subindex()
    {
        $this->data['main'] = 4;
        $this->data['sub'] = 5;
         if ($_POST) {
           
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('main', 'main', 'required');
            $this->validation->setRule('order', 'order', 'required');
            $this->validation->setRule('status', 'status', 'required');
       
         
            
           
          
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                
                 $main = strip_tags($this->request->getPost('main'));
                 $check_mobile = $this->om->get_selected_data("id","tbl_category", ["name"=>$main,"main_id"=>0]);
                 if(!empty( $check_mobile))
                 {
                    $this->data['errors']['main'] = "This main already exists";
                 }else{
                   
                    
                                     $data_val =[
                        "name"=>strip_tags($this->request->getPost('main')),
                        "main_id"=>0,
                        "sub_id"=>0,
                        "orderlist" =>strip_tags($this->request->getPost('order')),
                        "is_active" =>strip_tags($this->request->getPost('status')),
                        "icon"=>'',
                      
                    ];
                        $this->om->insert_data('tbl_category', $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');

                        return  redirect()->to(base_url() . 'users/menu');     
                 }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
        $this->data['result'] =  $this->om->get_selected_data("*","tbl_category",["main_id !="=>0,"sub_id !="=>0],["order"=>["id","desc"]]);
       // $this->data['icons'] = $this->om->icons();       
      
        return view('menu/submain',$this->data);
    }


    
public function main_edit($id)
{
    $this->data['main'] = 4;
    $this->data['sub'] = 5;

    if ($_POST) {

        $this->data['post'] = $post = $this->request->getPost();

        $this->validation->setRule('main', 'main', 'required');
        $this->validation->setRule('order', 'order', 'required');
        $this->validation->setRule('status', 'status', 'required');

        if ($this->validation->withRequest($this->request)->run() == TRUE) {

            $main = strip_tags($this->request->getPost('main'));
            $check = $this->om->get_selected_data(
                "id",
                "tbl_category",
                ["name" => $main, "main_id" => 0, "id !=" => $id]
            );

            if (!empty($check)) {
                $this->data['errors']['main'] = "This main already exists";

            } else {

                $data_val = [];

                /* -------------------- PIC UPLOAD -------------------- */
                $file = $this->request->getFile('pic');

                if (!empty($file->getName())) {

                    $rules = [
                        'pic' => [
                            'label' => 'Image',
                            'rules' => 'uploaded[pic]|mime_in[pic,image/jpg,image/jpeg,image/png,image/gif]|max_size[pic,2048]',
                        ]
                    ];

                    if (!$this->validate($rules)) {
                        $validationErrors = $this->validator->getErrors();
                        $this->session->setFlashdata('error', $validationErrors);
                        return redirect()->back()->withInput();
                    }

                    if ($file->isValid()) {
                        $cat = new MenuCategoryModel();
                        $old = $cat->select('img')->where('id', $id)->first();
                        $PATH = getcwd();

                        if (!empty($old->img) && file_exists($PATH . '/web/images/' . $old->img)) {
                            unlink($PATH . '/web/images/' . $old->img);
                        }

                        $filename = date("Y-m-d") . '_pic_' . $file->getRandomName();
                        $file->move($PATH . '/web/images/', $filename);

                        $data_val['img'] = $filename;
                    }
                }

                /* -------------------- ICON UPLOAD -------------------- */
                $icon = $this->request->getFile('icon');

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
                        $cat = new MenuCategoryModel();
                        $oldIcon = $cat->select('icon')->where('id', $id)->first();
                        $PATH = getcwd();

                        if (!empty($oldIcon->icon) && file_exists($PATH . '/web/images/' . $oldIcon->icon)) {
                            unlink($PATH . '/web/images/' . $oldIcon->icon);
                        }

                        $iconName = date("Y-m-d") . '_icon_' . $icon->getRandomName();
                        $icon->move($PATH . '/web/images/', $iconName);

                        $data_val['icon'] = $iconName;
                    }
                }

                /* -------------------- BANNER UPLOAD -------------------- */
                $banner = $this->request->getFile('banner');

                if (!empty($banner->getName())) {

                    $rules_banner = [
                        'banner' => [
                            'label' => 'Banner',
                            'rules' => 'uploaded[banner]|mime_in[banner,image/jpg,image/jpeg,image/png,image/gif]|max_size[banner,2048]',
                        ]
                    ];

                    if (!$this->validate($rules_banner)) {
                        $validationErrors = $this->validator->getErrors();
                        $this->session->setFlashdata('error', $validationErrors);
                        return redirect()->back()->withInput();
                    }

                    if ($banner->isValid()) {
                        $cat = new MenuCategoryModel();
                        $oldBanner = $cat->select('banner')->where('id', $id)->first();
                        $PATH = getcwd();

                        if (!empty($oldBanner->banner) && file_exists($PATH . '/web/images/' . $oldBanner->banner)) {
                            unlink($PATH . '/web/images/' . $oldBanner->banner);
                        }

                        $bannerName = date("Y-m-d") . '_banner_' . $banner->getRandomName();
                        $banner->move($PATH . '/web/images/', $bannerName);

                        $data_val['banner'] = $bannerName;
                    }
                }

                /* -------------------- MENU IMAGE UPLOAD -------------------- */
                $menuImage = $this->request->getFile('menu_image');

                if (!empty($menuImage->getName())) {

                    $rules_menu_image = [
                        'menu_image' => [
                            'label' => 'Menu Image',
                            'rules' => 'uploaded[menu_image]|mime_in[menu_image,image/jpg,image/jpeg,image/png,image/gif]|max_size[menu_image,2048]',
                        ]
                    ];

                    if (!$this->validate($rules_menu_image)) {
                        $validationErrors = $this->validator->getErrors();
                        $this->session->setFlashdata('error', $validationErrors); // FIXED HERE
                        return redirect()->back()->withInput();
                    }

                    if ($menuImage->isValid()) {
                        $cat = new MenuCategoryModel();
                        $oldMenuImage = $cat->select('menu_image')->where('id', $id)->first();
                        $PATH = getcwd();

                        if (!empty($oldMenuImage->menu_image) && file_exists($PATH . '/web/images/' . $oldMenuImage->menu_image)) {
                            unlink($PATH . '/web/images/' . $oldMenuImage->menu_image);
                        }

                        $menuImageName = date("Y-m-d") . '_menu_image_' . $menuImage->getRandomName();
                        $menuImage->move($PATH . '/web/images/', $menuImageName);

                        $data_val['menu_image'] = $menuImageName;
                    }
                }

                /* -------------------- EXTRA FIELDS -------------------- */
                $data_val = array_merge($data_val, [
                    "name"       => strip_tags($this->request->getPost('main')),
                    "orderlist"  => strip_tags($this->request->getPost('order')),
                    "is_active"  => strip_tags($this->request->getPost('status')),
                ]);

                /* -------------------- UPDATE DB -------------------- */
                $this->om->update_data('tbl_category', ["id" => $id], $data_val);

                $this->session->setFlashdata('success', 'Data Saved successfully!!');
                return redirect()->to(base_url() . 'users/menu');
            }

        } else {
            $this->data['errors'] = $this->validation->getErrors();
        }
    }

    $this->data['result'] = $this->om->get_selected_data(
        "*",
        "tbl_category",
        ["id" => $id],
        ["order" => ["id", "desc"]]
    );
    $this->data['icons'] = $this->om->icons();

    return view('menu/mainedit', $this->data);
}



    function menudlt() {
        


        $id = $this->request->getPost('deleteclii');
     
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . 'users/menu'); 
        } else {
            $all_error = 0;
            foreach ($id as $val) {
               

                $check = $this->om->get_selected_data("id","tbl_category", ["main_id"=>$val]);
                $check2 = $this->om->get_selected_data("id","tbl_category", ["sub_id"=>$val]);
               if(empty($check) && empty($check2))
               {
                $this->om->delete_data('tbl_category', ["id"=>$val]);
               }else{
                $all_error =1;
               }
                    
                      
              
            }

            if ($all_error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . 'users/menu'); 
        }
    
    }

    public function sub()
    {
        $this->data['main'] = 4;
        $this->data['sub'] = 6;
         if ($_POST) {
           
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('main', 'main', 'required');
            $this->validation->setRule('sub', 'sub', 'required');
            $this->validation->setRule('order', 'order', 'required');
            $this->validation->setRule('status', 'status', 'required');
          
           
          
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                
                 $main = strip_tags($this->request->getPost('main'));
                 $sub = strip_tags($this->request->getPost('sub'));
                
                 $check_mobile = $this->om->get_selected_data("id","tbl_category", ["name"=>$sub,"main_id"=>$main]);
                 if(!empty( $check_mobile))
                 {
                    $this->data['errors']['main'] = "This main already exists";
                 }else{
                   

                    $data_val =[
                        "name"=>strip_tags($this->request->getPost('sub')),
                        "main_id"=>$main,
                        "sub_id"=>0,
                        "orderlist" =>strip_tags($this->request->getPost('order')),
                        "is_active" =>strip_tags($this->request->getPost('status')),
                      
                    ];
                        $this->om->insert_data('tbl_category', $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');

                        return  redirect()->to(base_url() . 'users/sub');     
                 }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
        $this->data['result'] =  $this->om->jointbl("a.*,b.name as mainname","tbl_category a",["tbl_category b,b.id=a.main_id"],["a.main_id !="=>0,"a.sub_id"=>0],["order"=>["a.id","desc"]]);
        $this->data['mainmenu'] =  $this->om->get_selected_data("id,name","tbl_category",["main_id"=>0],["order"=>["id","desc"]]);
         
       
        return view('menu/sub',$this->data);
    }
    public function sub_edit($id)
    {
        $this->data['main'] = 4;
        $this->data['sub'] = 6;
         if ($_POST) {
           
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('main', 'main', 'required');
            $this->validation->setRule('sub', 'sub', 'required');
            $this->validation->setRule('order', 'order', 'required');
            $this->validation->setRule('status', 'status', 'required');
           
          
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                
                 $main = strip_tags($this->request->getPost('main'));
                 $sub = strip_tags($this->request->getPost('sub'));
                 $check = $this->om->get_selected_data("id","tbl_category", ["name"=>$sub,"main_id"=>$main,"id !="=>$id]);
                 if(!empty( $check))
                 {
                    $this->data['errors']['main'] = "This main already exists";
                 }else{
                   

                    $data_val =[
                        "name"=>strip_tags($this->request->getPost('sub')),
                        "main_id"=>$main, 
                        "orderlist" =>strip_tags($this->request->getPost('order')),
                        "is_active" =>strip_tags($this->request->getPost('status')),
                      
                    ];
                        $this->om->update_data('tbl_category',["id"=>$id],$data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');

                        return  redirect()->to(base_url() . 'users/sub');     
                 }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
        $this->data['result'] =  $this->om->get_selected_data("*","tbl_category",["id"=>$id],["order"=>["id","desc"]]);
               
        $this->data['main'] =  $this->om->get_selected_data("id,name","tbl_category",["main_id"=>0],["order"=>["id","desc"]]);
         
     
        return view('menu/subedit',$this->data);
    }
    function subdlt() {
        


        $id = $this->request->getPost('deleteclii');
     
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . 'users/sub'); 
        } else {
            $all_error = 0;
            foreach ($id as $val) {
               

                $check = $this->om->get_selected_data("id","tbl_category", ["main_id"=>$val]);
                $check2 = $this->om->get_selected_data("id","tbl_category", ["sub_id"=>$val]);
               if(empty($check) && empty($check2))
               {
                $this->om->delete_data('tbl_category', ["id"=>$val]);
               }else{
                $all_error =1;
               }
                    
                      
              
            }

            if ($all_error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . 'users/sub'); 
        }
    
    }
    function create()
    {
      
        $this->data['main'] = 4;
        $this->data['sub'] = 54;
         if ($_POST) {
           
            $this->data['post']=$post = $this->request->getPost();
            
            $this->validation->setRule('main', 'main', 'required');
            $this->validation->setRule('menuname', 'Menu Name', 'required');
          //  $this->validation->setRule('notes', 'Content', 'required');
            $this->validation->setRule('sub', 'sub', 'required');
            $this->validation->setRule('order', 'order', 'required');
            $this->validation->setRule('status', 'status', 'required');
           
          
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                
                 $main = strip_tags($this->request->getPost('main'));
                 $sub = strip_tags($this->request->getPost('sub'));
                 $menuname = strip_tags($this->request->getPost('menuname'));
                
                 $check_mobile = $this->om->get_selected_data("id","tbl_category", ["name"=>$menuname,"main_id"=>$main,"sub_id"=>$sub]);
                 if(!empty( $check_mobile))
                 {
                    $this->data['errors']['main'] = "This main already exists";
                 }else{
                   
       
                    $data_val =[
                        "name"=>strip_tags($this->request->getPost('menuname')),
                        "main_id"=>$main,
                        "sub_id"=>$sub,
                        "orderlist" =>strip_tags($this->request->getPost('order')),
                        "is_active" =>strip_tags($this->request->getPost('status')),
                        //"content"=>$this->request->getPost('notes'),
                        //"filename"=>$filename
                      
                    ];
                        $this->om->insert_data('tbl_category', $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . 'users/list');     
                 }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
        $this->data['result'] =  $this->om->jointbl("a.*,b.name as mainname","tbl_category a",["tbl_category b,b.id=a.main_id"],["a.main_id !="=>0,"a.sub_id"=>0],["order"=>["a.id","desc"]]);
        $this->data['mainmenu'] =  $this->om->get_selected_data("id,name","tbl_category",["main_id"=>0],["order"=>["id","desc"]]);
         
       
        return view('menu/create',$this->data);
     
    }
    function edit($id)
    {
        $this->data['result'] =  $this->om->get_selected_data("*","tbl_category",["id"=>$id],["order"=>["id","desc"]]);
     
        $this->data['main'] = 4;
        $this->data['sub'] = 6;
         if ($_POST) {
           
            $this->data['post']=$post = $this->request->getPost();
            
            $this->validation->setRule('main', 'main', 'required');
            $this->validation->setRule('menuname', 'Menu Name', 'required');
            $this->validation->setRule('sub', 'sub', 'required');
            $this->validation->setRule('order', 'order', 'required');
            $this->validation->setRule('status', 'status', 'required');
            
 
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                
                 $main = strip_tags($this->request->getPost('main'));
                 $sub = strip_tags($this->request->getPost('sub'));
                 $menuname = strip_tags($this->request->getPost('menuname'));
                
                 $check_mobile = $this->om->get_selected_data("id","tbl_category", ["name"=>$menuname,"main_id"=>$main,"sub_id"=>$sub,"id !="=>$id]);
                 if(!empty( $check_mobile))
                 {
                    $this->data['errors']['main'] = "This main already exists";
                 }else{
                    $filename = $this->data['result'][0]->filename;
                   
                    $data_val =[
                        "name"=>strip_tags($this->request->getPost('menuname')),
                        "main_id"=>$main,
                        "sub_id"=>$sub,
                        "orderlist" =>strip_tags($this->request->getPost('order')),
                        "is_active" =>strip_tags($this->request->getPost('status')),
                        "content"=>$this->request->getPost('notes'),
                        "filename"=>$filename
                      
                    ];
                        $this->om->update_data('tbl_category',["id"=>$id],$data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . 'users/list');     
                 }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
        $this->data['main'] =  $this->om->get_selected_data("id,name","tbl_category",["main_id"=>0],["order"=>["id","desc"]]);
         
       
        return view('menu/edit',$this->data);
     
    }
    function getsub()
    {
        $id = $this->request->GetPost('main');
        $result =  $this->om->get_selected_data("id,name","tbl_category",["main_id"=>$id,"sub_id"=>0],["order"=>["id","desc"]]);
        echo json_encode( $result);
    }
    function upload_ckeditor()
      {
     
        $CKEditor = $this->request->getGet("CKEditor");
        $funcNum =$this->request->getGet("CKEditorFuncNum"); 
           $file = $this->request->getFile('upload');
           $type = $file->getMimeType();
           if( $type == "application/pdf" || $type == "image/gif")
           {
            $validated = $this->validate([
              'file' => [
                  'uploaded[upload]',
                  'mime_in[upload,image/jpg,image/jpeg,image/png,application/pdf,image/gif]',
                  'max_size[upload,5120]',
              ],
          ]);
           }else{
            $validated = $this->validate([
              'file' => [
                  'uploaded[upload]',
                  'mime_in[upload,image/jpg,image/jpeg,image/png,application/pdf,image/gif]',
                  
              ],
          ]);
           }
          

          if ($validated) {
            $filename = date("Y-m-d") .'cllit'. $file->getRandomName();
            if(file_exists("./uploads/ckeditor/") == false)
            {
              mkdir("./uploads/ckeditor/", 0777, true);
              file_put_contents("./uploads/ckeditor/index.php", 'Hi hi Hi');
            }
            $PATH = getcwd();
            if( $type == "application/pdf" || $type == "image/gif")
            {
              $file->move($PATH . '/uploads/ckeditor/', $filename);
            }else{
              //$image = \Config\Services::image();
              //$image->withFile($file)->withResource()->save($PATH . '/uploads/ckeditor/'. $filename, 5);
              $file->move($PATH . '/uploads/ckeditor/', $filename);
            }
          
            $url = base_url() . '/uploads/ckeditor/'.$filename;
            echo '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "uploaded")</script>';
 
          }else{
            echo "Error: only allowed  jpg,png,pdf,gif or file max size is 5mb";
          }
          
         
      }
      function listfile()
      {
        $path = $this->request->getPost('path');
        if(!empty($path) && file_exists($path))
        {
          unlink($path);
        } 
        $data['fileList'] = glob('./uploads/ckeditor/*');
        return view('file', $data);
      }
       function list()
      {

        $this->data['main'] = 4;
        $this->data['sub'] = 8;
       
              
               $this->data['result'] =  $this->om->jointbl("a.*,b.name as mainname,c.name as submenu","tbl_category a",["tbl_category b,b.id=a.main_id","tbl_category c,c.id=a.sub_id"],["a.main_id !="=>0,"a.sub_id !="=>0],["order"=>["a.id","desc"]]);
      
        
     
        return view('menu/list',$this->data);

      }
      function dlt() {
        


        $id = $this->request->getPost('deleteclii');
     
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . 'users/menu'); 
        } else {
            $all_error = 0;
            foreach ($id as $val) {
               

                $check = $this->om->get_selected_data("id","tbl_category", ["main_id"=>$val]);
                $check2 = $this->om->get_selected_data("id","tbl_category", ["	sub_id"=>$val]);
               if(empty($check) && empty($check2))
               {
                $this->om->delete_data('tbl_category', ["id"=>$val]);
               }else{
                $all_error =1;
               }
                    
                      
              
            }

            if ($all_error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . 'users/list'); 
        }
    
    }
}
