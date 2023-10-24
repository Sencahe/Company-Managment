export default class Employee {

    constructor(employee = {}) {

        this.id = employee.id || '';
        this.name = employee.name || '';
        this.lastName = employee.lastName || '';
        this.companyId = employee.companyId || '';
        this.email = employee.email || '';
        this.phone = employee.phone || '';

    }


    toJSON() {
        return {
            id: this.id,
            name: this.name,
            lastName: this.lastName,
            companyId: this.companyId,
            email: this.email,
            phone: this.phone
        };
    }
}
