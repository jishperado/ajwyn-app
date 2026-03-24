<?php

namespace App\Models;

use CodeIgniter\Model;

class NavlistModel extends Model
{
    protected $table            = 'tbl_category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'name', 'main_id', 'sub_id'];

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

    public function get_navlist()
    {
       
        $result = $this->db->table($this->table . ' a')
            ->select('a.id as category_id, a.name as category_name, a.main_id, a.sub_id, 
                      b.id as sub_category_id, b.name as sub_category_name, ')
                     
            ->join('tbl_category b', 'b.main_id = a.id AND b.sub_id = 0', 'left')
           
            ->where('a.main_id', 0)
            ->where('a.sub_id', 0)
            ->orderBy('a.id', 'asc')
            ->get()
            ->getResultArray();

       

   
    }
}
