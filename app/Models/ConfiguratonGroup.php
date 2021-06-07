<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguratonGroup extends Model
{
    use HasFactory;

    protected $table = 'configuration_group';

    protected $fillable = [
        'configuration_group_title',
        'configuration_group_description',
        'sort_order',
        'visible',
    ];
}
