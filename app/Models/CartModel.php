<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table            = 'tbl_cart';
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

    public function get_data()
    {
        return $this->select('tbl_cart.*, pc.*, pi.*')
                    ->join('product pc', 'tbl_cart.product_id = pc.id')
                    ->join('product_images pi', 'tbl_cart.product_id = pi.product_id')
                    ->findAll();
    }

    public function get_data_by()
    {
        return $this->select('tbl_cart.id as cart_id, tbl_cart.*, pc.*, product_veriant.*, tbl_type.product_type as type_name')
        ->join('product_veriant', 'tbl_cart.variant = product_veriant.id')
        ->join('product pc', 'product_veriant.product_id = pc.id')
        ->join('tbl_type', 'pc.product_type = tbl_type.id', 'left')
       ;
    } 
    

public function getTotalCartPrice($user_id)
{
    $row = $this->select(
        'SUM( 
            (
                (
                    product_veriant.price 
                    + (product_veriant.price * IFNULL(product.tax, 0) / 100)
                )
                - (
                    (
                        product_veriant.price 
                        + (product_veriant.price * IFNULL(product.tax, 0) / 100)
                    ) * IFNULL(product_veriant.if_offer_per_price, 0) / 100
                )
            ) * tbl_cart.quantity
        ) AS total',
        false
    )
    ->join('product_veriant', 'product_veriant.id = tbl_cart.variant')
    ->join('product', 'product.id = product_veriant.product_id', 'left')
    ->where('tbl_cart.cus_id', $user_id)
    ->first();

    return $row->total ?? 0;
}



}
