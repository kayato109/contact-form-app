<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'detail',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'contact_tag');
    }

    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            1 => '男性',
            2 => '女性',
            default => 'その他',
        };
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getCategoryNameAttribute(): ?string
    {
        return optional($this->category)->content;
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', "%{$filters['keyword']}%")
                    ->orWhere('last_name', 'like', "%{$filters['keyword']}%")
                    ->orWhere('email', 'like', "%{$filters['keyword']}%");
            });
        }

        if (! empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['date'])) {
            $start = Carbon::parse($filters['date'], 'Asia/Tokyo')
                ->startOfDay()
                ->timezone('UTC');

            $end = Carbon::parse($filters['date'], 'Asia/Tokyo')
                ->endOfDay()
                ->timezone('UTC');

            $query->whereBetween('created_at', [$start, $end]);
        }

        return $query;
    }

    public function toCsvRow(): array
    {
        return [
            $this->id,
            $this->full_name,
            $this->gender_label,
            $this->email,
            $this->tel,
            $this->address,
            $this->building,
            $this->category_name,
            $this->detail,
            $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
