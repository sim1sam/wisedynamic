@extends('adminlte::page')

@section('title', 'View Message')

@section('content_header')
    <h1>View Message</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Message Details</h3>
            <div class="card-tools">
                <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-default">
                    <i class="fas fa-arrow-left"></i> Back to Messages
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="card-title">{{ $message->subject }}</h5>
                            <div class="card-tools">
                                <span class="badge badge-light">{{ $message->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="message-content">
                                {{ $message->message }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h5 class="card-title">Sender Information</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Name:</strong> {{ $message->name }}</p>
                            <p><strong>Email:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
                            <p><strong>Sent on:</strong> {{ $message->created_at->format('F d, Y') }}</p>
                            <p><strong>Time:</strong> {{ $message->created_at->format('h:i A') }}</p>
                            <p>
                                <strong>Status:</strong>
                                @if($message->is_read)
                                    <span class="badge badge-success">Read</span>
                                @else
                                    <span class="badge badge-warning">Unread</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class="fas fa-trash"></i> Delete Message
                            </button>
                        </form>
                    </div>
                    <div class="mt-3">
                        <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-primary btn-block">
                            <i class="fas fa-reply"></i> Reply via Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .message-content {
            white-space: pre-wrap;
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Message view page loaded.');
    </script>
@stop
