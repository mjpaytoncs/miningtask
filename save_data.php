<?php
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

// Pull useful identifiers
$participant_id = $data['participant_id'] ?? 'unknown';
$study_id = $data['study_id'] ?? 'unknown';
$session_id = $data['session_id'] ?? uniqid('session_', true);
$task_version = $data['task_version'] ?? 'unknown';
$submitted_at = date('Y-m-d_H-i-s');

// Sanitize filename parts
function safe_string($s) {
    return preg_replace('/[^a-zA-Z0-9_-]/', '_', $s);
}

$safe_participant = safe_string($participant_id);
$safe_study = safe_string($study_id);
$safe_session = safe_string($session_id);
$safe_version = safe_string($task_version);

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

// Build filename
$filename = $submitted_at . '__' . $safe_participant . '__' . $safe_study . '__' . $safe_session . '__' . $safe_version . '.json';
$filepath = $data_dir . '/' . $filename;

// Write file
$result = file_put_contents($filepath, $raw);

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