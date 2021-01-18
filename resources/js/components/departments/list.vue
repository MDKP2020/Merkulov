<template>
    <div class="content">
        <h1>Кафедры</h1>

        <div class="filter-wrap">

        <p>
            <router-link to="/departments/create">Создать кафедру</router-link>
        </p>

        <div class="field">
            <div class="control">
                <button @click="showWarning = true" class="button is-danger">Перевести на следующий учебный год</button>
            </div>
        </div>
        </div>

        <table class="table is-fullwidth" v-if="departments.length">
            <thead>
            <tr>
                <th></th>
                <th>Наименование</th>
                <th>Количество студентов</th>
                <th>Группы</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(department, index) in departments">
                <th>{{ ++index }}</th>
                <td><router-link :to="'/departments/' + department.id + '/edit'">{{ department.name }}</router-link></td>
                <td>34</td>
                <td>
                    <ul class="unmark">
                        <li v-for="(group, index) in department.groups">
                            <input type="checkbox">
                            <router-link :to="'/groups/' + group.id + '/edit'">{{ group.number }}</router-link>
                        </li>
                    </ul>
                </td>
                <td>
                   <router-link :to="'/groups/create/' + department.id">Создать группу</router-link>
                </td>
            </tr>
            </tbody>
        </table>
        <p v-else>Пока пусто</p>

        <warning v-if="showWarning"
                 text="перевести группы на следующий учебный год"
                 :path="''"
                 @close="closeWarning"></warning>

    </div>
</template>

<script>
    import moment from 'moment'
    import warning from '../extra/warning'

    moment.locale('ru');

    export default {
        components: {
            warning,
        },

        data: () => ({
            departments: [],
            showWarning: false,
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
            closeWarning(deleted) {
                this.showWarning = false;

                if (deleted) {
                    this.$router.push('/departments');
                }
            },
        },
    }
</script>

<style scoped>
    .unmark {
        list-style-type: none;
        margin: 0;
    }

    .filter-wrap {
        justify-content: space-between;
    }
</style>
