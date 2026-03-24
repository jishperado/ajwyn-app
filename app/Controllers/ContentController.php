<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContentModel;

class ContentController extends BaseController
{
   
public function index()  {
    
    $this->data['main'] = 2;
    $this->data['sub'] = 9;

    $flash  = new ContentModel();
    $this->data['result']  = $flash->findAll();
    return view('flash/index',$this->data);
}
  

    

    public function edit($id = null)
    {
        
        $this->data['main'] = 2;
        $this->data['sub'] = 9;
        $this->data['errors'] = $this->session->get('validation');
        $flash  = new ContentModel();
        $this->data['result']  = $flash->find($id);
        return view('flash/content',$this->data);
    }

    public function update($id = null)
    {
       
        $flash  = new ContentModel();
       
      //  $this->validation->setRule('message', 'message', 'required');
        $this->validation->setRule('title', 'title', 'required');
       
      
        if ($this->validation->withRequest($this->request)->run() == TRUE) {
         
          
             $data_val = [
                'content'=>$this->request->getPost('message'),
                "title"=>$this->request->getPost('title'),
            ];
            $flash->update($id,$data_val);
            $this->session->setFlashdata('success', 'Data Saved successfully!!');

             return  redirect()->to(base_url() . 'content/edit/'.$id);     
          
           
          
            
                
                
       
     } else {
        

           $data['errors'] = $this->validation->getErrors();
           
       }
       return redirect()->back()->withInput()->with('validation',  $data);

    
    
    }

   
   
}