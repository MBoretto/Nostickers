<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\InlineKeyboardMarkup;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * Abstract System Command Class
 */
abstract class NostickerCommand extends Command
{
    
    /**
     * User Conversation 
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    protected $conversation = null;

    /**
     * Execution if MySQL is required but not available
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function executeNoDb()
    {
        //Preparing message
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $data = [
            'parse_mode' => 'MARKDOWN',
            'chat_id' => $chat_id,
            'text'    => "Temporary down for maintenance. We'll be back soon!",
            ];
        return Request::sendMessage($data);
    }

    /**
     * A system command just executes
     *
     * Although system commands should just work and return a successful ServerResponse,
     * each system command can override this method to add custom functionality.
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function emptyResponse()
    {
        //System command, return empty ServerResponse
        return Request::emptyResponse();
    }
}
