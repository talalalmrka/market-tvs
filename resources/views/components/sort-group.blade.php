<div {{ $attributes }}
    x-sort="handleSort"
    x-sort:group="items"
    class="sort-group p-2">
    <h2 x-text="`parent: ${parentId}`"></h2>
    <pre x-text="jn(getItems(parentId))"></pre>
    <template x-for="(item, index) in getItems(parentId)">
        <div
            x-cloak
            :id="`sort-item-${item.id}`"
            :key="item.id"
            x-sort:item="item.id"
            class="">
            <span x-text="item.name"></span>
            <div x-data="{ parentId: item.id }" x-init="parentId = item.id">
                @include('components.sort-group')
            </div>
        </div>
    </template>
</div>
