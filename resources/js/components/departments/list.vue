<template>
    <div class="content">
        <h1>Студенты</h1>

        <p>
            <router-link to="/departments/create">Создать кафедру</router-link>
        </p>

        <table class="table is-fullwidth" v-if="departments.length">
            <thead>
            <tr>
                <th></th>
                <th>Наименование</th>
                <th>Группы</th>
                <th>Количество студентов</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(department, index) in departments">
                <th>{{ ++index }}</th>
                <td><router-link :to="'/departments/' + department.id + '/edit'">{{ department.name }}</router-link></td>
                <td>
                    <ul>
                        <li>
                            <a>ИВТ-161</a>
                        </li>
                        <li>
                            <a>ИВТ-162</a>
                        </li>
                        <li>
                            <a>ИВТ-261</a>
                        </li>
                        <li>
                            <a>ИВТ-361</a>
                        </li>
                        <li>
                            <a>ИВТ-461</a>
                        </li>
                        <li>
                            <a>ИВТ-462</a>
                        </li>
                    </ul>
                </td>
                <td>34</td>
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
            departments: []
        }),

        created() {
            this.getData();
        },

        methods: {
            getData() {
                this.$Progress.start();

                axios.get('api/departments').then(response => {
                    this.$Progress.finish();

                    this.departments = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
        },

    }
</script>

<style scoped>
</style>
