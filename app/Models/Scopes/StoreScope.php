<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * only records that belong to a user store
 */
class StoreScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
            $user = Auth::user();

            if($user && $user->store_id){ // becuase admin not have sotre_id so this condition not applying on hem
                $builder->where('store_id', '=', $user->store_id);
            }
    }
}
