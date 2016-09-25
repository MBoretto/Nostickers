<?php
/**
 * This file is part of the nostikers bot.
 *
 * (c) Marco Boretto <marco.bore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * Am I admin
     *
     * @var int $chat_id
     * @return bool
     */
    public function amIAdmin($chat_id)
    {
		$result = Request::getChatAdministrators(['chat_id' => $chat_id]);

        foreach ($result->getResult() as $administrator) {
            if ($administrator->getStatus() == 'administrator') {
                if (strtolower($administrator->getUser()->getUsername()) == strtolower($this->getTelegram()->getBotName())) {
                    return 1;
                }
			}
        }
        return 0;
    }

    /**
     * is admin
     *
     * @var int $chat_id
     * @var int $user_id
     * @return bool
     */
    public function isAdmin($chat_id, $user_id)
    {
		$result = Request::getChatAdministrators(['chat_id' => $chat_id]);

        foreach ($result->getResult() as $administrator) {
            if ($administrator->getUser()->getId() == $user_id) {
                return 1;
            }
        }
        return 0;
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
