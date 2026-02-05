<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OpenFeederController extends Controller
{
    /**
     * Show links to files in public/assets/template_import
     */
    public function index()
    {
        $dir = public_path('assets/template_import');
        $files = [];

        if (File::isDirectory($dir)) {
            $entries = File::files($dir);
            foreach ($entries as $file) {
                $filename = $file->getFilename();
                // remove extension, replace underscore with space, and convert to Title Case
                $nameOnly = pathinfo($filename, PATHINFO_FILENAME);
                $display = str_replace('_', ' ', $nameOnly);
                $display = ucwords(strtolower($display));

                $files[] = [
                    'name' => $filename,
                    'display_name' => $display,
                    'url'  => asset('assets/template_import/' . $filename),
                    'size' => $file->getSize(),
                    'modified' => date('d M Y H:i', $file->getMTime()),
                ];
            }

            usort($files, function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
        }

        return view('openfeeder.index', compact('files'));
    }
}
