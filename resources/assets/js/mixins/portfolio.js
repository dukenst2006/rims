export default {
    props: {
        endpoint: {
            type: String
        }
    },
    data() {
        return {
            portfolios: [],
            fetching: false,
            startCreating: false,
            created: false,
            creating: {
                active: false,
                form: {},
                errors: [],
                processing: false
            },
            editing: {
                id: null,
                form: {},
                errors: [],
                processing: false
            },
            deleting: {
                id: null,
                form: {},
                errors: [],
                processing: false
            },
            status: {
                id: null,
                errors: [],
                processing: false
            },
            skills: {
                id: null,
                errors: [],
                processing: false
            },
            updateable: {}
        }
    },
    methods: {
        create() {
            this.startCreating = true
            this.creating.active = true

            if (this.creating.active) {
                return axios.get(this.endpoint + '/create').then((response) => {
                    return Promise.resolve(response)
                }).catch((error) => {
                    return Promise.reject(error)
                })
            }
        },
        getPortfolios() {
            this.fetching = true

            return axios.get(this.endpoint + '/index').then((response) => {
                this.portfolios = response.data.data
            }).catch((error) => {
                console.log(error.response.data)
            }).finally(() => {
                this.fetching = false
            })
        },
        store(portfolio) {
            this.creating.processing = true

            return axios.post(this.endpoint + '/' + portfolio.identifier, this.creating.form).then((response) => {
                return Promise.resolve(response)
            }).catch((error) => {
                if (error.response.status === 422) {
                    this.creating.errors = error.response.data.errors
                }

                return Promise.reject(error)
            })
        },
        update(portfolio) {
            this.editing.processing = true

            return axios.put(this.endpoint + '/' + portfolio.identifier, this.editing.form).then((response) => {
                return Promise.resolve(response)
            }).catch((error) => {
                if (error.response.status === 422) {
                    this.editing.errors = error.response.data.errors
                }

                return Promise.reject(error)
            })
        },
        destroy(portfolio) {
            this.deleting.processing = true

            return axios.delete(this.endpoint + '/' + portfolio.identifier).then((response) => {
                return Promise.resolve(response)
            }).catch((error) => {
                return Promise.reject(error)
            })
        },
        toggleStatus(portfolio, form) {
            return axios.put(this.endpoint + '/' + portfolio.identifier + '/status', form).then((response) => {
                return Promise.resolve(response)
            }).catch((error) => {
                return Promise.reject(error)
            })
        },
    }
}