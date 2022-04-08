<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Node extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['height'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Node::class, 'parent_id')->with('children');
    }

    public function getHeightAttribute(): int
    {
        return $this->calculateHeight($this);
    }

    public function calculateHeight($node, $height = 0): int
    {
        if (is_null($node->parent)) {
            return $height;
        } else {
            $height++;
            return $this->calculateHeight($node->parent, $height);
        }
    }
}
