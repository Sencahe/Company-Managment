<template>
    <nav>
        <div class="p-2 py-md-4 d-flex flex-column justify-content-between bg-secondary h-100">

            <div class="p-0 d-flex flex-column ">
                <div class=" mb-0 d-flex align-items-center justify-content-between w-100">
                    <button class="btn p-1" @click="isNavPaneOpen = !isNavPaneOpen">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <!--<p class="d-block d-md-none text-light me-2">Company Managment System</p>-->
                </div>

                <div v-if="isNavPaneOpen" class="d-block mt-2">
                    <p  class="text-start ms-2 m-0 text-light">Hi {{user.name}}!</p>
                    <span class="text-start d-block p-0 ms-2 text-light">You are {{ userType }}!</span>
                </div>
            </div>

            <ul class="my-3 my-md-5 p-0 d-flex flex-column" :class="{'responsive-nav-items':!isNavPaneOpen}">
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

            <div class="d-flex align-items-center" :class="{'responsive-nav-items':!isNavPaneOpen}">
                <button class="btn p-1 d-flex" @click.prevent="logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                    <p v-if="isNavPaneOpen" class="ms-3 pb-0 mb-0 me-4 text-light">Logout</p>
                </button>
            </div>

        </div>

    </nav>
</template>

<script>
import axios from 'axios';

export default {
    name: "DashboardNavComponent",
    data() {
        return {
            isNavPaneOpen: false,
            user: "",
            userType: ""
        };
    },
    created() {
        this.user = JSON.parse(localStorage.getItem('user'));
        this.userType = this.user.admin === 1 ? "Admin" : "User"
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
                //console.error('Error:', error);
            });
        }
    }
}
</script>

<style>

@media (max-width: 767px) {
  .responsive-nav-items {
    display: none!important; /* Adjust the minimum width according to your requirement */
  }
}
</style>
