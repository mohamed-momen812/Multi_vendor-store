<?php

namespace App\Helpers\Controllers;


class UplodeImage
{
    public static function StoreImageAndGettingPass($request) {
        if(!$request->hasFile('image')) {
            return ;
        }

        $file = $request->file('image');

        $path = $file->store('uploads', [
            'disk'=> 'public',
        ]);

        return $path;
    }
}


