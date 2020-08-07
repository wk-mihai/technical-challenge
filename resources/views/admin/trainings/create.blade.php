@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $pageTitle }}</h4>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.trainings.store', 'files' => true]) !!}

                    @include('admin.trainings.includes.fields')

                    <div class="form-actions float-right">
                        <a class="btn btn-secondary"
                           href="{{ route('admin.trainings.index') }}">{{ __('app.back') }}</a>
                        {!! Form::submit(__('app.save'), ['class' => 'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
