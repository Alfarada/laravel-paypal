@extends('welcome')

@section('content')
    <div class="card">
    <div class="card-body">
      @if (session('status'))
          <h3>
              {{ session('status') }}
          </h3>
      @endif
    </div>
  </div>
  
@endsection