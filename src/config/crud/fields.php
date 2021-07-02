<?php

#use  Hekmatinasser\Verta\Facades\Verta;


return [

    # 'persian_datepicker' => [
    #    'beforeSet' => function ($input) {
    #        $v = Verta($input->value / 1000);
    #        return $v->formatGregorian('Y-m-d H:i:s');
    #    'beforeGet' => function ($datetime) {
    #        return strtotime($datetime) * 1000;
    #    }
    # ],


    'image' => [
        'beforeSet' => function ($input, $crud = null) {


            if ($crud->row && $input->value == $crud->row->{$input->key})
                return $crud->row->{$input->key};

            $disk = 'public';

            $destination_path = "/uploads";

            if ($input->value == null) {
                \Storage::disk($disk)->delete($crud->row->{$input->value});
            }


            if (Str::startsWith($input->value, 'data:image')) {
                $image = \Image::make($input->value)->encode('jpg', 90);

                $filename = md5($input->value . time()) . '.jpg';

                \Storage::disk($disk)->put($destination_path . '/' . $filename, $image->stream());

                if ($crud->row)
                    \Storage::disk($disk)->delete($crud->row->{$input->key});

                return ($destination_path . '/' . $filename);

            }
        }
    ]

];
