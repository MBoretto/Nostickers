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

use App\Commands\NostickersCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 */
class HelpCommand extends NostickersCommand
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
        $data['disable_web_page_preview'] = true;
        $text = '';
        $text .= '💣 *Nostickers* ' . "\n";
        $text .= 'I can ban users that send stickers, gif or voice.' . "\n";
        $text .= 'Add me in a group or supergroup chat as adminstrator! ' . "\n";
        $text .= '' . "\n";
        $text .= '*Commands*:' . "\n";
        $text .= '/settings - shows and sets options' . "\n";
        $text .= '/help - need help?' . "\n";
        $text .= '' . "\n";
        $text .= 'Rate me 5 ⭐️ on ';
        $text .= '[Storebot](telegram.me/storebot?start=nostickersbot)!' . "\n";
        $text .= 'Take a look at my code on [Github](https://www.github.com/MBoretto/Nostickers)!';

        $data['text'] = $text;

        return Request::sendMessage($data);
    }
}
