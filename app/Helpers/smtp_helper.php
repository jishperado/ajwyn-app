<?php

/**
 * Send an email via direct SMTP with TLS (bypasses CI4 email library).
 * Uses stream_socket_client with SSL context to handle self-signed certs.
 *
 * @param string $to      Recipient email
 * @param string $subject Email subject
 * @param string $htmlBody HTML body content
 * @return bool
 */
function smtp_send(string $to, string $subject, string $htmlBody): bool
{
    $host     = 'mail.ajwyn.site';
    $port     = 587;
    $username = 'info@ajwyn.site';
    $password = 'Ajwyn@2026';
    $from     = 'info@ajwyn.site';
    $fromName = 'AJWYN';

    try {
        // SSL context to skip certificate verification (self-signed cert on mail server)
        $context = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
                'crypto_method'     => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
            ]
        ]);

        $socket = @stream_socket_client(
            "tcp://{$host}:{$port}",
            $errno, $errstr, 30,
            STREAM_CLIENT_CONNECT, $context
        );
        if (!$socket) {
            log_message('error', "SMTP connect failed: {$errstr} ({$errno})");
            return false;
        }
        _smtp_read($socket);

        _smtp_cmd($socket, "EHLO ajwyn.site");

        _smtp_cmd($socket, "STARTTLS");

        $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
        if (!$crypto) {
            $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        }
        if (!$crypto) {
            $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);
        }
        if (!$crypto) {
            log_message('error', 'SMTP TLS handshake failed');
            fclose($socket);
            return false;
        }

        _smtp_cmd($socket, "EHLO ajwyn.site");

        _smtp_cmd($socket, "AUTH LOGIN");
        _smtp_cmd($socket, base64_encode($username));
        $resp = _smtp_cmd($socket, base64_encode($password));
        if (strpos($resp, '235') === false) {
            log_message('error', 'SMTP AUTH failed: ' . trim($resp));
            fclose($socket);
            return false;
        }

        _smtp_cmd($socket, "MAIL FROM:<{$from}>");
        _smtp_cmd($socket, "RCPT TO:<{$to}>");
        _smtp_cmd($socket, "DATA");

        // Build proper email with headers
        $messageId = '<' . uniqid('ajwyn_', true) . '@ajwyn.site>';
        $boundary  = 'AJWYN_' . md5(uniqid());
        $date      = date('r');

        // Plain text version
        $plainText = strip_tags(str_replace(
            ['<br>', '<br/>', '<br />', '</p>', '</div>', '</h2>', '</h3>'],
            "\n", $htmlBody
        ));
        $plainText = html_entity_decode($plainText, ENT_QUOTES, 'UTF-8');
        $plainText = trim(preg_replace('/\n{3,}/', "\n\n", $plainText));

        $msg  = "Date: {$date}\r\n";
        $msg .= "From: {$fromName} <{$from}>\r\n";
        $msg .= "Reply-To: {$from}\r\n";
        $msg .= "To: {$to}\r\n";
        $msg .= "Subject: {$subject}\r\n";
        $msg .= "Message-ID: {$messageId}\r\n";
        $msg .= "X-Mailer: AJWYN-Mailer/1.0\r\n";
        $msg .= "List-Unsubscribe: <mailto:{$from}?subject=Unsubscribe>\r\n";
        $msg .= "MIME-Version: 1.0\r\n";
        $msg .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
        $msg .= "\r\n";
        $msg .= "--{$boundary}\r\n";
        $msg .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $msg .= "Content-Transfer-Encoding: quoted-printable\r\n";
        $msg .= "\r\n";
        $msg .= $plainText . "\r\n\r\n";
        $msg .= "--{$boundary}\r\n";
        $msg .= "Content-Type: text/html; charset=UTF-8\r\n";
        $msg .= "Content-Transfer-Encoding: quoted-printable\r\n";
        $msg .= "\r\n";
        $msg .= $htmlBody . "\r\n\r\n";
        $msg .= "--{$boundary}--\r\n";
        $msg .= ".\r\n";

        fwrite($socket, $msg);
        $resp = _smtp_read($socket);

        _smtp_cmd($socket, "QUIT");
        fclose($socket);

        $success = (strpos($resp, '250') !== false);
        if (!$success) {
            log_message('error', 'SMTP DATA rejected: ' . trim($resp));
        }
        return $success;

    } catch (\Throwable $e) {
        log_message('error', 'SMTP exception: ' . $e->getMessage());
        return false;
    }
}

/** @internal */
function _smtp_cmd($socket, string $cmd): string
{
    fwrite($socket, $cmd . "\r\n");
    return _smtp_read($socket);
}

/** @internal */
function _smtp_read($socket): string
{
    $data = '';
    while ($line = @fgets($socket, 515)) {
        $data .= $line;
        if (isset($line[3]) && $line[3] === ' ') break;
    }
    return $data;
}
