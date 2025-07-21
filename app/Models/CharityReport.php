<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharityReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'charity_id',
        'report_type',
        'title',
        'description',
        'data',
        'file_path',
        'file_type',
        'report_date',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
        'report_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the charity that owns the report.
     */
    public function charity(): BelongsTo
    {
        return $this->belongsTo(User::class, 'charity_id');
    }

    /**
     * Get the report data as an array.
     */
    public function getReportDataAttribute(): array
    {
        return $this->data ?? [];
    }

    /**
     * Check if the report has been exported.
     */
    public function isExported(): bool
    {
        return $this->status === 'exported';
    }

    /**
     * Check if the report is archived.
     */
    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    /**
     * Get the file extension based on file type.
     */
    public function getFileExtensionAttribute(): string
    {
        return match($this->file_type) {
            'pdf' => '.pdf',
            'excel' => '.xlsx',
            'csv' => '.csv',
            default => '',
        };
    }

    /**
     * Scope to get reports by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Scope to get reports by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get reports within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
    }

    /**
     * Scope to get reports by charity.
     */
    public function scopeByCharity($query, $charityId)
    {
        return $query->where('charity_id', $charityId);
    }
}
