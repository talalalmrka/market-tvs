<x-layouts::app.header :title="$title">
    <div
        x-data="Chat"
        class="max-w-md mx-auto mt-10 border rounded p-4 space-y-3">
        <div class="h-64 overflow-y-auto border p-2 space-y-2">
            <template x-for="msg in messages" :key="msg.id">
                <div>
                    <strong x-text="msg.user"></strong>:
                    <span x-text="msg.message"></span>
                </div>
            </template>
            <template x-if="!hasMessages">
                <div class="alert alert-soft alert-info">
                    {{ __('No messages yet!') }}
                </div>
            </template>
        </div>

        <div class="flex gap-2">
            <input
                x-model="message"
                @keydown.enter="send"
                placeholder="Message..."
                class="border p-2 flex-1">
            <button
                x-on:click="send"
                class="bg-blue-500 text-white px-4">
                Send
            </button>
        </div>
    </div>
</x-layouts::app.header>
