<?php

/**
 * Laravel Application Entry Point
 * 
 * This file redirects all requests to the public folder
 * when the Laravel project is deployed in the root directory
 */

// Check if we're already in the public directory
if (basename(__DIR__) === 'public') {
    // We're in public, load the Laravel application
    require_once __DIR__.'/index.php';
} else {
    // We're in root, redirect to public
    $publicPath = __DIR__ . '/public';
    
    if (is_dir($publicPath)) {
        // Get the request URI
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove any existing /public/ from the URI to avoid double redirects
        $requestUri = preg_replace('#^/public/#', '/', $requestUri);
        
        // If it's the root request, load public/index.php directly
        if ($requestUri === '/' || $requestUri === '') {
            require_once $publicPath . '/index.php';
            exit;
        }
        
        // For other requests, check if file exists in public
        $filePath = $publicPath . $requestUri;
        
        if (file_exists($filePath) && is_file($filePath)) {
            // Serve static files directly
            $mimeType = mime_content_type($filePath);
            header('Content-Type: ' . $mimeType);
            readfile($filePath);
            exit;
        } else {
            // Let Laravel handle the routing
            require_once $publicPath . '/index.php';
            exit;
        }
    } else {
        // Public directory doesn't exist
        http_response_code(500);
        echo "Error: Laravel public directory not found. Please ensure your Laravel application is properly installed.";
        exit;
    }
}