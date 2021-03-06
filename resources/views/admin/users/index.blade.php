@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $pageTitle }}</h4>
                    <div class="actions">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.users.create') }}">
                            <i class="fas fa-plus"></i>
                            {{ __('app.add_new') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($records->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td>{{ $record->id }}</td>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ isset($record->role) ? $record->role->name: '-' }}</td>
                                        <td>{{ $record->email }}</td>
                                        <td class="text-center">
                                            @include('admin.partials.table-actions', ['showRoute' => 'admin.users.show', 'editRoute' => 'admin.users.edit'])
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @include('admin.partials.table-footer')
                    @else
                        <span>{{__('app.there_are_currently_no_records')}}. {!! linkToRoute('admin.users.create', __('app.create_a_new_record')) !!}.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
