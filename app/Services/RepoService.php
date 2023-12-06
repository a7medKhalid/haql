<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class RepoService
{

    private string $directory;
    private string $full_path;

    public function __construct($directory)
    {
        $this->directory = 'repos/' .  $directory;
        $this->full_path = Storage::path($this->directory);
        Log::write("debug", $this->full_path);

        //add this to repo

    }

    public function create(): bool|string|null
    {
        //create dir
        Storage::makeDirectory($this->directory);

        $full_path = Storage::path($this->directory);

        //git init
        $command = "cd " . escapeshellarg($full_path) . " && git init";
        $output = shell_exec($command);
        Log::write("debug", $output);
        Log::write("debug", $command);

        //add safe directory
        $command = "git config --global --add safe.directory " . escapeshellarg($full_path);
        $output = shell_exec($command);
        Log::write("debug", $output);
        Log::write("debug", $command);

        //create main branch
        $command = "cd " . escapeshellarg($full_path) . " && git checkout -b master";
        $output = shell_exec($command);
        Log::write("debug", $output);
        Log::write("debug", $command);

        //add first commit
        $command = "cd " . escapeshellarg($full_path) . " && echo 'initial' > initial.txt && git add . && git commit -m 'first commit'";
        $output = shell_exec($command);
        Log::write("debug", $output);
        Log::write("debug", $command);

        return $output;

    }

    public function uploadFiles($commit_name, $filesarray): string
    {
        //TODO: block all other operations while this is running

        $files = $filesarray['files[]'];
       //remove folder[] from array
        unset($filesarray['files[]']);
        $names = $filesarray;


        //create branch
        shell_exec("cd $this->full_path && git checkout -b $commit_name");
        Log::write("debug", "cd $this->full_path && git checkout -b $commit_name");


        //delete all files in branch
        shell_exec("cd $this->full_path && git rm -rf .");
        Log::write("debug", "cd $this->full_path && git rm -rf .");

        //upload files to branch

        $counter = 0;
        foreach ($files as $file) {

            $filename = $names[$counter];

            //remove folder name

            $filename = substr($filename, strpos($filename, '/') + 1);

            Storage::putFileAs($this->directory,$file, $filename );
            Log::write("debug", "$filename");
            $counter++;
        }

        //commit
        shell_exec("cd $this->full_path && git add . && git commit -m '$commit_name'");
        Log::write("debug", "cd $this->full_path && git add . && git commit -m '$commit_name'");

        //return to master
        shell_exec("cd $this->full_path && git checkout master");

        return $commit_name;
    }

    public function merge($branch_name): void
    {
        //merge branch to master
        shell_exec("cd $this->full_path && git merge $branch_name");
        Log::write("debug", "cd $this->full_path && git merge $branch_name");

        //later return if conflict

    }

    public function download($branch_name): string
    {
        //download branch
        shell_exec("cd $this->full_path && git checkout $branch_name");
//        Log::write("debug", "cd $this->full_path && git checkout $branch_name");

        // Define the zip file name and path
        $zipFileName = $branch_name . '.zip';
        $zipFilePath = storage_path($zipFileName);

        // Create a new Zip Archive instance
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $files = Storage::allFiles($this->directory);

            //exclude hidden files
            foreach ($files as $file) {

                // Check if any part of the path starts with a dot
                $parts = explode('/', $file);
                $containsHiddenSegment = false;

                foreach ($parts as $part) {
                    if (strpos($part, '.') === 0) {
                        $containsHiddenSegment = true;
                        break;
                    }
                }

                if ($containsHiddenSegment) {
                    continue; // Skip the file
                }

//                // Add each file to the zip
//                Log::write("debug", $file);
//                Log::write("debug", "hello");

                $zip->addFile(Storage::path($file), $file);
            }

            // Close the zip archive
            $zip->close();
        }

        //return to master
        shell_exec("cd $this->full_path && git checkout master");

        // Return the zip file
        return $zipFilePath;

    }


}
