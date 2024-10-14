<?php

namespace App\Http\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Yajra\DataTables\Facades\DataTables;

trait BaseTrait
{
    function sendResponse($message,$data,$code = 200, $status = true){
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ],$code);
    }

    function sendSuccess($message,$code = 200){
        return response()->json([
            'status'  => true,
            'message' => $message
        ],$code);
    }

    function sendError($message, $code = 400){
        return response()->json([
            'status'  => false,
            'message' => $message
        ],$code);
    }

    function sendValidationError($data){
        return response()->json([
            'status'  => false,
            'error'    => $data
        ],400);
    }

}
