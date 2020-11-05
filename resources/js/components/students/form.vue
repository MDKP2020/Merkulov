<template>
    <div class="content">
        <div class="title-row">
            <div>
                <p class="title">{{ studentId ? 'Редактирование' : 'Создание' }} студента</p>
            </div>

            <div v-if="studentId">
                <a @click="showDeleteWarning = true">Удалить</a>
            </div>
        </div>

        <nav class="breadcrumb has-bullet-separator">
            <ul>
                <li><router-link to="/students">Студенты</router-link></li>
                <li class="is-active" v-if="student.name"><a>{{ student.name }}</a></li>
            </ul>
        </nav>

        <p v-if="errorText" class="has-text-danger">{{ errorText }}</p>

        <div class="box">
            <form @submit="send" v-if="showForm">
                <div class="field">
                    <label class="label" for="name">Имя</label>
                    <div class="control">
                        <input id="name" type="text" class="input" v-model="student.name" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Фамилия</label>
                    <div class="control">
                        <input id="surname" type="text" class="input" v-model="student.surname" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Отчество</label>
                    <div class="control">
                        <input id="patronymic" type="text" class="input" v-model="student.patronymic" required max="255"/>
                    </div>
                </div>

                <div class="field is-grouped has-text-right">
                    <div class="control">
                        <button type="submit" class="button is-primary" :class="{ 'is-loading': loading }"
                                :disabled="loading">{{ studentId ? 'Обновить' : 'Сохранить' }}</button>
                        <router-link to="/students" class="button">Вернуться к списку</router-link>
                    </div>
                </div>
            </form>
            <div v-else>
                <p>Не удалось загрузить данные</p>
            </div>
        </div>

        <delete-warning v-if="showDeleteWarning"
                        type="студента"
                        :name="student.name + ' ' + student.surname + ' ' + student.patronymic"
                        :path="'/api/students/' + studentId"
                        @close="closeDeleteWarning"></delete-warning>
    </div>
</template>

<script>
    import deleteWarning from '../extra/deleteWarning'

    export default {
        name: "studentForm",

        components: {
            deleteWarning,
        },

        data: () => ({
            studentId: null,
            student: null,
            newImage: null,
            errorText: '',
            loading: false,
            showDeleteWarning: false,
        }),

        computed: {
            showForm() {
                return !_.isEmpty(this.student);
            },
        },

        created() {
            if (this.$route.params.id) {
                this.studentId = this.$route.params.id;
                this.getData();
            } else {
                this.$set(this, 'student', {
                    name: '',
                    surname: '',
                    patronymic: '',
                });
            }
        },

        methods: {
            getData() {
                this.$Progress.start();

                axios.get('api/students/' + this.studentId)
                    .then((response) => {
                        this.$Progress.finish();

                        if (response.data) {
                            this.student = response.data;
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

                let url = this.studentId ? '/api/students/' + this.studentId : '/api/students';
                let data = new FormData();

                data.append('name', this.student.name);
                data.append('surname', this.student.surname);
                data.append('patronymic', this.student.patronymic);

                data.append('_method', this.studentId ? 'put' : 'post');

                console.log(data);
                axios.post(url, data)
                    .then(response => {
                        this.$router.push('/students');
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
                    this.$router.push('/students');
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
