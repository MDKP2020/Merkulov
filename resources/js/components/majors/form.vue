<template>
    <div class="content">
        <div class="title-row">
            <div>
                <p class="title">{{ majorId ? 'Редактирование' : 'Создание' }} направления</p>
            </div>

            <div v-if="majorId">
                <a @click="showDeleteWarning = true">Удалить</a>
            </div>
        </div>

        <nav class="breadcrumb has-bullet-separator">
            <ul>
                <li><router-link to="/majors">Направления</router-link></li>
                <li class="is-active" v-if="major.full_name"><a>{{ major.full_name }} ({{major.abbreviation}})</a></li>
            </ul>
        </nav>

        <p v-if="errorText" class="has-text-danger">{{ errorText }}</p>

        <div class="box">
            <form @submit="send" v-if="showForm">
                <div class="field">
                    <label class="label" for="name">Название</label>
                    <div class="control">
                        <input id="surname" type="text" class="input" v-model="major.full_name" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Аббревиатура</label>
                    <div class="control">
                        <input id="name" type="text" class="input" v-model="major.abbreviation" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Кафедра</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control is-expanded">
                                <select id="departament" class="select is-fullwidth" v-model="major.department.id" :required="true" >
                                    <option v-for="(dep, index) in departaments"
                                            v-bind:value="dep.id"
                                            :selected="dep.id == major.department.id" >
                                        {{dep.name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="field is-grouped has-text-right">
                    <div class="control">
                        <button type="submit" class="button is-primary" :class="{ 'is-loading': loading }"
                                :disabled="loading">{{ majorId ? 'Обновить' : 'Сохранить' }}</button>
                        <router-link to="/majors" class="button">Вернуться к списку</router-link>
                    </div>
                </div>
            </form>
            <div v-else>
                <p>Не удалось загрузить данные</p>
            </div>
        </div>

        <delete-warning v-if="showDeleteWarning"
                        type="направление"
                        :name="major.full_name + ' (' + major.abbreviation + ') '"
                        :path="'/api/majors/' + majorId"
                        @close="closeDeleteWarning"></delete-warning>
    </div>
</template>

<script>
import deleteWarning from '../extra/deleteWarning'

export default {
    name: "majorCreate",

    components: {
        deleteWarning,
    },

    data: () => ({
        majorId: null,
        major: {},
        departaments: {},
        errorText: '',
        loading: false,
        showDeleteWarning: false,
    }),

    computed: {
        showForm() {
            return !_.isEmpty(this.major);
        },
    },

    created() {
        this.getDepartaments();
        if (this.$route.params.id) {
            this.majorId = this.$route.params.id;
            this.getData();
        } else {
            this.$set(this, 'major', {
                full_name: '',
                abbreviation: '',
                department: {}
            });
        }

    },

    methods: {
        getData() {
            this.$Progress.start();

            axios.get('api/majors/' + this.majorId)
                .then((response) => {
                    this.$Progress.finish();

                    if (response.data) {
                        this.major = response.data;
                    }
                })
                .catch(error => {
                    this.$Progress.finish();

                    console.log(error);
                    this.errorText = error.message;
                });


        },

        getDepartaments(){
            axios.get('api/departments/')
                .then((response) => {
                    this.$Progress.finish();

                    if (response.data) {
                        this.departaments = response.data;
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

            let url = this.majorId ? '/api/majors/' + this.majorId : '/api/majors';
            let data = new FormData();

            data.append('full_name', this.major.full_name);
            data.append('abbreviation', this.major.abbreviation);
            data.append('department_id', this.major.department.id);

            data.append('_method', this.majorId ? 'put' : 'post');

            console.log(data);
            axios.post(url, data)
                .then(response => {
                    this.$router.push('/majors');
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
                this.$router.push('/majors');
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
