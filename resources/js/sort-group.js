document.addEventListener('alpine:init', () => {
    Alpine.data('MySortGroup', () => ({
        items: [],
        active: [],
        isOpen(id) {
            return this.active.includes(id);
        },
        toggle(id) {
            if (this.isOpen(id)) {
                const index = this.active.findIndex(item => item === id);
                if (index >= 0) {
                    this.active.splice(index, 1);
                }
            } else {
                this.active.push(id);
            }
            this.saveLocal();
        },
        saveLocal() {
            localStorage.setItem('MySortGroup', JSON.stringify(this.active));
        },
        loadLocal() {
            const json = localStorage.getItem('MySortGroup');
            if (json) {
                this.active = JSON.parse(json);
            }
        },
        jn(obj) {
            return JSON.stringify(obj, null, 2)
        },
        log: {
            ["x-text"]() {
                return this.jn(this.items);
            },
        },
        currentPath(path, index, prop) {
            return `${path}[${index}].${prop}`;
        },
        groupHtml(items, parentId, path, className = '') {
            return `<div x-sort.ghost="handleSort"
            x-sort:group="items"
            data-parent="${parentId}"
            class="p-2 ${className}">
            ${items.map((item, index) => this.itemHtml(index, path, item)).join('')}
            </div>`;
        },
        itemHtml(index, path, item) {
            const currentPath = `${path}[${index}]`;
            return `
            <div
            x-cloak
            id="menu-item-${item.id}"
            :key="${item.id}"
            x-sort:item="${item.id}"
            class="">
            <div
                data-id="${item.id}"
                class="border text-sm rounded-lg shadow-sm"
                :class="{ 'ring-2 ring-primary/50': isOpen(${item.id}) }">
                <div class="flex items-center gap-2 p-2" :class="{ 'border-b': isOpen(${item.id}) }">
                    <button x-sort:handle type="button"
                        class="cursor-move"
                        title="Move">
                        <i class="icon bi-arrows-move"></i>
                    </button>
                    <div x-sort:ignore
                        x-on:click="toggle(${item.id})"
                        class="flex-1 flex items-center gap-2 select-none cursor-pointer">
                        <div
                            class="flex-1 flex items-center gap-2">
                            <i class="icon ${item.icon}"></i>
                            <span class="font-semibold">${item.name}</span>
                            <span class="badge badge-primary pill flex items-center font-medium gap-0.5">
                                <i class="icon bi-hash"></i>
                                <span>${item.id}</span>
                            </span>
                            <span class="badge badge-teal pill flex items-center gap-0.5">
                                <i class="icon bi-sort-alpha-down"></i>
                                <span>${item.order}</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge badge-indigo pill flex items-center gap-0.5 parent-badge">
                                <i class="icon bi-arrow-90deg-up"></i>
                                <span class="parent-span">${this.getParentName(item.parent_id)}</span>
                            </span>
                            <span class="badge badge-teal pill">${item.type}</span>
                            <i class="icon bi-chevron-down transition-transform" :class="{ 'rotate-180': isOpen(${item.id}) }"></i>
                        </div>
                    </div>
                    <button x-on:click="delete(${item.id})" type="button" title="{{ __('Delete') }}">
                        <i wire:loading.remove wire:target="delete(${item.id})"
                            class="icon bi-trash-fill"></i>
                        <fgx:loader wire:loading wire:target="delete(${item.id})" />
                    </button>
                </div>
                <div x-show="isOpen(${item.id})" class="p-2">
                    <div class="grid grid-cols-1 gap-3">
                        <div class="col">
                            <label for="${currentPath}.name" class="form-label">Name</label>
                            <input type="text" x-model="${currentPath}.name" class="form-control sm"
                                id="${currentPath}.name" />
                        </div>
                        <div class="col">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div class="col">
                                    <label for="${currentPath}.icon" class="form-label">Icon</label>
                                    <div class="form-control-container">
                                        <span class="start-icon">
                                            <i class="icon ${item.icon}"></i>
                                        </span>
                                        <input type="text"
                                            x-model="${currentPath}.icon"
                                            class="form-control sm has-start-icon" id="${currentPath}.icon"/>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="${currentPath}.title" class="form-label">Title</label>
                                    <input type="text" x-model="${currentPath}.title" class="form-control sm" id="${currentPath}.title" />
                                </div>
                                <div class="col">
                                    <label for="${currentPath}.class_name" class="form-label">CSS Class</label>
                                    <input type="text" x-model="${currentPath}.class_name" class="form-control sm" id="${currentPath}.class_name" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ${this.groupHtml(item.children, item.id, `${currentPath}.children`, 'ms-4')}
        </div>`;
        },

        handleSort(itemId, newPosition) {
            const newParentId = this.getNewParentId(itemId);
            this.sortMenu(itemId, newPosition, newParentId);
        },
        getNewParentId(itemId) {
            const el = document.querySelector(`#menu-item-${itemId}`);
            if (el) {
                const parentEl = el.closest('[data-parent]');
                if (parentEl) {
                    const parentId = parentEl.dataset.parent;
                    if (!parentId) {
                        return null;
                    }
                    if (parentId == 'null') {
                        return null;
                    }
                    return parentId;
                }
            }
            return null;
        },
        sortMenu(itemId, newIndex, parentId) {
            const item = this.extractItem(this.$wire.items, itemId);
            if (!item) return;
            this.insertItem(this.items, item, parentId, newIndex);
            this.reindexOrders(this.items);
            this.renderItems();
        },
        extractItem(items, itemId) {
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                if (item.id == itemId) {
                    const removed = item;
                    items.splice(i, 1);
                    return removed;
                }
                if (item.children && item.children.length) {
                    const found = this.extractItem(item.children, itemId);
                    if (found) return found;
                }
            }

            return null;
        },
        insertItem(items, item, parentId, position) {
            item.parent_id = parentId || null;
            item.parent_name = this.getItemProp(parentId, 'parent_name', 'Top');
            // updateItem(item);
            // Insert at root level
            if (!parentId) {
                items.splice(position, 0, item);
                return true;
            }

            for (let parent of items) {
                if (parent.id == parentId) {
                    if (!parent.children) {
                        parent.children = [];
                    }

                    parent.children.splice(position, 0, item);
                    return true;
                }

                if (parent.children && parent.children.length) {
                    const inserted = this.insertItem(
                        parent.children,
                        item,
                        parentId,
                        position
                    );

                    if (inserted) return true;
                }
            }

            return false;
        },
        getItemById(items, itemId) {
            for (let i = 0; i < items.length; i++) {
                const item = items[i];

                if (item.id == itemId) {
                    return item;
                }

                if (item.children && item.children.length) {
                    const found = this.getItemById(item.children, itemId);
                    if (found) return found;
                }
            }
            return null;
        },
        getItemProp(itemId, prop, defaultValue = null) {
            const item = this.getItemById(this.$wire.items, itemId);
            return data_get(item, prop, defaultValue);
        },
        getParentName(parentId) {
            return this.getItemProp(parentId, 'name', 'Top');
        },
        reindexOrders(items) {
            items.forEach((item, index) => {
                item.order = index;

                if (item.children && item.children.length) {
                    this.reindexOrders(item.children);
                }
            });
        },
        renderItems() {
            this.$refs.rootGroup.innerHTML = this.groupHtml(this.items, null, 'items');
        },
        init() {
            this.items = this.$wire.items;
            this.loadLocal();
            this.renderItems();
            this.$watch(() => this.$wire.items, (value, oldValue) => {
                this.items = value;
                // this.renderItems();
            });

        },
    }));
});
