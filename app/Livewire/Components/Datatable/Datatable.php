<?php

namespace App\Livewire\Components\Datatable;

use App\Livewire\Components\Datatable\Actions\Action;
use App\Livewire\Components\Datatable\Buttons\Button;
use App\Livewire\Components\Datatable\Columns\Column;
use App\Livewire\Components\Datatable\Columns\DateColumn;
use App\Traits\WithToast;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

abstract class Datatable extends Component
{
    use WithPagination, WithToast;
    #[Layout('layouts::app')]
    public $search = '';
    public $sortField = null;
    public $sortDirection = null;
    public $perPage = 10;
    #[Computed]
    public $tableClass = '';
    #[Computed]
    public $headClass = '';
    #[Computed]
    public $footClass = '';
    #[Computed]
    public $bodyClass = '';
    #[Computed]
    public $rowClass = '';
    #[Computed]
    public $cellClass = '';
    public $selected = [];
    public $selectAll = false;
    public $checkbox = true;
    public $created_at_column = true;
    public $updated_at_column = false;
    public $updated_at_column_type = 'human';
    public $id_column = true;
    public $date_format = null;
    public function boot()
    {
        $this->restoreState();
        $this->date_format = get_option('date_format', 'j F Y');
    }
    abstract public function builder();

    abstract public function getColumns();
    // abstract public function render();
    public function updatedPerPage()
    {
        $this->resetPage();
        $this->saveState();
    }
    public function updatedSearch()
    {
        $this->resetPage();
        $this->saveState();
    }
    public function updatedSelectAll($value)
    {
        $this->selectAll = $value;
        if ($value) {
            $this->selected = $this->items()->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }
    protected function saveState()
    {
        session()->put([
            "datatable.{$this->getPluralName()}.sortField" => $this->sortField,
            "datatable.{$this->getPluralName()}.sortDirection" => $this->sortDirection,
            "datatable.{$this->getPluralName()}.perPage" => $this->perPage,
            "datatable.{$this->getPluralName()}.search" => $this->search,
        ]);
    }
    protected function restoreState()
    {
        $this->sortField = session()->get(
            "datatable.{$this->getPluralName()}.sortField",
            null,
        );
        $this->sortDirection = session()->get(
            "datatable.{$this->getPluralName()}.sortDirection",
            null
        );
        $this->perPage = session()->get(
            "datatable.{$this->getPluralName()}.perPage",
            10
        );
        $this->search = session()->get(
            "datatable.{$this->getPluralName()}.search",
            ''
        );
    }

    #[Computed]
    public function hasSelected()
    {
        return sizeof($this->selected) > 0;
    }
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
        $this->resetPage();
        $this->saveState();
    }
    public function getActions()
    {
        return [
            Action::make('edit')->icon('bi-pencil-square')->title(__('Edit')),
            Action::make('delete')->icon('bi-trash')->title(__('Delete')),
        ];
    }
    #[Computed]
    public function actions()
    {
        return $this->getActions();
    }
    #[Computed]
    public function hasActions()
    {
        return sizeof($this->actions()) > 0;
    }
    public function getButtons()
    {
        return [
            Button::make('create')
                ->icon('bi-plus-lg')
                ->title(__('Create'))
                ->color('green'),

            Button::make('deleteSelected')
                ->icon('bi-x-lg')
                ->title(__('Delete'))
                ->color('red')
                ->attributes(['wire:confirm' => __('Are you shure to delete selected?')])
                ->disabled(!$this->hasSelected()),
        ];
    }
    #[Computed]
    public function buttons()
    {
        return $this->getButtons();
    }
    #[Computed]
    public function hasButtons()
    {
        return sizeof($this->buttons()) > 0;
    }
    public function createdAtColumnExists(): bool
    {
        foreach ($this->getColumns() as $col) {
            if ($col->name == 'created_at') {
                return true;
            }
        }
        return false;
    }
    public function mustAddCreatedAt(): bool
    {
        return $this->created_at_column && !$this->createdAtColumnExists();
    }
    public function getCreatedAtColumn()
    {
        $date_column = DateColumn::make('created_at')
            ->label('Creation date');
        if ($this->date_format) {
            $date_column = $date_column->format($this->date_format);
        }
        return $date_column;
    }
    public function updatedAtColumnExists(): bool
    {
        foreach ($this->getColumns() as $col) {
            if ($col->name == 'created_at') {
                return true;
            }
        }
        return false;
    }
    public function mustAddUpdatedAt(): bool
    {
        return $this->updated_at_column && !$this->updatedAtColumnExists();
    }
    public function getUpdatedAtColumn()
    {
        return Column::make('updated_at')
            ->label('Last modified')
            ->sortable()
            ->headClass('text-center')
            ->class('text-xs text-center')
            ->content(function ($item) {
                try {
                    if ($this->updated_at_column_type === 'human') {
                        return $item->updated_at?->diffForHumans(null, CarbonInterface::DIFF_ABSOLUTE, true);
                    }
                    if ($this->date_format) {
                        return Carbon::parse($item->updated_at)->format($this->date_format);
                    }
                } catch (\Exception $e) {
                    return '--';
                }
            });
    }
    #[Computed]
    public function cols()
    {
        $cols = [];
        if ($this->checkbox) {
            $cols[] = Column::make('id')
                ->label(view('livewire.components.datatable.check-all'))
                ->content(function ($item) {
                    return view('livewire.components.datatable.check-item', [
                        'item' => $item,
                    ]);
                });
        }
        if ($this->id_column) {
            $cols[] = Column::make('id')
                ->sortable()
                ->label(__('Id'));
        }
        foreach ($this->getColumns() as $column) {
            $cols[] = $column;
        }
        if ($this->mustAddCreatedAt()) {
            $cols[] = $this->getCreatedAtColumn();
        }
        if ($this->mustAddUpdatedAt()) {
            $cols[] = $this->getUpdatedAtColumn();
        }
        if ($this->hasActions()) {
            $cols[] = Column::make('actions')
                ->label(__('Actions'))
                ->content(function ($item) {
                    return view('livewire.components.datatable.actions', [
                        'actions' => $this->actions(),
                        'item' => $item,
                    ]);
                });
        }
        return $cols;
    }
    #[Computed]
    public function items()
    {
        $query = $this->builder();
        if ($this->search) {
            $query->where(function ($q) {
                foreach ($this->cols() as $col) {
                    if ($col->searchable) {
                        $q->orWhere($col->name, 'like', "%{$this->search}%");
                    }
                }
            });
        }
        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }
        return $query->paginate($this->perPage);
    }
    #[Computed]
    public function links()
    {
        return $this->items()->links();
    }
    public function setTableClass($class = null)
    {
        $this->tableClass = $class;
    }
    public function setHeadClass($class = null)
    {
        $this->headClass = $class;
    }
    public function setFootClass($class = null)
    {
        $this->footClass = $class;
    }
    public function setBodyClass($class = null)
    {
        $this->bodyClass = $class;
    }
    public function setRowClass($class = null)
    {
        $this->rowClass = $class;
    }
    public function setCellClass($class = null)
    {
        $this->cellClass = $class;
    }
    public function getModel()
    {
        return $this->builder()->getModel();
    }
    public function getKeyName()
    {
        return $this->builder()->getKeyName();
    }
    public function getPluralName()
    {
        return $this->getModel()->getTable();
    }
    public function getSingularName()
    {
        return str()->singular($this->getPluralName());
    }
    public function authorizeShow($id)
    {
        $this->authorize("manage_{$this->getPluralName()}", $id);
    }
    public function show($id)
    {
        $this->authorizeShow($id);
        $routeName = "{$this->getSingularName()}";
        if (Route::has($routeName)) {
            $this->redirect(route($routeName), true);
        }
    }
    public function authorizeCreate()
    {
        $this->authorize("manage_{$this->getPluralName()}");
    }

    public function create()
    {
        $this->authorizeCreate();
        $routeName = "dashboard.{$this->getPluralName()}.create";
        if (Route::has($routeName)) {
            $this->redirect(route($routeName), true);
        }
    }
    public function authorizeEdit($id)
    {
        $this->authorize("manage_{$this->getPluralName()}", $id);
    }
    public function edit($id)
    {
        $this->authorizeEdit($id);
        $routeName = "dashboard.{$this->getPluralName()}.edit";
        if (Route::has($routeName)) {
            $this->redirect(route($routeName, $id), true);
        }
    }
    public function authorizeDelete()
    {
        $this->authorize("manage_{$this->getPluralName()}");
    }
    public function delete($id)
    {
        try {
            $this->authorizeDelete();
            $delete = $this->getModel()->destroy($id);
            if ($delete) {
                $this->dispatch("{$this->getSingularName()}.deleted.$id");
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
        $this->authorizeDelete();
        if (!empty($this->selected)) {
            $count = sizeof($this->selected);
            $ids = $this->pull('selected');
            $delete = $this->getModel()->destroy($ids);
            if ($delete) {
                $this->dispatch("{$this->getPluralName()}.deleted", $ids);
                $this->toastSuccess(__(':count items deleted successfully.', ['count' => $count]));
                $this->selectAll = false;
            } else {
                $this->toastError(__('Delete failed!'));
            }
        } else {
            $this->toastError(__('No items selected!'));
        }
    }
    #[On('saved')]
    public function onSaved(string $model_name, int|null $itemId = null)
    {
        if ($model_name == $this->getSingularName()) {
            $this->dispatch('itemUpdated');
        }
    }
    public function loadEdit($id = null)
    {
        if ($id) {
            $this->edit($id);
        }
    }
    #[Computed]
    public function filtersView()
    {
        return null;
    }
    #[Computed]
    public function buttonsView()
    {
        return null;
    }
    #[Computed]
    public function table()
    {
        return view('livewire.components.datatable.datatable', [
            'search' => $this->search,
            'perPage' => $this->perPage,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'selectAll' => $this->selectAll,
            'selected' => $this->selected,
            'checkbox' => $this->checkbox,
        ]);
    }
}
