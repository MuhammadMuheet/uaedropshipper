@extends('admin.layouts.app')
@section('title','Media Library')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Add New Product</h1>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="flex-lg-auto min-w-lg-300px">
        <div class="row mt-3">
            <div class="col-md-12 mt-3">
                <label for="upload_file" class="form-label">Product Gallery</label>
                <input type="file" id="createInput" multiple>
            </div>
        </div>
        <div class="row mt-4">
            @foreach($media as $file)
                <div class="col-md-3">
                    <img src="{{ asset('storage/' . $file->file_path) }}" class="img-thumbnail w-100 h-auto" >
                    <p>{{ $file->name }}</p>
                    <form action="{{ route('media.delete', $file->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview
            );

            const pond = FilePond.create(document.getElementById('createInput'), {
                allowMultiple: true,
                maxFiles: 25,
                labelIdle: 'Drag & Drop your images or <span class="filepond--label-action">Browse</span>',
                maxFileSize: '500MB',
                server: {
                    process: '{{ route("media.upload") }}',
                    revert: null,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });
        });
    </script>
@endpush
