<?php

namespace App\Http\Controllers\Admin;

use UniSharp\LaravelFilemanager\Controllers\LfmController;

class FileManagerController extends LfmController
{
    public function show()
    {
        $data['pageTitle'] = __('File Manager');
        $data['activeFileManager'] = 'active';
        return view('laravel-filemanager::index', $data)
            ->withHelper($this->helper);
    }
}
