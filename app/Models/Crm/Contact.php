<?php

declare(strict_types=1);

namespace App\Models\Crm;

use App\Traits\HasActivities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\Crm\ContactSetAsPrimary;

class Contact extends Model
{
    use HasFactory, SoftDeletes, HasActivities;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'job_title',
        'department',
        'avatar',
        'notes',
        'is_primary',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the company that owns the contact.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the opportunities for the contact.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    /**
     * Get the tasks for the contact.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the full name of the contact.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Set as primary contact for the company.
     *
     * @return bool
     */
    public function setAsPrimary(): bool
    {
        // First, un-set any existing primary contacts
        $this->company->contacts()
            ->where('id', '!=', $this->id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);
        
        // Set this contact as primary
        $this->is_primary = true;
        $saved = $this->save(); // This will trigger the Observer's updated method
        
        // Dispatch event for other components to listen to
        if ($saved) {
            event(new ContactSetAsPrimary($this));
        }
        
        return $saved;
    }

    /**
     * Scope a query to only include primary contacts.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope a query to only include active contacts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 