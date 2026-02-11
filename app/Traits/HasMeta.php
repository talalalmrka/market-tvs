<?php

namespace App\Traits;

use App\Models\Meta;
use Illuminate\Database\Eloquent\Builder;

trait HasMeta
{
    /**
     * Get the meta relationship for the model.
     *
     * This method defines a polymorphic one-to-many relationship with the Meta model,
     * allowing the current model to have multiple meta entries associated with it.
     * Each meta entry stores key-value pairs of additional data for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metas()
    {
        return $this->morphMany(Meta::class, 'model');
    }

    /**
     * get meta item
     * @param string $key
     * @return \App\Models\Meta|null
     */
    public function meta(string $key)
    {
        return $this->metas()->firstWhere('key', $key);
    }
    public function getMeta(string $key, $defaultValue = null)
    {
        $meta = $this->meta($key);
        $value = $meta?->value ?? $defaultValue;
        if (is_json($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }
    public function updateMeta(string $key, $value): bool
    {
        $meta = $this->metas()->firstOrCreate(['key' => $key]);
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $meta->value = $value;
        return $meta->save();
    }
    public function getMetas(...$keys)
    {
        if (count($keys) === 1 && is_array($keys[0])) {
            $keys = $keys[0];
        }
        $metas = [];
        foreach ($keys as $key) {
            $metas[$key] = $this->getMeta($key);
        }
        return $metas;
    }
    public function saveMetas(array $metas)
    {
        $updated = true;
        foreach ($metas as $key => $value) {
            $update = $this->updateMeta($key, $value);
            if (!$update) {
                $updated = false;
            }
        }
        return $updated;
    }

    public function singleMetaEnabled()
    {
        return (bool) get_option(strtolower(class_basename($this)) . '_meta_enabled');
    }
    public function singleMetaItemEnabled($name)
    {
        return (bool) get_option(strtolower(class_basename($this)) . '_meta_' . $name);
    }

    /**
     * NEW & IMPROVED: Scope a query to filter by a meta key and value.
     * This version can handle simple value matching, array matching, and JSON matching.
     *
     * @param Builder $query
     * @param string $key The meta key.
     * @param mixed $value The value to match against.
     * @param string $operator The comparison operator (e.g., '=', 'like', 'in', 'json_contains').
     * @return Builder
     */
    public function scopeMeta(Builder $query, string $key, $value, string $operator = '='): Builder
    {
        return $query->whereHas('metas', function (Builder $q) use ($key, $value, $operator) {
            $q->where('key', $key);

            switch (strtolower($operator)) {
                case 'in':
                    // Handles: Quote::withMeta('status', ['active', 'pending'], 'in')
                    $q->whereIn('value', (array) $value);
                    break;

                case 'json_contains':
                    // Handles: Quote::withMeta('languages', 'en', 'json_contains')
                    // This is for finding a value within a meta value stored as a JSON array.
                    // Note: This requires a database that supports JSON functions (MySQL 5.7+, PostgreSQL).
                    $q->whereJsonContains('value', $value);
                    break;

                case 'like':
                    $q->where('value', 'like', $value);
                    break;

                default:
                    // Handles: Quote::withMeta('source', 'book')
                    $q->where('value', $operator, $value);
                    break;
            }
        });
    }
    /**
     * Scope a query to only include models that have all of the given meta key/value pairs.
     *
     * @param Builder $query
     * @param array $metas An associative array of meta keys and values.
     * @return Builder
     */
    /*public function scopeWithMetas(Builder $query, array $metas): Builder
    {
        // We loop through each key-value pair and apply the `withMeta` scope for each.
        // This ensures the model must have ALL the specified metas (an "AND" condition).
        foreach ($metas as $key => $value) {
            // If the value is an array, we assume you want to match any of the values in it.
            if (is_array($value)) {
                $query->withMeta($key, $value, 'in');
            } else {
                $query->withMeta($key, $value, '=');
            }
        }

        return $query;
    }*/
}
