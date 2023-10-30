<template>
    <h3 class="py-4">List of Employees</h3>

    <div class="d-flex justify-content-start w-100 mb-3">
        <RouterLink :to="{name: 'EmployeeFormComponentAdd'}">
            <i class="add-button fa-solid text-success fa-user-plus"></i>
        </RouterLink>
    </div>

    <div class="w-100 table-responsive">
        <table class="table">

            <thead>
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Company</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(employee, index) in employees" :key="index" :id="'employee-' + employee.id" class="table-row">
                    <td>
                        {{ employee.name }}
                    </td>
                    <td>
                        {{ employee.lastName }}
                    </td>
                    <td>
                        {{ employee.company.name }}
                    </td>
                    <td>
                        {{ employee.email }}
                    </td>
                    <td>
                        {{ employee.phone }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <RouterLink :to="{ name: 'EmployeeFormComponentEdit', params: { id: employee.id } }">
                                <i class="fas px-3 fa-edit text-primary"></i>
                            </RouterLink>
                            <button @click.prevent="deleteEmployeeConfirm(employee.id, employee.name, employee.lastName)" class="btn p-0">
                                <i class="fas px-3 fa-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>

        <nav class="mt-5">
            <ul class="pagination mb-4">
                <li class="page-item" v-for="page in pages" :key="page" :class="{ active: page.active, disabled: page.url === null }">
                    <button v-html="page.label" class="page-link" :disabled="page.active" href="#" @click="getCompanies(page.url)"></button>
                </li>
            </ul>
        </nav>

    </div>
</template>


<script>
export default {
    name: "EmployeesComponent",
    data() {
        return {
            employees: [],
            pages: [],
            currentPageUrl: ""
        }
    },
    created(){
        this.getEmployees("/request/employees?page=1");
    },
    computed: {
    },
    methods: {
        getEmployees(pageUrl) {
            axios.get(pageUrl
            ).then(response => {
                this.employees = response.data.data;
                this.pages = response.data.links;
                this.currentPageUrl = pageUrl;
            }).catch(error => {
                //
            });
        },
        deleteEmployeeConfirm(id, name, lastname) {
            this.$swal({
                title: 'Are you sure?',
                text: `You will delete ${name} ${lastname} from the employees list.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteEmployee(id,name,lastname);
                }
            });
        },
        deleteEmployee(id, name, lastname) {
            axios.delete('/request/employee/' + id
            ).then(response => {
                this.$swal({
                    icon: 'success',
                    title: 'Deleted!',
                    text: `${name} ${lastname} was successfully deleted`,
                })

                this.getEmployees(this.currentPageUrl);

            }).catch(error => {
                var title = "";
                var text = "";
                if(error.response.status == 403){
                    title = "Forbbiden";
                    text = "You are not allowed to perform this action!";
                } else {
                    title = "Oops...";
                    text = "Something went wrong!";
                }
                this.$swal({
                    icon: 'error',
                    title: title,
                    text: text
                })
            });
        }
    }
}
</script>

<style></style>
