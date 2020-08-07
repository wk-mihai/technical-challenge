@if (session('message'))
    <div class="alert alert-info">
        <span><i class="fa fa-info-circle"></i> {{ session('message') }}</span>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        <span><i class="fa fa-check"></i> {{ session('success') }}</span>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning">
        <span><i class="fa fa-info-circle"></i> {{ session('warning') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <span><i class="fa fa-exclamation-triangle"></i> {{ session('error') }}</span>
    </div>
@endif

@if ($errors->count())
    <div class="alert alert-danger">
        <span><i class="fa fa-exclamation-triangle"></i> There was a problem performing this action. Please check and try
            again.</span>
        @foreach ($errors->all() as $error)
            <div class="mb-1">
                <span>{{ $error }}</span>
            </div>
        @endforeach
    </div>
@endif
