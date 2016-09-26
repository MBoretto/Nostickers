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
use App\Commands\NostickersCommand;

/**
 * Generic message command
 */
class GenericmessageCommand extends NostickersCommand
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

        //Avoid to do a Query
        if (!($this->isSticker($message) || $this->isGif($message) || $this->isVoice($message))) {
            return Request::emptyResponse();
        }

        $chat_id = $chat->getId();
        $user = $message->getFrom();
        $user_id = $user->getId();

        $do_ban = false;
        $data = [];
        $data['parse_mode'] = 'MARKDOWN';
        //Please notice that i don't need to check if I am administrator
        //otherwise i will not receive generic messages from the group or supergroup chat
        $settings = Setting::find($chat_id);
        if ($this->isSticker($message) && $settings->ban_sticker) {
            $do_ban = true;
            $data['text'] = '💣 *Sticker* not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";
        }

        if ($this->isGif($message) && $settings->ban_gif) {
            $do_ban = true;
            $data['text'] = '💣 *Gif* not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";
        }

        if ($this->isVoice($message) && $settings->ban_voice) {
            $do_ban = true;
            $data['text'] = '💣 *Voice* not allowed.. ' . ucfirst($user->tryMention()) . " has been banned!";
        }

        if ($do_ban) {
            $data['chat_id'] = $chat_id;
            if ($this->isAdmin($chat_id, $user_id)) {
                //Can't do ban disableing restictions
                Request::sendSticker(['chat_id' => $chat_id, 'sticker' => 'BQADAwADeAEAAr-MkATYNlXgh_QaCwI']);
                $settings->ban_sticker = 0;
                $settings->ban_gif = 0;
                $settings->ban_voice = 0;
                $settings->save();
                $data['text'] = 'The admin ' . ucfirst($user->tryMention()) . ' broke the contract!' . "\n" . '💣 Users are free to share *any* content!';
                return Request::sendMessage($data);
            }

            Request::sendMessage($data);
            return Request::kickChatMember(['chat_id' => $chat_id, 'user_id' => $user_id]);
        }

        return Request::emptyResponse();
    }

    /**
     * isSticker
     *
     * @var object $message
     * @return bool
     */
    public function isSticker($message)
    {
        return $message->getType() == 'Sticker';
    }

    /**
     * isGif
     *
     * @var object $message
     * @return bool
     */
    public function isGif($message)
    {
        return $message->getType() == 'Document' && $message->getDocument()->getMimeType() == 'video/mp4';
    }

    /**
     * isVoice
     *
     * @var object $message
     * @return bool
     */
    public function isVoice($message)
    {
        return $message->getType() == 'Voice';
    }
}
