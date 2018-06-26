<template>
    <!-- Job -->
    <div>
        <b-card class="mb-3" no-body>
            <div class="card-body">
                <template v-if="editing.id !== job.id">
                    <div class="d-flex justify-content-between align-content-center">
                        <aside>
                            <!-- Title -->
                            <h4>
                                <b-link href="#">{{ job.title }}</b-link>
                            </h4>

                            <!-- Categories -->
                            <p v-if="job.categories.length > 0">
                                <template v-for="job_category in job.categories">
                                    <b-badge :variant="job_category.category.price == 0 ? 'secondary' : 'primary'">
                                        {{ job_category.category.name }}
                                        <i class="icon-check" v-if="job_category.approved"></i>
                                    </b-badge>&nbsp;
                                </template>
                            </p>
                        </aside>

                        <!-- Spinner -->
                        <div v-if="deleting.id == job.id || status.id == job.id">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"
                            />

                            <p v-if="deleting.processing == true">Deleting...</p>
                            <p v-if="status.processing == true">Updating status...</p>
                        </div>

                        <!-- Options -->
                        <b-nav right no-caret v-else>
                            <b-nav-item @click.prevent="edit(job)">Edit</b-nav-item>

                            <!-- Not live -->
                            <template v-if="job.live == false">
                                <b-nav-item @click.prevent="deleteJob(job)">Delete</b-nav-item>
                            </template>

                            <!-- More options dropdown -->
                            <b-nav-item-dropdown right no-caret v-if="job.isPublished">
                                <template slot="button-content">
                                    <i class="icon-options"></i>
                                    <span class="sr-only">Options</span>
                                </template>

                                <b-dropdown-item @click.prevent="toggleStatus(job)">
                                    {{ job.live == true ? 'Disable' : 'Make live' }}
                                </b-dropdown-item>

                                <!-- Live -->
                                <template v-if="job.live == true">
                                    <!-- Applications -->
                                    <b-dropdown-item>
                                        Applications
                                        <b-badge variant="primary"></b-badge>
                                    </b-dropdown-item>

                                    <!-- Set deadline -->
                                    <b-dropdown-item v-b-modal="job.identifier + '-deadline-modal'"
                                                     v-if="job.isPublished && job.closed_at == null">
                                        Set deadline
                                    </b-dropdown-item>

                                    <!-- Reset deadline -->
                                    <b-dropdown-item v-else-if="job.isOpenForRestore"
                                                     @click="restoreDeadline">
                                        Reset deadline
                                    </b-dropdown-item>
                                </template>
                            </b-nav-item-dropdown><!-- /.dropdown -->
                        </b-nav>
                    </div>

                    <!-- Overview Short -->
                    <p>{{ job.overview_short }}</p>

                    <!-- Salary -->
                    <p title="Salary"><i class="icon-credit-card"></i>
                        <template v-if="job.salary_max == job.salary_min">
                            {{ job.salary_min }}
                        </template>
                        <template v-else>
                            {{ job.salary_min }} - {{ job.salary_max }}
                        </template>
                    </p>

                    <!-- Applicants -->
                    <p title="Applicants"><i class="icon-people"></i> {{ job.applicants }}</p>

                    <!-- Location -->
                    <p><i class="icon-location-pin"></i> {{ job.area.name }}</p>

                    <!-- Site -->
                    <p>Job site: {{ job.on_location == false ? 'Remote / Off site' : 'On site'}}</p>

                    <!-- Type -->
                    <p>Type: {{ job.type == 'full-time' ? 'Full time' : 'Part time'}}</p>

                    <div class="d-flex justify-content-between align-content-center">
                        <aside>
                            <!-- Job Requirements -->
                            <b-nav v-if="extraOptionJob != job.id">
                                <!-- Education -->
                                <b-nav-item @click.prevent="toggleJobOption('education', job)">
                                    Education
                                    <i class="icon-check text-green"
                                       v-if="job.education.length > 0"></i>
                                </b-nav-item>

                                <!-- Skills -->
                                <b-nav-item @click.prevent="toggleJobOption('skillset', job)">
                                    Skills
                                    <i class="icon-check text-green"
                                       v-if="job.skills.length > 0"></i>
                                </b-nav-item>

                                <!-- Other requirements -->
                                <b-nav-item @click.prevent="toggleJobOption('requirements', job)">
                                    Requirements
                                    <i class="icon-check text-green"
                                       v-if="job.requirements.length > 0"></i>
                                </b-nav-item>

                                <!-- Categories -->
                                <b-nav-item @click.prevent="toggleJobOption('category', job)"
                                            v-if="categories.length > 0 && category.id == null">
                                    Categories
                                    <i class="icon-check text-green"
                                       v-if="job.categories.length > 0"></i>
                                </b-nav-item>
                            </b-nav>

                            <!-- Alerts -->
                            <div class="text-danger">
                                <p class="strong" v-if="job.education.length == 0">
                                    <i class="fa fa-warning"></i>
                                    Education qualifications are required.
                                </p>

                                <p class="strong" v-if="job.skills.length == 0">
                                    <i class="fa fa-warning"></i>
                                    Skills needed for the job are required.
                                </p>

                                <p class="strong" v-if="job.requirements.length == 0">
                                    <i class="fa fa-warning"></i>
                                    Additional requirements need to be specified.
                                </p>

                                <p class="strong" v-if="job.categories.length == 0">
                                    <i class="fa fa-warning"></i>
                                    Job needs to be listed at least in one category.
                                </p>
                            </div>

                            <!-- Changes Alerts -->
                            <div class="text-primary strong">
                                <p v-if="checkouts.skills">
                                    Checkout to apply skills changes to job.
                                </p>

                                <p v-if="checkouts.categories">
                                    Checkout to apply categories changes to job.
                                </p>
                            </div>
                        </aside>

                        <!-- Meta -->
                        <div class="text-right">
                            <!-- Job Created Date -->
                            <p>
                                <strong>Added</strong>
                                <timeago :since="job.created_at" :auto-update="60"></timeago>
                            </p>

                            <!-- Job Publish Date -->
                            <p v-if="job.isPublished">
                                <strong>Posted</strong>
                                <timeago :since="job.published_at"
                                         :auto-update="60"></timeago>
                            </p>

                            <!-- Job Deadline -->
                            <p v-if="job.closed_at == null">
                                <strong>Job deadline not set. </strong>
                                <b-link v-b-modal="job.identifier + '-deadline-modal'">
                                    Set deadline
                                </b-link>
                            </p>

                            <div class="my-1" v-if="job.isPublished && job.closed_at != null">
                                <Timer :starttime="job.published_at"
                                       :endtime="job.closed_at"
                                       trans='{
                                        "day":"d",
                                        "hours":"h",
                                        "minutes":"m",
                                        "seconds":"s",
                                        "expired":"Job application closed",
                                        "running":"Job application deadline",
                                        "upcoming":"Job application opens",
                                        "status": {
                                        "expired":"Closed",
                                        "running":"Open",
                                        "upcoming":"Opening time"
                                        }}'></Timer>

                                <p v-if="!deadline.processing && job.isOpenForRestore">
                                    Haven't found the right applicants?
                                    <b-link @click="restoreDeadline">
                                        Reset deadline
                                    </b-link>
                                </p>

                                <div v-else>
                                    <hollow-dots-spinner
                                            :animation-duration="1000"
                                            :dot-size="15"
                                            :dots-num="3"
                                            :color="'#ff1d5e'"/>

                                    <p>Resetting deadline</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Job Edit Form -->
                <tenant-job-edit :endpoint="endpoint"
                                 :job="job"
                                 v-bind:areas="areas"
                                 v-if="areas.length > 0 && editing.id == job.id"></tenant-job-edit>

            </div><!-- /.card-body -->

            <!-- Education -->
            <job-education :endpoint="endpoint"
                           :job="job"
                           v-bind:education-levels="education_levels"
                           v-if="education.id == job.id && education.active"></job-education>

            <!-- Skills -->
            <job-skills :endpoint="endpoint"
                        :job="job"
                        v-bind:skills="skills"
                        v-if="skillset.id == job.id && skillset.active"></job-skills>

            <!-- Requirements -->
            <job-requirements :endpoint="endpoint"
                              :job="job"
                              v-if="requirements.id == job.id && requirements.active"></job-requirements>

            <!-- Categories -->
            <job-categories :endpoint="endpoint"
                            :job="job"
                            v-bind:categories="categories"
                            v-if="category.id == job.id && category.active"></job-categories>

            <div class="card-body">
                <!-- Job Payment Link -->
                <div class="mt-2 d-flex justify-content-between align-content-center"
                     v-if="checkout">
                    <div>
                        <h4>Estimated total</h4>

                        <p class="h3">${{ cost }}</p>

                        <p class="text-muted">
                            Proceed to checkout to finalize changes.
                        </p>

                        <p class="text-muted">
                            &ast; There may be additional charges where applicable.
                        </p>
                    </div>

                    <div>
                        <!-- Initial checkout -->
                        <b-button variant="primary"
                                  class="pull-right"
                                  :href="endpoint + '/' + job.slug + '/checkout'"
                                  v-if="!job.isPublished">
                            Proceed to payment
                        </b-button>

                        <!-- Checkout changes -->
                        <b-button variant="primary"
                                  :href="endpoint + '/' + job.slug + '/checkout'"
                                  v-if="job.isPublished">
                            Checkout to apply changes
                        </b-button>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </b-card><!-- /.card -->

        <!-- Close Date Modal -->
        <b-modal lazy :busy="deadline.processing"
                 :id="job.identifier + '-deadline-modal'"
                 :title="job.title + ' application deadline'"
                 @ok="handleDeadlineOk"
                 @hide="handleDeadlineModal">

            <b-form @submit.prevent="storeDeadline">
                <b-form-group horizontal
                            description="The job application deadline."
                            label="Close at" 
                            :state="deadlineState"
                            :invalid-feedback="deadlineFeedback">
                    <div class="input-group mb-3">
                        <flat-pickr v-model="deadline.closed_at"
                                    :config="dateconfig"
                                    class="form-control"
                                    placeholder="Select date" data-input>
                        </flat-pickr>
                        <div class="input-group-append">
                            <div>
                                <button class="btn btn-primary" type="button" title="Toggle" data-toggle>
                                    <i class="fa fa-calendar">
                                        <span aria-hidden="true" class="sr-only">
                                            Toggle
                                        </span>
                                    </i>
                                </button>
                                <button class="btn btn-secondary" type="button" title="Clear" data-clear>
                                    <i class="fa fa-times">
                                        <span aria-hidden="true" class="sr-only">
                                            Clear
                                        </span>
                                    </i>
                                </button>
                            </div>                                
                        </div>
                    </div>
                </b-form-group>

                <b-form-group horizontal v-if="deadline.processing">
                    <hollow-dots-spinner
                            :animation-duration="1000"
                            :dot-size="15"
                            :dots-num="3"
                            :color="'#ff1d5e'"/>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    import BusEvent from '../../../../bus'
    import validation from '../../../../mixins/validation'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'
    import TenantJobEdit from './JobEdit'
    import JobEducation from './JobEducation'
    import JobSkills from './JobSkills'
    import JobLanguages from './JobLanguages'
    import JobRequirements from './JobRequirements'
    import JobCategories from './JobCategories'
    import Timer from '../../../../components/Timer'

    export default {
        name: "tenant-job",
        props: [
            'endpoint',
            'job',
            'areas',
            'education_levels',
            'skills',
            'categories'
        ],
        components: {
            HollowDotsSpinner,
            TenantJobEdit,
            JobEducation,
            JobSkills,
            JobLanguages,
            JobRequirements,
            JobCategories,
            Timer
        },
        mixins: [
            validation
        ],
        data() {
            return {
                initialJob: this.job,
                deadline: {
                    closed_at: this.job.closed_at || null,
                    errors: [],
                    inputProps: {
                        class: "input",
                        placeholder: "Please set a deadline",
                        readonly: true
                    },
                    processing: false
                },
                dateconfig: {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    minDate: moment().add(1, 'days').format('YYYY-MM-DD HH:mm'),
                    wrap: true,
                    allowInput: true,
                    static: true,
                    altFormat: "l F J, Y H:i K",
                    altInput: true,
                    altInputClass: "form-control"
                },
                checkouts: {
                    skills: false,
                    categories: false
                },
                education: {
                    id: null,
                    active: false
                },
                skillset: {
                    id: null,
                    active: false
                },
                langs: {
                    id: null,
                    active: false
                },
                requirements: {
                    id: null,
                    active: false
                },
                category: {
                    id: null,
                    active: false
                }
            }
        },
        computed: {
            extraOptionJob() {
                if (this.skillset.id != null) {
                    return this.skillset.id
                } else if (this.education.id != null) {
                    return this.education.id
                } else if (this.langs.id != null) {
                    return this.langs.id
                } else if (this.requirements.id != null) {
                    return this.requirements.id
                } else if (this.category.id != null) {
                    return this.category.id
                }

                return null
            },
            checkout() {   // watch job changes
                // count job categories
                var categoriesCount = this.job.categories.length

                if (this.job.isPublished) {   // checkout if published 

                    // filter unapproved skills
                    var filteredSkills = this.job.skills.filter(function (skill) {
                        return skill.approved == false
                    })

                    if (filteredSkills.length > 0) { // checkout if skills changed
                        this.checkouts.skills = true
                        console.log(this.job.title)
                        console.log(filteredSkills)
                        return true
                    }

                    // filter unapproved categories
                    var filteredCategories = this.job.categories.filter(function (category) {
                        return category.approved == false
                    })

                    if (filteredCategories.length > 0) { // checkout if categories changed
                        this.checkouts.categories = true
                        console.log(this.job.title)
                        console.log(filteredCategories)
                        return true
                    }
                }

                if (!this.job.isPublished) {// checkout if not published
                    if (this.job.education.length == 0) {
                        return false
                    }

                    if (this.job.skills.length == 0) {
                        return false
                    }

                    if (this.job.requirements.length == 0) {
                        return false
                    }

                    if (this.job.categories.length == 0) {
                        return false
                    }

                    // checkout if not published and checkout requirements met
                    return true
                }

                return this.job.isReadyForCheckout
            },
            cost() {
                // count job categories
                var categoriesCount = this.job.categories.length

                // filter unapproved skills
                var unapprovedSkills = this.job.skills.filter((skill) => {
                    return skill.approved == false
                })

                // unapproved skills count
                var unapprovedSkillsCount = unapprovedSkills.length

                // pluck prices skills prices
                var jobSkills = _.map(unapprovedSkills, function (obj) {
                    return parseFloat(obj.skill.price)
                })

                // sum the unapproved skills
                var unapprovedSkillsCost = _.sum(jobSkills)

                // filter unapproved categories
                var unapprovedCategories = this.job.categories.filter((category) => {
                    return category.approved == false
                })

                // unapproved categories count
                var unapprovedCategoriesCount = unapprovedCategories.length

                // pluck unapproved categories prices
                var jobCategories = _.map(unapprovedCategories, function (obj) {
                    return parseFloat(obj.category.price)
                })

                // sum the unapproved categories
                var unapprovedCategoriesCost = _.sum(jobCategories)

                // init cost
                var cost = this.job.cost

                // check if job is published
                if (this.job.isPublished) {

                    // check if job has unapproved categories
                    if (unapprovedCategoriesCount > 0) {
                        cost = unapprovedCategoriesCost
                    }

                    // check if job has unapproved skills and with no categories
                    if (unapprovedSkillsCount > 0 && categoriesCount == 0) {
                        cost = unapprovedSkillsCost
                    }

                    // check if job has any payments
                    if (this.job.saleCost > 0) {

                        if (categoriesCount > 0) {

                            // check if job has unapproved categories
                            if (unapprovedCategoriesCount > 0) {
                                cost = unapprovedCategoriesCost
                            } else {
                                cost = 0
                            }
                        }

                        // check if job has unapproved skills 
                        // & no (unapproved and or) categories

                    }
                }

                // check if job is not published
                if (!this.job.isPublished) {

                    // check if job has unapproved categories
                    if (unapprovedCategoriesCount > 0) {
                        cost = unapprovedCategoriesCost
                    }

                    // check if job has unapproved skills and with no categories
                    if (unapprovedSkillsCount > 0 && categoriesCount == 0) {
                        cost = unapprovedSkillsCost
                    }
                }

                return _.round(cost, 2)
            },
            applicationDeadline() {
                if(this.deadline.closed_at == null) {
                    return null
                }

                return this.deadline.closed_at
            },
            deadlineState() {
                if (this.deadline.errors['closed_at']) {
                    return false
                }

                return null
            },
            deadlineFeedback() {
                if (this.deadline.errors['closed_at']) {
                    return this.deadline.errors['closed_at'][0]
                }

                return null
            }
        },
        mounted() {
            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }

            // emit or listen to events
            BusEvent.$on('tenant-job:updated', function (job) {
                this.job = job
            }).$on('tenant-job:category-deleted', this.removeCategory)

        },
        methods: {
            edit(job) {
                this.editing.id = job.id
                this.editing.form = job
            },
            cancelEditing() {
                this.editing.id = null
                this.editing.form = []
            },
            destroy(job) {
                this.deleting.id = job.id
                this.deleting.processing = true

                return axios.delete(this.endpoint + '/' + job.slug).then((response) => {
                    return Promise.resolve(response)
                }).catch((error) => {
                    return Promise.reject(error)
                })
            },
            deleteJob(job) {
                this.destroy(job).then(() => {
                    // emit deleted event
                    BusEvent.$emit('tenant-job:deleted', job)

                    toastr.success('Job deleted successfully.', job.title)
                }).catch((error) => {
                    // log error to file or call webhook
                    console.log(error)

                    toastr.error('Failed deleting job. Please try again.', job.title)
                }).finally(() => {
                    this.deleting.id = null
                    this.deleting.processing = false
                })
            },
            toggleStatus(job) {
                this.status.processing = true
                this.status.id = job.id

                var live = !job.live

                this.status.form = {
                    live
                }

                axios.post(this.endpoint + '/' + job.slug + '/status', this.status.form).then((response) => {
                    this.status.form = {}

                    if (live === true) {
                        job.live = live
                        toastr.success('Status: Job now live.', job.title)
                    } else {
                        job.live = live
                        toastr.success('Status: Job disabled.', job.title)
                    }

                    // emit deleted event
                    BusEvent.$emit('tenant-job:status-updated', job)
                }).catch((error) => {
                    toastr.error('Whoops! Failed updating job status.', job.title)
                }).finally(() => {
                    this.status.id = null
                    this.status.processing = false
                })
            },
            toggleJobOption(option, job) {
                if (option == 'education') {
                    this.education.id = job.id
                    this.education.active = true
                }
                else if (option == 'skillset') {
                    this.skillset.id = job.id
                    this.skillset.active = true
                }
                else if (option == 'languages') {
                    this.langs.id = job.id
                    this.langs.active = true
                }
                else if (option == 'requirements') {
                    this.requirements.id = job.id
                    this.requirements.active = true
                }
                else if (option == 'category') {
                    this.category.id = job.id
                    this.category.active = true
                }
            },
            removeCategory(job_category) {
                this.job.categories = this.job.categories.filter(function (oldCategory) {
                    return oldCategory.id != job_category.id
                })
            },
            handleDeadlineOk(evt) {
                evt.preventDefault()

                this.storeDeadline()
            },
            handleDeadlineModal(evt) {
                if (this.deadline.processing) {
                    evt.preventDefault()
                    return
                }
            },
            storeDeadline() {
                if (this.deadline.processing) {
                    return
                }

                // start deadline processing
                this.deadline.errors = []
                this.deadline.processing = true

                axios.post(this.endpoint + '/' + this.job.slug + '/deadline', {
                    closed_at: this.deadline.closed_at
                }).then((response) => {
                    var newJob = response.data.data

                    this.job.closed_at = newJob.closed_at
                    this.job.isOpenForRestore = newJob.isOpenForRestore

                    toastr.success('Job deadline updated successfully.', this.job.title)

                    this.deadline.processing = false
                    
                    this.$root.$emit('bv::hide::modal', this.job.identifier + '-deadline-modal')
                }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.deadline.errors = error.response.data.errors
                    }

                    // log error to file or call webhook
                    console.log(error)

                    toastr.error('Failed setting job deadline. Please try again.', this.job.title)
                }).finally(() => {
                    this.deadline.processing = false
                })
            },
            restoreDeadline() {
                if (this.deadline.processing) {
                    return
                }

                // start deadline processing
                this.deadline.processing = true

                axios.put(this.endpoint + '/' + this.job.slug + '/deadline').then((response) => {
                    var newJob = response.data.data

                    this.deadline.closed_at = null
                    this.job.closed_at = newJob.closed_at
                    this.job.isOpenForRestore = newJob.isOpenForRestore

                    this.$root.$emit('bv::show::modal', this.job.identifier + '-deadline-modal')

                    toastr.success('Job deadline cleared successfully.', this.job.title)
                }).catch((error) => {
                    // log error to file or call webhook
                    console.log(error)

                    // forbidden
                    if (error.response && error.response.status === 403) {
                        toastr.error(
                            error.response.data.message,
                            this.job.title
                        )
                    }

                    toastr.error('Failed resetting job deadline. Please try again.', this.job.title)
                }).finally(() => {
                    this.deadline.processing = false
                })
            }
        }
    }
</script>

<style scoped>

</style>