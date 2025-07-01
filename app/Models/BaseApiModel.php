<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseApiModel extends Model
{
    protected $returnType = 'array';
    protected $useSoftDelete = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get paginated data with search and filters
     */
    public function getPaginated($perPage = 10, $search = '', $filters = [])
    {
        // Apply search if provided
        if (!empty($search) && property_exists($this, 'searchFields') && !empty($this->searchFields)) {
            $this->groupStart();
            foreach ($this->searchFields as $field) {
                $this->orLike($field, $search);
            }
            $this->groupEnd();
        }

        // Apply filters
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $this->where($field, $value);
            }
        }

        return $this->paginate($perPage);
    }

    /**
     * Get data with relationships
     */
    public function getWithRelations($id, $relations = [])
    {
        $data = $this->find($id);
        
        if (!$data) {
            return null;
        }

        foreach ($relations as $relation) {
            $data[$relation] = $this->getRelationData($data, $relation);
        }

        return $data;
    }

    /**
     * Get relation data (override in child models)
     */
    protected function getRelationData($data, $relation)
    {
        return [];
    }

    /**
     * Create with validation
     */
    public function createRecord($data)
    {
        if (!$this->validate($data)) {
            return [
                'success' => false,
                'errors' => $this->errors()
            ];
        }

        $id = $this->insert($data);
        
        if ($id) {
            return [
                'success' => true,
                'data' => $this->find($id)
            ];
        }

        return [
            'success' => false,
            'errors' => ['Failed to create record']
        ];
    }

    /**
     * Update with validation
     */
    public function updateRecord($id, $data)
    {
        $existing = $this->find($id);
        if (!$existing) {
            return [
                'success' => false,
                'errors' => ['Record not found']
            ];
        }

        if (!$this->validate($data)) {
            return [
                'success' => false,
                'errors' => $this->errors()
            ];
        }

        if ($this->update($id, $data)) {
            return [
                'success' => true,
                'data' => $this->find($id)
            ];
        }

        return [
            'success' => false,
            'errors' => ['Failed to update record']
        ];
    }
}
