<?php

declare(strict_types=1);

namespace App\Service\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $trickPictureDirectory;
    private string $userPictureDirectory;
    private SluggerInterface $slugger;
    private Filesystem $filesystem;

    public const TRICK_PICTURE_DIRECTORY = 'trickPicture';
    public const USER_PICTURE_DIRECTORY = 'userPicture';

    public function __construct(string $trickPictureDirectory, string $userPictureDirectory, SluggerInterface $slugger, Filesystem $filesystem)
    {
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
        $this->trickPictureDirectory = $trickPictureDirectory;
        $this->userPictureDirectory = $userPictureDirectory;
    }

    public function upload(UploadedFile $file, string $pictureType): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory($pictureType), $fileName);
        } catch (FileException $e) {
            dd($e);
        }

        return str_replace('/var/www/snow/public/', '', $this->getTargetDirectory($pictureType) . DIRECTORY_SEPARATOR . $fileName);
    }

    public function remove(string $fileName, string $pictureType)
    {
        $this->filesystem->remove($this->getTargetDirectory($pictureType) . DIRECTORY_SEPARATOR . $fileName);
    }

    public function getTargetDirectory(string $pictureType): string
    {
        if (self::USER_PICTURE_DIRECTORY === $pictureType) {
            return $this->userPictureDirectory;
        }

        return $this->trickPictureDirectory;
    }
}
