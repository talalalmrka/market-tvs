<div {{ $attributes }}
    x-sort="handleSort"
    x-sort:group="items"
    class="sort-group">
    <template x-for="(item, index) in items" :key="item.id">
        <div
            :id="`menu-item-${item.id}`"
            x-sort:item="item.id"
            :parent-id="item.parent_id"
            class="p-2">
            <div class="p-2 border shadow-sm rounded-lg flex items-center gap-2">
                <button x-sort:handle type="button"
                    class="cursor-move"
                    title="{{ __('Move') }}">
                    <i class="icon bi-arrows-move"></i>
                </button>
                <div x-sort:ignore class="flex-1 flex items-center gap-2">
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
                <div x-sort:ignore class="flex items-center gap-2">
                    <span class="badge badge-indigo pill flex items-center gap-0.5 parent-badge">
                        <i class="icon bi-arrow-90deg-up"></i>
                        <span class="parent-span" x-text="item.parent_id"></span>
                    </span>
                    <span class="badge badge-teal pill" x-text="item.type"></span>
                </div>

            </div>
            <div x-data="{ items: item.children, parentId: item.id }">
                <x-sort-group />
            </div>
        </div>

    </template>
</div>
