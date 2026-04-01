window.data_get = (object, path, defaultValue = null) => {
    if (object === null || object === undefined) {
        return defaultValue;
    }

    const pathArray = Array.isArray(path) ? path : path.split(".");
    let current = object;

    for (const segment of pathArray) {
        if (current === null || current === undefined) {
            return defaultValue;
        }

        // Handle Map and Set special cases
        if (current instanceof Map) {
            current = current.get(segment);
            continue;
        }

        if (current instanceof Set) {
            current = current.has(segment) ? segment : undefined;
            continue;
        }

        // Handle objects and arrays
        if (typeof current === "object" || typeof current === "function") {
            if (!(segment in current)) {
                return defaultValue;
            }
        }

        current = current[segment];
    }

    return current !== undefined ? current : defaultValue;
}


window.data_set = (object, path, value) => {
    const pathArray = Array.isArray(path) ? path : path.split(".");
    let current = object;

    for (let i = 0; i < pathArray.length; i++) {
        const segment = pathArray[i];
        const isLast = i === pathArray.length - 1;

        if (isLast) {
            if (current instanceof Map) {
                current.set(segment, value);
            } else if (current instanceof Set) {
                current.add(value);
            } else {
                current[segment] = value;
            }
            return object;
        }

        let next;
        if (current instanceof Map) {
            next = current.get(segment);
        } else if (current instanceof Set) {
            if (isArrayIndex(segment)) {
                const index = parseInt(segment, 10);
                let count = 0;
                for (const item of current) {
                    if (count === index) {
                        next = item;
                        break;
                    }
                    count++;
                }
            }
        } else {
            next = current[segment];
        }

        if (
            next !== undefined &&
            (next === null ||
                (typeof next !== "object" && typeof next !== "function"))
        ) {
            break;
        }

        if (next === undefined) {
            const nextSegment = pathArray[i + 1];
            next = isArrayIndex(nextSegment) ? [] : {};

            if (current instanceof Map) {
                current.set(segment, next);
            } else if (current instanceof Set) {
                break;
            } else {
                current[segment] = next;
            }
        }

        current = next;
    }

    return object;
}


window.jsonPretty = (obj) => {
    return JSON.stringify(obj, null, 2);
};
