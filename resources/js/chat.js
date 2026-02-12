document.addEventListener("alpine:init", () => {
    Alpine.data('Chat', () => ({
        messages: [],
        message: '',

        init() {
            window.Echo.channel('chat')
                .listen('.message.sent', (e) => {
                    console.log('New message', e);
                    this.messages.push({
                        id: Date.now(),
                        user: e.user,
                        message: e.message
                    })
                })
        },
        get hasMessages() {
            return this.messages.length;
        },
        async send() {
            if (!this.message) {
                alert('Insert message!');
            }

            await fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    message: this.message,
                })
            }).then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error("Failed to send:", error);
                })
                .finally(() => {
                    this.message = ''
                });


        }
    }));
});
