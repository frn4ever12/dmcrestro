@extends('layouts.admin')

@section('title', 'Edit Payment Gateway')

@section('page-title', 'Edit Payment Gateway')

@section('breadcrumb', 'Payment Management / Gateway Settings / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Configure {{ $info['name'] }}</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">{{ $info['description'] }}</p>
        
        <form method="POST" action="{{ route('admin.payment-gateways.update', $gateway) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                @foreach ($info['fields'] as $field => $label)
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="{{ $field }}">{{ $label }}</label>
                        @if ($field === 'instructions' || $field === 'verification_note')
                            <textarea class="form-control" id="{{ $field }}" name="{{ $field }}" rows="3">{{ old($field) }}</textarea>
                        @else
                            <input type="text" class="form-control" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Enable this payment gateway</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
                <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
