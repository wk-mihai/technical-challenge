@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $pageTitle }}</h4>
                    <div class="actions">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.trainings.edit', $record->id) }}">
                            <i class="fas fa-pen"></i>
                            {{ __('Edit') }}
                        </a>
                        <a href="#confirm_delete" class="btn btn-sm btn-danger" role="button" data-toggle="modal">
                            <i class="fas fa-trash"></i> {{ __('app.delete') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">{{ __('Name') }}:</label>
                                    <p class="form-control-plaintext">{{ $record->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">{{ __('Type') }}:</label>
                                    <p class="form-control-plaintext">{{ $typeName }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">{{ __('Images') }}:</label>

                                    @if(isset($images))
                                        @include('admin.trainings.includes._files_modal', ['files' => $images, 'type' => 'image', 'readOnly' => true])
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">{{ __('Videos') }}:</label>

                                    @if(isset($videos))
                                        @include('admin.trainings.includes._files_modal', ['files' => $videos, 'type' => 'video', 'readOnly' => true])
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">{{ __('Content') }}:</label>
                                    <p class="form-control-plaintext">{!! $record->content !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions float-right">
                            <a class="btn btn-secondary"
                               href="{{ route('admin.trainings.index') }}">{{ __('app.back') }}</a>
                        </div>
                    </form>
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
