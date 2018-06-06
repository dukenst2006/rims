<template>
    <div>
        <b-card no-body>
            <div class="card-body">
                <div class="card-title">
                    <div class="d-flex justify-content-between align-content-center">
                        <h3>Jobs</h3>

                        <div>
                            <b-link href="#" @click.prevent="create" v-if="!creating.start">
                                {{ creating.active ? 'Cancel' : 'Add new' }}
                            </b-link>
                        </div>
                    </div>
                </div>

                <p v-if="creating.start">
                    <hollow-dots-spinner
                            :animation-duration="1000"
                            :dot-size="15"
                            :dots-num="3"
                            :color="'#ff1d5e'"
                    />

                    Please wait... setting job template
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
                        <b-button type="submit" variant="primary" v-if="!creating.processing">Save</b-button>
                        <b-button type="button"
                                  variant="secondary"
                                  @click="creating.active = !creating.active">
                            Cancel
                        </b-button>

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
                    </b-form-group>
                </b-form><!-- /form -->
            </div><!-- /.card-body -->

            <b-list-group flush>
                <!-- Spinner -->
                <b-list-group-item v-if="fetching.jobs">
                    <hollow-dots-spinner
                            :animation-duration="1000"
                            :dot-size="15"
                            :dots-num="3"
                            :color="'#ff1d5e'"
                    />

                    <p>Fetching jobs...</p>
                </b-list-group-item>

                <!-- Filter & Options -->
                <b-list-group-item v-else>
                    <div class="d-flex justify-content-between align-content-center">
                        <div>
                            <h4>Sort by</h4>
                            <b-nav>
                                <b-nav-item active>Ending Soon</b-nav-item>

                                <!-- Time -->
                                <b-nav-item-dropdown text="Time" extra-toggle-classes="nav-link-custom" right>
                                    <b-dropdown-item>Latest</b-dropdown-item>
                                    <b-dropdown-item>Older</b-dropdown-item>
                                </b-nav-item-dropdown>

                                <!-- Work Site -->
                                <b-nav-item-dropdown text="Work Site" extra-toggle-classes="nav-link-custom" right>
                                    <b-dropdown-item>On location</b-dropdown-item>
                                    <b-dropdown-item>Remote / Off site</b-dropdown-item>
                                </b-nav-item-dropdown>

                                <!-- Type -->
                                <b-nav-item-dropdown text="Time" extra-toggle-classes="nav-link-custom" right>
                                    <b-dropdown-item>Full time</b-dropdown-item>
                                    <b-dropdown-item>Part time</b-dropdown-item>
                                </b-nav-item-dropdown>

                                <!-- Status -->
                                <b-nav-item-dropdown text="Status" extra-toggle-classes="nav-link-custom" right>
                                    <b-dropdown-item>Open</b-dropdown-item>
                                    <b-dropdown-item>Closed</b-dropdown-item>
                                </b-nav-item-dropdown>

                                <!-- Areas -->
                                <b-nav-item-dropdown text="Areas" extra-toggle-classes="nav-link-custom" right>
                                    <b-dropdown-item>one</b-dropdown-item>
                                    <b-dropdown-item>two</b-dropdown-item>
                                    <b-dropdown-divider></b-dropdown-divider>
                                    <b-dropdown-item>My Location</b-dropdown-item>
                                </b-nav-item-dropdown>
                            </b-nav>
                        </div>

                        <b-link href="#" @click.prevent="getJobs">
                            <i class="icon-refresh"></i> Refresh jobs
                        </b-link>
                    </div>
                </b-list-group-item>

                <template v-for="job in jobs" v-if="jobs.length > 0">
                    <!-- Job -->
                    <b-list-group-item>
                        <template v-if="editing.id !== job.id">
                            <div class="d-flex justify-content-between align-content-center">
                                <h4>
                                    <b-link href="#">{{ job.title }}</b-link>
                                </h4>

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
                                <b-nav v-else>
                                    <b-nav-item @click.prevent="edit(job)">Edit</b-nav-item>

                                    <!-- Not live -->
                                    <template v-if="job.live == false">
                                        <b-nav-item @click.prevent="deleteJob(job)">Delete</b-nav-item>
                                    </template>

                                    <b-nav-item @click.prevent="toggleStatus(job)" v-if="job.isPublished">
                                        {{ job.live == true ? 'Disable' : 'Make live' }}
                                    </b-nav-item>

                                    <!-- Live -->
                                    <template v-if="job.live == true">
                                        <b-nav-item>
                                            Applications
                                            <b-badge variant="primary"></b-badge>
                                        </b-nav-item>
                                        <b-nav-item>Close</b-nav-item>
                                    </template>
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

                            <!-- Extra Options -->
                            <div class="d-flex justify-content-between align-content-center">
                                <div>
                                    <!-- Auto-update time every 60 seconds -->
                                    Added
                                    <timeago :since="job.created_at" :auto-update="60"></timeago>
                                </div>
                                <b-nav v-if="extraOptionJob != job.id">
                                    <b-nav-item @click.prevent="toggleJobOption('education', job)">
                                        Education
                                    </b-nav-item>
                                    <b-nav-item @click.prevent="toggleJobOption('skillset', job)">
                                        Skills
                                    </b-nav-item>
                                    <b-nav-item @click.prevent="toggleJobOption('requirements', job)">
                                        Requirements
                                    </b-nav-item>
                                </b-nav>
                            </div>

                            <!-- Job Payment Link -->
                            <div class="my-2" v-if="job.isReadyForCheckout && !job.isPublished">
                                <b-button :href="endpoint + '/' + job.slug + '/checkout'" variant="primary">
                                    Proceed to payment
                                </b-button>
                            </div>

                            <!-- Job Publish Date -->
                            <div class="my-1" v-else>
                                Posted on
                                {{ job.published_at }} |
                                <timeago :since="job.published_at"
                                         :auto-update="60"></timeago>
                            </div>
                        </template>

                        <!-- Job Edit Form -->
                        <b-form @submit.prevent="update" v-else>
                            <h4>Editing {{ job.title }}</h4>

                            <!-- Title -->
                            <b-form-group
                                    description="The job position."
                                    label="Title"
                                    label-for="title"
                                    :invalid-feedback="invalidFeedback('editing', 'title')">
                                <b-form-input id="title"
                                              max="250"
                                              :state="fieldState('editing', 'title')"
                                              v-model="editing.form.title"></b-form-input>
                            </b-form-group>

                            <!-- Overview short -->
                            <b-form-group
                                    description="Short description of job position."
                                    label="Overview short"
                                    label-for="overview_short"
                                    :invalid-feedback="invalidFeedback('editing', 'overview_short')">
                                <b-form-input id="overview_short"
                                              max="160"
                                              :state="fieldState('editing', 'overview_short')"
                                              v-model="editing.form.overview_short"></b-form-input>
                            </b-form-group>

                            <!-- Overview -->
                            <b-form-group
                                    description="A detailed description of job position."
                                    label="Overview"
                                    label-for="overview"
                                    :invalid-feedback="invalidFeedback('editing', 'overview')">
                                <b-form-textarea id="overview"
                                                 :state="fieldState('editing', 'overview')"
                                                 v-model="editing.form.overview"
                                                 :rows="4"
                                                 :max-row="6"></b-form-textarea>
                            </b-form-group>

                            <!-- Applicants -->
                            <b-form-group horizontal
                                          description="The no. of applicants for the job position."
                                          label="Applicants"
                                          label-for="applicants"
                                          :invalid-feedback="invalidFeedback('editing', 'applicants')">
                                <b-col md="4">
                                    <b-form-input type="number"
                                                  id="applicants"
                                                  min="1"
                                                  :state="fieldState('editing', 'applicants')"
                                                  v-model="editing.form.applicants"></b-form-input>
                                </b-col>
                            </b-form-group>

                            <!-- Type -->
                            <b-form-group horizontal
                                          description="The job work time."
                                          label="Job type"
                                          :state="fieldState('editing', 'type')"
                                          :invalid-feedback="invalidFeedback('editing', 'type')">

                                <b-form-radio-group :options="job_types"
                                                    name="type"
                                                    class="pt-2"
                                                    :state="fieldState('editing', 'type')"
                                                    v-model="editing.form.type"/>
                            </b-form-group>

                            <!-- Location -->
                            <b-form-group horizontal
                                          description="The job location."
                                          label="Job location"
                                          :state="fieldState('editing', 'area_id')"
                                          :invalid-feedback="invalidFeedback('editing', 'area_id')">

                                <b-col md="6">
                                    <b-form-select id="area_id"
                                                   v-model="editing.form.area_id"
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
                                          :state="fieldState('editing', 'on_location')"
                                          :invalid-feedback="invalidFeedback('editing', 'on_location')">

                                <b-form-radio-group :options="job_site"
                                                    name="on_location"
                                                    class="pt-2"
                                                    :state="fieldState('editing', 'on_location')"
                                                    v-model="editing.form.on_location"/>
                            </b-form-group>

                            <!-- Salary -->
                            <b-form-group horizontal
                                          breakpoint="md"
                                          description="* Leave empty if you want it to be confidential"
                                          label="Salary"
                                          label-size="lg"
                                          label-class="font-weight-bold pt-0"
                                          class="mb-0">

                                <!-- Min -->
                                <b-form-group horizontal
                                              description="The minimum job salary."
                                              label="Min."
                                              label-for="salary_min"
                                              :state="fieldState('editing', 'salary_min')"
                                              :invalid-feedback="invalidFeedback('editing', 'salary_min')">
                                    <b-col md="4">
                                        <b-form-input id="salary_min"
                                                      :state="fieldState('editing', 'salary_min')"
                                                      v-model="editing.form.salary_min"></b-form-input>
                                    </b-col>
                                </b-form-group>

                                <!-- Max -->
                                <b-form-group horizontal
                                              description="The maximum job salary."
                                              label="Max."
                                              label-for="salary_max"
                                              :state="fieldState('editing', 'salary_max')"
                                              :invalid-feedback="invalidFeedback('editing', 'salary_max')">
                                    <b-col md="4">
                                        <b-form-input id="salary_max"
                                                      :state="fieldState('editing', 'salary_max')"
                                                      v-model="editing.form.salary_max"></b-form-input>
                                    </b-col>
                                </b-form-group>
                            </b-form-group>

                            <!-- Buttons -->
                            <b-form-group horizontal>
                                <b-button type="submit" variant="primary" v-if="!editing.processing">
                                    Save changes
                                </b-button>
                                <b-button type="button"
                                          variant="secondary"
                                          @click="cancelEditing">
                                    Cancel
                                </b-button>

                                <!-- Spinner -->
                                <div class="my-1" v-if="editing.processing">
                                    <hollow-dots-spinner
                                            :animation-duration="1000"
                                            :dot-size="15"
                                            :dots-num="3"
                                            :color="'#ff1d5e'"
                                    />
                                </div>
                            </b-form-group>
                        </b-form><!-- /form -->
                    </b-list-group-item>

                    <!-- Education -->
                    <job-education :endpoint="endpoint"
                                   :job="job"
                                   v-bind:education-levels="education_levels"
                                   v-if="education.id == job.id && education.active"></job-education>

                    <!-- Education -->
                    <job-skills :endpoint="endpoint"
                                :job="job"
                                v-bind:skills="skills"
                                v-if="skillset.id == job.id && skillset.active"></job-skills>

                    <!-- Requirements -->
                    <job-requirements :endpoint="endpoint"
                                      :job="job"
                                      v-if="requirements.id == job.id && requirements.active"></job-requirements>
                </template>

                <!-- No jobs alert -->
                <b-list-group-item v-if="fetching.jobs == false && jobs.length == 0">
                    <p>No jobs found.</p>

                    <p>
                        <b-link href="#" @click.prevent="getJobs">
                            <i class="icon-refresh"></i> Refresh jobs
                        </b-link>
                        or
                        <b-link href="#" @click.prevent="create" v-if="!creating.start || creating.job == null">
                            {{ creating.active ? 'Cancel' : 'Add new' }}
                        </b-link>
                    </p>
                </b-list-group-item>
            </b-list-group><!-- /.list-group -->
        </b-card><!-- /.card -->
    </div>
