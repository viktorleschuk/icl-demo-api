<?php

namespace App\Helpers\Traits;

trait Filterable
{
    public function scopeFilter($query, $search)
    {
        if (!empty($search)) {
            $fillable = $this->getFillable();
            $query->where(function ($q) use ($search, $fillable) {
                foreach ($fillable as $key => $item) {
                    $q->{$key == 0 ? 'where' : 'orWhere'}($item, 'like', '%' . $search . '%');
                }
            });
        }

        return $query;
    }
}
