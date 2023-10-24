export default class Company {

    constructor(company = {}) {

        this.id = company.id || '';
        this.name = company.name || '';
        this.website = company.website || '';
        this.email = company.email || '';
        this.logo = company.logo || '';
        this.logoFile = company.logoFile || '';
    }


    toJSON() {
        return {
            id: this.id,
            name: this.name,
            website: this.website,
            email: this.email,
            logo: this.logo,
            phone: this.phone
        };
    }

}
