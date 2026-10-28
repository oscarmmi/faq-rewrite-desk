<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RewriteJob extends Model
{
    protected $fillable = ['source', 'status'];

    /**
     * @return HasOne<Rewrite, $this>
     */
    public function rewrite(): HasOne
    {
        return $this->hasOne(Rewrite::class);
    }
}
