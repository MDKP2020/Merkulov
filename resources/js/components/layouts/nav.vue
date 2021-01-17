<template>
    <aside class="menu">
        <ul class="menu-list">
            <p class="menu-label">Основное</p>
                <li>
                    <router-link to="/students">Студенты</router-link>
                </li>
                <li>
                    <router-link to="/applicants">Абитуриенты</router-link>
                </li>
                <li>
                    <router-link to="/departments">Кафедры</router-link>
                </li>
                <li>
                    <router-link to="/majors">Направления</router-link>
                </li>
        </ul>
    </aside>
</template>

<script>
</script>

<style scoped>
    .active-menu-item {
        border: 1px solid #1976D2;
        border-radius: 4px !important;
        box-shadow: 0 0 10px rgba(25, 118, 210, 1);
    }

    .active-menu-item:hover {
        border-radius: 4px !important;
        border: 1px solid #1976D2;
        border-radius: 4px !important;
        box-shadow: 0 0 10px rgba(25, 118, 210, 1);
    }

    .tag.indicate {
        float: right;
        margin-top: -2px;
        margin-right: -8px;
    }

    .menu-list a {
        border-radius: 4px;
    }
</style>
