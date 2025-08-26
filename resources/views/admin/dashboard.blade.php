@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-4 col-12">
    <div class="small-box bg-white shadow-sm">
      <div class="inner">
        <h3>{{ \App\Models\User::count() }}</h3>
        <p>Total Users</p>
      </div>
      <div class="icon">
        <i class="fas fa-users"></i>
      </div>
      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-4 col-12">
    <div class="small-box bg-white shadow-sm">
      <div class="inner">
        <h3>{{ \App\Models\User::where('is_admin', true)->count() }}</h3>
        <p>Admins</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-shield"></i>
      </div>
      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-4 col-12">
    <div class="small-box bg-white shadow-sm">
      <div class="inner">
        <h3>{{ strtoupper(app()->environment()) }}</h3>
        <p>Environment</p>
      </div>
      <div class="icon">
        <i class="fas fa-server"></i>
      </div>
      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>
@endsection
