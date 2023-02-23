<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanvassAbstractItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'canvass_abstracts_id',
        'quotation_items_id',
    ];

    public function canvass_abstract()
    {
        return $this->belongsTo(CanvassAbstract::class, 'canvass_abstracts_id', 'id');
    }

    public function quotation_item()
    {
        return $this->hasOne(QuotationItem::class, 'id', 'quotation_items_id');
    }
}
