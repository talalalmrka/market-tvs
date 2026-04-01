<div
    {{ $attributes->merge([
        'x-sort' => 'handleSort',
        'x-sort:group' => 'items',
        'class' => 'p-2',
    ]) }}>
    <template x-for="item in itemsLevel" :key="item.id">
        <div x-sort:item="item.id" x-data="{ open: false }" class="">
            <div class="border shadow-xs p-2">
                <div class="flex items-center gap-2">
                    <button x-sort:handle type="button"
                        class="cursor-move"
                        title="Move">
                        <i class="icon bi-arrows-move"></i>
                    </button>
                    <div x-sort:ignore x-on:click="open = !open"
                        class="flex-1 flex items-center gap-2 select-none cursor-pointer">
                        <div class="flex-1 flex items-center gap-2">
                            <i class="icon" :class="item.icon"></i>
                            <span class="font-semibold" x-text="item.name"></span>
                            <span class="badge badge-primary pill flex items-center font-medium gap-0.5">
                                <i class="icon bi-hash"></i>
                                <span x-text="item.id"></span>
                            </span>
                            <span class="badge badge-teal pill flex items-center gap-0.5">
                                <i class="icon bi-sort-alpha-down"></i>
                                <span x-text="item.order">${item.order}</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge badge-indigo pill flex items-center gap-0.5 parent-badge">
                                <i class="icon bi-arrow-90deg-up"></i>
                                <span class="parent-span" :x-text="item.parent_name ?? 'Top'"></span>
                            </span>
                            <span class="badge badge-teal pill" x-text="item.type"></span>
                            <i class="icon bi-chevron-down transition-transform"
                                :class="{ 'rotate-180': open }"></i>
                        </div>
                    </div>
                </div>
            </div>
            <template x-if="item.children && item.children.length">
                <div class="ms-4"
                    x-data="{ itemsLevel: item.children, parentId: item.id }" x-init="$root.itemsLevel = item.children">
                    {{-- <x-menu-group /> --}}
                </div>
            </template>
        </div>
    </template>
</div>
