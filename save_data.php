<?php

//PPPP   AAAAA  Y   Y  TTTTTT  OOOO   N   N
//P   P  A   A   Y Y     TT   O    O  NN  N
//PPPP   AAAAA    Y      TT   O    O  N N N
//P      A   A    Y      TT   O    O  N  NN
//P      A   A    Y      TT    OOOO   N   N

//Author: Michael Payton
//Project: Rumination

//Notes: 
//JSON saving to Alab server for miningtask.html

header('Content-Type: application/json');

// Read raw POST body
$raw = file_get_contents('php://input');

if (!$raw) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "No request body received"
    ]);
    exit;
}

// Parse JSON
$data = json_decode($raw, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => "Invalid JSON"
    ]);
    exit;
}

// Prefer filename from payload 
if (isset($data['file_name']) && !empty($data['file_name'])) {
    $filename = basename($data['file_name']); // prevents directory injection
} else {
    // fallback (should rarely happen)
    $participant_id = $data['prolific_pid'] ?? 'unknown';
    $task_version = $data['task_version'] ?? 'unknown';
    $submitted_at = date('Y-m-d_H-i-s');

    function safe_string($s) {
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', $s);
    }

    $safe_participant = safe_string($participant_id);
    $safe_version = safe_string($task_version);

    $filename = $submitted_at . '__' . $safe_participant . '__' . $safe_version . '.json';
}

// Make sure data directory exists
$data_dir = __DIR__ . '/data';
if (!is_dir($data_dir)) {
    if (!mkdir($data_dir, 0775, true)) {
        http_response_code(500);
        echo json_encode([
            "ok" => false,
            "error" => "Failed to create data directory"
        ]);
        exit;
    }
}

// Full path to file
$filepath = $data_dir . '/' . $filename;

// Write file and add file lock
$result = file_put_contents($filepath, $raw, LOCK_EX);

if ($result === false) {
    http_response_code(500);
    echo json_encode([
        "ok" => false,
        "error" => "Failed to write file"
    ]);
    exit;
}

echo json_encode([
    "ok" => true,
    "filename" => $filename,
    "bytes_written" => $result
]);
?>