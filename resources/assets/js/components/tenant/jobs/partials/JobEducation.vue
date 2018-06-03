<template>
    <div>
        <!-- Education -->
        <b-list-group flush>
            <!-- Header -->
            <b-list-group-item>
                <div class="d-flex justify-content-between align-content-center">
                    <p class="h5">Job education requirements</p>

                    <b-nav>
                        <template v-if="!fetching">
                            <b-nav-item @click.prevent="getJobEducation">Refresh</b-nav-item>
                            <b-nav-item v-b-modal="'job-education-modal'">Add new</b-nav-item>
                        </template>

                        <b-nav-item @click.prevent="close">Close</b-nav-item>
                    </b-nav>
                </div>
            </b-list-group-item>

            <!-- Spinner -->
            <b-list-group-item v-if="fetching">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
                <p>Please wait. Refreshing...</p>
            </b-list-group-item>

            <!-- List -->
            <b-list-group-item v-for="level in job.education"
                               :key="level.id"
                               v-if="job.education || job.education.length != 0">
                <!-- Education Level Content -->
                <template v-if="level.id != editing.id">
                    <div class="d-flex justify-content-between align-content-center">
                        <h5>{{ level.education.name }}</h5>

                        <b-nav v-if="level.id != deleting.id">
                            <b-nav-item @click.prevent="editing.id = level.id">
                                Edit
                            </b-nav-item>
                            <b-nav-item @click.prevent="destroy(level)">
                                Delete
                            </b-nav-item>
                        </b-nav>

                        <div v-else-if="level.id == deleting.id">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"
                            />

                            <p v-if="deleting.processing">
                                Deleting...
                            </p>
                        </div>
                    </div>

                    <div class="my-1">
                        {{ level.details }}
                    </div>
                </template>

                <!-- Education Editing Form -->
                <b-form action="#"
                        @submit.prevent="update(level)"
                        v-else>
                    <h5>{{ level.education.name }}</h5>

                    <!-- Education Level -->
                    <b-form-group horizontal
                                  description="The job education level required."
                                  label="Education level"
                                  :state="fieldState('editing', 'education_id')"
                                  :invalid-feedback="invalidFeedback('editing', 'education_id')">
                        <b-col md="6">
                            <b-form-select id="education_id"
                                           v-model="editing.form.education_id = level.education_id"
                                           class="mb-3"
                                           readonly>
                                <template slot="first">
                                    <option :value="null" disabled>
                                        -- Please select an education level --
                                    </option>
                                </template>
                                <option :value="level.id"
                                        v-for="level in education_levels">
                                    {{ level.name }}
                                </option>
                            </b-form-select>
                        </b-col>
                    </b-form-group>

                    <!-- Details -->
                    <b-form-group horizontal
                                  description="A short description of the education requirement."
                                  label="Details"
                                  label-for="details"
                                  :invalid-feedback="invalidFeedback('editing', 'details')">
                        <b-form-textarea id="details"
                                         :state="fieldState('editing', 'details')"
                                         v-model="editing.form.details = level.details"
                                         :rows="4"
                                         :max-row="6"></b-form-textarea>
                    </b-form-group>

                    <!-- Spinner -->
                    <b-form-group horizontal>
                        <template v-if="!editing.processing">
                            <b-button type="submit" variant="primary">
                                Save
                            </b-button>
                            <b-button type="button"
                                      variant="secondary"
                                      @click="clearEditing">
                                Cancel
                            </b-button>
                        </template>

                        <div class="my-1" v-if="editing.processing">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"
                            />
                            <p>Saving changes...</p>
                        </div>
                    </b-form-group>
                </b-form>
            </b-list-group-item>

            <!-- No languages found -->
            <b-list-group-item v-else>
                <p>
                    No education requirements found.
                </p>
                <p>
                    <b-link @click.prevent="getJobEducation">Refresh</b-link>
                    or
                    <b-link v-b-modal="'job-education-modal'">Add new</b-link>
                </p>
            </b-list-group-item>
        </b-list-group>

        <!-- Education Modal -->
        <b-modal size="lg"
                 id="job-education-modal"
                 ref="jobEducationModal"
                 :title="job.title + ' education'"
                 @ok="store"
                 @shown="clearCreating"
                 ok-title="Save">

            <!-- Create Education Form -->
            <b-form action="#" @submit.prevent="store">
                <!-- Education Level -->
                <b-form-group horizontal
                              description="The job education level required."
                              label="Education level"
                              :state="fieldState('creating', 'education_id')"
                              :invalid-feedback="invalidFeedback('creating', 'education_id')">
                    <b-col md="6">
                        <b-form-select id="education_id"
                                       v-model="creating.form.education_id"
                                       class="mb-3">
                            <template slot="first">
                                <option :value="null" disabled>-- Please select an education --</option>
                            </template>
                            <option :value="level.id" v-for="level in education_levels">
                                {{ level.name }}
                            </option>
                        </b-form-select>
                    </b-col>
                </b-form-group>

                <!-- Details -->
                <b-form-group horizontal
                              description="A short description of the education requirement."
                              label="Details"
                              label-for="details"
                              :invalid-feedback="invalidFeedback('creating', 'details')">
                    <b-form-textarea id="details"
                                     :state="fieldState('creating', 'details')"
                                     v-model="creating.form.details"
                                     :rows="4"
                                     :max-row="6"></b-form-textarea>
                </b-form-group>

                <!-- Spinner -->
                <b-form-group horizontal>
                    <div class="my-1" v-if="creating.processing">
                        <hollow-dots-spinner
                                :animation-duration="1000"
                                :dot-size="15"
                                :dots-num="3"
                                :color="'#ff1d5e'"
                        />
                        <p>Saving...</p>
                    </div>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    import validation from '../../../../mixins/validation'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "tenant-job-education",
        props: [
            'endpoint',
            'job',
            'educationLevels'
        ],
        components: {
            HollowDotsSpinner
        },
        mixins: [
            validation
        ],
        data() {
            return {
                fetching: false,
                education_levels: this.educationLevels,
            }
        },
        mounted() {
            if (!this.job.education || this.job.education.length == 0) {
                this.getJobEducation()
            }

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }
        },
        methods: {
            close() {
                this.$parent.education.id = null
                this.$parent.education.active = false
            },
            getJobEducation() {
                this.fetching = true

                axios.get(this.endpoint + '/' + this.job.slug + '/education').then((response) => {
                    this.job['education'] = response.data.data
                }).catch((error) => {
                    console.log(error)
                }).finally(() => {
                    this.fetching = false
                })
            },
            store(e) {
                e.preventDefault()

                this.creating.processing = true

                axios.post(this.endpoint + '/' + this.job.slug + '/education', this.creating.form).then((response) => {
                    var education = response.data.data

                    // add education response to array
                    this.job['education'].push(education)

                    // hide modal
                    this.$refs.jobEducationModal.hide()

                    // clear form
                    this.creating.form = {}

                    toastr.success('Education requirement added successfully.', this.job.title)
                }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.creating.errors = error.response.data.errors
                    }

                    console.log(error)

                    toastr.error('Failed adding education requirement. Please try again!', 'Whoops')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            update(level) {
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.job.slug + '/education/' + level.id, this.editing.form)
                    .then((response) => {
                        var education = response.data.data

                        // add education response to array
                        var index = this.job.education.indexOf(level)

                        this.job.education[index] = education

                        toastr.success('Education requirement updated successfully.', this.job.title)

                        // clear form
                        this.clearEditing()
                    }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    console.log(error)

                    toastr.error('Failed updating education requirement. Please try again!', 'Whoops')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            destroy(level) {
                this.deleting.processing = true
                this.deleting.id = level.id

                axios.delete(
                    this.endpoint + '/' + this.job.slug + '/education/' + level.id
                ).then((response) => {

                    this.job.education = this.job.education.filter((el) => {
                        return el.id != level.id
                    })

                    toastr.success(
                        level.education.name + ' requirement removed successfully.',
                        this.job.title
                    )
                }).catch((error) => {
                    if (error.response && error.response.status === 403) {
                        toastr.error(
                            'Job must have at least one education requirement.',
                            this.job.title
                        )
                    }

                    console.log(error)

                    toastr.error('Failed deleting education requirement. Please try again!', 'Whoops')
                }).finally(() => {
                    this.clearDeleting()

                    this.deleting.processing = false
                })
            }
        }
    }
</script>

<style scoped>

</style>