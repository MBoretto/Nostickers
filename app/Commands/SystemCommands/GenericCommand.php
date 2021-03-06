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
use App\Commands\NostickersCommand;

/**
 * Generic Command
 */
class GenericCommand extends NostickersCommand
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

        if ($chat->isPrivateChat() && $message->getType() == 'command') {
            $data = [];
            $data['chat_id'] = $chat_id;
            $data['text'] = 'Command not found.. ☹';
            return Request::sendMessage($data);
        }
        return Request::emptyResponse();
    }
}
