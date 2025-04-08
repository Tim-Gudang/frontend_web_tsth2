<?php

namespace App\Repositories;

use App\Models\Satuan; // Asumsi ada model Satuan
use Illuminate\Pagination\LengthAwarePaginator;

class SatuanRepository
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Satuan::paginate($perPage);
    }

    public function findById(int $id): ?Satuan
    {
        return Satuan::find($id);
    }

    public function create(array $data): Satuan
    {
        return Satuan::create($data);
    }

    public function update(Satuan $satuan, array $data): bool
    {
        return $satuan->update($data);
    }

    public function delete(Satuan $satuan): bool
    {
        return $satuan->delete();
    }

    public function restore(int $id): ?Satuan
    {
        $satuan = Satuan::onlyTrashed()->find($id);
        if ($satuan) {
            $satuan->restore();
            return $satuan;
        }
        return null;
    }

    public function forceDelete(int $id): bool
    {
        $satuan = Satuan::onlyTrashed()->find($id);
        return $satuan ? $satuan->forceDelete() : false;
    }
}