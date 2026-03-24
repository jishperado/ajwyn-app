<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentStatusModel extends Model
{
    protected $table            = 'payment_sts';
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
    protected $beforeInsert   = [
        'beforeInsert'
    ];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [
        'beforeUpdate'
    ];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    function beforeInsert($data){
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }
    function beforeUpdate($data){
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }
}
