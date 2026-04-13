document.addEventListener("alpine:init", () => {
    Alpine.data("RichSelect", (model) => ({
        open: false,
        search: '',
        highlighted: 0,
        multiple: false,
        options: [],
        selected: null,
        model: model,

        get filteredOptions() {
            if (!this.search) return this.options;

            return this.options.filter(opt =>
                (opt.label ?? '').toLowerCase().includes(this.search.toLowerCase())
            );
        },
        get selectedLabel() {
            if (this.multiple) {
                let selectedOptions = this.options.filter(o => this.selected.includes(o.value));
                if (!selectedOptions.length) return 'Select...';
                return selectedOptions.map(o => o.label).join(', ');
            }

            let found = this.options.find(o => o.value == this.selected);
            return found ? found.label : 'Select...';
        },

        isSelected(value) {
            if (this.multiple) return this.selected.includes(value);
            return this.selected == value;
        },

        toggleOption(value) {
            if (this.multiple) {
                if (this.selected.includes(value)) {
                    this.selected = this.selected.filter(v => v !== value);
                } else {
                    this.selected.push(value);
                }
                this.$refs.hiddenSelect.selectedOptions = this.selected;
            } else {
                this.selected = value;
                this.$refs.hiddenSelect.value = this.selected;
                this.open = false;
            }
        },

        clear() {
            this.selected = this.multiple ? [] : null;
        },

        focusSearch() {
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
            });
        },

        openDropdown() {
            if (this.$refs.hiddenSelect.disabled) return;
            this.open = true;
            this.highlighted = 0;
            this.focusSearch();
        },

        closeDropdown() {
            this.open = false;
            this.search = '';
            this.highlighted = 0;
        },

        moveDown() {
            if (!this.open) return this.openDropdown();
            if (this.highlighted < this.filteredOptions.length - 1) this.highlighted++;
        },

        moveUp() {
            if (!this.open) return this.openDropdown();
            if (this.highlighted > 0) this.highlighted--;
        },

        selectHighlighted() {
            let opt = this.filteredOptions[this.highlighted];
            if (!opt) return;
            this.toggleOption(opt.value);
        },
        clear() {
            this.selected = this.$refs.hiddenSelect.multiple
                ? []
                : null;
        },
        init() {
            this.$watch('selected', (val) => {
                if (this.model) {
                    try {
                        this.$wire.set(this.model, val);
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
            this.$nextTick(() => {
                this.multiple = this.$refs.hiddenSelect.multiple;
                this.options = [...this.$refs.hiddenSelect.options]
                    .filter(o => !o.disabled)
                    .map(o => ({
                        label: o.textContent.trim(),
                        value: o.value
                    }));
                this.selected = this.$refs.hiddenSelect.multiple
                    ? [...this.$refs.hiddenSelect.selectedOptions].map(o => o.value) // array
                    : this.$refs.hiddenSelect.value;
            });
        }
    }));
});
