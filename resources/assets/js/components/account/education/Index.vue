<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>My Education History</h4>

                    <a href="#" class="pull-right ml-auto" @click.prevent="creating.active = !creating.active">
                        {{ creating.active ? 'Cancel' : 'Add new' }}
                    </a>
                </div>
            </div>

            <!-- Create Form -->
            <form action="#" v-if="creating.active" @submit.prevent="store">
                <h5>Add new record</h5>

                <!-- Education Level -->
                <div class="form-group row" :class="{ 'has-error': creating.errors['education_id']  }">
                    <label for="education" class="control-label col-md-4">
                        Education level
                    </label>
                    <div class="col-md-6">
                        <select id="education" class="form-control custom-select"
                                :class="{ 'is-invalid': creating.errors['education_id']  }"
                                v-model="creating.form.education_id">
                            <option value="">---------</option>
                            <option :value="level.id" v-for="level in levels">{{ level.name }}</option>
                        </select>

                        <div class="invalid-feedback" v-if="creating.errors['education_id']">
                            <strong>{{ creating.errors['education_id'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Name -->
                <div class="form-group row" :class="{ 'has-error': creating.errors['name']  }">
                    <label for="name" class="control-label col-md-4">Institution name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control"
                               :class="{ 'is-invalid': creating.errors['name']  }"
                               id="name" placeholder="name"
                               v-model="creating.form.name">

                        <div class="invalid-feedback" v-if="creating.errors['name']">
                            <strong>{{ creating.errors['name'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Course -->
                <div class="form-group row" :class="{ 'has-error': creating.errors['course']  }">
                    <label for="course" class="control-label col-md-4">
                        What did you study (course)?
                    </label>
                    <div class="col-md-6">
                        <input type="text" class="form-control"
                               :class="{ 'is-invalid': creating.errors['course']  }"
                               id="course"
                               placeholder="course"
                               v-model="creating.form.course">

                        <div class="invalid-feedback" v-if="creating.errors['course']">
                            <strong>{{ creating.errors['course'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Started at -->
                <div class="form-group row" :class="{ 'has-error': creating.errors['started_at']  }">
                    <label for="started_at" class="control-label col-md-4">
                        When did you join? (started at)
                    </label>
                    <div class="col-md-6">
                        <div class="input-group" id="started_at">
                            <date-picker
                                    :config="config"
                                    :class="{ 'is-invalid': creating.errors['started_at']  }"
                                    v-model="creating.form.started_at"></date-picker>

                            <div class="input-group-append">
                                <span class="input-group-text"><i class="icon-calendar"></i></span>
                            </div>

                            <div class="invalid-feedback" v-if="creating.errors['started_at']">
                                <strong>{{ creating.errors['started_at'][0] }}</strong>
                            </div>
                        </div><!-- /.input-group -->
                    </div>
                </div>

                <!-- Ended at -->
                <div class="form-group row" :class="{ 'has-error': creating.errors['ended_at']  }">
                    <label for="ended_at" class="control-label col-md-4">
                        When did you graduate? (ended at)
                    </label>
                    <div class="col-md-6">
                        <div class="input-group" id="ended_at">
                            <date-picker
                                    :config="config"
                                    :class="{ 'is-invalid': creating.errors['ended_at']  }"
                                    v-model="creating.form.ended_at"></date-picker>

                            <div class="input-group-append">
                                <span class="input-group-text"><i class="icon-calendar"></i></span>
                            </div>

                            <div class="invalid-feedback" v-if="creating.errors['ended_at']">
                                <strong>{{ creating.errors['ended_at'][0] }}</strong>
                            </div>
                        </div><!-- /.input-group -->
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>

                        <!-- Spinner -->
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
            <!-- Spinner -->
            <div class="list-group-item" v-if="fetching">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
                <p>Fetching...</p>
            </div>

            <template v-for="school in schools">
                <div class="list-group-item">
                    <!-- Item Content -->
                    <template v-if="school.school.id !== editing.id">
                        <div class="d-flex justify-content-between content-align-center">
                            <h4>{{ school.school.name }}</h4>

                            <div class="nav">
                                <a href="#" class="nav-link" @click.prevent="edit(school)">Edit</a>
                                <a href="#" class="nav-link" @click.prevent="remove(school)">Delete</a>
                            </div>
                        </div>
                        <p class="h5">Level: {{ school.name }}</p>
                        <p class="lead">Course: {{ school.school.course }}</p>
                        <p>{{ school.school.started_at }} - {{ school.school.ended_at }}</p>

                        <!-- Spinner -->
                        <div class="my-1"
                             v-if="deleting.processing && deleting.id == school.school.id || status.processing && status.id == school.school.id">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"
                            />

                            <p v-if="deleting.id == school.school.id">Deleting...</p>

                            <p v-if="status.id == school.school.id">Updating status...</p>
                        </div>
                    </template>

                    <!-- Record Update Form -->
                    <template v-else>
                        <form action="#" @submit.prevent="update">
                            <div class="form-group row" :class="{ 'has-error': editing.errors['education_id']  }">
                                <label :for="'education-' + school.school.id" class="control-label col-md-4">
                                    Education level
                                </label>
                                <div class="col-md-6">
                                    <select :id="'education-' + school.school.id" class="form-control custom-select"
                                            :class="{ 'is-invalid': editing.errors['education_id']  }"
                                            v-model="updateable.education_id = school.id">
                                        <option value="">---------</option>
                                        <option :value="level.id" v-for="level in levels"
                                                :selected="level.id === school.id">
                                            {{ level.name }}
                                        </option>
                                    </select>

                                    <div class="invalid-feedback" v-if="editing.errors['education_id']">
                                        <strong>{{ editing.errors['education_id'][0] }}</strong>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->

                            <div class="form-group row" :class="{ 'has-error': editing.errors['name']  }">
                                <label :for="'name-' + school.school.id" class="control-label col-md-4">Institution
                                    name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control"
                                           :class="{ 'is-invalid': editing.errors['name']  }"
                                           :id="'name-' + school.school.id"
                                           placeholder="name"
                                           v-model="updateable.name = school.school.name">

                                    <div class="invalid-feedback" v-if="editing.errors['name']">
                                        <strong>{{ editing.errors['name'][0] }}</strong>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->

                            <div class="form-group row" :class="{ 'has-error': editing.errors['course']  }">
                                <label for="'course-' + school.school.id" class="control-label col-md-4">
                                    What did you study (course)?
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control"
                                           :class="{ 'is-invalid': editing.errors['course']  }"
                                           id="'course-' + school.school.id"
                                           placeholder="course"
                                           v-model="updateable.course = school.school.course">

                                    <div class="invalid-feedback" v-if="editing.errors['course']">
                                        <strong>{{ editing.errors['course'][0] }}</strong>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->

                            <div class="form-group row" :class="{ 'has-error': editing.errors['started_at']  }">
                                <label for="started_at" class="control-label col-md-4">
                                    When did you join? (started at)
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group date datepicker" :id="'started_at-' + school.school.id">
                                        <date-picker
                                                :config="config"
                                                :class="{ 'is-invalid': editing.errors['started_at']  }"
                                                v-model="updateable.started_at = school.school.started_at"></date-picker>

                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="icon-calendar"></i></span>
                                        </div>

                                        <div class="invalid-feedback" v-if="editing.errors['started_at']">
                                            <strong>{{ editing.errors['started_at'][0] }}</strong>
                                        </div>
                                    </div><!-- /.input-group -->
                                </div>
                            </div><!-- /.form-group -->

                            <div class="form-group row" :class="{ 'has-error': editing.errors['ended_at']  }">
                                <label for="'ended_at-' + school.school.id" class="control-label col-md-4">
                                    When did you graduate? (ended at)
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group" :id="'ended_at-' + school.school.id">
                                        <date-picker
                                                :config="config"
                                                :class="{ 'is-invalid': editing.errors['ended_at']  }"
                                                v-model="updateable.ended_at = school.school.ended_at"></date-picker>

                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="icon-calendar"></i></span>
                                        </div>

                                        <div class="invalid-feedback" v-if="editing.errors['ended_at']">
                                            <strong>{{ editing.errors['ended_at'][0] }}</strong>
                                        </div>
                                    </div><!-- /.input-group -->
                                </div>
                            </div><!-- /.form-group -->

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save changes
                                    </button>
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
                            </div><!-- /.form-group -->
                        </form>
                    </template>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'
    import datePicker from 'vue-bootstrap-datetimepicker'
    import 'tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css'

    export default {
        name: "user-education-index",
        props: [
            'endpoint'
        ],
        components: {
            HollowDotsSpinner,
            datePicker
        },
        data() {
            return {
                fetching: false,
                schools: [],
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
                updateable: {},
                config: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                }
            }
        },
        mounted() {
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
                    this.schools = response.data.data
                }).catch((error) => {
                    console.log(error.response.data)
                }).finally(() => {
                    this.fetching = false
                })
            },
            getLevels() {
                axios.get(this.endpoint + '/levels').then((response) => {
                    this.levels = response.data.data
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

                    toastr.success('Record added successfully.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.creating.errors = error.response.data.errors
                    }

                    toastr.error('Failed adding record.', 'Whoops!')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            edit(school) {
                this.editing.errors = []
                this.editing.id = school.school.id
                this.editing.form = this.updateable
            },
            update() {
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.editing.id, this.editing.form).then((response) => {
                    this.getRecords().then(() => {
                        this.editing.id = null
                        this.editing.form = {}
                    })

                    toastr.success('Record updated successfully.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    toastr.error('Failed updating record.', 'Whoops!')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            remove(school) {
                this.deleting.id = school.school.id
                this.deleting.form.education_id = school.id

                this.destroy()
            },
            destroy() {
                this.deleting.processing = true

                axios.delete(this.endpoint + '/' + this.deleting.id, this.deleting.form).then((response) => {
                    this.getRecords().then(() => {
                        this.deleting.form = {}
                    })

                    toastr.success('Record deleted successfully.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.deleting.errors = error.response.data.errors
                    }

                    toastr.error('Failed deleting record.', 'Whoops!')
                }).finally(() => {
                    this.deleting.id = null
                    this.deleting.processing = false
                })
            }
        }
    }
</script>

<style scoped>

</style>