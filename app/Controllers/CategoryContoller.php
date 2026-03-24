<?php

namespace App\Controllers;

use App\Controllers\BaseStaff;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ContentModel;
use App\Models\CategoryModel;
class CategoryContoller extends BaseStaff
{
    function getCommonData() : array {
        $data['main'] = 2;
        $data['sub'] = 9;
        return $data;
    }
    public function show($id)
    {
        $content = new ContentModel();
        $catemodel = new CategoryModel();
      
        $this->data['id'] = $id;
        $this->data['content'] =  $content->find($id); 
        $this->data['result'] =  $catemodel->where("type_id",$id)->findAll(); 
        $this->data['err'] = $this->session->get('validationerror');
     
      
        
        return view("category/index",$this->data + $this->getCommonData());
    }
    public function create()
    {
        
      
        $catemodel = new CategoryModel();
        $this->validation->setRule('url', 'url', 'required');
       
        $file = $this->request->getFile('photo');
       
        if (!empty($file) && !empty($file->getName())) 
           {
           
              $validated = $this->validate([
                 'photo' => [
                     'uploaded[photo]',
                     'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image]',
                    // 'max_size[photo,1024]',
                    // "max_dims[photo,1920,450]"
                 ],
             ]);
             if(!$validated)
             {
             
              $msg = $this->validation->getErrors();
              $this->validation->setRule('photo', 'photo', 'required',["required"=>$msg['photo']]);
       
             
             }
          
           }else{
            $this->validation->setRule('photo', 'photo', 'required');
       
           }
       
      
        if ($this->validation->withRequest($this->request)->run() == TRUE) {
          $PATH = getcwd();
      
          $filename = $file->getRandomName();
            
            $file->move($PATH . '/uploads/cate/', $filename);
           $data_val = [
              'title'=>$this->request->getPost('title'),
              'description'=>$this->request->getPost('notes'),
              'url'=>$this->request->getPost('url'),
              'type_id'=>$this->request->getPost('id'),
              "image"=> $filename ,
              "cid"=>$this->user_id,
              "created_at"=>date('Y-m-d H:i')
          ];
    
           $catemodel->insert($data_val);
           
            $this->session->setFlashdata('success', 'Data Saved successfully!!');
            return  redirect()->to('category/show/'.$this->request->getPost('id'));   
         
        } else {
           
           
        return redirect()->back()->withInput()->with('validationerror',  $this->validation->getErrors());

           
       }
   
    }
  
    public function edit($id)
    {
        
        $content = new ContentModel();
        $catemodel = new CategoryModel();
        $this->data['result']= $res =  $catemodel->find($id); 
        $this->data['id'] = $id;
        $this->data['content'] =  $content->find($res->type_id); 
        $this->data['err'] = $this->session->get('validationerror');
     
      
        
        return view("category/edit",$this->data + $this->getCommonData());
    }
  
    public function update($id)
    {
      
        $catemodel = new CategoryModel();
        $file = $this->request->getFile('photo');
        $validation = \Config\Services::validation();
        if (!empty($file) && !empty($file->getName())) {
            $validation->setRule('photo', 'photo', 'required',["required"=>"Please select image"]);
            $validation->setRule('photo', 'photo', 'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png]');
            $validation->setRule('photo', 'photo', 'max_size[photo,1024]');
        }
        if ($this->validation->withRequest($this->request)->run() == TRUE) {
            $PATH = getcwd();
            if (!empty($file) && !empty($file->getName())) {
                $filename = $file->getRandomName();
                $file->move($PATH . '/uploads/cate/', $filename);
                $data_val = [
                    'title'=>$this->request->getPost('title'),
                    'description'=>$this->request->getPost('notes'),
                    'url'=>$this->request->getPost('url'),
                    'type_id'=> '6',
                    "image"=> $filename ,
                    "cid"=>$this->user_id,
                    "created_at"=>date('Y-m-d H:i')
                ];
             //   dd($data_val);
                $catemodel->update($id, $data_val);
            } else {
                $data_val = [
                    'title'=>$this->request->getPost('title'),
                    'description'=>$this->request->getPost('notes'),
                    'url'=>$this->request->getPost('url'),
                    'type_id'=>$this->request->getPost('id'),
                    "cid"=>$this->user_id,
                    "created_at"=>date('Y-m-d H:i')
                ];
                $catemodel->update($id, $data_val);
            }
            $this->session->setFlashdata('success', 'Data Updated successfully!!');
            return  redirect()->to('category/show/6')->withInput();  
        } else {
            return redirect()->back()->withInput()->with('validationerror',  $this->validation->getErrors());
        }
    }
    
    public function delete()
    {
        $catemodel = new CategoryModel();
        $ids = $this->request->getPost('deleteclii');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $catemodel->delete($id);
            }
            $this->session->setFlashdata('success', 'Data deleted successfully!!');
        } else {
            $this->session->setFlashdata('error', 'No items selected for deletion.');
        }
        return redirect()->back()->withInput();
    }


}
