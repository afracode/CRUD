@php
    $result = [];
      if($crud->row) {
         $medias = $crud->row->getMedia($field['name']);
          foreach ($medias as $media)
              $result[] = ([
                  "name" => $media->name,
                  "size" => $media->size,
                  "url" => $media->getUrl(),
                  "type" => $pieces = explode("/", $media->mime_type)[0],
              ]);


  }
$id = 'mediable-'.$field['name'];
$method = 'mediable'.ucfirst($field['name']);

@endphp


<div class="form-group">
    <div class="needsclick dropzone" id="{{$id}}">
    </div>
</div>



@if ($crud->notLoaded($field))
    @push('fields_scripts')

        <script src="/js/dropzone.min.js"></script>

        <script>

            let removeMedia = function (file) {
                let result = confirm("آیا مطمین هستید؟");
                if (!result) {
                    return true;
                }
                var xhttp = new XMLHttpRequest();
                xhttp.open("GET", '/crud/deleteMedia/' + file.name, true);
                xhttp.send();
                // file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedMediableMap[file.name]
                }
                $('form').find('input[name="' + '{{$field['name']}}[]' + '"][value="' + name + '"]').remove();

                location.reload();
                return true;
            };


            let uploadedMediableMap = {}


            function preview_video(file, fieldName) {
                var src = file.url;
                var video = document.createElement('video');
                video.src = src;
                video.width = 120;
                let div_preview = document.createElement('div');
                div_preview.setAttribute('class', 'dz-preview dz-complete dz-image-preview');
                let div_remove = document.createElement('div');
                div_remove.setAttribute('class', 'dz-remove');
                let a_remove = document.createElement('a');
                a_remove.onclick = function () {
                    removeMedia(file);
                };
                a_remove.innerHTML = "Remove file"
                div_remove.appendChild(a_remove)

                let icon = document.createElement('a');
                icon.href = file.url;
                icon.setAttribute('class', 'video-icon ');
                icon.innerHTML = '<i class="cil-video"></i>';
                let div = document.createElement('div');
                div.setAttribute('class', 'wrap-video ');
                div.appendChild(video);
                div.appendChild(icon);
                div_preview.appendChild(div);
                div_preview.appendChild(div_remove);
                $("#mediable-" + fieldName).append(div_preview);
            }

            function preview_image(file, fieldName) {
                var src = file.url;
                var img = document.createElement('img');
                img.src = src;
                img.width = 120;
                let div_preview = document.createElement('div');
                div_preview.setAttribute('class', 'dz-preview dz-complete dz-image-preview');
                let div_remove = document.createElement('div');
                div_remove.setAttribute('class', 'dz-remove');
                let a_remove = document.createElement('a');
                a_remove.onclick = function () {
                    removeMedia(file);
                };
                a_remove.innerHTML = "Remove file"
                div_remove.appendChild(a_remove)

                let icon = document.createElement('a');
                icon.href = file.url;
                icon.setAttribute('class', 'video-icon ');
                icon.innerHTML = '<i class="cil-image"></i>'
                let div = document.createElement('div');
                div.setAttribute('class', 'wrap-video ');
                div.appendChild(img);
                div.appendChild(icon);
                div_preview.appendChild(div);
                div_preview.appendChild(div_remove);
                $("#mediable-" + fieldName).append(div_preview);
            }

            function preview_audio(file, fieldName) {
                var src = file.url;
                let div_preview = document.createElement('div');
                div_preview.setAttribute('class', 'dz-preview dz-complete dz-image-preview');
                let div_remove = document.createElement('div');
                div_remove.setAttribute('class', 'dz-remove');
                let a_remove = document.createElement('a');
                a_remove.onclick = function () {
                    removeMedia(file);
                };
                a_remove.innerHTML = "Remove file"
                div_remove.appendChild(a_remove)

                let div_video = document.createElement('div');
                let icon = document.createElement('a');
                icon.href = file.url;
                icon.setAttribute('class', 'video-icon ');
                icon.innerHTML = '<i class="cil-music-note"></i>'
                let div = document.createElement('div');
                div.setAttribute('class', 'wrap-video ');
                div.appendChild(icon);
                div_preview.appendChild(div);
                div_preview.appendChild(div_remove);


                $("#mediable-" + fieldName).append(div_preview);
            }
        </script>

    @endpush

    @push('fields_css')
        <link href="/js/dropzone.min.css" rel="stylesheet">
        <style>
            .wrap-video {
                display: inline-block;
                position: relative;
                padding: 0px !important;
                margin: 0px !important;
            }

            .video-icon {
                background: red;
                padding: 12px !important;
                text-removeMediagn: center;
                color: white;
                background: rgba(0, 0, 0, .3);
                position: absolute;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                cursor: pointer !important;
                text-align: center;
            }

            .video-icon i {
                font-size: 30px;
                position: absolute;
                top: 15px;
                right: 15px;
                color: white !important;
            }

            .wrap-video {
                background: black;
                border-radius: 20px;
                overflow: hidden;
                width: 120px;
                height: 120px;
                position: relative;
            }


        </style>
    @endpush
@endif


@push('fields_scripts')
    <script>
        Dropzone.options['{{$method}}'] = {
            url: '{{ route('crud.storeMedia') }}',
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="' + '{{$field['name']}}[]' + '" value="' + response.name + '">')
                uploadedMediableMap[file.name] = response.name
            },
            removedfile: removeMedia,
            init: function () {
                let mockFile = '';
                @foreach($result as $media)
                    mockFile = {!! json_encode($media) !!};

                if (mockFile.type == "video") {
                    preview_video(mockFile, "{{$field['name']}}")
                } else if (mockFile.type == "audio") {
                    preview_audio(mockFile, "{{$field['name']}}")
                } else {
                    preview_image(mockFile, "{{$field['name']}}")
                    // this.emit("addedfile", mockFile);
                    // this.emit("thumbnail", mockFile, mockFile.url);
                    // this.emit("complete", mockFile);
                }
                @endforeach
            }
        }
    </script>
@endpush
