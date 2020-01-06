@extends('layouts.app')

@section('title', 'PageAnalyzer')

@section('content')
    <form method="POST" action="{!! route('domains.store') !!}">
        <div class="form-row align-items-center">
            <div class="col-sm-6">
                <input type="url" name="url" class="form-control mb-2" placeholder="Enter url" >
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-2">Submit</button>
            </div>
        </div>
    </form>
@endsection
