<template>
    <div class="content">
        <h1>Студенты</h1>

        <p>
            <router-link to="/students/create">Создать студента</router-link>
        </p>

        <table class="table is-fullwidth" v-if="students.length">
            <thead>
            <tr>
                <th></th>
                <th>Имя студента</th>
                <th>Номер зачетной книжки</th>
                <th>Группа</th>
                <th>Дата начала обучения</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(student, index) in students">
                <th>{{ ++index }}</th>
                <td><router-link :to="'/students/' + student.id + '/edit'">{{ student.name + ' ' + student.surname + ' ' + student.patronymic }}</router-link></td>
                <td>Номер зачетной книжки</td>
                <td>Группа</td>
                <td>Дата начала обучения</td>
                <td>Статус</td>
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
            students: []
        }),

        created() {
            this.getData();
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
</style>
