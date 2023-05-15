<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(private SluggerInterface $slugger) {}
    public function upload(UploadedFile $file, string $directory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $fileName = strtolower($fileName);
        //dd($fileName);
        try {
            $file->move($directory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            $e->getMessage();
        }
        return $fileName;
    }
    public function uploads(UploadedFile $file, string $directory): array
    {
        $vid = ['mp4', 'avi', 'flv', 'mpeg', '3gp', 'webm', 'm4v', 'mov', 'mkv', 'wmv'];
        $is_vid = false;
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid();

        if (in_array($file->guessExtension(), $vid)) { // C'est une video
            $is_vid = true;
            $fileName = $fileName.'.webm';
        } else $fileName = $fileName.'.webp';

        $fileName = strtolower($fileName);
        try {
            $file->move($directory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            $e->getMessage();
        }
        return [$fileName, $is_vid];
    }
}