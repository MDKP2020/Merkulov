<template>
    <div class="content">
        <h1>Абитуриенты</h1>


        <div class="filter-wrap">

            <p>
                <router-link to="/applicants/create">Создать абитуриента </router-link>
            </p>
<!--            <v-select v-model="kafedra"
                    multiple
                    placeholder="Выберите кафедру"
                    :options="['ПОАС', 'Физика', 'ЭВМ']">
            </v-select>

            <v-select
                    multiple
                    placeholder="Выберите направление"
                    :options="['ПрИн', 'Физика', 'САПР']">
            </v-select>-->
        </div>

        <input type="text"
               class="input"
               placeholder="Начните вводить фамилию абитуриента"
               autocomplete="off"
               @blur="showOptions = false"
               @focus="(!isEmpty) ? showOptions = true : ''"
               v-model = "apl_name"
        />

        <table class="table is-fullwidth" v-if="applicants.length">
            <thead>
            <tr>
                <th></th>
                <th>Имя абитуриента</th>
                <th>Сумма баллов</th>
                <th>Номер аттестата</th>
                <th>Уровень обучения</th>
                <th>Направления</th>
                <th>Дата создания</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(applicant, index) in applicants" v-if="!apl_name || apl_name && applicant.surname.toLowerCase().includes(apl_name.toLowerCase())">
                <th>{{ ++index }}</th>
                <td>
                    <router-link :to="'/applicants/' + applicant.id + '/edit'">{{ applicant.surname + ' ' +
                        applicant.name + ' ' + applicant.patronymic }}
                    </router-link>
                </td>
                <td>{{ applicant.score }}</td>
                <td>{{ applicant.certificate.serial + ' ' + applicant.certificate.number}}</td>
                <td>{{ $parent.ACADEMIC_DEGREES[applicant.academic_degree] }}</td>
                <td>
                    <ul>
                        <li v-for="(major, index) in applicant.majors">
                            <a>{{major.full_name}}</a>
                        </li>

                    </ul>
                </td>
                <td>{{ applicant.created_at }}</td>
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
            applicants: [],
            showOptions: false,
            apl_name : ''
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

                axios.get('api/applicants').then(response => {
                    this.$Progress.finish();

                    this.applicants = response.data;
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
