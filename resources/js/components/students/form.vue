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
                <li class="is-active" v-if="student.surname"><a>{{ student.surname }}</a></li>
            </ul>
        </nav>

        <p v-if="errorText" class="has-text-danger">{{ errorText }}</p>

        <div class="box">
            <form @submit="send" v-if="showForm">
                <div class="field">
                    <label class="label" for="name">Фамилия</label>
                    <div class="control">
                        <input id="surname" type="text" class="input" v-model="student.surname" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Имя</label>
                    <div class="control">
                        <input id="name" type="text" class="input" v-model="student.name" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Отчество</label>
                    <div class="control">
                        <input id="patronymic" type="text" class="input" v-model="student.patronymic" required max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="name">Номер зачетной книжки</label>
                    <div class="control">
                        <input id="transcript" type="text" class="input" v-model="student.transcript" max="255"/>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="status">Статус</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control is-expanded">
                                <select id="status" class="select is-fullwidth" v-model="student.status">
                                    <option value="studying">Учится</option>
                                    <option value="expelled">Отчислен</option>
                                    <option value="academic_leave">В академическом отпуске</option>
                                    <option value="graduated">Выпустился</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="academic_degree">Академическая ступень</label>
                    <div class="field-body">
                        <div class="field">
                            <div class="control is-expanded">
                                <select id="academic_degree" class="select is-fullwidth" v-model="student.academic_degree">
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
                <label class="label" for="major_id">Группа</label>
                <div v-if="transferStudent">
                  <div class="control" v-if="groups && groups.length">
                    <div class="select  is-fullwidth">
                      <select id="major_id" v-model="group_id">
                        <option :value="null">Не выбрано</option>
                        <option :value="group.id" v-for="group in groups">{{ group.number }}</option>
                      </select>
                    </div>
                  </div>
                  <p v-else>Группы отсутствуют
                    <router-link to="/groups/create/1">Создать группу</router-link>
                  </p>
                </div>
                <div v-else>
                  <ul>
                    <li v-for="(group, index) in student.groups">
                      <p style="margin-top: 20px" class="has-text-success" v-if="index === 1">Ранее состоял в группах</p>
                      <router-link :to="'/groups/' + group.id + '/edit'">{{ group.number }}</router-link>
                    </li>
                  </ul>
                  <div class="field">
                    <div class="control">
                      <button v-if="student.groups.length" @click="transferStudent = true" class="button is-danger">Перевести в другую группу</button>
                      <button v-else @click="transferStudent = true" class="button is-danger">Зачислить в группу</button>
                    </div>
                  </div>
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
                        :name="student.surname + ' ' + student.name + ' ' + student.patronymic"
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
            group_id: null,
            student: {},
            errorText: '',
            loading: false,
            showDeleteWarning: false,
            groups: {},
            transferStudent: false
        }),

        computed: {
            showForm() {
                return !_.isEmpty(this.student);
            },
        },

        created() {
          this.getGroups();
            if (this.$route.params.id) {
                this.studentId = this.$route.params.id;
                this.getData();
            } else {
                this.$set(this, 'student', {
                    name: '',
                    surname: '',
                    patronymic: '',
                    transcript: '',
                    status: 'studying',
                    academic_degree: 'bachelor'
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

            getGroups() {
              this.$Progress.start();
              axios.get('api/groups/')
                  .then((response) => {
                    this.$Progress.finish();
                    if (response.data) {
                      this.groups = response.data;
                    }
                  })
                  .catch(error => {
                    this.$Progress.finish();
                    console.log(error);
                    this.errorText = error.message;
                  });
            },

            send() {
              if (!this.group_id) {
                this.$toast.error('Студенту необходимо присвоить группу.');
              } else if(this.student.groups.length && this.group_id === this.student.groups[0].id) {
                this.$toast.info('Перевод невозможен. Группа, в которую вы собираетесь перевести студента, аналогична его текущей группе.');
              } else {
                this.$Progress.start();
                this.loading = true;

                let url = this.studentId ? '/api/students/' + this.studentId : '/api/students';
                let data = new FormData();

                data.append('name', this.student.name);
                data.append('surname', this.student.surname);
                data.append('patronymic', this.student.patronymic);
                data.append('transcript', this.student.transcript);
                data.append('status', this.student.status);
                data.append('academic_degree', this.student.academic_degree);
                data.append('group_id', this.group_id ?? '');

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
              }
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
