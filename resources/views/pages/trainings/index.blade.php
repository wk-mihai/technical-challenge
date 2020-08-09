@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 py-2 trainings">
            <div class="page-title mb-3">
                <h1>{{ $pageTitle }}</h1>
            </div>
            <div class="page-content">
                @if($types->isNotEmpty())
                    <ul class="nav nav-tabs mb-2">
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() === route('trainings.index') ? 'active' : '' }}"
                               href="{{ route('trainings.index', request()->only('search')) }}">{{ __('All') }}</a>
                        </li>
                        @foreach($types as $type)
                            <li class="nav-item">
                                <a class="nav-link {{ url()->current() === route('trainings.index', $type->slug) ? 'active' : '' }}"
                                   href="{{ route('trainings.index', array_merge(request()->only('search'), ['type' => $type->slug])) }}">{{ $type->name }}
                                    ({{ $type->trainings_count }})</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="trainings-items">
                    @if($trainings->isNotEmpty())
                        @foreach($trainings as $training)
                            <a href="{{ route('trainings.show', $training->id) }}" class="training-item">
                                <h5 class="name">{{ $training->name }}</h5>
                                <div class="type">
                                    {{ __('Type') }}:
                                    <span>{{ isset($training->type) ? $training->type->name : '-' }}</span>
                                </div>
                                <div class="details">
                                    @if(isset($training->images_count))
                                        <div class="detail">
                                            <span class="detail-content">{{ $training->images_count }}&nbsp;</span>
                                            {{ __($training->images_count == 1 ? 'Images' : 'Image') }}
                                        </div>
                                    @endif
                                    @if(isset($training->videos_count))
                                        <div class="detail">
                                            <span class="detail-content">{{ $training->videos_count }}&nbsp;</span>
                                            {{ __($training->videos_count == 1 ? 'Videos' : 'Video') }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @else
                        <span>{{__('app.there_are_currently_no_records')}}.</span>
                    @endif
                </div>
                <div class="d-flex align-items-center justify-content-center mt-2">
                    {{ $trainings->onEachSide(0)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
