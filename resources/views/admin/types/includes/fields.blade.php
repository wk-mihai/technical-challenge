<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="name">{{ __('Name') }}<span class="required">*</span></label>
            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control' . ($errors->first('name') ? ' is-invalid' : '')] ) !!}
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="slug">{{ __('Slug') }}<span class="required">*</span></label>
            {!! Form::text('slug', null, ['id' => 'slug', 'class' => 'form-control' . ($errors->first('slug') ? ' is-invalid' : '')] ) !!}
            @error('slug')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
</div>
