@if(isset($files) && isset($type) && $files->isNotEmpty())
    <span class="view-all-files" data-toggle="modal" data-target="#all-{{ $type === 'video' ? 'videos' : 'images' }}">
        {{ __($type === 'video' ? 'app.view_all_videos' : 'app.view_all_images', ['count' => count($files)]) }}
    </span>

    <div class="modal fade files-modal {{ isset($readOnly) && $readOnly ? 'read-only' : '' }}"
         id="all-{{ $type === 'video' ? 'videos' : 'images' }}" tabindex="-1"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel">{{ __($type === 'video' ? 'Videos' : 'Images') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body files-wrap">
                    @foreach($files as $file)
                        <div class="file-item" data-id="{{ $file->id }}">
                            <a data-fancybox="{{ $type === 'video' ? 'video' : 'image' }}-gallery"
                               data-type="{{ $type === 'video' ? 'video' : 'image' }}" data-caption="{{ $file->name }}"
                               href="{{ $file->fullFileUrl }}">
                                <span class="file-preview"></span>
                            </a>
                            <div class="file-title" title="{{ __('app.select') }}">{{ $file->name }}</div>
                            {!! Form::checkbox('_delete_files[]', $file->id, null, ['class' => 'deleted-files-checkbox']) !!}
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('app.close') }}</button>
                    @if(!isset($readOnly) || !$readOnly)
                        <button type="button" class="btn btn-danger delete-files"
                                disabled>{{ __('app.confirm_selection') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
