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
 * User "/slap" command
 */
class SlapCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'slap';
    protected $description = 'Slap someone with their username';
    protected $usage = '/slap <@user>';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->telegram->executeCommand('Generic');
    }
}
