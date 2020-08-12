@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 py-2 training-page">
            <div class="page-title mb-2">
                <h1>{{ $pageTitle }}</h1>
            </div>
            <div class="page-content">
                <div class="info-wrap mb-2 border-bottom d-inline-block py-2">
                    <div class="info">
                        Type: <span class="font-weight-bold">{{ $training->type->name }}</span>
                    </div>
                    <div class="info">
                        Files: <span class="font-weight-bold">{{ $training->files->count() }}</span>
                    </div>
                </div>
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
