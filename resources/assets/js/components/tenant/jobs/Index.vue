<template>
    <div id="tenant-jobs-wrapper">
        <b-card no-body class="mb-3">
            <div class="card-body">
                <div class="card-title">
                    <div class="d-flex justify-content-between align-content-center">
                        <h3>Jobs</h3>

                        <div>
                            <!-- Add new link -->
                            <b-link href="#" @click.prevent="create" v-if="!creating.start">
                                {{ creating.active ? 'Cancel' : 'Add new' }}
                            </b-link>

                            <!-- Refresh link -->
                            <b-link href="#" @click.prevent="getJobs">
                                Refresh Jobs
                            </b-link>
                        </div>
                    </div>
                </div><!-- /.card-title -->

                <p v-if="creating.start">
                    <hollow-dots-spinner
                            :animation-duration="1000"
                            :dot-size="15"
                            :dots-num="3"
                            :color="'#ff1d5e'"
                    />

                    Please wait... setting up job template
                </p>

                <!-- Job Create Form -->
                <b-form v-if="creating.job !== null && creating.active" @submit.prevent="store">
                    <h4>Create new job</h4>

                    <!-- Title -->
                    <b-form-group
                            description="The job position."
                            label="Title"
                            label-for="title"
                            :invalid-feedback="invalidFeedback('creating', 'title')">
                        <b-form-input id="title"
                                      max="250"
                                      :state="fieldState('creating', 'title')"
                                      v-model="creating.form.title"></b-form-input>
                    </b-form-group>

                    <!-- Overview short -->
                    <b-form-group
                            description="Short description of job position."
                            label="Overview short"
                            label-for="overview_short"
                            :invalid-feedback="invalidFeedback('creating', 'overview_short')">
                        <b-form-input id="overview_short"
                                      max="160"
                                      :state="fieldState('creating', 'overview_short')"
                                      v-model="creating.form.overview_short"></b-form-input>
                    </b-form-group>

                    <!-- Overview -->
                    <b-form-group
                            description="A detailed description of job position."
                            label="Overview"
                            label-for="overview"
                            :invalid-feedback="invalidFeedback('creating', 'overview')">
                        <b-form-textarea id="overview"
                                         :state="fieldState('creating', 'overview')"
                                         v-model="creating.form.overview"
                                         :rows="4"
                                         :max-row="6"></b-form-textarea>
                    </b-form-group>

                    <!-- Applicants -->
                    <b-form-group horizontal
                                  description="The no. of applicants for the job position."
                                  label="Applicants"
                                  label-for="applicants"
                                  :invalid-feedback="invalidFeedback('creating', 'applicants')">
                        <b-col md="4">
                            <b-form-input type="number"
                                          id="applicants"
                                          min="1"
                                          :state="fieldState('creating', 'applicants')"
                                          v-model="creating.form.applicants"></b-form-input>
                        </b-col>
                    </b-form-group>

                    <!-- Type -->
                    <b-form-group horizontal
                                  description="The job work time."
                                  label="Job type"
                                  :state="fieldState('creating', 'type')"
                                  :invalid-feedback="invalidFeedback('creating', 'type')">

                        <b-form-radio-group :options="job_types"
                                            name="type"
                                            class="pt-2"
                                            :state="fieldState('creating', 'type')"
                                            v-model="creating.form.type"/>
                    </b-form-group>

                    <!-- Location -->
                    <b-form-group horizontal
                                  description="The job location."
                                  label="Job location"
                                  :state="fieldState('creating', 'area_id')"
                                  :invalid-feedback="invalidFeedback('creating', 'area_id')">

                        <b-col md="6">
                            <b-form-select id="area_id"
                                           v-model="creating.form.area_id"
                                           class="mb-3">
                                <template slot="first">
                                    <!-- this slot appears above the options from 'options' prop -->
                                    <option :value="null" disabled>-- Please select an area --</option>
                                </template>
                                <!-- these options will appear after the ones from 'options' prop -->
                                <areas-select :area="area" v-for="area in areas" :key="area.id"></areas-select>
                            </b-form-select>
                        </b-col>

                        <b-col md="2">
                            <b-link href="#"
                                    @click.prevent="getAreas"
                                    v-if="fetching.areas == false && areas.length == 0">
                                <i class="icon-refresh"></i> Refresh areas
                            </b-link>

                            <!-- Spinner -->
                            <div class="my-1" v-if="fetching.areas">
                                <hollow-dots-spinner
                                        :animation-duration="1000"
                                        :dot-size="15"
                                        :dots-num="3"
                                        :color="'#ff1d5e'"
                                />
                            </div>
                        </b-col>
                    </b-form-group>

                    <!-- Work site -->
                    <b-form-group horizontal
                                  description="The job work site."
                                  label="Job site"
                                  :state="fieldState('creating', 'on_location')"
                                  :invalid-feedback="invalidFeedback('creating', 'on_location')">

                        <b-form-radio-group :options="job_site"
                                            name="on_location"
                                            class="pt-2"
                                            :state="fieldState('creating', 'on_location')"
                                            v-model="creating.form.on_location"/>
                    </b-form-group>

                    <!-- Salary -->
                    <b-form-group horizontal
                                  breakpoint="md"
                                  description="* Leave empty if you want it to be confidential"
                                  label="Salary"
                                  label-size="lg"
                                  label-class="font-weight-bold pt-0"
                                  class="mb-0">

                        <!-- Currency -->
                        <b-form-group horizontal
                                      breakpoint="md"
                                      description="* Salary payment currency"
                                      label="Currency"
                                      class="mb-0">
                            <b-form-select id="currency"
                                           v-model="creating.form.currency"
                                           class="mb-3">
                                <template slot="first">
                                    <!-- this slot appears above the options from 'options' prop -->
                                    <option :value="null" disabled>-- Please select salary currency --</option>
                                </template>
                                <!-- these options will appear after the ones from 'options' prop -->
                                <option :value="currency.cc" v-for="currency in currencies">
                                    {{ currency.cc }} ({{ currency.name }})
                                </option>
                            </b-form-select>
                        </b-form-group>

                        <!-- Min -->
                        <b-form-group horizontal
                                      description="The minimum job salary."
                                      label="Min."
                                      label-for="salary_min"
                                      :state="fieldState('creating', 'salary_min')"
                                      :invalid-feedback="invalidFeedback('creating', 'salary_min')">
                            <b-col md="4">
                                <b-form-input id="salary_min"
                                              :state="fieldState('creating', 'salary_min')"
                                              v-model="creating.form.salary_min"></b-form-input>
                            </b-col>
                        </b-form-group>

                        <!-- Max -->
                        <b-form-group horizontal
                                      description="The maximum job salary."
                                      label="Max."
                                      label-for="salary_max"
                                      :state="fieldState('creating', 'salary_max')"
                                      :invalid-feedback="invalidFeedback('creating', 'salary_max')">
                            <b-col md="4">
                                <b-form-input id="salary_max"
                                              :state="fieldState('creating', 'salary_max')"
                                              v-model="creating.form.salary_max"></b-form-input>
                            </b-col>
                        </b-form-group>
                    </b-form-group>

                    <!-- Buttons -->
                    <b-form-group horizontal>
                        <!-- Spinner -->
                        <div class="my-1" v-if="creating.processing">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"
                            />
                            <p>Creating job...</p>
                        </div>

                        <!-- Buttons -->
                        <template v-else>
                            <!-- Save -->
                            <b-button type="submit" variant="primary">Save</b-button>

                            <!-- Cancel -->
                            <b-button type="button"
                                      variant="secondary"
                                      @click="creating.active = !creating.active">
                                Cancel
                            </b-button>
                        </template>
                    </b-form-group>
                </b-form><!-- /form -->
            </div><!-- /.card-body -->

        </b-card><!-- /.card -->

        <!-- Spinner -->
        <b-card no-body class="mb-3" v-if="fetching.jobs">
            <div class="card-body">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"/>

                <p>Fetching jobs...</p>
            </div>
        </b-card>

        <!-- No jobs alert -->
        <b-card no-body class="mb-3" v-if="fetching.jobs == false && jobs.length == 0">
            <div class="card-body">
                <p>No jobs found.</p>

                <p>
                    <b-link href="#" @click.prevent="getJobs">
                        Refresh Jobs
                    </b-link>
                    or
                    <b-link href="#" @click.prevent="create" v-if="!creating.start || creating.job == null">
                        {{ creating.active ? 'Cancel' : 'Add new' }}
                    </b-link>
                </p>
            </div>
        </b-card>

        <tenant-job v-for="job in jobs"
                    :endpoint="endpoint"
                    :job="job"
                    :key="'job_' + job.identifier"
                    v-bind:areas="areas"
                    v-bind:currencies="currencies"
                    v-bind:education_levels="education_levels"
                    v-bind:skills="skills"
                    v-bind:categories="categories"
                    v-if="jobs.length > 0"></tenant-job>

        <div class="my-1" v-if="meta.current_page < meta.last_page">
            <pagination :meta="meta"></pagination>
        </div>
    </div>
