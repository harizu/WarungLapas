<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Order extends Model implements HasMedia
{
    use HasMediaTrait;

    const STATUS_CANCELED = -2;
    const STATUS_REJECTED = -1;
    const STATUS_NEW_ORDER = 0;
    const STATUS_ON_PROCESS = 1;
    const STATUS_COMPLETED = 2;

    public $table = 'orders';
    public $primaryKey = 'id';

    protected $fillable = [
        'order_no',
        'user_id',
        'warga_binaan_id',
        'total',
        'biaya_layanan',
        'expired_at',
        'created_at',
    ];

    protected $appends = [
        'order_complete_attachments',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wargaBinaan()
    {
        return $this->belongsTo(WargaBinaan::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview');
    }

    public function getTotalPembayaranAttribute()
    {
        return ($this->total + $this->biaya_layanan);
    }

    public function getIsExpiredAttribute()
    {
        return ($this->status == static::STATUS_NEW_ORDER && (time() >= strtotime($this->expired_at)));
    }

    public function getStatusNameAttribute()
    {
        if ($this->is_expired) {
            return trans('status.order.expired');
        }

        switch ($this->status) {
            case static::STATUS_CANCELED:
                return trans('status.order.canceled');
                break;

            case static::STATUS_REJECTED:
                return trans('status.order.rejected');
                break;

            case static::STATUS_NEW_ORDER:
                return trans('status.order.waitingConfirmation');
                break;

            case static::STATUS_ON_PROCESS:
                return trans('status.order.onProcess');
                break;

            case static::STATUS_COMPLETED:
                return trans('status.order.complete');
                break;

            default:
                return trans('status.order.unknown');
                break;
        }
    }

    public function getOrderCompleteAttachmentsAttribute()
    {
        return $this->getMedia('order_complete_attachments');
    }
}
