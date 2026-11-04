<?php

namespace Tests\Unit;

use App\Support\VisitClassifier;
use PHPUnit\Framework\TestCase;

class VisitClassifierTest extends TestCase
{
    private const MAC_CHROME = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36';
    private const IPHONE_SAFARI = 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1';

    public function test_real_browsers_are_people(): void
    {
        $this->assertSame(['type' => 'human', 'agent' => null], VisitClassifier::classify(self::MAC_CHROME));
        $this->assertSame('human', VisitClassifier::classify(self::IPHONE_SAFARI)['type']);
    }

    public function test_link_previews(): void
    {
        $this->assertSame(['type' => 'preview', 'agent' => 'LinkedIn'], VisitClassifier::classify('LinkedInBot/1.0 (compatible; Mozilla/5.0; Apache-HttpClient +http://www.linkedin.com)'));
        $this->assertSame('Slack', VisitClassifier::classify('Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)')['agent']);
        $this->assertSame('WhatsApp', VisitClassifier::classify('WhatsApp/2.24.1.1 A')['agent']);
    }

    public function test_bots_and_tools(): void
    {
        foreach ([
            'curl/8.5.0' => 'curl',
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)' => 'Google',
            'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.2; +https://openai.com/gptbot)' => 'OpenAI',
            'python-requests/2.32.0' => 'Python',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/120.0 Safari/537.36' => 'Headless browser',
            'Mozilla/5.0 (compatible; SomeNewCrawler/1.0)' => 'Other bot',
            'Symfony' => 'Other bot',
            '' => 'No user agent',
        ] as $ua => $agent) {
            $this->assertSame(['type' => 'bot', 'agent' => $agent], VisitClassifier::classify($ua), $ua);
        }
    }

    public function test_device_browser_os(): void
    {
        $this->assertSame(['desktop', 'Chrome', 'macOS'], [VisitClassifier::device(self::MAC_CHROME), VisitClassifier::browser(self::MAC_CHROME), VisitClassifier::os(self::MAC_CHROME)]);
        $this->assertSame(['mobile', 'Safari', 'iOS'], [VisitClassifier::device(self::IPHONE_SAFARI), VisitClassifier::browser(self::IPHONE_SAFARI), VisitClassifier::os(self::IPHONE_SAFARI)]);
    }

    public function test_referrer_sources(): void
    {
        $this->assertSame('linkedin.com', VisitClassifier::referrer('https://www.linkedin.com/in/someone/', 'lexisvar.me'));
        $this->assertSame('linkedin.com', VisitClassifier::referrer('https://lnkd.in/abc', 'lexisvar.me'));
        $this->assertSame('google', VisitClassifier::referrer('https://www.google.com.au/', 'lexisvar.me'));
        $this->assertSame('seek.com.au', VisitClassifier::referrer('https://www.seek.com.au/job/1', 'lexisvar.me'));
        $this->assertNull(VisitClassifier::referrer('https://lexisvar.me/#projects', 'lexisvar.me'));
        $this->assertNull(VisitClassifier::referrer(null, 'lexisvar.me'));
    }

    public function test_ip_is_masked(): void
    {
        $this->assertSame('79.127.228.0', VisitClassifier::maskIp('79.127.228.17'));
        $this->assertSame('2001:db8:85a3::', VisitClassifier::maskIp('2001:db8:85a3:8d3:1319:8a2e:370:7348'));
        $this->assertSame('', VisitClassifier::maskIp('not-an-ip'));
    }
}
