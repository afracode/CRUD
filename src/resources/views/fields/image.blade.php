@php


        $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
        $field['wrapper']['class'] = $field['wrapper']['class'] ?? "form-group col-sm-12";
        $field['wrapper']['class'] = $field['wrapper']['class'].' cropperImage';
        $field['wrapper']['data-aspectRatio'] = $field['aspect_ratio'] ?? 0;
        $field['wrapper']['data-crop'] = $field['crop'] ?? false;
        $field['wrapper']['data-field-name'] = $field['wrapper']['data-field-name'] ?? $field['name'];
        $field['wrapper']['data-init-function'] = $field['wrapper']['data-init-function'] ?? 'bpFieldInitBase64CropperImageElement';

       $field['filename'] =  isset($field['filename']) ? $field['filename'] : null;

        if (!is_null(old($field['name']))) {
            $value = old($field['name']);
        }  else {
            $value = $field['value'] ?? $field['default'] ?? '';
        }







@endphp

@include('crud::fields.inc.wrapper_start')
<div>
    @include('crud::fields.inc.translatable_icon')
</div>
<!-- Wrap the image or canvas element with a block element (container) -->
<div class="row">
    <div class="col-sm-6" data-handle="previewArea" style="margin-bottom: 20px;">
        <img data-handle="mainImage" src="">
    </div>
    @if(isset($field['crop']) && $field['crop'])
        <div class="col-sm-3" data-handle="previewArea">
            <div class="docs-preview clearfix">
                <div class="img-preview preview-lg">
                    <img src=""
                         style="display: block; min-width: 0px !important; min-height: 0px !important; max-width: none !important; max-height: none !important; margin-left: -32.875px; margin-top: -18.4922px; transform: none;">
                </div>
            </div>
        </div>
    @endif
    <input type="hidden" class="hiddenFilename" name="{{ $field['filename'] }}" value="">
</div>
<div class="btn-group">
    <div class="btn btn-light btn-sm btn-file">
        {{ trans('crud.choice_file') }} <input type="file" accept="image/*"
                                               data-handle="uploadImage" @include('crud::fields.inc.attributes', ['default_class' => 'hide'])>
        <input type="hidden" data-handle="hiddenImage" name="{{ $field['name'] }}" value="{{ $value }}">
    </div>
    @if(isset($field['crop']) && $field['crop'])
        <button class="btn btn-light btn-sm" data-handle="rotateLeft" type="button" style="display: none;"><i
                class="la la-rotate-left"></i></button>
        <button class="btn btn-light btn-sm" data-handle="rotateRight" type="button" style="display: none;"><i
                class="la la-rotate-right"></i></button>
        <button class="btn btn-light btn-sm" data-handle="zoomIn" type="button" style="display: none;"><i
                class="la la-search-plus"></i></button>
        <button class="btn btn-light btn-sm" data-handle="zoomOut" type="button" style="display: none;"><i
                class="la la-search-minus"></i></button>
        <button class="btn btn-light btn-sm" data-handle="reset" type="button" style="display: none;"><i
                class="la la-times"></i></button>
    @endif
    <button class="btn btn-light btn-sm" data-handle="remove" type="button"><i class="la la-trash"></i></button>
</div>


@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif




