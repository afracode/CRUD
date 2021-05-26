@php
    $result = [];
      if($crud->row) {
         $medias = $crud->row->getMedia($field['name']);
          foreach ($medias as $media)
              $result[] = ([
                  "name" => $media->name,
                  "size" => $media->size,
                  "url" => $media->getUrl(),
                  "type" => $media->type,
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
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        alert("{{trans('message.deleted')}}")
                    }
                };
                xhttp.open("GET", '/crud/deleteMedia/' + file.name, true);
                xhttp.send();
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedMediableMap[file.name]
                }
                $('form').find('input[name="' + '{{$field['name']}}[]' + '"][value="' + name + '"]').remove()
            };


            let uploadedMediableMap = {}






            function video(file, fieldName) {
                // var html;
                var src = file.url; ///video url not youtube or vimeo,just video on server
                var video = document.createElement('video');
                video.src = src;
                video.width = 120;
                // video.height = 120;

                // var canvas = document.createElement('canvas');
                // canvas.width = 360;
                // canvas.height = 240;
                // var context = canvas.getContext('2d');

                // video.addEventListener('loadeddata', function () {
                //     context.drawImage(video, 0, 0, canvas.width, canvas.height);
                //     var dataURI = canvas.toDataURL('image/jpeg');
                //     html += '<figure>';
                //     html += '<img src="' + dataURI + '' + '" alt="' + 'salam' + '" />';
                //     html += '<figurecaption>' + 'salam' + '</figurecaption>'
                //     html += '</figure>';
                // });

                let div_preview = document.createElement('div');
                div_preview.setAttribute('class', 'dz-preview dz-complete dz-image-preview');
                let div_remove = document.createElement('div');
                div_remove.setAttribute('class', 'dz-remove');
                let a_remove = document.createElement('a');
                a_remove.onclick = function (){
                    removeMedia(file);
                };
                a_remove.innerHTML = "Remove file"
                div_remove.appendChild(a_remove)

                let div_video = document.createElement('div');
                let icon = document.createElement('a');
                icon.href = file.url;
                icon.setAttribute('class', 'video-icon ');
                icon.innerHTML = "play"
                let div = document.createElement('div');
                div.setAttribute('class', 'wrap-video ');
                div.appendChild(video);
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
                padding: 12px 9px !important;
                border-radius: 4px;
                text-removeMediagn: center;
                color: white;
                background: #333;
                opacity: .8 !important;
                position: absolute;
                left: 38px;
                top: 35px;
                font-size: 12px;
                cursor: pointer !important;
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
                    video(mockFile , "{{$field['name']}}")
                } else {
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, mockFile.url);
                    this.emit("complete", mockFile);
                }
                @endforeach
            }
        }
    </script>
@endpush
