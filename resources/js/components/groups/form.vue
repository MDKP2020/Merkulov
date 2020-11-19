<template>
    <div class="content">
        <div class="title-row">
            <div>
                <p class="title">{{ groupId ? 'Редактирование' : 'Создание' }} группы</p>
            </div>

            <div v-if="groupId">
                <a @click="showDeleteWarning = true">Удалить</a>
            </div>
        </div>

        <nav class="breadcrumb has-bullet-separator">
            <ul>
                <li>
                    <router-link to="/departments">Кафедры</router-link>
                </li>
                <li class="is-active"><a>ПрИн-167</a></li>
            </ul>
        </nav>

        <p v-if="errorText" class="has-text-danger">{{ errorText }}</p>

        <div class="box">
                <table class="table is-fullwidth">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Имя студента</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>1</th>
                        <td>
                            <ul>
                                <li>
                                    <a>Виктор Комаров</a>
                                </li>
                            </ul>
                        </td>
                        <td>
                            <a>Отчислить</a>
                        </td>
                    </tr>
                    <tr>
                        <th>2</th>
                        <td>
                            <ul>
                                <li>
                                    <a>Григорий Комаров</a>
                                </li>
                            </ul>
                        </td>
                        <td>
                            <a>Отчислить</a>
                        </td>
                    </tr>
                    <tr>
                        <th>3</th>
                        <td>
                            <ul>
                                <li>
                                    <a>Петр Смирнов</a>
                                </li>
                            </ul>
                        </td>
                        <td>
                            <a>Отчислить</a>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="field">
                    <div class="control">
                        <button class="button is-danger">Расформировать группу</button>
                    </div>
                </div>

                <div class="field is-grouped has-text-right">
                    <div class="control">
                        <button type="submit" class="button is-primary" :class="{ 'is-loading': loading }"
                                :disabled="loading">{{ groupId ? 'Обновить' : 'Сохранить' }}
                        </button>
                        <router-link to="/groups" class="button">Вернуться к списку</router-link>
                    </div>
                </div>
        </div>

        <delete-warning v-if="showDeleteWarning"
                        type="группы"
                        :name="group.surname + ' ' + group.name + ' ' + group.patronymic"
                        :path="'/api/groups/' + groupId"
                        @close="closeDeleteWarning"></delete-warning>
    </div>
</template>

<script>
    import deleteWarning from '../extra/deleteWarning'

    export default {
        name: "groupForm",

        components: {
            deleteWarning,
        },

        data: () => ({
            groupId: 1,
            group: {},
            errorText: '',
            loading: false,
            showDeleteWarning: false,
        }),

        computed: {
            showForm() {
                return !_.isEmpty(this.group);
            },
        },

        created() {
            // if (this.$route.params.id) {
            //     this.groupId = this.$route.params.id;
            //     this.getData();
            // } else {
            //     this.$set(this, 'group', {
            //         name: '',
            //         surname: '',
            //         patronymic: '',
            //         score: '',
            //         academic_degree: 'bachelor'
            //     });
            // }
        },

        methods: {
            getData() {
                this.$Progress.start();

                axios.get('api/groups/' + this.groupId)
                    .then((response) => {
                        this.$Progress.finish();

                        if (response.data) {
                            this.group = response.data;
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

                let url = this.groupId ? '/api/groups/' + this.groupId : '/api/groups';
                let data = new FormData();

                data.append('name', this.group.name);
                data.append('surname', this.group.surname);
                data.append('patronymic', this.group.patronymic);
                data.append('score', this.group.score);
                data.append('academic_degree', this.group.academic_degree);

                data.append('_method', this.groupId ? 'put' : 'post');

                console.log(data);
                axios.post(url, data)
                    .then(response => {
                        this.$router.push('/groups');
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
                    this.$router.push('/groups');
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
