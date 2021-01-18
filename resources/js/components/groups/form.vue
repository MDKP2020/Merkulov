<template>
  <div class="content">
    <div class="title-row">
      <div>
        <p class="title">{{ groupId ? 'Редактирование' : 'Создание' }} группы</p>
      </div>
    </div>

    <nav class="breadcrumb has-bullet-separator">
      <ul>
        <li>
          <router-link to="/departments">Кафедры</router-link>
        </li>
        <!--        <li class="is-active" v-if="group.major"><a>{{ student.major }}</a></li>-->
      </ul>
    </nav>

    <p v-if="errorText" class="has-text-danger">{{ errorText }}</p>

    <div class="box">
      <form @submit="send" v-if="showForm">
        <div class="field">
          <label class="label" for="academic_degree">Академическая ступень</label>
          <div class="field-body">
            <div class="field">
              <div class="control is-expanded">
                <select id="academic_degree" class="select is-fullwidth" v-model="group.academic_degree">
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
          <label class="label" for="major_id">Направление</label>
          <div class="control" v-if="majors && majors.length">
            <div class="select  is-fullwidth">
              <select id="major_id" v-model="group.major_id">
                <option :value="null">Не выбрано</option>
                <option :value="major.id" v-for="major in majors">{{ major.full_name }}</option>
              </select>
            </div>
          </div>
          <p v-else>Направления отсутствуют</p>
        </div>

        <div class="field">
          <label class="label" for="number">Номер группы</label>
          <div class="control">
            <input id="number" type="text" class="input" v-model="group.number" max="255"/>
          </div>
        </div>

        <label v-if="groupId" class="label" for="group_students">Состав группы</label>
        <table v-if="groupId" id="group_students" class="table is-fullwidth">
          <thead>
          <tr>
            <th></th>
            <th>Имя студента</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(student, index) in group.students">
            <th>{{ ++index }}</th>
            <td>
              <router-link :to="'/students/' + student.id + '/edit'" v-if="student.groups[0].number === group.number">{{
                  student.surname + ' ' +
                  student.name + ' ' + student.patronymic
                }} ({{ $parent.STATUSES[student.status] }})
              </router-link>
              <router-link style="color: red" :to="'/students/' + student.id + '/edit'" v-else>{{
                  student.surname + ' ' +
                  student.name + ' ' + student.patronymic
                }} (переведен из данной группы в текущем учебном году)
              </router-link>
            </td>
            <td>
              <a v-if="student.groups[0].number === group.number && student.status !== 'expelled'"
                 @click="setStudentStatusExpelled(index - 1)">Отчислить</a>
            </td>
          </tr>
          </tbody>
        </table>

        <!--      <div class="field control" v-else>-->
        <!--        <button class="button is-success">Добавить студента</button>-->
        <!--        &lt;!&ndash;          TODO здесь предлагать абитуриентов &ndash;&gt;-->
        <!--      </div>-->

        <div class="field" v-if="groupId">
          <div class="control">
            <button type="button" @click="showDeleteWarning = true" class="button is-danger">Расформировать группу</button>
          </div>
        </div>

        <div class="field is-grouped has-text-right">
          <div class="control">
            <button type="submit" class="button is-primary" :class="{ 'is-loading': loading }"
                    :disabled="loading">{{ groupId ? 'Обновить' : 'Сохранить' }}
            </button>
            <router-link to="/departments" class="button">Вернуться к списку</router-link>
          </div>
        </div>
      </form>
      <div v-else>
        <p>Не удалось загрузить данные</p>
      </div>
    </div>

    <delete-warning v-if="showDeleteWarning"
                    type="группу"
                    :name="group.number"
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
    groupId: null,
    group: null,
    majors: {},
    errorText: '',
    loading: false,
    showDeleteWarning: false
  }),
  computed: {
    showForm() {
      return !_.isEmpty(this.group);
    },
  },
  created() {
    this.getMajors();

    if (this.$route.params.id) {
      this.groupId = this.$route.params.id;
      this.getData();
    } else {
      this.$set(this, 'group', {
        number: '',
        academic_degree: 'bachelor',
        department_id: this.$route.params.department_id,
        major_id: {}
      });
    }
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

    getMajors() {
      this.$Progress.start();
      axios.get('api/majors/')
          .then((response) => {
            this.$Progress.finish();
            if (response.data) {
              this.majors = response.data;
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

      data.append('number', this.group.number);
      data.append('academic_degree', this.group.academic_degree);
      data.append('major_id', this.group.major_id);

      data.append('_method', this.groupId ? 'put' : 'post');
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
    setStudentStatusExpelled(index) {
        this.group.students[index].status = 'expelled';
        //TODO Давыд
    }
  },
}
</script>

<style scoped>
.field {
  margin-bottom: 25px;
}
</style>