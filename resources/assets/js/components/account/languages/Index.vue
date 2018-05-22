<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>My Languages</h4>

                    <a href="#" class="pull-right ml-auto" @click.prevent="creating.active = !creating.active">
                        {{ creating.active ? 'Cancel' : 'Add new' }}
                    </a>
                </div>
            </div>

            <!-- Create Form -->
            <form action="#" v-if="creating.active" @submit.prevent="store">
                <h5>Add new language</h5>

                <div class="form-group row" :class="{ 'has-error': creating.errors['language_id']  }">
                    <label for="language_id" class="control-label col-md-4">Language</label>
                    <div class="col-md-4">
                        <select id="language_id"
                                class="form-control custom-select"
                                :class="{ 'is-invalid': creating.errors['language_id']  }"
                                v-model="creating.form.language_id">
                            <option value="">-----------</option>
                            <template v-for="language in languages">
                                <optgroup :label="language.name" v-if="language.children.length">
                                    <option :value="child.id" v-for="child in language.children">
                                        {{ child.name }}
                                    </option>
                                </optgroup>
                                <option :value="language.id" v-else>
                                    {{ language.name }}
                                </option>
                            </template>
                        </select>

                        <div class="invalid-feedback" v-if="creating.errors['language_id']">
                            <strong>{{ creating.errors['language_id'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group row" :class="{ 'has-error': creating.errors['level_id']  }">
                    <label for="level_id" class="control-label col-md-4">Level</label>
                    <div class="col-md-4">
                        <select id="level_id"
                                class="form-control custom-select"
                                :class="{ 'is-invalid': creating.errors['level_id']  }"
                                v-model="creating.form.level_id">
                            <option value="">-----------</option>
                            <option :value="level.id" v-for="level in levels">
                                {{ level.name }}
                            </option>
                        </select>

                        <div class="invalid-feedback" v-if="creating.errors['level_id']">
                            <strong>{{ creating.errors['level_id'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>

                        <div class="my-1" v-if="creating.processing">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"
                            />
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- /.card-body -->
        <div class="list-group list-group-flush">
            <div class="list-group-item" v-if="fetching">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
                <p>Fetching...</p>
            </div>
            <div class="list-group-item" v-for="lang in records">

                <!-- List item Content -->
                <template v-if="lang.id !== editing.id">
                    <div class="d-flex justify-content-between align-content-center">
                        <h4>
                            {{ lang.language.name }}
                            <template v-if="lang.language.ancestors.length">
                                (
                                <span v-for="ancestor in lang.language.ancestors"
                                      v-if="lang.language.ancestors.length > 1">
                                        {{ ancestor.name }} /
                                    </span>
                                <span v-for="ancestor in lang.language.ancestors" v-else>
                                        {{ ancestor.name }}
                                    </span>
                                )
                            </template>
                        </h4>

                        <div>
                            <a href="#" @click.prevent="toggleStatus(lang)">
                                {{ lang.usable == true ? 'Disable' : 'Enable' }}
                            </a>
                            <a href="#" @click.prevent="edit(lang)">Edit</a>
                            <a href="#" @click.prevent="destroy(lang)">Delete</a>
                        </div>
                    </div>
                    <p>Level: {{ lang.level.name }}</p>

                    <!-- Spinner -->
                    <div class="my-1"
                         v-if="deleting.processing && deleting.id == skillset.id || status.processing && status.id == skillset.id">
                        <hollow-dots-spinner
                                :animation-duration="1000"
                                :dot-size="15"
                                :dots-num="3"
                                :color="'#ff1d5e'"
                        />

                        <p v-if="deleting.id == skillset.id">Deleting...</p>

                        <p v-if="status.id == skillset.id">Updating status...</p>
                    </div>
                </template>

                <!-- Edit Form -->
                <template v-else>
                    <form action="#" @submit.prevent="update">

                        <div class="form-group row" :class="{ 'has-error': editing.errors['language_id']  }">
                            <label for="edit_language_id" class="control-label col-md-4">Language</label>
                            <div class="col-md-4">
                                <input type="text" readonly
                                       class="form-control-plaintext"
                                       :class="{ 'is-invalid': editing.errors['language_id']  }"
                                       id="edit_language_id"
                                       v-model="lang.language.name"/>
                                <input type="hidden" v-model="updateable.language_id = lang.language.id"/>

                                <div class="invalid-feedback" v-if="editing.errors['language_id']">
                                    <strong>{{ editing.errors['language_id'][0] }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row" :class="{ 'has-error': editing.errors['level_id']  }">
                            <label for="edit_level_id" class="control-label col-md-4">Level</label>
                            <div class="col-md-4">
                                <select id="edit_level_id"
                                        class="form-control custom-select"
                                        :class="{ 'is-invalid': editing.errors['level_id']  }"
                                        v-model="updateable.level_id = lang.level.id">
                                    <option value="">-----------</option>
                                    <option :value="level.id" v-for="level in levels"
                                            :selected="level.id === lang.level.id">
                                        {{ level.name }}
                                    </option>
                                </select>

                                <div class="invalid-feedback" v-if="editing.errors['level_id']">
                                    <strong>{{ editing.errors['level_id'][0] }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn btn-secondary" @click="editing.id = null">
                                    Cancel
                                </button>

                                <!-- Spinner -->
                                <div class="my-1" v-if="editing.processing">
                                    <hollow-dots-spinner
                                            :animation-duration="1000"
                                            :dot-size="15"
                                            :dots-num="3"
                                            :color="'#ff1d5e'"
                                    />
                                    <p>Please wait, saving changes...</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "user-languages-index",
        props: [
            'endpoint'
        ],
        components: {
            HollowDotsSpinner
        },
        data() {
            return {
                fetching: false,
                records: [],
                languages: [],
                levels: [],
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
                updateable: {}
            }
        },
        mounted() {
            this.getLanguages()
            this.getLevels()
            this.getRecords()

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }
        },
        methods: {
            getRecords() {
                this.fetching = true

                return axios.get(this.endpoint + '/index').then((response) => {
                    this.records = response.data.data;
                }).catch((error) => {
                    console.log(error.response.data)
                }).finally(() => {
                    this.fetching = false
                })
            },
            getLanguages() {
                axios.get(this.endpoint + '/list').then((response) => {
                    this.languages = response.data.data;
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            getLevels() {
                axios.get(this.endpoint + '/levels').then((response) => {
                    this.levels = response.data.data;
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            store() {
                this.creating.processing = true
                this.creating.errors = []

                axios.post(this.endpoint, this.creating.form).then((response) => {
                    this.creating.active = false

                    this.getRecords().then(() => {
                        this.creating.form = {}
                    })

                    toastr.success('Language added successfully.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.creating.errors = error.response.data.errors
                    }

                    toastr.error('Failed adding language.', 'Whoops!')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            edit(lang) {
                this.editing.errors = []
                this.editing.id = lang.id
                this.editing.form = this.updateable
            },
            update() {
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.editing.id, this.editing.form).then((response) => {
                    this.getRecords().then(() => {
                        this.editing.id = null
                        this.editing.form = {}
                    })

                    toastr.success('Language updated successfully.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    toastr.error('Failed updating record.', 'Whoops!')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            destroy(lang) {
                this.deleting.id = lang.id
                this.deleting.processing = true

                axios.delete(this.endpoint + '/' + lang.id).then((response) => {
                    this.getRecords().then(() => {
                    })

                    toastr.success('Language deleted successfully.', lang.language.name)
                }).catch((error) => {
                    toastr.error('Failed deleting language.', 'Whoops!')
                }).finally(() => {
                    this.deleting.id = null
                    this.deleting.processing = false
                })
            },
            toggleStatus(lang) {
                this.status.id = skillset.id
                this.status.processing = true

                var usable = !lang.usable

                axios.put(this.endpoint + '/' + lang.id + '/status', {
                    usable
                }).then((response) => {
                    this.getRecords().then(() => {
                        this.editing.id = null
                        this.editing.form = {}
                    })

                    if (usable == true) {
                        toastr.success('Language enabled.', lang.language.name)
                    } else {
                        toastr.success('Language disabled.', lang.language.name)
                    }
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    toastr.error('Failed updating language status.', 'Whoops!')
                }).finally(() => {
                    this.status.id = null
                    this.status.processing = false
                })
            }
        }
    }
</script>

<style scoped>

</style>