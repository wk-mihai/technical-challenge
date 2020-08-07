@if($records instanceof \Illuminate\Pagination\LengthAwarePaginator)
    @if ($records->lastPage() > 1)
        <div class="row align-items-center table-controls">
            <div class="col-sm-12 col-md-5">
                {{ __('app.showing_paginated_records', ['first' => $records->currentPage(), 'last' => $records->lastPage(), 'total' => $records->total()]) }}
            </div>
            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-end">
                {{ $records->onEachSide(0)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @else
        {{ __('app.showing_all') }} {{ $records->total() }} {{ strtolower(__('app.records')) }}
    @endif
@else
    {{ __('app.showing_all') }} {{ $records->count() }} {{ strtolower(__('app.records')) }}
@endif
