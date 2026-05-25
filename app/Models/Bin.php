<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Bin extends Model
{
    use LogsActivity;

    protected $fillable = [
        'aisle_id',
        'name',
        'barcode',
    ];

    protected static function booted(): void
    {
        static::creating(function (Bin $bin) {
            $bin->barcode ??= static::generateBarcode();
        });
    }

    public function aisle(): BelongsTo
    {
        return $this->belongsTo(Aisle::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['aisle_id', 'name', 'barcode'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    private static function generateBarcode(): string
    {
        do {
            $barcode = 'BIN-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (static::where('barcode', $barcode)->exists());

        return $barcode;
    }
}
