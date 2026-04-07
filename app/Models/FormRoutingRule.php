<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormRoutingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_type',
        'region',
        'sector',
        'institution_id',
        'priority_order',
        'is_active',
        'conditions',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'conditions' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the institution for this routing rule.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Check if rule is active.
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Check if rule matches the given criteria.
     */
    public function matches($formType, $region = null, $sector = null)
    {
        // Check if form type matches
        if ($this->form_type !== $formType) {
            return false;
        }

        // Check if region matches (if specified in rule)
        if ($this->region && $region && $this->region !== $region) {
            return false;
        }

        // Check if sector matches (if specified in rule)
        if ($this->sector && $sector && $this->sector !== $sector) {
            return false;
        }

        // Check additional conditions if any
        if ($this->conditions) {
            foreach ($this->conditions as $condition) {
                if (!$this->evaluateCondition($condition, $formType, $region, $sector)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Evaluate a condition against the given parameters.
     */
    private function evaluateCondition($condition, $formType, $region, $sector)
    {
        // This can be extended to support more complex conditions
        switch ($condition['type']) {
            case 'min_investment_amount':
                return isset($condition['value']) && is_numeric($condition['value']);
            case 'specific_sector':
                return isset($condition['value']) && $condition['value'] === $sector;
            case 'region_priority':
                return isset($condition['value']) && $condition['value'] === $region;
            default:
                return true;
        }
    }

    /**
     * Scope for active rules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for rules by form type.
     */
    public function scopeByFormType($query, $formType)
    {
        return $query->where('form_type', $formType);
    }

    /**
     * Scope for rules by region.
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope for rules by sector.
     */
    public function scopeBySector($query, $sector)
    {
        return $query->where('sector', $sector);
    }

    /**
     * Scope for rules ordered by priority.
     */
    public function scopeOrderedByPriority($query)
    {
        return $query->orderBy('priority_order', 'asc');
    }
}
