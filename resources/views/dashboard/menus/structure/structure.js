/* $wire.intercept("sortMenu", ({ action, onSend }) => {
    const itemId = action.params[0];
    const newIndex = action.params[1];
    const parentId = action.params[2];
    sortMenu(itemId, newIndex, parentId);
    onSend(({ call }) => {
        // call: { method, params, metadata }
    })
});

function sortMenu(itemId, newIndex, parentId) {
    const item = extractItem($wire.items, itemId);
    if (!item) return;
    insertItem($wire.items, item, parentId, newIndex);
    reindexOrders($wire.items);
    logItems();
}

function extractItem(items, itemId) {
    for (let i = 0; i < items.length; i++) {
        const item = items[i];
        if (item.id == itemId) {
            const removed = item;
            items.splice(i, 1);
            return removed;
        }
        if (item.children && item.children.length) {
            const found = extractItem(item.children, itemId);
            if (found) return found;
        }
    }

    return null;
}

function getItemById(items, itemId) {
    for (let i = 0; i < items.length; i++) {
        const item = items[i];

        if (item.id == itemId) {
            return item;
        }

        if (item.children && item.children.length) {
            const found = getItemById(item.children, itemId);
            if (found) return found;
        }
    }
    return null;
}

function getItemName(id) {
    if (id == null || id == '') {
        return null;
    }
    const item = getItemById($wire.items, id);
    return item.name ?? null;
}

function insertItem(items, item, parentId, position) {
    item.parent_id = parentId || null;
    const parent_name = getItemName(parentId);
    item.parent_name = parent_name;
    updateItem(item);
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
}
function updateItem(item) {
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
}
function reindexOrders(items) {
    items.forEach((item, index) => {
        item.order = index;

        if (item.children && item.children.length) {
            reindexOrders(item.children);
        }
    });
}

function logItems() {
    const pre = document.querySelector('#items-pre');
    const pretty = JSON.stringify($wire.items, null, 2);
    pre.innerHTML = pretty;
}

logItems();
 */

function preItems() {
    const pre = document.querySelector('#items-pre');
    if (pre) {
        const pretty = JSON.stringify($wire.items, null, 2);
        pre.innerHTML = pretty;
    }

}

preItems();
