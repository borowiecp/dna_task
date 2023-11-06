<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $merchantId
 * @property string $name
 * @method static Builder|Merchant query()
 */
class Merchant extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchants';

    /**
     * The primary key associated with the table.
     *
     * @var int
     */
    protected $primaryKey = 'id';

    protected $fillable = ['merchantId', 'name'];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public $timestamps = false;
}
