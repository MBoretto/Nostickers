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

use Longman\TelegramBot\Commands\UserCommand;

/**
 * User "/whoami" command
 */
class WhoamiCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'whoami';
    protected $description = 'Show your id, name and username';
    protected $usage = '/whoami';
    protected $version = '1.0.1';
    protected $public = true;
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->telegram->executeCommand('Generic');
    }
}
