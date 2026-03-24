<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    public function getList()
    {
       $db = \Config\Database::connect();

$sub = $db->table('product_veriant')
    ->select('product_id, MIN(price) AS min_price')
    ->groupBy('product_id');

$this->select('
        a.id AS pro_id,
        CONCAT(a.product_name, " - ", c.veriant) AS product_name,
        b.product_image AS img,
        c.price AS product_price,
        c.id AS veriant_id,
        a.product_type,
        a.tax,
        c.qty,
        c.id as pv_id,
        c.if_offer_per_price AS offers
    ')
    ->from('product a')
    ->join('product_images b', 'a.id = b.product_id')
    ->join('product_veriant c', 'a.id = c.product_id')
    ->join('('.$sub->getCompiledSelect().') d', 'c.product_id = d.product_id AND c.price = d.min_price')
    ->where('a.is_active', 'Y')
    ->groupBy('a.id')
    ->orderBy('a.id', 'DESC')
    ->limit(30);

return $this->findAll();

    }
    
    function category()
    {
       return  $this->join("product_category pc","pc.product_id = product.id");
      
    }
    function categorys()
    {
       return  $this->join("product_category pc","pc.product_id = product.id")
       ->join("tbl_category pg","pg.id = pc.sub_id");;
    }
    function status()
    {
       return  $this->join("categorystatus cs","cs.id = product.categorystatus");
    }
    function images()
    {
       return  $this->join("product_images pi","pi.product_id = product.id");
    }
    function veriants()
    {
       return  $this->join("product_veriant pv","pv.product_id = product.id");
    }
        function ver()
    {
       return  $this->join("product_veriant vv","vv.product_id = product.id");
    }
  
    function active()  {
        return $this->where("product.is_active","Y");
    }
    function order($type)  {
        return $this->orderBy("orderlist",$type);
    }
    function seoFriendlyUrl($string) {
      // Convert to lowercase
      $string = strtolower($string);
  
      // Replace spaces and special characters with hyphens
      $string = preg_replace('/[^\w\s]+/', '', $string);
      $string = preg_replace('/[\s-]+/', '-', $string);
  
      // Trim hyphens from the beginning and end
      $string = trim($string, '-');
  
      return $string;
  }


  function get_product_list(){
    return $this->where('is_active', 'Y')
    ->select('product.id as pro_id, product.tax,tbl_type.product_type, product.product_name, product.img, product_veriant.price as product_price,
              product_veriant.if_offer_per_price as offers, product_veriant.qty, product_veriant.id as pv_id')
    ->join('product_images', 'product_images.product_id = product.id', 'left')
    ->join('product_veriant', 'product_veriant.product_id = product.id', 'left')
    ->join('tbl_type', 'product.product_type = tbl_type.id', 'left')
    ->findAll();
  }
}
