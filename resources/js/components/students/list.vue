<template>
    <div class="content">
        <h1>Студенты</h1>
        <p>
            <router-link to="/students/create">Создать студента</router-link>
        </p>
        <div style="display: flex; flex-direction: row;">
            <button class="button custom-btn-blue" style="margin: 15px 10px 15px 0px " onclick="document.querySelector('#filter-c').classList.toggle('disable-element');">Фильтр</button>
            <input type="text"
                   class="input"
                   placeholder="Начните вводить фамилию студента или ее часть"
                   autocomplete="off"
                   @blur="showOptions = false"
                   @focus="(!isEmpty) ? showOptions = true : ''"
                   v-model = "st_name"
            />
        </div>
        <div class="disable-element fltr-container" id="filter-c">
<!--            <v-select
                    multiple
                    placeholder="Выберите кафедру"
                    :options="['ПОАС', 'Физика', 'ЭВМ']">
            </v-select>
            <v-select
                    style="margin-top: 10px;"
                    multiple
                    placeholder="Выберите направление"
                    :options="['ПрИн', 'Физика']">
            </v-select>-->
            <v-select v-model="ac_deg"
                      style="margin-top: 10px;"
                      multiple
                      placeholder="Выберите уровень обучения"
                      :options="['Бакалавр', 'Магистр', 'Аспирант', 'Специалист']">
            </v-select>
<!--            <div style="display: flex; flex-direction: row; margin-top: 20px;">
                <v-select
                    style="width: auto;"
                    placeholder="Выберите учебный год"
                    :options="['2019-2020']">
                </v-select>

            </div>-->
        </div>

        <table class="table is-fullwidth" v-if="students.length">
            <thead>
            <tr>
                <th></th>
                <th>Имя студента</th>
                <th>Номер зачетной книжки</th>
                <th>Группа</th>
                <th>Дата начала обучения</th>
                <th>Статус</th>
                <th>Уровень обучения</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(student, index) in students" v-if="(!ac_deg.length || ac_deg.length && ac_deg.includes($parent.ACADEMIC_DEGREES[student.academic_degree])) && (!st_name || st_name && student.surname.toLowerCase().includes(st_name.toLowerCase()))">
                <th>{{ ++index }}</th>
                <td><router-link :to="'/students/' + student.id + '/edit'">{{ student.surname + ' ' + student.name + ' ' + student.patronymic }}</router-link></td>
                <td>{{ student.transcript }}</td>
                <td>
                  <ul>
                    <li v-for="(group, index) in student.groups">
                      <p style="margin-top: 20px" class="has-text-success" v-if="index === 1">Ранее состоял в группах</p>
                        <router-link :to="'/groups/' + group.id + '/edit'">{{ group.number }}</router-link>
                    </li>
                  </ul>
                </td>
                <td>Дата начала обучения</td>
                <td>{{ $parent.STATUSES[student.status] }}</td>
                <td>{{ $parent.ACADEMIC_DEGREES[student.academic_degree] }}</td>
            </tr>
            </tbody>
        </table>
        <p v-else>Пока пусто</p>
    </div>
</template>

<script>
    import moment from 'moment'

    moment.locale('ru');

    export default {
        data: () => ({
            students: [],
            showOptions: false,
            ac_deg : [],
            st_name : ''
        }),

        created() {
            this.getData();
        },

        watch: {
            isEmpty: function (empty) {
                if (empty) {
                    this.showOptions = false;
                }
            }
        },

        methods: {
            getData() {
                this.$Progress.start();

                axios.get('api/students').then(response => {
                    this.$Progress.finish();

                    this.students = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
        },

    }
</script>

<style scoped>
    .input {
        margin: 15px 0;
    }

    .disable-element{
        display: none!important;
    }

    .custom-btn-blue{
        color: white;
        border: 1px solid white;
        background-color: #0a78ae;
    }

    .fltr-container{
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }
</style>
