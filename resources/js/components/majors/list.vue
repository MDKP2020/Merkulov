<template>
    <div class="content">
        <h1>Направления</h1>


        <div class="filter-wrap">

            <p>
                <router-link to="/majors/create">Создать направление</router-link>
            </p>


        </div>


        <table class="table is-fullwidth" v-if="majors.length">
            <thead>
            <tr>
                <th></th>
                <th>Название</th>
                <th>Кафедра</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(major, index) in majors">
                <th>{{ ++index }}</th>
                <td>
                    <router-link :to="'/majors/' + major.id + '/edit'">{{ major.full_name + ' (' +
                        major.abbreviation + ') '}}
                    </router-link>
                </td>

                <td>{{ major.department.name }}</td>
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
            majors: [],
            showOptions: false,
        }),

        created() {
            this.getData();
        },

        methods: {
            getData() {
                this.$Progress.start();

                axios.get('api/majors').then(response => {
                    this.$Progress.finish();

                    this.majors = response.data;
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
