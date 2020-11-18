<template>
    <div class="content">
        <div class="title-row">
            <div>
                <p class="title">{{ departmentId ? 'Редактирование' : 'Создание' }} кафедры</p>
            </div>

            <div v-if="departmentId">
                <a @click="showDeleteWarning = true">Удалить</a>
            </div>
        </div>

        <nav class="breadcrumb has-bullet-separator">
            <ul>
                <li><router-link to="/departments">Кафедры</router-link></li>
                <li class="is-active" v-if="department.name"><a>{{ department.name }}</a></li>
            </ul>
        </nav>

        <p v-if="errorText" class="has-text-danger">{{ errorText }}</p>

        <div class="box">
            <form @submit="send" v-if="showForm">
                <div class="field">
                    <label class="label" for="name">Название</label>
                    <div class="control">
                        <input id="name" type="text" class="input" v-model="department.name" required max="255"/>
                    </div>
                </div>

                <div class="field is-grouped has-text-right">
                    <div class="control">
                        <button type="submit" class="button is-primary" :class="{ 'is-loading': loading }"
                                :disabled="loading">{{ departmentId ? 'Обновить' : 'Сохранить' }}</button>
                        <router-link to="/departments" class="button">Вернуться к списку</router-link>
                    </div>
                </div>
            </form>
            <div v-else>
                <p>Не удалось загрузить данные</p>
            </div>
        </div>

        <delete-warning v-if="showDeleteWarning"
                        type="кафедру"
                        :name="department.name"
                        :path="'/api/departments/' + departmentId"
                        @close="closeDeleteWarning"></delete-warning>
    </div>
</template>

<script>
    import deleteWarning from '../extra/deleteWarning'

    export default {
        name: "departmentForm",

        components: {
            deleteWarning,
        },

        data: () => ({
            departmentId: null,
            department: {},
            errorText: '',
            loading: false,
            showDeleteWarning: false,
        }),

        computed: {
            showForm() {
                return !_.isEmpty(this.department);
            },
        },

        created() {
            if (this.$route.params.id) {
                this.departmentId = this.$route.params.id;
                this.getData();
            } else {
                this.$set(this, 'department', {
                    name: '',
                });
            }
        },

        methods: {
            getData() {
                this.$Progress.start();

                axios.get('api/departments/' + this.departmentId)
                    .then((response) => {
                        this.$Progress.finish();

                        if (response.data) {
                            this.department = response.data;
                        }
                    })
                    .catch(error => {
                        this.$Progress.finish();

                        console.log(error);
                        this.errorText = error.message;
                    });
            },

            send() {
                this.$Progress.start();
                this.loading = true;

                let url = this.departmentId ? '/api/departments/' + this.departmentId : '/api/departments';
                let data = new FormData();

                data.append('name', this.department.name);

                data.append('_method', this.departmentId ? 'put' : 'post');

                console.log(data);
                axios.post(url, data)
                    .then(response => {
                        this.$router.push('/departments');
                    })
                    .catch(error => {
                        this.$toast.error('Ошибка сервера. Не удалось сохранить');
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.$Progress.finish();
                    });
            },

            closeDeleteWarning(deleted) {
                this.showDeleteWarning = false;

                if (deleted) {
                    this.$router.push('/departments');
                }
            },
        },
    }
</script>

<style scoped>
    .field {
        margin-bottom: 25px;
    }
</style>
