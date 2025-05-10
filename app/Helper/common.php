<?php
use Illuminate\Support\Facades\Log;

if (!function_exists('errorResponse')) {
    function errorResponse($message = null, $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $code);
    }
}

if (!function_exists('validateResponse')) {
    function validateResponse($errors, $code = 422)
    {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $errors
        ], $code);
    }
}

if (!function_exists('successResponse')) {
    function successResponse($message = null, $data = [], $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            ...$data
        ], $code);
    }
}


if (!function_exists('logExceptionDetail')) {
    function logExceptionDetail(Throwable $exception): void
    {
        $trace = $exception->getTrace()[0] ?? null;
        $class = $trace['class'] ?? 'N/A';
        $function = $trace['function'] ?? 'N/A';
        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();

        // Format the log message with newlines for readability
        $formattedMessage = "Exception caught\n";
        $formattedMessage .= "Message: " . $message . "\n";
        $formattedMessage .= "Class: " . $class . "\n";
        $formattedMessage .= "Function: " . $function . "\n";
        $formattedMessage .= "File: " . $file . "\n";
        $formattedMessage .= "Line: " . $line . "\n\n";

        // Log the formatted message
        Log::error($formattedMessage);
    }
}

