<template>
    <nav>

        <!--Top -->
        <div class="d-flex flex-column justify-content-between bg-secondary h-100">
            <!-- Hamburguer menu -->
            <div class="py-2 d-flex flex-column ">
                <div class="ms-1 mb-0 d-flex align-items-center justify-content-start w-100">
                    <button class="btn p-1" @click="isNavPaneOpen = !isNavPaneOpen">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <!-- User Data -->
                <div v-if="isNavPaneOpen" class="d-block mt-2">
                    <p  class="text-start ms-2 m-0 text-light">Hi {{user.name}}!</p>
                    <span class="text-start d-block p-0 ms-2 text-light">You are {{ userType }}!</span>
                </div>
            </div>
            <!--Middle -->
            <ul class="my-3 my-md-5 p-0 d-flex flex-column" :class="{'responsive-nav-items':!isNavPaneOpen}">
                <!--Dashboard nav -->
                <li class="nav-item">
                    <RouterLink to="/dashboard" class="nav-link p-2 py-3" active-class="active-link">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-dashboard"></i>
                            <p v-if="isNavPaneOpen" class="ms-3 p-0 m-0 me-4 text-light">Dashboard</p>
                        </div>
                    </RouterLink>
                </li>
                <!--Employees nav -->
                <li class="nav-item">
                    <RouterLink to="/employees" class="nav-link p-2 py-3" active-class="active-link">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-users"></i>
                            <p v-if="isNavPaneOpen" class="ms-3 p-0 m-0 me-4 text-light">Employees</p>
                        </div>
                    </RouterLink>
                </li>
                <!--Companies nav -->
                <li class="nav-item">
                    <RouterLink to="/companies" class="nav-link p-2 py-3" active-class="active-link">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-building"></i>
                            <p v-if="isNavPaneOpen" class="ms-3 pb-0 mb-0 me-4 text-light">Companies</p>
                        </div>
                    </RouterLink>
                </li>
            </ul>
            <!--Bottom -->
            <div class="d-flex" :class="{'responsive-nav-items':!isNavPaneOpen}">
                <!--Logout -->
                <button class="btn flex-grow-1 d-flex align-items-center p-2 py-3 nav-item" @click.prevent="logout">
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
    name: "NavComponent",
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
    display: none!important; 
  }
}
</style>
