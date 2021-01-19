<template>
    <div class="modal is-active">
        <div @click="close" class="modal-background"></div>
        <div class="modal-content" style="padding: 25px">
            <div>
              {{ text }}
            </div>

            <div class="has-text-right" style="margin-top: 10px; margin-bottom: 10px">
                <button @click.prevent="close(false)" class="button is-outlined" :disabled="loading">Отмена</button>
<!--                <button @click.prevent="remove" class="button is-outlined" :class="{ 'is-loading': loading }"-->
<!--                        :disabled="loading">Подтвердить</button>-->
            </div>

            <p v-if="error" class="has-text-danger">{{ error }}</p>
        </div>
        <button @click="close" class="modal-close is-large" aria-label="close"></button>
    </div>
</template>

<script>
	export default {
		name: "warning",

        props: {
		    text: {
		        type: String,
            },
            path: {
		        type: String,
                required: true,
            },
        },

        data: () => ({
            error: '',
            loading: false,
        }),

        methods: {
		    close(deleted = false) {
		        this.$emit('close', deleted);
            },

            // remove() {
		        // axios.delete(this.path)
            //         .then(response => {
            //             this.close(true);
            //         })
            //         .catch(error => {
            //             console.log(error);
            //
            //             this.error = error.response && error.response.data && error.response.data.message
            //                 ? error.response.data.message
            //                 : error.message;
            //         });
            // },
        },
	}
</script>

<style scoped>

</style>
