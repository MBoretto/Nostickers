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
 * User "/settings" command
 */
class SettingsCommand extends NostickerCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'settings';
    protected $description = 'Show change SettingsCommand';
    protected $usage = '/settings';
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

        //if ($chat->isPrivateChat()) {
        //    $data = [];
        //    $data['chat_id'] = $chat_id;
        //    $data['parse_mode'] = 'MARKDOWN';
        //    $data['text'] = 'Add *Nostickersbot* to a group or supergroup as administrator! ';
        //    return Request::sendMessage($data);
        //}


        $data = [];
        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'MARKDOWN';
        $data['text'] = $this->printSettings($chat_id);
        $data['reply_markup'] = $this->printKeyboard();

        return Request::sendMessage($data);
    }
}
