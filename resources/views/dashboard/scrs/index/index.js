// Add your JavaScript here
console.log('Scrs index');
export default () => ({
    title: '',
    content: '',
    saving: false,

    init() {
        // Runs when the component is initialized
        console.log('Post Create JS loaded')
    },

    submit() {
        this.saving = true

        this.$wire.save().finally(() => {
            this.saving = false
        })
    },
})
