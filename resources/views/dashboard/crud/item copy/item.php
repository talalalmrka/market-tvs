<?php

use App\Livewire\Components\DashboardPage;
use App\Traits\WithToast;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends DashboardPage
{
    use WithPagination, WithToast;
    public string $table;
    public string $title;

    public $filters = [
        'search' => '',
        'per_page' => 15,
        'sort_field' => null,
        'sort_direction' => null,
        'select_all' => false,
        'selected' => [],
    ];

    public function mount(string $table)
    {
        $this->table = $table;
        $this->title = str($table)
            ->replace(['-', '_'], ' ')
            ->title()
            ->value();
        $this->filters['per_page'] = $this->model()->getPerPage();
        $this->filters['sort_field'] = $this->model()->getKeyName();
        $this->filters['sort_direction'] = 'desc';
    }

    #[Computed()]
    public function columns()
    {
        return collect(Schema::getColumnListing($this->table))->map(function ($column) {
            $type = Schema::getColumnType($this->table, $column);
            $searchable = in_array($type, ['string', 'text', 'longtext', 'mediumtext', 'tinytext', 'char', 'varchar', 'enum', 'set']);
            $sortable = in_array($type, ['string', 'text', 'longtext', 'mediumtext', 'tinytext', 'char', 'varchar', 'enum', 'set', 'integer', 'float', 'double', 'decimal', 'real', 'numeric', 'double precision', 'float precision', 'decimal precision', 'decimal scale', 'decimal precision scale']);
            return [
                'name' => $column,
                'type' => $type,
                'label' => str($column)->replace(['-', '_'], ' ')->title()->value(),
                'searchable' => $searchable,
                'sortable' => $sortable,
            ];
        });
    }
    #[Computed()]
    public function model()
    {
        $model = models()->filter(fn($model) => data_get($model, 'table') === $this->table)->first();
        if (empty($model)) {
            return null;
        }
        return app(data_get($model, 'morph'));
    }
    protected function query()
    {
        return $this->model()->query();
    }
    #[Computed()]
    public function count()
    {
        return $this->query()->count();
    }

    #[Computed()]
    public function selectedCount()
    {
        return sizeof($this->filters['selected']);
    }

    #[Computed()]
    public function columnsCount()
    {
        return sizeof($this->columns()) + 2;
    }

    #[Computed()]
    public function items()
    {
        $query = $this->query();
        if ($this->filters['search']) {
            $query->where(function ($q) {
                foreach ($this->columns() as $column) {
                    if ($column['searchable']) {
                        $q->orWhere($column['name'], 'like', "%{$this->filters['search']}%");
                    }
                }
            });
        }
        if ($this->filters['sort_field'] && $this->filters['sort_direction']) {
            $query->orderBy($this->filters['sort_field'], $this->filters['sort_direction']);
        }
        return $query->paginate($this->filters['per_page']);
    }
    public function updatedFiltersSelectAll($value)
    {
        $this->filters['select_all'] = $value;
        if ($value) {
            $this->filters['selected'] = $this->items()->pluck('id')->toArray();
        } else {
            $this->filters['selected'] = [];
        }
    }
    public function sortBy($field)
    {
        if ($this->filters['sort_field'] === $field) {
            $this->filters['sort_direction'] = $this->filters['sort_direction'] === 'asc' ? 'desc' : 'asc';
        } else {
            $this->filters['sort_direction'] = 'asc';
        }

        $this->filters['sort_field'] = $field;
        $this->resetPage();
    }
    public function updatedFiltersSearch()
    {
        $this->resetPage();
    }
    public function delete($id)
    {
        try {
            $model = $this->model()->find($id);
            $this->authorize('delete', $model);
            $delete = $this->model()->destroy($id);
            if ($delete) {
                $this->dispatch("{str($this->table)->singular()->value()}.deleted.$id");
                $this->toastSuccess(__('Delete success.'));
            } else {
                $this->toastError(__('Delete failed!'));
            }
        } catch (\Exception $e) {
            $this->toastError($e->getMessage());
        }
    }
    public function render()
    {
        return view('dashboard.crud.item.item')
            ->layout('layouts::app.sidebar', [
                'title' => $this->title,
            ]);
    }
};
