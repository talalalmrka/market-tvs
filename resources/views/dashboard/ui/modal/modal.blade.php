<div {{ $attributes->merge([
    'class' => 'card',
]) }}>
    <div class="card-body">
        <button type="button" class="btn btn-primary" data-fg-toggle="modal" data-fg-target="#basic-modal">
            Open modal
        </button>
        <div id="basic-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-fg-dismiss="modal">
                            <i class="icon bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        Modal body
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-fg-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div><!-- Modal Content -->
            </div><!-- Modal Dialog -->
        </div><!-- Modal -->
    </div>
</div>
