<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'url',
        'repo_url',
        'stack',
        'description',
        'start_date',
        'finish_date',
        'sort_order',
    ];

    /**
     * The tech stack is stored as a comma-separated string.
     *
     * @return array<int, string>
     */
    public function stackList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->stack))));
    }
}