</template>

<script>
    import BusEvent from '../../../bus'
    import validation from '../../../mixins/validation'
    import AreasSelect from '../../areas/partials/forms/AreasSelect'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'
    import TenantJob from './partials/Job'
    import Pagination from '../../pagination/Pagination'

    export default {
        name: "tenant-job-index",
        props: [
            'endpoint'
        ],
        components: {
            HollowDotsSpinner,
            AreasSelect,
            TenantJob,
            Pagination
        },
        mixins: [
            validation
        ],
        data() {
            return {
                jobs: [],
                meta: {},
                areas: [],
                categories: [],
                currencies: [],
                fetching: {
                    jobs: false,
                    areas: false,
                    categories: false,
                    currencies: false,
                    education_levels: false,
                    skills: false,
                    languages: false,
                    levels: false
                },
                skills: [],
                languages: [],
                education_levels: [],
                levels: [],
                job_educations: [],
                job_types: [
                    {text: 'Full time', value: 'full-time'},
                    {text: 'Part time', value: 'part-time'}
                ],
                job_site: [
                    {text: 'On site', value: 1},
                    {text: 'Remote', value: 0}
                ],
                creating: {
                    start: false,
                    active: false,
                    job: null,
                    form: {},
                    errors: [],
                    processing: false
                },
                scrollOptions: {
                    easing: 'ease-in-out'
                }
            }
        },
        watch: {
            '$route.query': {
                handler(query) {
                    this.getJobs(1, query)
                },
                deep: true
            }
        },
        mounted() {
            this.getAreas()
            this.getJobs()
            this.getCurrencies()

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }

            this.creating.form.applicants = 1
            this.creating.form.type = 'full-time'
            this.creating.form.on_location = true

            // emit or listen to events
            BusEvent.$on('tenant-job:updated', this.updateJob)
                .$on('tenant-job:deleted', this.removeJob)
        },
        methods: { // todo: add method to check if requirements added and ready for payment
            getJobs(page = this.$route.query.page, query = this.$route.query) {
                this.fetching.jobs = true

                axios.get(this.endpoint + '/index', {
                    params: {
                        page,
                        ...query
                    }
                }).then((response) => {
                    this.jobs = response.data.data
                    this.meta = response.data.meta
                    this.returnToTop()
                }).catch((error) => {
                    // log error to file or call webhook

                    console.log(error)
                }).finally(() => {
                    this.getEducationLevels()
                    this.getSkills()
                    this.getLevels()
                    this.getLanguages()
                    this.getCategories()

                    this.fetching.jobs = false
                })
            },
            getCategories() {
                this.fetching.categories = true

                axios.get('/categories/jobs').then((response) => {
                    this.categories = response.data.data
                }).catch((error) => {
                    // log error to file or call webhook

                    console.log(error)
                }).finally(() => {
                    this.fetching.categories = false
                })
            },
            getAreas() {
                this.fetching.areas = true

                axios.get(this.endpoint + '/areas').then((response) => {
                    this.areas = response.data.data
                }).catch((error) => {
                    // log error to file or call webhook

                    console.log(error)
                }).finally(() => {
                    this.fetching.areas = false
                })
            },
            getEducationLevels() {
                axios.get('/education').then((response) => {
                    this.education_levels = response.data.data
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            getSkills() {
                axios.get('/skills').then((response) => {
                    this.skills = response.data.data
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            getLevels() {
                axios.get('/levels').then((response) => {
                    this.levels = response.data.data
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            getLanguages() {
                axios.get('/languages').then((response) => {
                    this.languages = response.data.data
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            getCurrencies() {
                axios.get('/currencies').then((response) => {
                    this.currencies = response.data.data
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            create() {
                // check if create form is active
                if (this.creating.active) {

                    // delete initialised job
                    this.destroy(this.creating.job).then(() => {
                        // reset creating vars
                        this.creating.job = null
                        this.creating.active = false
                    }).catch((error) => {
                        // log error to file or call webhook
                        console.log(error)
                    }).finally(() => {
                        this.deleting.processing = false
                    })

                    return
                }

                // start create action and activate form
                this.creating.start = true

                // if create action started
                if (this.creating.start) {
                    axios.get(this.endpoint + '/create').then((response) => {
                        // activate create form
                        this.creating.active = true

                        // init job template
                        this.creating.job = response.data.data
                    }).catch((error) => {
                        // deactivate create form
                        this.creating.active = false

                        toastr.error('Failed setting up job template. Please try again.', 'Whoops')

                        // log error to file or call webhook
                        console.log(error)
                    }).finally(() => {
                        this.creating.start = false
                    })
                }
            },
            store() {
                this.creating.processing = true

                axios.post(this.endpoint + '/' + this.creating.job.slug, this.creating.form).then((response) => {
                    var job = response.data.data

                    // add job to jobs list
                    this.jobs.unshift(job)

                    // reset create vars
                    this.creating.active = false
                    this.creating.form = {}
                    this.creating.job = null

                    // setup job for editing requirements

                    toastr.success('Job created successfully.', job.title)
                }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.creating.errors = error.response.data.errors
                    }

                    console.log(error)

                    toastr.error('Failed saving job. Please try again!', 'Whoops')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            updateJob(newJob) {
                var filtered = this.jobs.filter(function (job) {
                    return job.id == newJob.id
                })

                var oldJob = _.head(filtered)

                // add job to jobs list
                var index = this.jobs.indexOf(oldJob)

                // replace with updated
                this.jobs[index] = newJob
            },
            removeJob(job) {
                // filter job out of list
                this.jobs = this.jobs.filter((el) => {
                    return el.identifier !== job.identifier
                })
            },
            returnToTop() {
                this.$scrollTo('#tenant-jobs-wrapper', 500, this.scrollOptions)
            }
        }
    }
</script>

<style scoped>

</style>