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
 * Start command
 */
class StartCommand extends NostickerCommand
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

        $chat_id = $message->getChat()->getId();
        $text = "*Nostickerbot* can *ban* unruly user!\nAdd me to a group or supergroup chat!"; 

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
            'parse_mode' => 'MARKDOWN',
        ];

        return Request::sendMessage($data);
    }
}
