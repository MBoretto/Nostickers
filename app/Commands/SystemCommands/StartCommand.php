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
 * Start command
 */
class StartCommand extends NostickersCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'start';
    protected $description = 'Start command';
    protected $usage = '/start';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();

        $link_parameters = trim($message->getText(true));

        $chat = $message->getChat();
        $chat_id = $chat->getId();

        if ($chat->isPrivateChat()) {
            $data = [];
            $data['chat_id'] = $chat_id;
            $data['parse_mode'] = 'MARKDOWN';
            $data['text'] = 'Add *Nostickers* to a group or supergroup as administrator! ';
            return Request::sendMessage($data);
        }

        if (!$this->iAmAdmin($chat_id)) {
            $data = [];
            $data['chat_id'] = $chat_id;
            $data['parse_mode'] = 'MARKDOWN';
            $data['text'] = '*Nostickers* must be an administrator!';
            return Request::sendMessage($data);
        }

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'MARKDOWN';
        $data['text'] = '💣 *Nostickers* is active in this chat! Type /settings to know how you can be banned!';
        return Request::sendMessage($data);
    }
}
