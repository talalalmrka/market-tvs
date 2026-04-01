document.addEventListener('alpine:init', () => {
    Alpine.data('MySort', () => ({
        // items: this.$wire.entangle('items'),
        items: [],
        pre: {
            ["x-text"]() {
                return JSON.stringify(this.$wire.items, null, 2);
            }
        },
        sortGroup(parentId) {
            return {
                ['x-bind:data-parent']() {
                    console.log('this.$el', this.$el);
                    this.$el.setAttribute('data-parent', parentId);
                },
                ["@click"]() {
                    console.log(this.$el.dataset.parent);
                },
            };
        },
        handleSort(itemId, newPosition) {
            const newParentId = this.getNewParentId(itemId);
            this.sortMenu(itemId, newPosition, newParentId);
            const data = {
                'itemId': itemId,
                'newPosition': newPosition,
                'newParentId': newParentId,
            };
            Toast.success(JSON.stringify(data));
            /* const parentId = this.getParentId(id);
             const data = {
                 'id': id,
                 'position': position,
                 'parentId': parentId,
             };

             console.log('data', data);
              */
        },
        getNewParentId(itemId) {
            const el = document.querySelector(`#menu-item-${itemId}`);
            console.log('el', el);
            if (el) {
                const parentEl = el.closest('.sort-group');
                console.log('parentEl', parentEl);
                if (parentEl) {
                    const parentId = parentEl.dataset.parent;
                    console.log('parentId', parentId);
                    return parentId;
                }
            }
            return null;
        },
        sortMenu(itemId, newIndex, parentId) {
            const item = this.extractItem(this.$wire.items, itemId);
            if (!item) return;
            this.insertItem(this.$wire.items, item, parentId, newIndex);
            this.reindexOrders(this.$wire.items);
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
                    const inserted = insertItem(
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
        /*updateItem(item) {
            const itemEl = document.querySelector(`#sort-item-${item.id}`);
            if (itemEl) {
                const parentBadge = itemEl.querySelector('.parent-badge');

                if (parentBadge) {
                    const parentSpan = itemEl.querySelector('.parent-span');
                    parentSpan.innerHTML = item.parent_name;
                }
                if (item.parent_name == null || item.parent_name == '') {
                    parentBadge.classList.add('hidden');
                } else {
                    parentBadge.classList.remove('hidden');
                }
            }
        },*/
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
        reindexOrders(items) {
            items.forEach((item, index) => {
                item.order = index;

                if (item.children && item.children.length) {
                    reindexOrders(item.children);
                }
            });
        },
        init() {
            console.log('init');
            this.items = this.$wire.items;
            // this.items = this.$wire.items;
            // console.log('this.$wire', this.$wire.items)
        }
    }));
});
