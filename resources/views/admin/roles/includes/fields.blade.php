<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="name">{{ __('Name') }}<span class="required">*</span></label>
            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control' . ($errors->first('name') ? ' is-invalid' : '')] ) !!}
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        @if(!isset($record) || !$record->isAdmin())
            <div class="form-group">
                <label for="slug">{{ __('Slug') }}<span class="required">*</span></label>
                {!! Form::text('slug', null, ['id' => 'slug', 'class' => 'form-control' . ($errors->first('slug') ? ' is-invalid' : '')] ) !!}
                @error('slug')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="types">{{ __('Training types') }}<span class="required">*</span></label>
                {!! Form::select('types[]', $types, null, ['id' => 'types', 'class' => 'form-control select2', 'multiple', 'data-placeholder' => __('Select...')]) !!}
                @error('types')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        @else
            <div class="form-group">
                <label for="slug">{{ __('Slug') }}</label>
                {!! Form::text('slug', $record->slug, ['id' => 'slug', 'class' => 'form-control', 'readonly'] ) !!}
            </div>
        @endif
    </div>
</div>