@if (true)
    @push('fields_css')

        <link rel="stylesheet" type="text/css"
              href="http://127.0.0.1:8001/packages/backpack/base/css/bundle.css?v=4.1.18@8109ca0d308e47fb24c8baa8bd9893e21a187214">
        <link rel="stylesheet" type="text/css"
              href="http://127.0.0.1:8001/packages/source-sans-pro/source-sans-pro.css?v=4.1.18@8109ca0d308e47fb24c8baa8bd9893e21a187214">
        <link rel="stylesheet" type="text/css"
              href="http://127.0.0.1:8001/packages/line-awesome/css/line-awesome.min.css?v=4.1.18@8109ca0d308e47fb24c8baa8bd9893e21a187214">


        <link rel="stylesheet" href="http://127.0.0.1:8001/packages/backpack/crud/css/crud.css">
        <link rel="stylesheet" href="http://127.0.0.1:8001/packages/backpack/crud/css/form.css">
        <link rel="stylesheet" href="http://127.0.0.1:8001/packages/backpack/crud/css/create.css">

        <!-- CRUD FORM CONTENT - crud_fields_styles stack -->
        <link href="http://127.0.0.1:8001/packages/cropperjs/dist/cropper.min.css" rel="stylesheet" type="text/css"/>


        <link href="{{ asset('packages/cropperjs/dist/cropper.min.css') }}" rel="stylesheet" type="text/css"/>
        <style>
            .hide {
                display: none;
            }

            .image .btn-group {
                margin-top: 10px;
            }

            img {
                max-width: 100%; /* This rule is very important, please do not ignore this! */
            }

            .img-container, .img-preview {
                width: 100%;
                text-align: center;
            }

            .img-preview {
                float: left;
                margin-right: 10px;
                margin-bottom: 10px;
                overflow: hidden;
            }

            .preview-lg {
                width: 263px;
                height: 148px;
            }

            .btn-file {
                position: relative;
                overflow: hidden;
            }

            .btn-file input[type=file] {
                position: absolute;
                top: 0;
                right: 0;
                min-width: 100%;
                min-height: 100%;
                font-size: 100px;
                text-align: right;
                filter: alpha(opacity=0);
                opacity: 0;
                outline: none;
                background: white;
                cursor: inherit;
                display: block;
            }
        </style>
    @endpush
    @push('fields_scripts')




        <script src="{{ asset('packages/cropperjs/dist/cropper.min.js') }}"></script>
        <script src="{{ asset('packages/jquery-cropper/dist/jquery-cropper.min.js') }}"></script>



        <script>
            function bpFieldInitBase64CropperImageElement(element) {
                // Find DOM elements under this form-group element
                var $mainImage = element.find('[data-handle=mainImage]');
                var $uploadImage = element.find("[data-handle=uploadImage]");
                var $hiddenImage = element.find("[data-handle=hiddenImage]");
                var $hiddenFilename = element.find(".hiddenFilename");
                var $rotateLeft = element.find("[data-handle=rotateLeft]");
                var $rotateRight = element.find("[data-handle=rotateRight]");
                var $zoomIn = element.find("[data-handle=zoomIn]");
                var $zoomOut = element.find("[data-handle=zoomOut]");
                var $reset = element.find("[data-handle=reset]");
                var $remove = element.find("[data-handle=remove]");
                var $previews = element.find("[data-handle=previewArea]");
                // Options either global for all image type fields, or use 'data-*' elements for options passed in via the CRUD controller
                var options = {
                    viewMode: 2,
                    checkOrientation: false,
                    autoCropArea: 1,
                    responsive: true,
                    preview: element.find('.img-preview'),
                    aspectRatio: element.attr('data-aspectRatio')
                };
                var crop = element.attr('data-crop');

                // Hide 'Remove' button if there is no image saved
                if (!$hiddenImage.val()) {
                    $previews.hide();
                    $remove.hide();
                }
                // Make the main image show the image in the hidden input
                $mainImage.attr('src', $hiddenImage.val());

                // Only initialize cropper plugin if crop is set to true
                if (crop) {

                    $remove.click(function () {
                        $mainImage.cropper("destroy");
                        $mainImage.attr('src', '');
                        $hiddenImage.val('');
                        if (filename == "true") {
                            $hiddenFilename.val('removed');
                        }
                        $rotateLeft.hide();
                        $rotateRight.hide();
                        $zoomIn.hide();
                        $zoomOut.hide();
                        $reset.hide();
                        $remove.hide();
                        $previews.hide();
                    });
                } else {

                    $remove.click(function () {
                        $mainImage.attr('src', '');
                        $hiddenImage.val('');
                        $hiddenFilename.val('removed');
                        $remove.hide();
                        $previews.hide();
                    });
                }

                //Set hiddenFilename field to 'removed' if image has been removed.
                //Otherwise hiddenFilename will be null if no changes have been made.

                $uploadImage.change(function () {
                    var fileReader = new FileReader(),
                        files = this.files,
                        file;

                    if (!files.length) {
                        return;
                    }
                    file = files[0];

                    if (/^image\/\w+$/.test(file.type)) {
                        $hiddenFilename.val(file.name);
                        fileReader.readAsDataURL(file);
                        fileReader.onload = function () {
                            $uploadImage.val("");
                            $previews.show();
                            if (crop) {
                                $mainImage.cropper(options).cropper("reset", true).cropper("replace", this.result);
                                // update the hidden input after selecting a new item or cropping
                                $mainImage.on('ready cropstart cropend', function () {
                                    var imageURL = $mainImage.cropper('getCroppedCanvas').toDataURL(file.type);
                                    $hiddenImage.val(imageURL);
                                    return true;
                                });
                                $rotateLeft.click(function () {
                                    $mainImage.cropper("rotate", 90);
                                });
                                $rotateRight.click(function () {
                                    $mainImage.cropper("rotate", -90);
                                });
                                $zoomIn.click(function () {
                                    $mainImage.cropper("zoom", 0.1);
                                });
                                $zoomOut.click(function () {
                                    $mainImage.cropper("zoom", -0.1);
                                });
                                $reset.click(function () {
                                    $mainImage.cropper("reset");
                                });
                                $rotateLeft.show();
                                $rotateRight.show();
                                $zoomIn.show();
                                $zoomOut.show();
                                $reset.show();
                                $remove.show();

                            } else {
                                $mainImage.attr('src', this.result);
                                $hiddenImage.val(this.result);
                                $remove.show();
                            }
                        };
                    } else {
                        new Noty({
                            type: "error",
                            text: "<strong>Please choose an image file</strong><br>The file you've chosen does not look like an image."
                        }).show();
                    }
                });
            }
        </script>

        <script>
            function initializeFieldsWithJavascript(container) {
                var selector;
                if (container instanceof jQuery) {
                    selector = container;
                } else {
                    selector = $(container);
                }
                selector.find("[data-init-function]").not("[data-initialized=true]").each(function () {
                    var element = $(this);
                    var functionName = element.data('init-function');

                    if (typeof window[functionName] === "function") {
                        window[functionName](element);

                        // mark the element as initialized, so that its function is never called again
                        element.attr('data-initialized', 'true');
                    }
                });
            }

            jQuery('document').ready(function ($) {

                // trigger the javascript for all fields that have their js defined in a separate method
                initializeFieldsWithJavascript('form');


                // Save button has multiple actions: save and exit, save and edit, save and new
                var saveActions = $('#saveActions'),
                    crudForm = saveActions.parents('form'),
                    saveActionField = $('[name="save_action"]');

                saveActions.on('click', '.dropdown-menu a', function () {
                    var saveAction = $(this).data('value');
                    saveActionField.val(saveAction);
                    crudForm.submit();
                });

                // Ctrl+S and Cmd+S trigger Save button click
                $(document).keydown(function (e) {
                    if ((e.which == '115' || e.which == '83') && (e.ctrlKey || e.metaKey)) {
                        e.preventDefault();
                        $("button[type=submit]").trigger('click');
                        return false;
                    }
                    return true;
                });

                // prevent duplicate entries on double-clicking the submit form
                crudForm.submit(function (event) {
                    $("button[type=submit]").prop('disabled', true);
                });

                // Place the focus on the first element in the form

                var focusField = $('form').find('input, textarea, select').not('[type="hidden"]').eq(0),

                    fieldOffset = focusField.offset().top,
                    scrollTolerance = $(window).height() / 2;

                focusField.trigger('focus');

                if (fieldOffset > scrollTolerance) {
                    $('html, body').animate({scrollTop: (fieldOffset - 30)});
                }

                // Add inline errors to the DOM

                $("a[data-toggle='tab']").click(function () {
                    currentTabName = $(this).attr('tab_name');
                    $("input[name='current_tab']").val(currentTabName);
                });

                if (window.location.hash) {
                    $("input[name='current_tab']").val(window.location.hash.substr(1));
                }

            });
        </script>


    @endpush
@endif

