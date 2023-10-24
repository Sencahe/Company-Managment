<template>
    <div class="px-3">
        <div class="form-control container text-start" style="max-width: 450px">

            <form class="d-flex flex-column">
                <p v-if="loginAttemptFailed != ''" class="text-danger">{{ loginAttemptFailed }}</p>

                <label for="email" class="py-2">Email</label>
                <input type="email" email="email" v-model="user.email">

                <label for="password" class="py-2">Password</label>
                <input type="password" name="password" v-model="user.password">

                <button @click.prevent="login()" class="mt-4 btn btn-primary w-50" >Login</button>
            </form>

        </div>
</div>
</template>


<script>
import Axios from "axios"

export default {
    name: "LoginComponent",
    data() {
        return {
            user: {
                email: "",
                password: ""
            },
            loginAttemptFailed: "",
        }
    },
    methods: {
        login() {
            if (this.user.email === "" || this.user.password === "") {
                this.loginAttemptFailed = "Email and/or Password must be filled"
                return;
            } else {
                this.loginAttemptFailed = "";
            }

            Axios.post('/auth', {
                email: this.user.email,
                password: this.user.password
            }, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                this.$router.push("/dashboard");
            })
            .catch(error => {
                if(error.response.status == 401){
                    this.loginAttemptFailed = "Username and/or Password are incorrect";
                } else {
                    this.loginAttemptFailed = "There has been an error. Please, try again later";
                }

            });
        }
    }

}
</script>
