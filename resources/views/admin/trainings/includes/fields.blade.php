<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->first('name') ? 'is-invalid' : '' }}">
            <label for="name">{{ __('Name') }}<span class="required">*</span></label>
            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control' . ($errors->first('name') ? ' is-invalid' : '')] ) !!}
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="types">{{ __('Types') }}<span class="required">*</span></label>
            {!! Form::select('type_id', $types, null, ['id' => 'types', 'class' => 'form-control']) !!}
            @error('type_id')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="images">{{ __('Images') }}</label>
            {!! Form::file('images[]', ['id' => 'images', 'class' => 'form-control-file', 'multiple', 'accept' => 'image/x-png,image/gif,image/jpeg']) !!}
            @error('images')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
            @enderror

            @if(isset($images))
                @include('admin.trainings.includes._files_modal', ['files' => $images, 'type' => 'image'])
            @endif
        </div>
        <div class="form-group">
            <label for="videos">{{ __('Videos') }}</label>
            {!! Form::file('videos[]', ['id' => 'videos', 'class' => 'form-control-file', 'multiple', 'accept' => 'video/mp4,video/x-m4v,video/mov']) !!}
            @error('videos')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
            @enderror

            @if(isset($videos))
                @include('admin.trainings.includes._files_modal', ['files' => $videos, 'type' => 'video'])
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="content">{{ __('Content') }}</label>
            {!! Form::textarea('content', null, ['id' => 'content', 'class' => 'form-control tiny']) !!}
            @error('content')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
</div>
