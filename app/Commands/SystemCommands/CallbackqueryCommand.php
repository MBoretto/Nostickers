<?php
/**
 * This file is part of the nostikers bot.
 *
 * (c) Marco Boretto <marco.bore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\InlineKeyboardMarkup;
use App\Commands\NostickersCommand;
use App\Setting;

/**
 * Callback query command
 */
class CallbackqueryCommand extends NostickersCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'callbackquery';
    protected $description = 'Reply to callback query';
    protected $version = '1.0.0';
    protected $need_mysql = true;
    /**#@-*/

    /**
     * Execution if MySQL is required but not available
     *
     * @return boolean
     */
    public function executeNoDb()
    {
        $data = [];
        $data['chat_id'] = $message->getChat()->getId();
        $data['message_id'] = $message->getMessageId();
        $data['parse_mode'] = 'MARKDOWN';
        $data['text'] = "Temporary down for maintenance. We'll be back soon!";
        return Request::editMessageText($data);
    }
    
    /**
     * Let you respond to an inline query, catch the exception if you are on timeout
     *
     * @int $callback_query_id
     * @string string $text
     * @bool bool $alert
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse|mixed
     */
    protected function answerCallbackQuery($callback_query_id, $text = '', $alert = false)
    {
        $data = [];
        $data['callback_query_id'] = $callback_query_id;
        $data['text'] = $text;
        $data['show_alert'] = $alert;
        try {
            return Request::answerCallbackQuery($data);
        } catch (TelegramException $e) {
            //this  will be executed if the answer to the callback query will take too much time
            // in this way the editing of the message will go on without breaks
            return Request::emptyResponse();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $callback_query = $update->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data = $callback_query->getData();
        $message = $callback_query->getMessage();

        $user = $callback_query->getFrom();
        $user_id = $user->getId();

        //Coming from an chat message ie chat_id defined
        $chat = $message->getChat();
        $chat_id = $chat->getId();

        if (!$this->iAmAdmin($chat_id)) {
            $this->answerCallbackQuery($callback_query_id);
            $data = [];
            $data['chat_id'] = $chat_id;
            $data['message_id'] = $message->getMessageId();
            $data['parse_mode'] = 'MARKDOWN';
            $data['text'] = '*Nostickers* must be an administrator!';
            return Request::editMessageText($data);
        }

        if (!$this->isAdmin($chat_id, $user_id)) {
            return $this->answerCallbackQuery($callback_query_id, 'Only admins can edit settings', true);
        }

        $settings = Setting::find($chat_id);
        $set_to = null;
        if ($callback_data == 'sticker') {
            $settings->ban_sticker = !$settings->ban_sticker;
            $set_to = $settings->ban_sticker;
        } elseif ($callback_data == 'gif') {
            $settings->ban_gif = !$settings->ban_gif;
            $set_to = $settings->ban_gif;
        } elseif ($callback_data == 'voice') {
            $settings->ban_voice = !$settings->ban_voice;
            $set_to = $settings->ban_voice;
        }
        $settings->updated_by = $user_id;
        $settings->save();

        $action = 'disabled';
        if ($set_to) {
            $action = 'enabled';
        }

        $callback_text = ucfirst($callback_data) . ' ban ' . $action .'!';

        $this->answerCallbackQuery($callback_query_id, $callback_text, true);

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['message_id'] = $message->getMessageId();
        $data['parse_mode'] = 'MARKDOWN';
        $data['text'] = $this->printSettings($chat_id);
        $data['reply_markup'] = $this->printKeyboard();
        return Request::editMessageText($data);
    }
}
