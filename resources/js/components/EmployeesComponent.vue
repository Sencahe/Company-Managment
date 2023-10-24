<template>
    <h3 class="py-4">List of Employees</h3>

    <div class="d-flex justify-content-start w-100">
        <RouterLink to="/dashboard/employee" tooltip="asdasdsa">
            <i class="ms-4 ms-lg-5 fa-solid text-success fa-user-plus">

            </i></RouterLink>
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
                <tr v-for="(employee, index) in paginatedEmployees" :key="index" :id="'employee-' + employee.id">
                    <td>
                        {{ employee.name }}
                    </td>
                    <td>
                        {{ employee.lastName }}
                    </td>
                    <td>
                        {{ employee.company }}
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
                            <button @click.prevent="deleteRecord(employee.id, employee.name, employee.lastName)" class="btn p-0">
                                <i class="fas px-3 fa-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>

        <nav class="mt-5">
            <ul class="pagination mb-4">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <a class="page-link" href="#" @click="prevPage">Previous</a>
                </li>
                <li class="page-item" v-for="page in pages" :key="page" :class="{ active: page === currentPage }">
                    <a class="page-link" href="#" @click="changePage(page)">{{ page }}</a>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === pages }">
                    <a class="page-link" href="#" @click="nextPage">Next</a>
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
            currentPage: 1,
            itemsPerPage: 10,
            employees: [
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" }, { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" }, { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
                { id: 1, name: "Francisco", lastName: "Cahe", company: "Company", email: "franciscocahe@gmail.com", phone: "+541167054615" },
            ]
        }
    },
    computed: {
        paginatedEmployees() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = this.currentPage * this.itemsPerPage;
            return this.employees.slice(start, end);
        },
        pages() {
            return Math.ceil(this.employees.length / this.itemsPerPage);
        }
    },
    methods: {
        changePage(page) {
            this.currentPage = page;
        },
        nextPage() {
            if (this.currentPage < this.pages) this.currentPage++;
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        deleteRecord(id,name, lastname) {
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
                    this.$swal(
                        'Deleted!',
                        `${name} ${lastname} was successfully deleted`,
                        'success'
                    )
                }
            });
        }
    }
}
</script>

<style></style>
