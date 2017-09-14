<?php
/**
 * Created by PhpStorm.
 * User: hcm-102-0001
 * Date: 14/09/2017
 * Time: 10:25
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ReplyCountScope implements Scope
{
    /**
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->withCount('replies');
    }
}