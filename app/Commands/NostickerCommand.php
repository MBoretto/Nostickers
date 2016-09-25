<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\InlineKeyboardMarkup;
use App\Setting;

/**
 * Abstract System Command Class
 */
abstract class NostickerCommand extends Command
{

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
     * Print Settings
     *
     * @var int $chat_id
     * @return string
     */
    public function printSettings($chat_id)
    {
        $settings = Setting::find($chat_id);
		
        if ($settings == null) {
            $settings = Setting::firstOrCreate(['chat_id' => $chat_id]);
            $settings->updated_by = null;
            $settings->save();
		}

        $text = '';
        $text .= ' *Settings*: ' . "\n";
        $text .= ' Ban Sticker:  ' . $this->tickOrCross($settings->ban_sticker) . "\n";
        $text .= ' Ban Gif:  ' . $this->tickOrCross($settings->ban_gif) . "\n";
        $text .= ' Ban Voice:  ' . $this->tickOrCross($settings->ban_voice) . "\n";
        return $text;
    }

    /**
     * Return Keyboard
     *
     * @return mixed
     */
    public function printKeyboard()
    {
		$button1 = new InlineKeyboardButton(['text' => 'Sticker', 'callback_data' => 'sticker']);
		$button2 = new InlineKeyboardButton(['text' => 'Gif', 'callback_data' => 'gif']);
		$button3 = new InlineKeyboardButton(['text' => 'Voice', 'callback_data' => 'voice']);
        //$keyboard = [[$button1], [$button2], [$button3]];
        $keyboard = [[$button1, $button2, $button3]];
        return new InlineKeyboardMarkup(['inline_keyboard' => $keyboard,]);
    }

    /**
     * Return thick or cross
     *
     * @var int
     * @return string
     */
    protected function tickOrCross($is_true)
    {
        if ($is_true) {
            return '✔️';
		}
        return '✖️';
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
