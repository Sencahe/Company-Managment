<template>
    <nav class="min-vh-100">
        <!-- Left Pane Content -->

        <div class="nav-bar-cus p-2 py-4 d-flex flex-column justify-content-between bg-secondary h-100">

            <div class=" mb-0 d-flex align-items-center">
                <button class="btn p-1" @click="isNavPaneOpen = !isNavPaneOpen">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <ul class="mt-5 p-0 d-flex flex-column ">
                <li class="p-1 mb-4">
                    <RouterLink to="/dashboard" class="nav-link p-0">
                        <div class="d-flex">
                            <i class="fa-solid fa-dashboard"></i>
                            <p v-if="isNavPaneOpen" class="ms-3 p-0 m-0 me-4 text-light">Dashboard</p>
                        </div>
                    </RouterLink>
                </li>
                <li class="p-1 mb-4">
                    <RouterLink to="/dashboard/employees" class="nav-link p-0">
                        <div class="d-flex">
                            <i class="fa-solid fa-users"></i>
                            <p v-if="isNavPaneOpen" class="ms-3 p-0 m-0 me-4 text-light">Employees</p>
                        </div>
                    </RouterLink>
                </li>
                <li class="p-1 mb-4">
                    <RouterLink to="/dashboard/companies" class="nav-link p-0">
                        <div class="d-flex">
                            <i class="fa-solid fa-building"></i>
                            <p v-if="isNavPaneOpen" class="ms-3 pb-0 mb-0  me-4 text-light">Companies</p>
                        </div>
                    </RouterLink>
                </li>
            </ul>

            <div class="mt-5 d-flex align-items-center">
                <button class="btn p-1 d-flex" @click.prevent="logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                    <p v-if="isNavPaneOpen" class="ms-3 pb-0 mb-0 me-4 text-light">Logout</p>
                </button>
            </div>

        </div>

    </nav>

</template>

<script>


export default {
    name: "DashboardNavComponent",
    data() {
        return {
            isNavPaneOpen: false
        };
    },
    methods: {
        logout() {
            axios.post('/auth/logout',
                {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }
            ).then(response => {

                this.$router.push("/");

            }).catch(error => {

                console.error('Error:', error);

            });
        }
    }
}


</script>
<style>
i {
    font-size: 1.6rem;
    color: #ffffff;
    min-width: 2.4rem;
    text-align: center;

}

ul {
    list-style-type: none;
}

p {
    font-size: 1.2rem;
}
</style>
