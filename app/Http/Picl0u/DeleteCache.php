<?php

namespace App\Http\Picl0u;

use File;
use Nwidart\Modules\Facades\Module;
use Spatie\PartialCache\PartialCacheFacade;

trait DeleteCache
{
    public function flush(string $name, $id)
    {
        $module = Module::find($name);
        if($module){
            $path = $module->getPath() . '/' . 'Resources' . '/' . 'views' . '/' . config('ikCommerce.cacheFolder');
            if(file_exists($path)){
                foreach (File::files($path) as $file) {
                    $fileName = $name."::" .
                        config('ikCommerce.cacheFolder') . "." .
                        str_replace(".blade.php","",$file->getFilename());
                    PartialCacheFacade::forget($fileName, $id);
                    foreach(config('ikCommerce.languages') as $lang) {
                        PartialCacheFacade::forget($fileName, $id . "_" . $lang);
                    }
                }
            }
        }

    }


}