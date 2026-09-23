<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function index()
    {
        $templates = SmsTemplate::orderBy('id')->get();
        return view('admin.sms.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.sms.templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'          => 'required|string|max:100|unique:sms_templates,key|regex:/^[a-z0-9_]+$/',
            'name'         => 'required|string|max:150',
            'body'         => 'required|string|max:1000',
            'description'  => 'nullable|string|max:500',
            'placeholders' => 'nullable|string',
            'is_active'    => 'boolean',
        ]);

        $data['placeholders'] = $this->parsePlaceholders($request->input('placeholders'));
        $data['is_active']    = $request->has('is_active');

        SmsTemplate::create($data);

        return redirect()
            ->route('admin.sms.templates.index')
            ->with('success', "Template '{$data['name']}' created successfully.");
    }

    public function edit(SmsTemplate $template)
    {
        return view('admin.sms.templates.edit', ['smsTemplate' => $template]);
    }

    public function update(Request $request, SmsTemplate $template)
    {
        $data = $request->validate([
            'key'          => 'required|string|max:100|regex:/^[a-z0-9_]+$/|unique:sms_templates,key,' . $template->id,
            'name'         => 'required|string|max:150',
            'body'         => 'required|string|max:1000',
            'description'  => 'nullable|string|max:500',
            'placeholders' => 'nullable|string',
            'is_active'    => 'boolean',
        ]);

        $data['placeholders'] = $this->parsePlaceholders($request->input('placeholders'));
        $data['is_active']    = $request->has('is_active');

        $template->update($data);

        return redirect()
            ->route('admin.sms.templates.index')
            ->with('success', "Template '{$template->name}' updated successfully.");
    }

    public function destroy(SmsTemplate $template)
    {
        $name = $template->name;
        $template->delete();

        return redirect()
            ->route('admin.sms.templates.index')
            ->with('success', "Template '{$name}' deleted.");
    }

    public function duplicate(SmsTemplate $template)
    {
        $baseKey = $template->key . '_copy';
        $key     = $baseKey;
        $i       = 1;

        while (SmsTemplate::where('key', $key)->exists()) {
            $key = $baseKey . '_' . $i;
            $i++;
        }

        SmsTemplate::create([
            'key'          => $key,
            'name'         => $template->name . ' (Copy)',
            'body'         => $template->body,
            'description'  => $template->description,
            'placeholders' => $template->placeholders,
            'is_active'    => false,
        ]);

        return redirect()
            ->route('admin.sms.templates.index')
            ->with('success', "Template duplicated as '{$key}'.");
    }

    public function toggle(SmsTemplate $template)
    {
        $template->update(['is_active' => !$template->is_active]);
        $status = $template->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Template '{$template->name}' {$status}.");
    }

    public function reset(SmsTemplate $template)
    {
        \Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\SmsTemplateSeeder',
            '--force' => true,
        ]);

        return back()->with('success', 'Templates restored to defaults.');
    }

    public function preview(SmsTemplate $template)
    {
        $samples = [
            'name'           => 'মোঃ রহিম উদ্দিন',
            'reg_id'         => 'D15-2026-001',
            'amount'         => '1,020',
            'payment_number' => '01761983617',
            'trn'            => 'AIUD222DJHD25',
            'reason'         => 'ভুল TRN',
            'contact'        => '01721308219',
            'mobile'         => '01712345678',
            'datetime'       => now()->format('d M Y, h:i A'),
        ];

        $rendered = $template->body;
        foreach ($samples as $k => $v) {
            $rendered = str_replace('{' . $k . '}', $v, $rendered);
        }

        return view('admin.sms.templates.preview', [
            'template' => $template,
            'rendered' => $rendered,
            'samples'  => $samples,
        ]);
    }

    protected function parsePlaceholders(?string $input): array
    {
        if (empty($input)) {
            return [];
        }

        return collect(explode(',', $input))
            ->map(fn ($p) => trim($p, " \t\n\r\0\x0B{}"))
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }
}