<?php

namespace App\Support;

/**
 * Classifies a request for the visit log: real person, link preview
 * (someone shared the link in LinkedIn, Slack, WhatsApp...) or bot.
 */
class VisitClassifier
{
    /** Link-preview fetchers: a sign that someone shared the page. */
    private const PREVIEWS = [
        'LinkedInBot' => 'LinkedIn',
        'Slackbot' => 'Slack',
        'facebookexternalhit' => 'Facebook / iMessage',
        'Facebot' => 'Facebook',
        'Twitterbot' => 'X / Twitter',
        'WhatsApp' => 'WhatsApp',
        'TelegramBot' => 'Telegram',
        'Discordbot' => 'Discord',
        'SkypeUriPreview' => 'Teams / Skype',
        'MicrosoftPreview' => 'Microsoft Teams',
        'Google-PageRenderer' => 'Google Chat',
        'redditbot' => 'Reddit',
        'Pinterestbot' => 'Pinterest',
        'Iframely' => 'Link preview',
        'Embedly' => 'Link preview',
    ];

    /** Known crawlers, AI bots, monitors and command-line tools. */
    private const BOTS = [
        'Googlebot' => 'Google', 'bingbot' => 'Bing', 'DuckDuckBot' => 'DuckDuckGo', 'YandexBot' => 'Yandex',
        'Baiduspider' => 'Baidu', 'Applebot' => 'Apple', 'AhrefsBot' => 'Ahrefs', 'SemrushBot' => 'Semrush',
        'MJ12bot' => 'Majestic', 'DotBot' => 'Moz', 'PetalBot' => 'Petal', 'Amazonbot' => 'Amazon',
        'GPTBot' => 'OpenAI', 'ChatGPT-User' => 'OpenAI', 'OAI-SearchBot' => 'OpenAI', 'ClaudeBot' => 'Anthropic',
        'Claude-User' => 'Anthropic', 'anthropic-ai' => 'Anthropic', 'PerplexityBot' => 'Perplexity',
        'CCBot' => 'Common Crawl', 'Bytespider' => 'ByteDance',
        'HeadlessChrome' => 'Headless browser', 'PhantomJS' => 'Headless browser', 'Lighthouse' => 'Lighthouse',
        'UptimeRobot' => 'Uptime monitor', 'Pingdom' => 'Uptime monitor', 'StatusCake' => 'Uptime monitor',
        'curl/' => 'curl', 'Wget' => 'wget', 'python-requests' => 'Python', 'python-httpx' => 'Python',
        'aiohttp' => 'Python', 'Go-http-client' => 'Go', 'okhttp' => 'Java/Android client', 'Java/' => 'Java',
        'axios/' => 'Node.js', 'node-fetch' => 'Node.js', 'undici' => 'Node.js', 'libwww-perl' => 'Perl',
        'Scrapy' => 'Scrapy', 'Postman' => 'Postman', 'insomnia' => 'Insomnia',
    ];

    private const GENERIC_BOT = '/bot\b|bot\/|crawl|spider|slurp|scrap|scan|monitor|probe|http-client|fetcher/i';

    /**
     * @return array{type: string, agent: ?string}
     */
    public static function classify(?string $userAgent): array
    {
        $ua = trim((string) $userAgent);
        if ($ua === '') {
            return ['type' => 'bot', 'agent' => 'No user agent'];
        }
        foreach (self::PREVIEWS as $needle => $name) {
            if (stripos($ua, $needle) !== false) {
                return ['type' => 'preview', 'agent' => $name];
            }
        }
        foreach (self::BOTS as $needle => $name) {
            if (stripos($ua, $needle) !== false) {
                return ['type' => 'bot', 'agent' => $name];
            }
        }
        if (preg_match(self::GENERIC_BOT, $ua) || ! str_contains($ua, 'Mozilla/')) {
            return ['type' => 'bot', 'agent' => 'Other bot'];
        }

        return ['type' => 'human', 'agent' => null];
    }

    public static function device(?string $ua): string
    {
        $ua = (string) $ua;
        if (preg_match('/iPad|Tablet/i', $ua)) {
            return 'tablet';
        }

        return preg_match('/Mobi|iPhone|Android/i', $ua) ? 'mobile' : 'desktop';
    }

    public static function browser(?string $ua): string
    {
        $ua = (string) $ua;

        return match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'OPR/') => 'Opera',
            str_contains($ua, 'Firefox/') || str_contains($ua, 'FxiOS') => 'Firefox',
            str_contains($ua, 'CriOS') || str_contains($ua, 'Chrome/') => 'Chrome',
            str_contains($ua, 'Safari/') => 'Safari',
            default => 'Other',
        };
    }

    public static function os(?string $ua): string
    {
        $ua = (string) $ua;

        return match (true) {
            (bool) preg_match('/iPhone|iPad|iPod/', $ua) => 'iOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Mac OS X') => 'macOS',
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'CrOS') => 'ChromeOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Other',
        };
    }

    /**
     * Where the visitor came from, as a domain ("linkedin.com"), or null for
     * direct visits and links within this site.
     */
    public static function referrer(?string $referer, ?string $ownHost): ?string
    {
        $host = parse_url((string) $referer, PHP_URL_HOST);
        if (! $host) {
            return null;
        }
        $host = strtolower(preg_replace('/^(www\.|m\.|l\.|lm\.)/i', '', $host));
        if ($ownHost && $host === strtolower(preg_replace('/^www\./i', '', $ownHost))) {
            return null;
        }

        return match (true) {
            str_ends_with($host, 'linkedin.com') || $host === 'lnkd.in' => 'linkedin.com',
            (bool) preg_match('/(^|\.)google\.[a-z.]+$/', $host) => 'google',
            default => $host,
        };
    }

    /** Masks the last part of the address: 79.127.228.17 → 79.127.228.0. */
    public static function maskIp(?string $ip): string
    {
        $ip = (string) $ip;
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\.\d+$/', '.0', $ip);
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return implode(':', array_slice(explode(':', $ip), 0, 3)).'::';
        }

        return '';
    }

    /** One-way hash used only to count unique visitors. */
    public static function hashIp(?string $ip): ?string
    {
        return $ip ? hash_hmac('sha256', $ip, (string) config('app.key')) : null;
    }
}
