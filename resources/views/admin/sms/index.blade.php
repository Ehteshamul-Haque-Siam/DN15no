@extends('layouts.app')
@section('title', 'SMS Logs')

@section('content')
<div class="container py-4">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
        <h3 class="mb-0">SMS Logs</h3>
        <div class="text-muted small align-self-center">
            Gateway: <strong>{{ config('services.sms.gateway') }}</strong> |
            Status: <strong>{{ config('services.sms.enabled') ? 'Enabled' : 'Disabled (dry-run)' }}</strong>
        </div>
    </div>

    {{-- Test SMS form --}}
    <div class="card shadow-soft mb-3">
        <div class="card-header"><strong>Send Test SMS</strong></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sms.test') }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <input type="text" name="mobile" class="form-control" placeholder="01XXXXXXXXX" required>
                </div>
                <div class="col-md-7">
                    <input type="text" name="message" class="form-control" placeholder="Test message (Bengali allowed)" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Send</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Log table --}}
    <div class="table-responsive">
        <table class="table table-sm bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Mobile</th><th>Type</th>
                    <th>Message</th><th>Status</th><th>Sent</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->mobile }}</td>
                        <td><span class="badge bg-secondary">{{ $log->type }}</span></td>
                        <td style="max-width:400px;">
                            {{ Str::limit($log->message, 90) }}
                            @if($log->response)
                                <details class="small text-muted mt-1">
                                    <summary>Gateway response</summary>
                                    <code style="word-break:break-all;">{{ $log->response }}</code>
                                </details>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $log->status==='sent'?'success':($log->status==='failed'?'danger':'warning') }}">
                                {{ $log->status }}
                            </span>
                        </td>
                        <td>{{ $log->created_at->format('d M H:i') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.sms.resend', $log->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary">Resend</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-3">No logs.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $logs->links() }}
</div>
@endsection