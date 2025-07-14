<?php
use Illuminate\Support\Facades\Storage;

header('Content-Type: text/plain');

$logFile = storage_path('logs/laravel.log');

if (file_exists($logFile)) {
    echo file_get_contents($logFile);
} else {
    echo "Le fichier de log n'existe pas ou n'est pas accessible.";
}
