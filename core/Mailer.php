<?php

declare(strict_types=1);

namespace Core;

final class Mailer
{
    public static function send(string $to, string $subject, string $message, array $mailConfig): bool
    {
        $headers = 'From: ' . $mailConfig['from_name'] . ' <' . $mailConfig['from_email'] . ">\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $sent = mail($to, $subject, $message, $headers);

        if (!$sent) {
            $line = sprintf("[%s] TO:%s | SUBJECT:%s | BODY:%s\n", date('c'), $to, $subject, $message);
            file_put_contents(__DIR__ . '/../storage/mail.log', $line, FILE_APPEND);
        }

        return $sent;
    }
}
