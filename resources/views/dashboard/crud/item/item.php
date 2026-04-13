<?php

use App\Livewire\Components\DashboardPage;
use App\Livewire\Components\Datatable\Actions\Action;
use App\Livewire\Components\Datatable\Buttons\Button;
use App\Livewire\Components\Datatable\Columns\Column;
use App\Traits\WithToast;
use Illuminate\Support\Collection;
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
        'active_columns' => [],
    ];
    public $date_format = 'j F Y - h:i a';
    public bool $select_all = false;
    public array $selected = [];

    //public $form = [];
    public function mount(string $table)
    {
        $this->authorize("manage_{$table}");
        $this->table = $table;
        $this->title = str($table)
            ->replace(['-', '_'], ' ')
            ->title()
            ->value();
        $this->filters['per_page'] = $this->model()->getPerPage();
        $this->filters['sort_field'] = $this->model()->getKeyName();
        $this->filters['sort_direction'] = 'desc';
        $this->date_format = config('app.datatable_date_format', 'j F Y - h:i a');
        $this->restoreState();
    }

    protected function saveState()
    {
        session()->put([
            "crud.{$this->table}.filters" => $this->filters,
        ]);
    }
    protected function restoreState()
    {
        $this->filters = session()->get(
            "crud.{$this->table}.filters",
            [
                'search' => '',
                'per_page' => 15,
                'sort_field' => null,
                'sort_direction' => null,
                'active_columns' => $this->allColumns(),
            ],
        );
    }

    #[Computed]
    public function hasSelected()
    {
        return sizeof($this->selected) > 0;
    }

    #[Computed()]
    public function actions()
    {
        return [
            Action::make('edit')->icon('bi-pencil-square')->title(__('Edit')),
            Action::make('delete')->icon('bi-trash')->title(__('Delete')),
        ];
    }

    #[Computed()]
    public function buttons()
    {
        return [
            Button::make('create')
                ->icon('fg-plus')
                ->title(__('Create'))
                ->color('green'),

            Button::make('deleteSelected')
                ->icon('bi-trash-fill')
                ->title(__('Delete'))
                ->color('red')
                ->attributes(['wire:confirm' => __('Are you shure to delete selected?')])
                ->disabled(!$this->hasSelected()),
        ];
    }

    #[Computed]
    public function allColumns()
    {
        return Schema::getColumnListing($this->table);
    }

    /**
     * get columns
     * @return Illuminate/Support/Collection<App/Livewire/Components/Datatable/Columns/Column>
     */
    #[Computed]
    public function columns()
    {
        $columns = collect([]);
        $activeColumns = data_get($this->filters, 'active_columns', $this->allColumns());
        $columns->add(Column::make('check')
            ->label(view('livewire.components.datatable.check-all', [
                'id' => 'select_all',
            ]))
            ->content(function ($item) {
                return view('livewire.components.datatable.check-item', [
                    'item' => $item,
                    'id' => 'selected',
                ]);
            }));
        collect($this->allColumns())
            ->filter(function ($column) use ($activeColumns) {
                return in_array($column, $activeColumns);
            })
            ->values()
            ->each(function ($column) use (&$columns) {
                $type = Schema::getColumnType($this->table, $column);
                $label = str($column)->replace(['-', '_'], ' ', $column)->title()->value();
                $searchable = in_array($type, ['string', 'text', 'longtext', 'mediumtext', 'tinytext', 'char', 'varchar', 'enum', 'set']);
                $sortable = in_array($type, ['string', 'text', 'longtext', 'mediumtext', 'tinytext', 'char', 'varchar', 'enum', 'set', 'integer', 'float', 'double', 'decimal', 'real', 'numeric', 'double precision', 'float precision', 'decimal precision', 'decimal scale', 'decimal precision scale']);
                $col = column($column)
                    ->label($label)
                    ->content(function ($item) use ($column, $type) {
                        $content = data_get($item, $column);
                        if ($type === 'tinyint') {
                            return boolval($content);
                        }
                        if ($type === 'datetime') {
                            return $content?->format($this->date_format);
                        }
                        return $content;
                    });
                if ($searchable) {
                    $col = $col->searchable();
                }
                if ($sortable) {
                    $col = $col->sortable();
                }
                $columns->add($col);
            });
        $columns->add(Column::make('actions')
            ->label(__('Actions'))
            ->content(function ($item) {
                return view('livewire.components.datatable.actions', [
                    'actions' => $this->actions(),
                    'item' => $item,
                ]);
            }));
        return $columns;
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
        $modelItem = models()->firstWhere('table', $this->table);
        $args = $modelItem ? data_get($modelItem, 'args', []) : [];
        $query = $this->model()->query();
        if (!empty($args)) {
            foreach ($args as $key => $value) {
                $query->where($key, $value);
            }
        }
        return $query;
    }

    #[Computed()]
    public function count()
    {
        return $this->query()->count();
    }

    #[Computed()]
    public function selectedCount()
    {
        return sizeof($this->selected);
    }

    #[Computed()]
    public function columnsCount()
    {
        return $this->columns()->count();
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

    public function resetFilters()
    {
        $this->reset('filters');
        $this->filters['active_columns'] = $this->allColumns();
        $this->saveState();
    }

    public function updatedSelectAll($value)
    {
        $this->select_all = $value;
        if ($value) {
            $this->selected = $this->items()->pluck('id')->toArray();
        } else {
            $this->selected = [];
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
        $this->saveState();
    }
    public function updatedFiltersSearch()
    {
        $this->resetPage();
        $this->saveState();
    }
    public function updatedFiltersPerPage()
    {
        $this->resetPage();
        $this->saveState();
    }
    public function updatedFiltersActiveColumns()
    {
        $this->saveState();
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

    public function deleteSelected()
    {
        $this->authorize('deleteGroup', $this->selected);
        if (!empty($this->selected)) {
            $count = sizeof($this->selected);
            $ids = $this->pull('selected');
            $delete = $this->model()->destroy($ids);
            if ($delete) {
                $this->dispatch("{$this->table}.deleted", $ids);
                $this->toastSuccess(__(':count items deleted successfully.', ['count' => $count]));
                $this->select_all = false;
            } else {
                $this->toastError(__('Delete failed!'));
            }
        } else {
            $this->toastError(__('No items selected!'));
        }
    }

    #[Computed()]
    public function fields()
    {
        return collect($this->model()->getFillable())->map(function ($col) {
            $type = Schema::getColumnType($this->table, $col);
            $label = str($col)->replace(['-', '_'], ' ', $col)->title()->value();
            return [
                'type' => match ($type) {
                    'text' => 'textarea',
                    'integer' => 'number',
                    'tinyint' => 'switch',
                    default => 'text',
                },
                'label' => $label,
                'id' => "form.{$col}",
            ];
        });
    }
    public function render()
    {
        return view('dashboard.crud.item.item')
            ->layout('layouts::app.sidebar', [
                'title' => $this->title,
            ]);
    }
};
