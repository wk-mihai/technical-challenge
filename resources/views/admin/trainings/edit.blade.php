@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $pageTitle }}</h4>
                    <div class="actions">
                        <a href="#confirm_delete" class="btn btn-sm btn-danger" role="button" data-toggle="modal">
                            <i class="fas fa-trash"></i> {{ __('app.delete') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::model($record, ['method' => 'patch', 'route' => ['admin.trainings.update', $record->id], 'files' => true]) !!}

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

    @include('admin.trainings.includes._delete_modal')

@endsection

@push('scripts')
    <script>
        new TrainingFiles()
    </script>
@endpush
