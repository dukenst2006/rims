<template>
    <div>
        <b-list-group flush v-if="job.id == job.id">
            <b-list-group-item>
                <div class="d-flex justify-content-between align-content-center">
                    <p class="h5">{{ job.title }} other requirements</p>

                    <b-nav>
                        <template v-if="!fetching">
                            <b-nav-item @click.prevent="getRequirements">Refresh</b-nav-item>
                            <b-nav-item v-b-modal="'job-requirements-modal'">Add new</b-nav-item>
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

            <!-- Requirements -->
            <b-list-group-item v-for="requirement in job.requirements"
                               :key="requirement.id"
                               v-if="job.requirements || job.requirements.length != 0">
                <template v-if="requirement.id != editing.id">
                    <div class="d-flex justify-content-between align-content-center">
                        <h5>
                            {{ requirement.title }}
                        </h5>

                        <b-nav v-if="requirement.id != deleting.id">
                            <b-nav-item @click.prevent="editing.id = requirement.id">
                                Edit
                            </b-nav-item>
                            <b-nav-item @click.prevent="destroy(requirement)">
                                Delete
                            </b-nav-item>
                        </b-nav>

                        <div v-else-if="requirement.id == deleting.id">
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
                        {{ requirement.details }}
                    </div>
                </template>

                <!-- Editing Form -->
                <b-form action="#" @submit.prevent="update(requirement)" v-else>
                    <h5>Editing {{ requirement.title }}</h5>

                    <!-- Title -->
                    <b-form-group horizontal
                                  description="Job requirement title."
                                  label="Title"
                                  label-for="edit_title"
                                  :invalid-feedback="invalidFeedback('editing', 'title')">
                        <b-form-input id="edit_title"
                                      max="160"
                                      :state="fieldState('editing', 'title')"
                                      v-model="editing.form.title = requirement.title"></b-form-input>
                    </b-form-group>

                    <!-- Details -->
                    <b-form-group horizontal
                                  description="A detailed description of the requirement."
                                  label="Details"
                                  label-for="edit_details"
                                  :invalid-feedback="invalidFeedback('editing', 'details')">
                        <b-form-textarea id="edit_details"
                                         :state="fieldState('editing', 'details')"
                                         v-model="editing.form.details = requirement.details"
                                         :rows="4"
                                         :max-row="6"
                                         max-length="1000"></b-form-textarea>
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

            <!-- No requirements found -->
            <b-list-group-item v-else>
                <p>
                    No requirements found.
                </p>
                <p>
                    <b-link @click.prevent="getRequirements">Refresh</b-link>
                    or
                    <b-link v-b-modal="'job-requirements-modal'">Add new</b-link>
                </p>
            </b-list-group-item>
        </b-list-group>

        <!-- Requirements Modal -->
        <b-modal size="lg"
                 id="job-requirements-modal"
                 ref="jobRequirementsModal"
                 :title="job.title + ' other requirements'"
                 @ok="store"
                 ok-title="Save">

            <!-- Create Requirements Form -->
            <b-form action="#" @submit.prevent="store">
                <!-- Title -->
                <b-form-group horizontal
                              description="Job requirement title."
                              label="Title"
                              label-for="title"
                              :invalid-feedback="invalidFeedback('creating', 'title')">
                    <b-form-input id="title"
                                  max="160"
                                  :state="fieldState('creating', 'title')"
                                  v-model="creating.form.title"></b-form-input>
                </b-form-group>

                <!-- Details -->
                <b-form-group horizontal
                              description="A detailed description of the requirement."
                              label="Details"
                              label-for="details"
                              :invalid-feedback="invalidFeedback('creating', 'details')">
                    <b-form-textarea id="details"
                                     :state="fieldState('creating', 'details')"
                                     v-model="creating.form.details"
                                     :rows="4"
                                     :max-row="6"
                                     max-length="1000"></b-form-textarea>
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
        name: "tenant-job-requirements",
        props: [
            'endpoint',
            'job'
        ],
        components: {
            HollowDotsSpinner
        },
        mixins: [
            validation
        ],
        data() {
            return {
                fetching: false
            }
        },
        mounted() {
            if(!this.job.requirements || this.job.requirements.length == 0) {
                this.getRequirements()
            }

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }
        },
        methods: {
            close() {
                this.$parent.requirements.id = null
                this.$parent.requirements.active = false
            },
            getRequirements() {
                this.fetching = true

                axios.get(this.endpoint + '/' + this.job.slug + '/requirements').then((response) => {
                    this.job['requirements'] = response.data.data
                }).catch((error) => {
                    console.log(error)
                }).finally(() => {
                    this.fetching = false
                })
            },
            store(e) {
                e.preventDefault()

                this.creating.errors = []
                this.creating.processing = true

                axios.post(this.endpoint + '/' + this.job.slug + '/requirements', this.creating.form).then((response) => {
                    var requirement = response.data.data

                    // add education response to array
                    this.job['requirements'].push(requirement)

                    // hide modal
                    this.$refs.jobRequirementsModal.hide()

                    // clear form
                    this.clearCreating()

                    toastr.success('Requirement added successfully.', this.job.title)
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 403) {
                            toastr.error('Requirement already exists.', 'Whoops')
                        }

                        if (error.response.status === 422) {
                            this.creating.errors = error.response.data.errors
                        }
                        console.log(error.response)
                    }

                    console.log(error)

                    toastr.error('Failed adding requirement. Please try again!', 'Whoops')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            update(requirement) {
                this.editing.errors = []
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.job.slug + '/requirements/' + requirement.id, this.editing.form)
                    .then((response) => {
                        var jobRequirement = response.data.data

                        // add education response to array
                        var index = this.job.requirements.indexOf(requirement)

                        this.job.requirements[index] = jobRequirement

                        toastr.success('Requirement updated successfully.', this.job.title)

                        // clear form
                        this.clearEditing()
                    }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    console.log(error)

                    toastr.error('Failed updating requirement. Please try again!', 'Whoops')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            destroy(requirement) {
                this.deleting.processing = true
                this.deleting.id = requirement.id

                axios.delete(
                    this.endpoint + '/' + this.job.slug + '/requirements/' + requirement.id
                ).then((response) => {

                    this.job.requirements = this.job.requirements.filter((el) => {
                        return el.id != requirement.id
                    })

                    toastr.success(
                        requirement.title + ' requirement removed successfully.',
                        this.job.title
                    )
                }).catch((error) => {
                    if (error.response && error.response.status === 403) {
                        toastr.error(
                            'Job must have at least one requirement.',
                            this.job.title
                        )
                    }

                    console.log(error)

                    toastr.error('Failed deleting requirement. Please try again!', 'Whoops')
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