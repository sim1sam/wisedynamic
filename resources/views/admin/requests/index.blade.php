@extends('adminlte::page')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Customer Requests</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.requests.index') }}">Requests</a></li>
          <li class="breadcrumb-item active">List</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">All Requests</h3>
            <div class="card-tools">
              <a href="{{ route('admin.requests.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Request</a>
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th style="width: 220px;">Customer</th>
                  <th>Page Name</th>
                  <th>Social Media</th>
                  <th>Budget (BDT)</th>
                  <th>Days</th>
                  <th>Post Link</th>
                  <th>Status</th>
                  <th>Created</th>
                  <th class="text-right" style="width: 240px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($requests as $req)
                  <tr>
                    <td>
                      <div class="font-weight-bold">{{ $req->user->name ?? 'N/A' }}</div>
                      <div class="text-muted small">{{ $req->user->email ?? '' }}</div>
                    </td>
                    <td>{{ $req->page_name }}</td>
                    <td>{{ $req->social_media }}</td>
                    <td>{{ number_format((float)$req->ads_budget_bdt, 2) }}</td>
                    <td>{{ $req->days }}</td>
                    <td>
                      @if($req->post_link)
                        <a href="{{ $req->post_link }}" target="_blank" rel="noopener">Open</a>
                      @else
                        -
                      @endif
                    </td>
                    <td>
                      <span class="badge badge-secondary text-uppercase">{{ \Illuminate\Support\Str::headline($req->status) }}</span>
                    </td>
                    <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-right">
                      <form method="POST" action="{{ route('admin.requests.status', $req) }}" class="form-inline justify-content-end">
                        @csrf
                        @method('PATCH')
                        <div class="input-group input-group-sm mr-2" style="width: 180px;">
                          <select name="status" class="form-control">
                            <option value="pending" @selected($req->status==='pending')>Pending</option>
                            <option value="in_progress" @selected($req->status==='in_progress')>In Progress</option>
                            <option value="done" @selected($req->status==='done')>Done</option>
                          </select>
                          <div class="input-group-append">
                            <button class="btn btn-info">Update</button>
                          </div>
                        </div>
                        <a href="{{ route('admin.requests.show', $req) }}" class="btn btn-default btn-sm mr-1"><i class="far fa-eye"></i></a>
                        <a href="{{ route('admin.requests.edit', $req) }}" class="btn btn-default btn-sm"><i class="far fa-edit"></i></a>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center text-muted py-5">No requests found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="card-footer clearfix">
            {{ $requests->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
