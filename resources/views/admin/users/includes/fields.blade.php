<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ __('Name') }}<span class="required">*</span></label>
            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control' . ($errors->first('name') ? ' is-invalid' : '')] ) !!}
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="roles">{{ __('Roles') }}<span class="required">*</span></label>
            {!! Form::select('role_id', $roles, null, ['id' => 'roles', 'class' => 'form-control']) !!}
            @error('role_id')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">{{ __('Email') }}<span class="required">*</span></label>
            {!! Form::text('email', null, ['id' => 'email', 'class' => 'form-control' . ($errors->first('email') ? ' is-invalid' : '')] ) !!}
            @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">
                {{ __('Password') }}
                @if(!isset($record))
                    <span class="required">*</span>
                @endif
            </label>
            {!! Form::password('password', ['id' => 'password', 'class' => 'form-control' . ($errors->first('password') ? ' is-invalid' : ''), 'aria-describedby' => 'passwordHelpBlock'] ) !!}
            <small id="passwordHelpBlock" class="form-text text-muted">
                {{ __('app.leave_empty_password') }}
            </small>
            @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="repeat_password">
                {{ __('Repeat password') }}
                @if(!isset($record))
                    <span class="required">*</span>
                @endif
            </label>
            {!! Form::password('repeat_password', ['id' => 'repeat_password', 'class' => 'form-control' . ($errors->first('repeat_password') ? ' is-invalid' : '')] ) !!}
            @error('repeat_password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
</div>