</template>

<script>
    import validation from '../../../mixins/validation'
    import AreasSelect from '../../areas/partials/forms/AreasSelect'
    import LanguagesSelect from '../../languages/partials/forms/LanguagesSelect'
    import SkillsSelect from '../../skills/partials/forms/SkillsSelect'
    import JobEducation from './partials/JobEducation'
    import JobSkills from './partials/JobSkills'
    import JobLanguages from './partials/JobLanguages'
    import JobRequirements from './partials/JobRequirements'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "tenant-job-index",
        props: [
            'endpoint'
        ],
        components: {
            HollowDotsSpinner,
            AreasSelect,
            LanguagesSelect,
            SkillsSelect,
            JobEducation,
            JobSkills,
            JobLanguages,
            JobRequirements
        },
        mixins: [
            validation
        ],
        data() {
            return {
                jobs: [],
                areas: [],
                fetching: {
                    jobs: false,
                    areas: false,
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
                }
            }
        },
        computed: {
            extraOptionShow() {
                if (this.skillset.active) {
                    return true
                } else if (this.education.active) {
                    return true
                } else if (this.langs.active) {
                    return true
                } else if (this.requirements.active) {
                    return true
                }

                return false
            },
            extraOptionJob() {
                if (this.skillset.id != null) {
                    return this.skillset.id
                } else if (this.education.id != null) {
                    return this.education.id
                } else if (this.langs.id != null) {
                    return this.langs.id
                } else if (this.requirements.id != null) {
                    return this.requirements.id
                }

                return null
            }
        },
        mounted() {
            this.getAreas()
            this.getJobs()
            this.getEducationLevels()
            this.getSkills()
            this.getLevels()
            this.getLanguages()

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }

            this.creating.form.applicants = 1
            this.creating.form.type = 'full-time'
            this.creating.form.on_location = true
        },
        methods: { // todo: add method to check if requirements added and ready for payment
            fieldState(action, field) {
                if (action == 'creating' && this.creating.errors[field]) {
                    return false
                }
                else if (action == 'editing' && this.editing.errors[field]) {
                    return false
                }
                else if (action == 'deleting' && this.deleting.errors[field]) {
                    return false
                }
                else if (action == 'status' && this.status.errors[field]) {
                    return false
                }
                else if (action == 'requirements' && this.requirements.errors[field]) {
                    return false
                }
                else if (action == 'langs' && this.langs.errors[field]) {
                    return false
                }
                else if (action == 'skillsset' && this.skillsset.errors[field]) {
                    return false
                }

                return null
            },
            invalidFeedback(action, field) {
                if (this.fieldState(action, field) == false) {
                    if (action == 'creating') {
                        return this.creating.errors[field][0]
                    } else if (action == 'editing') {
                        return this.editing.errors[field][0]
                    } else if (action == 'deleting') {
                        return this.deleting.errors[field][0]
                    } else if (action == 'status') {
                        return this.status.errors[field][0]
                    } else if (action == 'requirements') {
                        return this.requirements.errors[field][0]
                    } else if (action == 'langs') {
                        return this.langs.errors[field][0]
                    } else if (action == 'skillsset') {
                        return this.skillsset.errors[field][0]
                    }

                    return 'Whoops! Please enter valid data.'
                }

                return ''
            },
            getJobs() {
                this.fetching.jobs = true

                axios.get(this.endpoint + '/index').then((response) => {
                    this.jobs = response.data.data
                }).catch((error) => {
                    // log error to file or call webhook

                    console.log(error)
                }).finally(() => {
                    this.fetching.jobs = false
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
            edit(job) {
                this.editing.id = job.id
                this.editing.form = job
            },
            cancelEditing() {
                this.editing.id = null
                this.editing.form = []
            },
            update() {
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.editing.form.slug, this.editing.form).then((response) => {
                    var job = response.data.data

                    // add job to jobs list
                    var index = this.jobs.indexOf(this.editing.form)

                    // replace
                    this.jobs[index] = job

                    // reset editing
                    this.cancelEditing()

                    toastr.success('Job updated successfully.', job.title)
                }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    console.log(error)

                    toastr.error('Failed updating job. Please try again!', this.editing.form.title)
                }).finally(() => {
                    this.editing.processing = false
                })
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
                    // remove job from list
                    this.jobs = this.jobs.filter((el) => {
                        return el.identifier !== job.identifier
                    })

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
            }
        }
    }
</script>

<style scoped>

</style>