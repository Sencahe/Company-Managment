import { createWebHistory, createRouter } from "vue-router";
import axios from "axios";
import IndexComponent from "./components/IndexComponent.vue"
import MainComponent from "./components/MainComponent.vue"
import DashboardComponent from "./components/DashboardComponent.vue"
import EmployeesComponent from "./components/EmployeesComponent.vue"
import EmployeeFormComponent from "./components/EmployeeFormComponent.vue"
import CompaniesComponent from "./components/CompaniesComponent.vue"
import CompanyFormComponent from "./components/CompanyFormComponent.vue"
import NotFound from "./components/NotFound.vue"


const routes = [
    { path: "/", name: "Index", component: IndexComponent },
    {
        path: "/", name: "MainComponent", component: MainComponent, meta: { requiresAuth: true }, children: [
            { path: "/dashboard", name: "Dashboard", component: DashboardComponent },
            { path: "/employees", name: "EmployeesComponent", component: EmployeesComponent },
            { path: "/employees/add", name: "EmployeeFormComponentAdd", component: EmployeeFormComponent },
            { path: "/employee/edit/:id", name: "EmployeeFormComponentEdit", component: EmployeeFormComponent },
            { path: "/companies", name: "CompaniesComponent", component: CompaniesComponent },
            { path: "/company/add", name: "CompanyFormComponentAdd", component: CompanyFormComponent },
            { path: "/company/edit/:id", name: "CompanyFormComponentEdit", component: CompanyFormComponent }
        ]
    },
    { path: "/:pathMatch(.*)*", component: NotFound }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        axios.get('/auth/session')
            .then(() => {
                next();
            })
            .catch(() => {
                next('/');
            });

    } else {
        // Proceed to the requested route
        next();
    }
});




export default router;
