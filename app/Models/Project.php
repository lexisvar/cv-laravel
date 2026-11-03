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
        'links',
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

    /**
     * Extra links, one per line: "Label | https://...". Only http(s) URLs are kept.
     *
     * @return array<int, array{label: string, url: string}>
     */
    public function linkList(): array
    {
        $links = [];
        foreach (preg_split('/\R/', (string) $this->links) as $line) {
            $parts = array_map('trim', explode('|', $line, 2));
            if (count($parts) === 2 && $parts[0] !== '' && preg_match('#^https?://#i', $parts[1])) {
                $links[] = ['label' => $parts[0], 'url' => $parts[1]];
            }
        }

        return $links;
    }
}
