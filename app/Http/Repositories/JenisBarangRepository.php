<?php

namespace App\Repositories;

use App\Models\JenisBarang; // Asumsi ada model JenisBarang
use Illuminate\Pagination\LengthAwarePaginator;

class JenisBarangRepository
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return JenisBarang::paginate($perPage);
    }

    public function getById(int $id): ?JenisBarang
    {
        return JenisBarang::find($id);
    }

    public function getTrashedById(int $id): ?JenisBarang
    {
        return JenisBarang::onlyTrashed()->find($id);
    }

    public function create(array $data): JenisBarang
    {
        return JenisBarang::create($data);
    }

    public function update(JenisBarang $jenisBarang, array $data): bool
    {
        return $jenisBarang->update($data);
    }

    public function delete(JenisBarang $jenisBarang): bool
    {
        return $jenisBarang->delete();
    }

    public function restore(JenisBarang $jenisBarang): bool
    {
        return $jenisBarang->restore();
    }

    public function forceDelete(JenisBarang $jenisBarang): bool
    {
        return $jenisBarang->forceDelete();
    }
}