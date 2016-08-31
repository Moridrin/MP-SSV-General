<?php
if (!defined('ABSPATH')) {
    exit;
}

class Message
{
    const NOTIFICATION_MESSAGE = 'notification';
    const ERROR_MESSAGE = 'error';

    public $message;
    public $type;

    /**
     * Message constructor.
     *
     * @param string $message is the
     * @param string $type
     */
    public function __construct($message, $type = Message::NOTIFICATION_MESSAGE)
    {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * @return string with the message.
     */
    public function __toString()
    {
        return $this->message;
    }
}
