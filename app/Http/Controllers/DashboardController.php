<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public const TABS = ['human' => 'People', 'preview' => 'Link previews', 'bot' => 'Bots', 'all' => 'All'];

    public function index(Request $request): View
    {
        $tab = array_key_exists($request->query('type'), self::TABS) ? $request->query('type') : 'human';
        $since = now()->subDays(30);
        $recent = fn () => Audit::where('created_at', '>=', $since);

        $summary = [
            'views' => $recent()->where('type', 'human')->where('function', 'index')->count(),
            'visitors' => $recent()->where('type', 'human')->whereNotNull('ip_hash')->distinct()->count('ip_hash'),
            'cv' => $recent()->where('type', 'human')->whereIn('function', ['viewPDF', 'createPDF', 'simple'])->count(),
            'previews' => $recent()->where('type', 'preview')->count(),
            'bots' => $recent()->where('type', 'bot')->count(),
        ];

        $sources = $recent()->where('type', 'human')
            ->selectRaw("COALESCE(referrer, 'direct') as source, COUNT(*) as total")
            ->groupBy('source')->orderByDesc('total')->limit(6)->get();

        $previewSources = $recent()->where('type', 'preview')
            ->selectRaw('agent, COUNT(*) as total')
            ->groupBy('agent')->orderByDesc('total')->limit(6)->get();

        $devices = $recent()->where('type', 'human')
            ->selectRaw('device, COUNT(*) as total')
            ->groupBy('device')->orderByDesc('total')->get();

        $visits = Audit::query()
            ->when($tab !== 'all', fn ($query) => $query->where('type', $tab))
            ->orderByDesc('created_at')
            ->paginate(50)
            ->withQueryString();

        return view('dashboard', compact('tab', 'summary', 'sources', 'previewSources', 'devices', 'visits') + ['tabs' => self::TABS]);
    }
}
