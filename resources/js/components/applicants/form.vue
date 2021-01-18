<template>
    <div class="content">
        <div class="title-row">
            <div>
                <p class="title">{{ applicantId ? 'Редактирование' : 'Создание' }} абитуриента</p>
            </div>

            <div v-if="applicantId">
                <a @click="showDeleteWarning = true">Удалить</a>
            </div>
        </div>

        <nav class="breadcrumb has-bullet-separator">
            <ul>
                <li>
                    <router-link to="/applicants">Абитуриенты</router-link>
                </li>
                <li class="is-active" v-if="applicant.surname"><a>{{ applicant.surname }}</a></li>
            </ul>
        </nav>

        <p v-if="errorText" class="has-text-danger">{{ errorText }}</p>

        <div class="box">
            <form @submit="send" v-if="showForm">
                <div class="field">
                    <label class="label" for="name">Фамилия</label>
                    <div class="control">
                        <input id="surname" type="text" class="input" v-model="applicant.surname" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Имя</label>
                    <div class="control">
                        <input id="name" type="text" class="input" v-model="applicant.name" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Отчество</label>
                    <div class="control">
                        <input id="patronymic" type="text" class="input" v-model="applicant.patronymic" max="255"/>
                    </div>
                </div>

                <!--                <div class="field">-->
                <!--                    <label class="label" for="name">Номер зачетной книжки</label>-->
                <!--                    <div class="control">-->
                <!--                        <input id="transcript" type="text" class="input" v-model="applicant.transcript" max="255"/>-->
                <!--                    </div>-->
                <!--                </div>-->

                <div class="field">
                    <label class="label" for="academic_degree">Академическая ступень</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control is-expanded">
                                <select id="academic_degree" class="select is-fullwidth"
                                        v-model="applicant.academic_degree">
                                    <option value="bachelor">Бакалавр</option>
                                    <option value="specialist">Специалист</option>
                                    <option value="master">Магистр</option>
                                    <option value="postgraduate">Аспирант</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="academic_degree">Направление 1</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control is-expanded">
                                <select id="major1" class="select is-fullwidth"
                                        v-model="applicant.majors[0].id">
                                    <option :value="null">- - -</option>
                                    <option
                                        v-for="(maj, index) in allMajors"
                                        v-if="maj.id != applicant.majors[1].id  && maj.id != applicant.majors[2].id"
                                        :value="maj.id">
                                        {{ maj.full_name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <label class="label" for="academic_degree">Направление 2</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control is-expanded">
                                <select id="major2" class="select is-fullwidth"
                                        v-model="applicant.majors[1].id">
                                    <option :value="null">- - -</option>
                                    <option
                                        v-for="(maj, index) in allMajors"
                                        v-if="maj.id != applicant.majors[0].id  && maj.id != applicant.majors[2].id"
                                        :value="maj.id">
                                        {{ maj.full_name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <label class="label" for="academic_degree">Направление 3</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control is-expanded">
                                <select id="major3" class="select is-fullwidth"
                                        v-model="applicant.majors[2].id">
                                    <option :value="null">- - -</option>
                                    <option
                                        v-for="(maj, index) in allMajors"
                                        v-if="maj.id != applicant.majors[0].id  && maj.id != applicant.majors[1].id"
                                        :value="maj.id">
                                        {{ maj.full_name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Сумма баллов</label>
                    <div class="control">
                        <input id="score" type="text" class="input" v-model="applicant.score" max="255"/>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <button class="button is-danger">Зачислить</button>
                    </div>
                </div>

                <div class="field is-grouped has-text-right">
                    <div class="control">
                        <button type="submit" class="button is-primary" :class="{ 'is-loading': loading }"
                                :disabled="loading">{{ applicantId ? 'Обновить' : 'Сохранить' }}
                        </button>
                        <router-link to="/applicants" class="button">Вернуться к списку</router-link>
                    </div>
                </div>
            </form>
            <div v-else>
                <p>Не удалось загрузить данные</p>
            </div>
        </div>

        <delete-warning v-if="showDeleteWarning"
                        type="абитуриента"
                        :name="applicant.surname + ' ' + applicant.name + ' ' + applicant.patronymic"
                        :path="'/api/applicants/' + applicantId"
                        @close="closeDeleteWarning"></delete-warning>
    </div>
</template>

<script>
    import deleteWarning from '../extra/deleteWarning'

    export default {
        name: "applicantForm",

        components: {
            deleteWarning,
        },

        data: () => ({
            applicantId: null,
            applicant: {},
            errorText: '',
            loading: false,
            showDeleteWarning: false,
            allMajors: []
        }),

        computed: {
            showForm() {
                return !_.isEmpty(this.applicant);
            },
        },

        created() {
            this.getAllMajors();
            if (this.$route.params.id) {
                this.applicantId = this.$route.params.id;
                this.getData();
            } else {
                this.$set(this, 'applicant', {
                    name: '',
                    surname: '',
                    patronymic: '',
                    score: '',
                    academic_degree: 'bachelor',
                    majors: [{id:null},{id:null},{id:null}]
                });
            }
        },

        methods: {
            getData() {
                this.$Progress.start();

                axios.get('api/applicants/' + this.applicantId)
                    .then((response) => {
                        this.$Progress.finish();

                        if (response.data) {
                            this.applicant = response.data;

                            var unfillMajors = 3-this.applicant.majors.length;
                            for(var i = 0; i < unfillMajors; i++ ){
                                this.applicant.majors.push({id:null})
                            }
                        }
                    })
                    .catch(error => {
                        this.$Progress.finish();

                        console.log(error);
                        this.errorText = error.message;
                    });


            },

            getAllMajors(){
                axios.get('api/majors/')
                    .then((response) => {
                        this.$Progress.finish();

                        if (response.data) {
                            this.allMajors = response.data;
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

                var majors = []
                for(var i = 0; i<3;i++)
                    if(this.applicant.majors[i].id != null)
                        majors.push(this.applicant.majors[i].id)



                let url = this.applicantId ? '/api/applicants/' + this.applicantId : '/api/applicants';
                let data = new FormData();


                data.append('name', this.applicant.name);
                data.append('surname', this.applicant.surname);
                data.append('patronymic', this.applicant.patronymic);
                data.append('score', this.applicant.score);
                data.append('academic_degree', this.applicant.academic_degree);
                data.append('majors', majors);

                data.append('_method', this.applicantId ? 'put' : 'post');

                console.log(data);
                axios.post(url, data)
                    .then(response => {
                        this.$router.push('/applicants');
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
                    this.$router.push('/applicants');
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
