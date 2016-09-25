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

use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use App\Setting;
use App\Commands\NostickerCommand;

/**
 * Generic message command
 */
class GenericmessageCommand extends NostickerCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.2';
    protected $need_mysql = true;
    /**#@-*/

    /**
     * Execution if MySQL is required but not available
     *
     * @return boolean
     */
    public function executeNoDb()
    {
        //Do nothing
        return Request::emptyResponse();
    }

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat = $message->getChat();

        if ($chat->isPrivateChat()) {
            return Request::emptyResponse();
        }

        $chat_id = $chat->getId();
        $user = $message->getFrom();
        $user_id = $user->getId();

        //Please notice that i don't need to check if I am administrator
        //otherwise i will not receive generic messages from the group or supergroup chat
        $settings = Setting::find($chat_id);
        if ($message->getType() == 'Sticker' && $settings->ban_sticker) {
            $data = [];
            $data['chat_id'] = $chat_id;
            $data['text'] = 'Sticker not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";
            Request::sendMessage($data);
			return Request::kickChatMember(['chat_id' => $chat_id, 'user_id' => $user_id]);
        }

        if ($message->getType() == 'Document' && $settings->ban_voice) {
			if ($message->getDocument()->getMimeType() == 'video/mp4') {
                $data = [];
                $data['chat_id'] = $chat_id;
                $data['text'] = 'Gif not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";

                Request::sendMessage($data);
			    return Request::kickChatMember(['chat_id' => $chat_id, 'user_id' => $user_id]);
			}
        }

        if ($message->getType() == 'Voice' && $settings->ban_voice) {
            $data = [];
            $data['chat_id'] = $chat_id;
            $data['text'] = 'Voice not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";
            Request::sendMessage($data);
			return Request::kickChatMember(['chat_id' => $chat_id, 'user_id' => $user_id]);
        }
    }
}
