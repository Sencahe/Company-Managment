<template>
    <h3 class="my-5">{{ labelAction }} Employee</h3>

    <form v-if="isNewEmployee || employee.id != undefined" class="text-start border p-4">

        <p v-if="unexpectedError" class="text-danger">There has been an unexpected error. Please, try again later</p>
        <!-- Name -->
        <div class="mb-3">
            <div class="d-flex">
                <label for="employeeName" class="form-label" :class="{ 'text-danger': errorData.name }">Name</label>
                <label v-if="errorData.name" class="text-danger ms-1"> - {{ errorData.name[0] }}</label>
            </div>
            <input v-model="employee.name" type="text" class="form-control" :class="{ 'border-danger': errorData.name }" id="employeeName" placeholder="Enter employee name" required>
        </div>
        <!-- Last Name -->
        <div class="mb-3">
            <div class="d-flex">
                <label for="employeeLastName" class="form-label" :class="{ 'text-danger': errorData.lastName }">LastName</label>
                <label v-if="errorData.lastName" class="text-danger ms-1"> - {{ errorData.lastName[0] }}</label>
            </div>
            <input  v-model="employee.lastName" type="text" class="form-control" :class="{ 'border-danger': errorData.lastName }" id="employeeName" placeholder="Enter employee last name" required>

        </div>
        <!-- Companies -->
        <div class="mb-3">
            <div class="d-flex" >
                <label for="employeeCompany" class="form-label"  :class="{ 'text-danger': errorData.company_id }">Company</label>
                <label v-if="errorData.company_id" class="text-danger ms-1"> - {{ errorData.company_id[0] }}</label>
            </div>
            <select v-model="employee.company_id" class="form-select text-dark" :class="{ 'border-danger': errorData.company_id }" id="employeeCompany" :filterable="true" required>
                <option v-if="isNewEmployee" value="" disabled selected hidden>Select a company...</option>
                <option v-for="(company, index) in companies" :key="index" :value="company.id">{{ company.name }}</option>
            </select>

        </div>
        <!-- Email -->
        <div class="mb-3">
            <div class="d-flex">
                <label for="employeeEmail" class="form-label" :class="{ 'text-danger': errorData.email }">Email</label>
                <label v-if="errorData.email" class="text-danger ms-1"> - {{ errorData.email[0] }}</label>
            </div>
            <input v-model="employee.email" type="email" class="form-control" :class="{ 'border-danger': errorData.email }" id="employeeEmail" placeholder="Enter company email">
        </div>
        <!-- Phone -->
        <div class="mb-3">
            <div class="d-flex">
                <label for="employeePhone" class="form-label" :class="{ 'text-danger': errorData.phone }">Phone</label>
                <label v-if="errorData.phone" class="text-danger ms-1"> - {{ errorData.phone[0] }}</label>
            </div>
            <input v-model="employee.phone" type="tel" class="form-control" :class="{ 'border-danger': errorData.phone }" id="websiteUrl" placeholder="Enter employee phone number">
        </div>

        <button @click.prevent="submit" class="btn btn-primary">Submit</button>
    </form>

    <div v-else>
        <p>404 Employee not found :(</p>
    </div>

</template>

<script>
import Employee from "../classes/employee.js"
import axios from "axios";


export default {
    name: "EmployeeFormComponent",
    created() {
        if (this.isNewEmployee) {
            this.employee = new Employee();
        } else {
            axios.get('/request/employee/' + this.id
            ).then(response => {
                this.employee = response.data;
            }).catch(error => {

            });
        }
        this.getCompanies();
    },
    data() {
        return {
            id: null,
            employee: {},
            companies: [],
            errorData: {},
            unexpectedError: false
        }
    },
    computed: {
        labelAction() {
            return this.isNewEmployee ? "Add New" : "Edit";
        },
        isNewEmployee() {
            this.id = this.$route.params.id;
            return this.id == null;
        },
        axiosConfig() {

            var config = {
                method: this.isNewEmployee ? "post" : "put",
                url: "/request/employee" + (this.isNewEmployee ? "" : "/" + this.id),
                data: this.employee,
                headers: { 'Content-Type': 'multipart/form-data' }
            };
            return config;
        }
    },
    methods: {
        getCompanies() {
            axios.get('/request/companies')
                .then(response => {
                    this.companies = response.data;
                }).catch(error => {
                    //console.log(error);
                })
        },
        submit() {
            this.errorData = {};
            this.unexpectedError = false;

            axios(this.axiosConfig)
                .then(response => {

                    this.$swal({
                        icon: 'success',
                        title: `${this.isNewEmployee ? 'Created' : 'Updated'}!`,
                        text: `${this.employee.name} ${this.employee.lastName} was successfully ${this.isNewEmployee ? 'created' : 'updated'}!`,
                    })
                    this.employee = this.isNewEmployee ? {} : this.employee;

                }).catch(error => {

                    if (error.response.status == 422) {
                        this.errorData = error.response.data.errors;

                    } else if (error.response.status == 403) {
                        this.$swal({
                            icon: 'error',
                            title: 'Forbidden!',
                            text: 'You are not allowed to perform this action!',
                        })
                    } else {
                        this.unexpectedError = true;
                    }

                });
        }

    }
}
</script>

<style></style>
