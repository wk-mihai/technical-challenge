@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 py-2 training-page">
            <div class="page-title mb-2">
                <h1>{{ $pageTitle }}</h1>
            </div>
            <div class="page-content">
                @if(!empty($training->content))
                    <div class="content">{!! $training->content !!}</div>
                @endif
                @if($training->files->isNotEmpty())
                    @foreach($training->files as $file)
                        <div class="file-wrap">
                            <div class="name">{{ $file->name }}</div>
                            <div class="file">
                                @if($file->type === 'image')
                                    <img src="{{ $file->fullFileUrl }}" alt="{{ $file->name }}">
                                @else
                                    <video src="{{ $file->fullFileUrl }}" height="500" controls></video>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
