<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class ChunkUploader extends Controller
{
    use BaseTrait;

    // Index Module
    public function index()
    {
        return view('jq-upload');
    }

    // Upload File In Chunk
    public function uploadChunk(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required|file'
        ]);

        if($validator->fails()){
            return $this->sendValidationError($validator->errors());
        }

        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return $this->saveFile($save->getFile());
        }

        $handler = $save->handler();

        return $this->sendResponse("Chunk has been uploaded successfully !",$handler);
    }

    protected function saveFile(UploadedFile $file){
        $fileName = $this->createFilename($file);
        $file->move(public_path('uploads'), $fileName);
        return $this->sendResponse("Chunk has been uploaded successfully !",[
            'path' => url("uploads/$fileName"),
            'name' => $fileName
        ]);
    }

    protected function createFilename(UploadedFile $file)
    {
        // Define a dynamic mapping of MIME types to file extensions
        $mimeToExtensionMap = [
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/ogg' => 'ogv',
            'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi',
            'video/x-flv' => 'flv',
            'video/x-matroska' => 'mkv',
            'video/x-ms-wmv' => 'wmv',
            'video/3gpp' => '3gp',
            'video/x-ms-asf' => 'asf',
            'video/x-m4v' => 'm4v',
            'video/x-mng' => 'mng',
            'video/x-ms-vob' => 'vob',
            'audio/mpeg' => 'mp3',
            'audio/wav' => 'wav',
            'audio/ogg' => 'ogg',
            'audio/midi' => 'mid',
            'audio/x-ms-wma' => 'wma',
            'audio/x-ms-wax' => 'wax',
            'audio/x-aac' => 'aac',
            'audio/x-flac' => 'flac',
            'audio/x-matroska' => 'mka',
            'audio/mp4' => 'm4a',
            'audio/amr' => 'amr',
            'audio/aiff' => 'aif',
            'audio/x-wavpack' => 'wv',
            'image/jpeg' => 'jpeg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'image/tiff' => 'tiff',
            'image/x-icon' => 'ico',
            'image/vnd.wap.wbmp' => 'wbmp',
            'image/jp2' => 'jp2',
            'image/vnd.ms-photo' => 'wdp',
            'image/x-png' => 'png',
            'application/zip' => 'zip',
            'application/x-7z-compressed' => '7z',
            'application/x-gzip' => 'gz',
            'application/x-tar' => 'tar',
            'application/x-bzip2' => 'bz2',
            'application/x-rar-compressed' => 'rar',
            'application/x-xz' => 'xz',
            'text/plain' => 'txt',
        ];

        $defaultExtension = 'txt';

        $mime = $file->getMimeType();

        $extension = $mimeToExtensionMap[$mime] ?? $defaultExtension;

        // Generate a unique filename
        $filename = "file_" . uniqid() . "." . $extension;

        return $filename;
    }

}
