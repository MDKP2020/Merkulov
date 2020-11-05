<template>
    <div class="field">
        <ol>
            <li v-for="(input, index) in inputs">
                <input type="text" v-model="input.value">
                <button v-if="index > 0" class="button is-primary is-small" @click="deleteRow(index)">Удалить</button>
            </li>
        </ol>

        <button class="button is-primary is-small" @click.prevent="addRow">Добавить</button>
    </div>
</template>

<script>
    export default {
        props: [
            'inputs'
        ],

        methods: {
            addRow() {
                this.inputs.push({
                    value: ''
                })
            },
            deleteRow(index) {
                this.inputs.splice(index,1)
            }
        }
    }
</script>
