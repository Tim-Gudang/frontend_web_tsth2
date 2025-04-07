<?php

namespace App\Repositories;

use App\Models\Gudang;
use Illuminate\Pagination\LengthAwarePaginator;

class GudangRepository
{
    public function getAll(int $perpage = 10): LengthAwarePaginator
    {
        return Gudang::paginate($perpage);
    }

    public function findById(int $id): ?Gudang
    {
        return Gudang::find($id);
    }

    public function create(array $data): Gudang
    {
        return Gudang::create($data);
    }

    public function update(Gudang $gudang, array $data): bool
    {
        return $gudang->update($data);
    }

    public function delete(Gudang $gudang): bool
    {
        return $gudang->delete();
    }

    public function restore(int $id): ?Gudang
    {
        $gudang = Gudang::onlyTrashed()->find($id);
        if ($gudang) {
            $gudang->restore();
            return $gudang;
        }
        return null;
    }

    public function forceDelete(int $id): bool
    {
        $gudang = Gudang::onlyTrashed()->find($id);
        return $gudang ? $gudang->forceDelete() : false;
    }
}
