<?php
// Direct SMTP email test - delete this file after testing
header('Content-Type: text/plain');

$host     = 'mail.ajwyn.site';
$port     = 587;
$username = 'info@ajwyn.site';
$password = 'Ajwyn@2026';
$from     = 'info@ajwyn.site';
$to       = 'jish@domoimate.com';

echo "=== SMTP Email Test ===\n\n";

// Step 1: Connect
echo "1. Connecting to $host:$port...\n";
$socket = @fsockopen($host, $port, $errno, $errstr, 30);
if (!$socket) {
    die("FAILED to connect: $errstr ($errno)\n");
}
$resp = readSmtp($socket);
echo "   Server: $resp\n";

// Step 2: EHLO
echo "2. Sending EHLO...\n";
$resp = sendSmtp($socket, "EHLO ajwyn.site");
echo "   Response: " . substr($resp, 0, 100) . "\n";

// Step 3: STARTTLS
echo "3. Starting TLS...\n";
$resp = sendSmtp($socket, "STARTTLS");
echo "   Response: $resp\n";

$crypto = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
if (!$crypto) {
    // Try broader TLS
    $crypto = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
}
echo "   TLS: " . ($crypto ? "OK" : "FAILED") . "\n";
if (!$crypto) die("TLS negotiation failed!\n");

// Step 4: EHLO again
echo "4. EHLO after TLS...\n";
$resp = sendSmtp($socket, "EHLO ajwyn.site");
echo "   Response: " . substr($resp, 0, 200) . "\n";

// Step 5: AUTH
echo "5. AUTH LOGIN...\n";
sendSmtp($socket, "AUTH LOGIN");
sendSmtp($socket, base64_encode($username));
$resp = sendSmtp($socket, base64_encode($password));
echo "   Auth: $resp\n";
if (strpos($resp, '235') === false) {
    die("AUTH FAILED!\n");
}

// Step 6: MAIL FROM
echo "6. MAIL FROM...\n";
$resp = sendSmtp($socket, "MAIL FROM:<$from>");
echo "   Response: $resp\n";

// Step 7: RCPT TO
echo "7. RCPT TO <$to>...\n";
$resp = sendSmtp($socket, "RCPT TO:<$to>");
echo "   Response: $resp\n";

// Step 8: DATA
echo "8. Sending DATA...\n";
sendSmtp($socket, "DATA");

$msg  = "From: AJWYN <$from>\r\n";
$msg .= "To: $to\r\n";
$msg .= "Subject: AJWYN Promo Test from Server\r\n";
$msg .= "MIME-Version: 1.0\r\n";
$msg .= "Content-Type: text/html; charset=UTF-8\r\n";
$msg .= "\r\n";
$msg .= "<div style='font-family:Arial;padding:20px'><h2 style='color:#0B61D6'>Test Promo Email</h2><p>This email was sent directly from the AJWYN server via PHP SMTP.</p></div>\r\n";
$msg .= ".\r\n";

fwrite($socket, $msg);
$resp = readSmtp($socket);
echo "   Response: $resp\n";

// Step 9: QUIT
sendSmtp($socket, "QUIT");
fclose($socket);

if (strpos($resp, '250') !== false) {
    echo "\n=== SUCCESS! Email sent to $to ===\n";
} else {
    echo "\n=== FAILED at DATA stage ===\n";
}

function sendSmtp($socket, $cmd) {
    fwrite($socket, $cmd . "\r\n");
    return readSmtp($socket);
}

function readSmtp($socket) {
    $data = '';
    while ($line = @fgets($socket, 515)) {
        $data .= $line;
        if (isset($line[3]) && $line[3] === ' ') break;
    }
    return trim($data);
}
