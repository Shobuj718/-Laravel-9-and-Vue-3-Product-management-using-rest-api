<?php

namespace App\Repository;

interface ProductRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function restore($id);
    public function forceDelete($id);
}
