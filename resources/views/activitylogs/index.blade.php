@extends('layout.backend')

@section('content')
@include('layout.alert')
<div class="container-fluid px-3 px-md-4">
    <h2 class="mb-2 d-none d-md-block role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-list me-3"></i> Activity Logs
        </span>
    </h2>
    <h5 class="mb-2 d-md-none role-title">
        <span class="role-content">
            <i class="fas fa-fw fa-list me-2"></i> Activity Logs
        </span>
    </h5>

    <hr class="section-divider">

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('activitylogs.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Cari deskripsi / properti">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select mb-0">
                        <option value="">- Semua -</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ (string)$u->id === (string)$filters['user_id'] ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label">Log Name</label>
                    <select name="log_name" class="form-select mb-0">
                        <option value="">- Semua -</option>
                        @foreach($logNames as $name)
                        <option value="{{ $name }}" {{ $name === $filters['log_name'] ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Dari</label>
                    <input type="text" name="date_from" value="{{ $filters['date_from'] }}" class="form-control datepicker" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Sampai</label>
                    <input type="text" name="date_to" value="{{ $filters['date_to'] }}" class="form-control datepicker" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('activitylogs.index') }}" class="btn btn-outline-secondary btn-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-2 table-wrapper">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-gradient-header">
                        <tr class="text-center">
                            <th style="width: 12%">Waktu</th>
                            <th style="width: 14%">User</th>
                            <th style="width: 10%">Log</th>
                            <th>Deskripsi</th>
                            <th style="width: 18%">Subject</th>
                            <th style="width: 22%">Properties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        @php
                        $props = $activity->properties ? $activity->properties->toArray() : [];
                        $json = json_encode($props, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                        $subjectType = $activity->subject_type ? \Illuminate\Support\Str::afterLast($activity->subject_type, '\\') : '-';
                        @endphp
                        <tr>
                            <td class="text-nowrap">{{ optional($activity->created_at)->format('Y-m-d H:i:s') }}</td>
                            <td>{{ optional($activity->causer)->name ?? '-' }}</td>
                            <td class="text-center"><span class="badge bg-info text-dark">{{ $activity->log_name ?? 'default' }}</span></td>
                            <td>{{ $activity->description }}</td>
                            <td>
                                <div class="small">Type: <strong>{{ $subjectType }}</strong></div>
                                <div class="small">ID: <strong>{{ $activity->subject_id ?? '-' }}</strong></div>
                            </td>
                            <td>
                                <code class="small">{{ \Illuminate\Support\Str::limit($json, 140) }}</code>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">Total: {{ $activities->total() }}</div>
                <div>{{ $activities->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.flatpickr) {
            flatpickr('.datepicker', {
                dateFormat: 'Y-m-d',
                allowInput: true
            });
        }
    });
</script>
@endpush