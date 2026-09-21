<?php

namespace Tests\Feature\Admin;

use App\Models\SmsLog;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminSmsControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    private const GATEWAY = 'apibd.rmlconnect.net/bulksms/personalizedbulksms*';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.sms.enabled' => true,
            'services.sms.gateway' => 'routemobile',
            'services.sms.username' => 'user',
            'services.sms.password' => 'secret',
            'services.sms.sender' => '8809617614054',
        ]);
    }

    public function test_test_sms_to_local_number_is_sent_with_880_country_code(): void
    {
        Http::preventStrayRequests();
        Http::fake([self::GATEWAY => Http::response('1701|8801635227460|msg-1')]);
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->post(route('admin.sms.test'), ['mobile' => '01635227460', 'message' => 'Hello'])
            ->assertRedirect();

        Http::assertSent(fn (Request $request) => $request['destination'] === '8801635227460'
            && $request['type'] === 0
            && $request['message'] === 'Hello');
        $this->assertSame('sent', SmsLog::sole()->status);
    }

    public function test_bengali_message_is_sent_as_unicode_hex(): void
    {
        Http::preventStrayRequests();
        Http::fake([self::GATEWAY => Http::response('1701|8801635227460|msg-2')]);
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->post(route('admin.sms.test'), ['mobile' => '01635227460', 'message' => 'কখ'])
            ->assertRedirect();

        Http::assertSent(fn (Request $request) => $request['type'] === 2
            && $request['message'] === '09950996');
    }

    public function test_resend_marks_log_failed_for_number_not_in_01_format(): void
    {
        Http::preventStrayRequests();
        Http::fake();
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $log = SmsLog::create([
            'mobile' => '8801635227460',
            'message' => 'Hello',
            'type' => 'otp',
            'status' => 'failed',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.sms.resend', $log))
            ->assertRedirect();

        Http::assertNothingSent();
        $this->assertSame('failed', SmsLog::latest('id')->first()->status);
    }
}
