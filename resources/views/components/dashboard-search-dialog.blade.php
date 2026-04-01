<div x-cloak x-bind="dialogBackDrop" class="modal-backdrop show"></div>
<div x-cloak x-bind="dialog" id="dashboard-search-modal" class="modal fade show">
    <div class="modal-dialog">
        <div class="modal-content h-full w-full">
            <div class="modal-header flex items-center gap-2">
                <div class="form-control-container flex-1">
                    <span class="start-icon">
                        <i class="icon bi-search"></i>
                    </span>
                    <input id="dashboard-search" x-ref="searchInput" type="search" placeholder="Search"
                        class="form-control has-start-icon sm pill" x-model="term">
                </div>
                <button
                    x-on:click="close"
                    type="button">
                    <i class="icon bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <template x-if="loading">
                    <div class="p-2 text-center" :class="{ 'absolute-center': items.length }">
                        <i class="icon fg-loader-dots-scale"></i>
                    </div>
                </template>
                <template x-if="!loading && term && !items.length">
                    <div class="alert alert-soft-info">
                        {{ __('No search results') }}
                    </div>
                </template>
                <template x-if="items.length">
                    <nav class="nav nav-vertical">
                        <template x-for="(item, index) in items" key="index">
                            <a class="nav-link" :href="item.href" :title="item.label" :aria-label="item.label"
                                wire:navigate>
                                <i x-show="item.icon" class="icon" :class="item.icon"></i>
                                <span x-text="item.label"></span>
                            </a>
                        </template>
                    </nav>
                </template>
            </div>
        </div><!-- Modal Content -->
    </div><!-- Modal Dialog -->
</div><!-- Modal -->
