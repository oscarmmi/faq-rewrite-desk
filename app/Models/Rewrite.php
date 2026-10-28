<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property list<string> $kept_facts
 * @property list<string> $missing_facts
 */
class Rewrite extends Model
{
    protected $fillable = ['rewritten', 'kept_facts', 'is_valid'];

    protected function casts(): array
    {
        return [
            'kept_facts' => 'array',
            'is_valid' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<RewriteJob, $this>
     */
    public function rewriteJob(): BelongsTo
    {
        return $this->belongsTo(RewriteJob::class);
    }
}
