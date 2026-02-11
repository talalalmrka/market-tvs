<?php

namespace App\Traits;


trait HasRelated
{
    public function related()
    {
        $table = $this->getTable();
        $related_count = get_option("related_{$table}_count", get_option('related_posts_count', 5));
        $related_query = get_option("related_{$table}_query", get_option('related_posts_query', 'category'));
        // Return related posts based on similar category, tag, or author if found
        $query = self::where('id', '!=', $this->id);
        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($related_query === 'category') {
            $query->category($this->getCategoryIds()->toArray());
        } elseif ($related_query === 'tag') {
            $query->tag($this->getTagIds()->toArray());
        } elseif ($related_query === 'user') {
            $query->where('user_id', $this->author_id);
        } else {
            $query->inRandomOrder();
        }
        return $query->latest()->take($related_count)->get();
    }
}
