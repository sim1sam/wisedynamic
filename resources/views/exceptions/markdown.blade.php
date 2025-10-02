# {{ $exception->class() }} - {!! $exception->title() !!}
{!! $exception->message() !!}

PHP {{ PHP_VERSION }}
Laravel {{ app()->version() }}
{{ $exception->request()->httpHost() }}

## Stack Trace

@foreach($exception->frames() as $index => $frame)
{{ $index }} - {{ $frame->file() }}:{{ $frame->line() }}
@endforeach

## Request

{{ $exception->request()->method() }} {{ Str::start($exception->request()->path(), '/') }}

## Headers

@if ($exception->requestHeaders())
@foreach ($exception->requestHeaders() as $key => $value)
* **{{ $key }}**: {!! $value !!}
@endforeach
@else
No header data available.
@endif

## Route Context

@if ($exception->applicationRouteContext())
@foreach($exception->applicationRouteContext() as $name => $value)
{{ $name }}: {!! $value !!}
@endforeach
@else
No routing data available.
@endif

## Route Parameters

@if ($routeParametersContext = $exception->applicationRouteParametersContext())
{!! $routeParametersContext !!}
@else
No route parameter data available.
@endif

## Database Queries

@if ($exception->applicationQueries())
@foreach ($exception->applicationQueries() as $query)
* {{ $query['connectionName'] ?? 'default' }} - {!! $query['sql'] !!} ({{ $query['time'] ?? 0 }} ms)
@endforeach
@else
No database queries detected.
@endif