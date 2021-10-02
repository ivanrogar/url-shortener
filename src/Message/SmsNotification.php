<?php

declare(strict_types=1);

namespace App\Message;

class SmsNotification
{
    private string $recipient;
    private string $content;

    public function __construct(string $recipient, string $content)
    {
        $this->recipient = $recipient;
        $this->content = $content;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
