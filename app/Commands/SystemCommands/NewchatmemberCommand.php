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

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * New chat member command
 */
class NewchatmemberCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Newchatmember';
    protected $description = 'New Chat Member';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $participant = $message->getNewChatMember();
        $chat_id = $message->getChat()->getId();
        $member = $message->getNewChatMember();

        if (strtolower($participant->getUsername()) == strtolower($this->getTelegram()->getBotName())) {
            $text = 'Hello!';
        } else {
            $text = 'Hi ' . $participant->tryMention() . '!';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
