<?php

#use  Hekmatinasser\Verta\Facades\Verta;


return [

    #    'adaptForDatabase' => [
    #        'persian_datepicker' => function ($value) {
    #            $v = Verta($value / 1000);
    #            return $v->formatGregorian('Y-m-d H:i:s');
    #        }
    #    ],


    'image' => [
        'adaptForDatabase' => function ($value, $crud = null) {

            if ($value == $crud->row->avatar)
                return $crud->row->avatar;

            $disk = 'public';

            $destination_path = "/uploads";

            if ($value == null) {
                \Storage::disk($disk)->delete($crud->row->avatar);
            }


            if (Str::startsWith($value, 'data:image')) {
                $image = \Image::make($value)->encode('jpg', 90);

                $filename = md5($value . time()) . '.jpg';

                \Storage::disk($disk)->put($destination_path . '/' . $filename, $image->stream());

                \Storage::disk($disk)->delete($crud->row->avatar);

                return ($destination_path . '/' . $filename);

            }
        }
    ]

];
