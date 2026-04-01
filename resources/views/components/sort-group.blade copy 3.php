<div {{ $attributes }}
    x-sort="handleSort"
    x-sort:group="items"
    class="sort-group p-2">
    <h2 x-text="parentId"></h2>
    <template x-for="(item, index) in getItems(parentId)">
        <div
            x-cloak
            :id="`sort-item-${item.id}`"
            :key="item.id"
            x-sort:item="item.id"
            class="">
            <div
                class="border text-sm rounded-lg shadow-sm"
                :class="{ 'ring-2 ring-primary/50': isOpen(item.id) }">
                <div class="flex items-center gap-2 p-2" :class="{ 'border-b': isOpen(item.id) }">
                    <button x-sort:handle type="button"
                        class="cursor-move"
                        title="Move">
                        <i class="icon bi-arrows-move"></i>
                    </button>
                    <div x-sort:ignore
                        x-on:click="toggle(item.id)"
                        class="flex-1 flex items-center gap-2 select-none cursor-pointer">
                        <div
                            class="flex-1 flex items-center gap-2">
                            <i class="icon" :class="item.icon"></i>
                            <span class="font-semibold" x-text="item.name"></span>
                            <span class="badge badge-primary pill flex items-center font-medium gap-0.5">
                                <i class="icon bi-hash"></i>
                                <span x-text="item.id"></span>
                            </span>
                            <span class="badge badge-teal pill flex items-center gap-0.5">
                                <i class="icon bi-sort-alpha-down"></i>
                                <span x-text="item.order"></span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge badge-indigo pill flex items-center gap-0.5 parent-badge">
                                <i class="icon bi-arrow-90deg-up"></i>
                                <span class="parent-span" x-text="item.parent_name ?? 'Top'"></span>
                            </span>
                            <span class="badge badge-teal pill" x-text="item.type"></span>
                            <i class="icon bi-chevron-down transition-transform"
                                :class="{ 'rotate-180': isOpen(item.id) }"></i>
                            <button x-on:click="delete(item.id)" type="button" title="{{ __('Delete') }}">
                                <i class="icon bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div x-show="isOpen(item.id)" class="p-2">
                    <div class="grid grid-cols-1 gap-3">
                        <div class="col">
                            <label for="" class="form-label">Name</label>
                            <input type="text" x-model="item.name" class="form-control sm" />
                        </div>
                    </div>
                </div>
            </div>
            {{-- <x-sort-group x-data="{ parentId: item.id }" x-init="parentId = item.id" /> --}}
        </div>
    </template>
</div>
