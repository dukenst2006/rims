export default {
    props: {
        endpoint: {
            type: String
        }
    },
    data() {
        return {
            portfolios: [],
            skills: [],
            languages: [],
            levels: [],
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
                form: {},
                errors: [],
                processing: false
            },
            skillables: {
                active: false,
                editing: {
                    id: null,
                    processing: false
                },
                deleting: {
                    id: null,
                    processing: false
                },
                portfolio: {
                    identifier: null,
                    title: null
                },
                skillset: {},
                id: null,
                form: {},
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
        getSkills() {
            return axios.get(this.endpoint + '/skills/list').then((response) => {
                this.skills = response.data.data;
            }).catch((error) => {
                console.log(error.response.data)
            })
        },
        getLevels() {
            return axios.get(this.endpoint + '/skills/levels').then((response) => {
                this.levels = response.data.data;
            }).catch((error) => {
                console.log(error.response.data)
            })
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
        edit(portfolio) {
            this.editing.id = portfolio.identifier
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
        toggleStatus(portfolio) {
            return axios.put(this.endpoint + '/' + portfolio.identifier + '/status', this.status.form).then((response) => {
                return Promise.resolve(response)
            }).catch((error) => {
                return Promise.reject(error)
            })
        },
        skillStore(portfolio) {
            this.skillables.processing = true

            return axios.post(this.endpoint + '/' + portfolio.identifier + '/skills', this.skillables.form)
                .then((response) => {
                    return Promise.resolve(response)
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.skillables.errors = error.response.data.errors
                    }

                    return Promise.reject(error)
                })
        },
        editSkillables(portfolio, skillset) {
            this.skillables.editing.id = skillset.id
            this.skillables.portfolio = portfolio
            this.skillables.form = {
                level_id: skillset.level_id,
                skill_id: skillset.skillable.id
            }
            this.skillables.skillset = skillset
        },
        cancelSkillableEditing() {
            this.skillables.editing.id = null
            this.skillables.editing.processing = false
            this.clearSkillable()
        },
        cancelSkillableDeleting() {
            this.skillables.deleting.id = null
            this.skillables.deleting.processing = false
            this.clearSkillable()
        },
        clearSkillable() {
            // this.skillables.portfolio = {
            //     identifier: null,
            //     title: null
            // }
            this.skillables.form = {}
            this.skillables.skillset = {}
        },
        skillUpdate(portfolio) {
            this.skillables.editing.processing = true

            return axios.put(
                this.endpoint + '/' + portfolio.identifier + '/skills/' + this.skillables.editing.id,
                this.skillables.form
            ).then((response) => {
                return Promise.resolve(response)
            }).catch((error) => {
                if (error.response.status === 422) {
                    this.skillables.errors = error.response.data.errors
                }

                return Promise.reject(error)
            })
        },
        skillDestroy(portfolio) {
            this.skillables.deleting.processing = true

            return axios.delete(this.endpoint + '/' + portfolio.identifier + '/skills/' + this.skillables.deleting.id)
                .then((response) => {
                    return Promise.resolve(response)
                }).catch((error) => {
                    return Promise.reject(error)
                })
        },
        closeSkillables() {
            this.skillables.portfolio = {
                identifier: null,
                title: null
            }
        }
    }
}