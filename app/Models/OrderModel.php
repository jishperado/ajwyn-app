<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'tbl_order';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


  
    public function get_data_by()
    {
        return $this->select('tbl_order.id as ord_id, tbl_order.*, pc.*, product_veriant.*')
        ->join('product_veriant', 'tbl_order.variant = product_veriant.id')
        ->join('product pc', 'product_veriant.product_id = pc.id')
       ;
    } 
    public function get_data_by2()
    {
        return $this->select('tbl_order.id as ord_id, tbl_order.*, payment_sts.tnx_id as order_id, pc.*, product_veriant.*')
        ->join('product_veriant', 'tbl_order.variant = product_veriant.id')
        ->join('product pc', 'product_veriant.product_id = pc.id')
       ;
    } 

        public function get_data()
    {
        return $this->select('tbl_order.id as ord_id, tbl_order.*,tbl_order.created_date as ord_date, pc.*, product_veriant.*, tbl_cus_add.name,tbl_cus_add.address,tbl_cus_add.landmark,tbl_cus_add.pincode,cities.name as city')
        ->join('product_veriant', 'tbl_order.variant = product_veriant.id')
        ->join('product pc', 'product_veriant.product_id = pc.id')
        ->join('tbl_cus', 'tbl_order.cus_id = tbl_cus.id')
        ->join('tbl_cus_add', 'tbl_cus.id = tbl_cus_add.cus_id','left')
        ->join('cities', 'tbl_cus_add.city = cities.id', 'left')
      ->where('tbl_cus_add.id = tbl_order.add_id', null, false)

       ;
    } 
    public function customer()
    {
        return $this->join('tbl_cus', 'tbl_order.cus_id = tbl_cus.id');
        
    }
    public function payment(){
        return $this->join('payment_sts', 'payment_sts.id = tbl_order.payment_id','left');
    }
    

  
}
