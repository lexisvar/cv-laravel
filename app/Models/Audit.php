<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audit';
    
    protected $fillable = [
      'ip',
      'controller',
      'function',
      'uagent',
      'ip_hash',
      'type',
      'agent',
      'referrer',
      'device',
      'browser',
      'os',
    ];

    public const PAGES = [
      'index' => 'Home page',
      'simple' => 'Plain CV',
      'viewPDF' => 'PDF (viewed)',
      'createPDF' => 'PDF (downloaded)',
    ];

    public function pageLabel(): string
    {
      return self::PAGES[$this->function] ?? $this->function;
    }
}
