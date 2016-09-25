<?php
/**
 * This file is part of the nostikers bot.
 *
 * (c) Marco Boretto <marco.bore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nostk_settings';

    /**
     * The primarykey associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'chat_id'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['chat_id'];
}
