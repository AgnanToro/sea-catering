<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    /**
     * Get paginated data with search functionality
     */
    public function getPaginatedData($search = '', $perPage = 10, $page = 1)
    {
        if (!empty($search) && !empty($this->allowedFields)) {
            $this->groupStart();
            foreach ($this->allowedFields as $field) {
                $this->orLike($field, $search);
            }
            $this->groupEnd();
        }
        
        return [
            'data' => $this->paginate($perPage, 'default', $page),
            'pager' => $this->pager,
            'total' => $this->countAllResults(false)
        ];
    }

    /**
     * Get data by ID with error handling
     */
    public function getById($id)
    {
        $data = $this->find($id);
        if (!$data) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data dengan ID $id tidak ditemukan");
        }
        return $data;
    }

    /**
     * Soft delete functionality
     */
    public function softDelete($id)
    {
        return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
