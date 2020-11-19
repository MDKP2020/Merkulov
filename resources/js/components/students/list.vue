<template>
    <div class="content">
        <h1>Студенты</h1>

        <p>
            <router-link to="/students/create">Создать студента</router-link>
        </p>
<!--            <v-select-->
<!--                    multiple-->
<!--                    placeholder="Выберите кафедру"-->
<!--                    :options="['ПОАС', 'Физика', 'ЭВМ']">-->
<!--            </v-select>-->

<!--            <v-select-->
<!--                    multiple-->
<!--                    placeholder="Выберите направление"-->
<!--                    :options="['ПрИн', 'Физика']">-->
<!--            </v-select>-->

<!--            <v-select-->
<!--                    placeholder="Выберите учебный год"-->
<!--                    :options="['2019-2020']">-->
<!--            </v-select>-->

        <input type="text"
               class="input"
               placeholder="Начните вводить имя студента"
               autocomplete="off"
               @blur="showOptions = false"
               @focus="(!isEmpty) ? showOptions = true : ''"
        />

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
            <tr v-for="(student, index) in students">
                <th>{{ ++index }}</th>
                <td><router-link :to="'/students/' + student.id + '/edit'">{{ student.surname + ' ' + student.name + ' ' + student.patronymic }}</router-link></td>
                <td>{{ student.transcript }}</td>
                <td>Группа</td>
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
</style>
