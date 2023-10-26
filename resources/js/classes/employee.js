export default class Employee {

    constructor(employee = {}) {

        this.id = employee.id || '';
        this.name = employee.name || '';
        this.lastName = employee.lastName || '';
        this.company_id = employee.company_id || '';
        this.email = employee.email || '';
        this.phone = employee.phone || '';

    }


    toJSON() {
        return {
            id: this.id,
            name: this.name,
            lastName: this.lastName,
            company_id: this.company_id,
            email: this.email,
            phone: this.phone
        };
    }
}
