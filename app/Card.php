<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function decks()
    {
        return $this->belongsTo('App\Card');
    }
}
