<?php
/**
 * This file is part of the nostikers bot.
 *
 * (c) Marco Boretto <marco.bore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Commands\NostickerCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 */
class HelpCommand extends NostickerCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'help';
    protected $description = 'Show the Help';
    protected $usage = '/help';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $user = $message->getFrom();

        $chat_id = $message->getChat()->getId();
        $user_id = $user->getId();

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'MARKDOWN';
        $text = '';
        $text .= ' *Nostickers* ' . "\n";
        $text .= ' _Law in crowded groups_ ' . "\n";
        $text .= '' . "\n";
        $text .= '*Commands*:' . "\n";
        $text .= '/settings - shows and sets options' . "\n";
        $text .= '/help - need help?' . "\n";
        $text .= '' . "\n";
        $text .= 'Please rate us 5 ⭐️!' . "\n";
        $text .= 'telegram.me/storebot?start=nostickersbot';

        $data['text'] = $text;

        return Request::sendMessage($data);
    }
}
