<template>
    <h3 class="my-5">{{ actionLabel }} Employee</h3>

    <form class="text-start border p-4">

        <div class="mb-3">
            <label for="employeeName" class="form-label">Name</label>
            <input v-model="employee.name" type="text" class="form-control" id="employeeName" placeholder="Enter employee name" required>
        </div>

        <div class="mb-3">
            <label for="employeeLastName" class="form-label">LastName</label>
            <input  v-model="employee.lastName" type="text" class="form-control" id="employeeName" placeholder="Enter employee last name" required>
        </div>

        <div class="mb-3">
            <label for="employeeCompany" class="form-label">Company</label>
            <select v-model="employee.companyId" class="form-select" id="employeeCompany">
                <option v-if="isNewEmployee" value="" disabled selected hidden>Select a company...</option>
                <option v-for="(company,index) in companies" :key="index" :value="company.id">{{company.name}}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="employeeEmail" class="form-label">Email</label>
            <input v-model="employee.email" type="email" class="form-control" id="employeeEmail" placeholder="Enter company email" required>
        </div>

        <div class="mb-3">
            <label for="employeePhone" class="form-label">Phone</label>
            <input v-model="employee.phone" type="tel" class="form-control" id="websiteUrl" placeholder="Enter employee phone number" required>
        </div>
        <button @click="submit" class="btn btn-primary">Submit</button>
    </form>


</template>

<script>
import Employee from "../classes/employee.js"
import Company from "../classes/company.js"

export default {
    name: "EmployeeFormComponent",
    created() {
        this.id = this.$route.params.id;
        this.isNewEmployee = this.id == null
        this.actionLabel = this.isNewEmployee ? "Add New" : "Edit";

        if(this.isNewEmployee){
            this.employee = new Employee();
        } else {
            this.employee = new Employee(
                { id: 1, name: "Francisco", lastName: "Cahe", companyId: 1, email: "franciscocahe@gmail.com", phone: "+541167054615" },
            );
        }
    },
    data() {
        return {
            id: null,
            isNewEmployee: true,
            actionLabel: "",
            employee: null,
            companies: [
                { id: 1, name: "company", email:"ceo@company.com", websiteUrl: "www.company.com", logo: "/storage/images/company.png" },
            ]
        }
    },
    computed: {
    },
    methods: {
        getEmployee(){

        },
        submit(){
            console.log(this.employee);
        }
    }
}
</script>

<style></style>
