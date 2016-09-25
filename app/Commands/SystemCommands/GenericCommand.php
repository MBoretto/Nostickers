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
use App\Commands\NostickerCommand;

/**
 * Generic Command
 */
class GenericCommand extends NostickerCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Generic';
    protected $description = 'Handle generic commands or is executed by default when a command is not found';
    protected $usage = '/';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat = $message->getChat();
        $chat_id = $chat->getId();
        $user = $message->getFrom();
        $user_id = $user->getId();

        //if (!$chat->isPrivateChat()) {
            if ($message->getType() == 'Sticker') {
                $data = [];
                $data['chat_id'] = $chat_id;
                $data['text'] = 'Sticker not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";
                Request::sendMessage($data);
				return Request::kickChatMember(['chat_id' => $chat_id, 'user_id' => $user_id]);
            }

            if ($message->getType() == 'Voice') {
                $data = [];
                $data['chat_id'] = $chat_id;
                $data['text'] = 'Voice not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";
                Request::sendMessage($data);
				return Request::kickChatMember(['chat_id' => $chat_id, 'user_id' => $user_id]);
            }

            if ($message->getType() == 'Document') {
		    	if ($message->getDocument()->getMimeType() == 'video/mp4') {
                    $data = [];
                    $data['chat_id'] = $chat_id;
                    $data['text'] = 'Gif not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";

                    Request::sendMessage($data);
				    return Request::kickChatMember(['chat_id' => $chat_id, 'user_id' => $user_id]);
		    	}
            }
        //}

        if ($chat->isPrivateChat() && $message->getType() == 'command') {

            $data = [];
            $data['chat_id'] = $chat_id;
            $data['text'] = 'Command not found.. ☹';
            return Request::sendMessage($data);
        }
        return Request::emptyResponse();
    }
}
