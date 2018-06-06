<template>
    <div>
        <!-- Skills -->
        <b-list-group flush>
            <b-list-group-item>
                <div class="d-flex justify-content-between align-content-center">
                    <p class="h5">{{ job.title }} skills</p>

                    <b-nav>
                        <template v-if="!fetching">
                            <b-nav-item @click.prevent="getJobSkills">Refresh</b-nav-item>
                            <b-nav-item v-b-modal="'job-skills-modal'">Add new</b-nav-item>
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

            <!-- Skillset -->
            <b-list-group-item v-for="skillset in job.skills"
                               :key="skillset.id"
                               v-if="job.skills || job.skills.length != 0">
                <template v-if="skillset.id != editing.id">
                    <div class="d-flex justify-content-between align-content-center">
                        <h5>
                            <b-badge v-for="ancestor in skillset.skill.ancestors" :key="ancestor.id">
                                {{ ancestor.name }}
                            </b-badge>
                            {{ skillset.skill.name }}
                        </h5>

                        <b-nav v-if="skillset.id != deleting.id">
                            <b-nav-item @click.prevent="editing.id = skillset.id">
                                Edit
                            </b-nav-item>
                            <b-nav-item @click.prevent="destroy(skillset)">
                                Delete
                            </b-nav-item>
                        </b-nav>

                        <div v-else-if="skillset.id == deleting.id">
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
                        {{ skillset.details }}
                    </div>
                </template>

                <!-- Edit Skill Form -->
                <b-form action="#" @submit.prevent="update(skillset)" v-else>
                    <h5>Editing {{ skillset.skill.name }}</h5>

                    <!-- Skills -->
                    <b-form-group horizontal
                                  description="The skill required for the job."
                                  label="Skill"
                                  label-for="edit_skill_id"
                                  :state="fieldState('editing', 'skill_id')"
                                  :invalid-feedback="invalidFeedback('editing', 'skill_id')">
                        <treeselect :flat="true"
                                    :options="tree"
                                    v-model="editing.form.skill_id = skillset.skill.id"
                                    :normalizer="normalizer"
                                    :disable-branch-nodes="true"
                                    :show-count="true"
                                    id="edit_skill_id"
                                    class="mb-3"/>
                    </b-form-group><!-- /.b-form-group -->

                    <!-- Details -->
                    <b-form-group horizontal
                                  description="A short description of the skill requirements."
                                  label="Details"
                                  label-for="edit_details"
                                  :invalid-feedback="invalidFeedback('editing', 'details')">
                        <b-form-textarea id="edit_details"
                                         :state="fieldState('editing', 'details')"
                                         v-model="editing.form.details = skillset.details"
                                         :rows="4"
                                         :max-row="6"></b-form-textarea>
                    </b-form-group><!-- /.b-form-group -->

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
                </b-form><!-- /.b-form -->
            </b-list-group-item>

            <!-- No skills found -->
            <b-list-group-item v-else>
                <p>
                    No skills found.
                </p>
                <p>
                    <b-link @click.prevent="getJobSkills">Refresh</b-link>
                    or
                    <b-link v-b-modal="'job-skills-modal'">Add new</b-link>
                </p>
            </b-list-group-item>
        </b-list-group>

        <!-- Skills Modal -->
        <b-modal size="lg"
                 id="job-skills-modal"
                 ref="jobSkillsModal"
                 :title="job.title + ' skills'"
                 @ok="store"
                 @shown="clearCreating"
                 ok-title="Save">

            <!-- Create Skill Form -->
            <b-form action="#" @submit.prevent="store">
                <!-- Skills -->
                <b-form-group horizontal
                              description="The skill required for the job."
                              label="Skill"
                              :state="fieldState('creating', 'skill_id')"
                              :invalid-feedback="invalidFeedback('creating', 'skill_id')">
                    <treeselect :flat="true"
                                :options="tree"
                                v-model="creating.form.skill_id"
                                :normalizer="normalizer"
                                :disable-branch-nodes="true"
                                :show-count="true"
                                id="skill_id"
                                class="mb-3"/>
                </b-form-group>

                <!-- Details -->
                <b-form-group horizontal
                              description="A short description of the skill requirements."
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
    import nestedtreeselect from '../../../../mixins/nestedtreeselect'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "tenant-job-skills",
        props: [
            'endpoint',
            'job',
            'skills'
        ],
        components: {
            HollowDotsSpinner,
            Treeselect
        },
        mixins: [
            validation,
            nestedtreeselect
        ],
        data() {
            return {
                fetching: false
            }
        },
        mounted() {
            if (!this.job.skills || this.job.skills.length == 0) {
                this.getJobSkills()
            }

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }

            this.nestedoptions = this.skills
        },
        methods: {
            close() {
                this.$parent.skillset.id = null
                this.$parent.skillset.active = false
            },
            getJobSkills() {
                this.fetching = true

                axios.get(this.endpoint + '/' + this.job.slug + '/skills').then((response) => {
                    this.job['skills'] = response.data.data
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

                axios.post(this.endpoint + '/' + this.job.slug + '/skills', this.creating.form).then((response) => {
                    var skillable = response.data.data

                    // add education response to array
                    this.job['skills'].push(skillable)

                    // hide modal
                    this.$refs.jobSkillsModal.hide()

                    // clear form
                    this.clearCreating()

                    toastr.success('Skill added successfully.', this.job.title)
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 403) {
                            toastr.error('Skill already exists.', 'Whoops')
                        }

                        if (error.response.status === 422) {
                            this.creating.errors = error.response.data.errors
                        }
                        console.log(error.response)
                    }

                    console.log(error)

                    toastr.error('Failed adding skill. Please try again!', 'Whoops')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            update(skillset) {
                this.editing.errors = []
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.job.slug + '/skills/' + skillset.id, this.editing.form)
                    .then((response) => {
                        var jobSkillable = response.data.data

                        // add education response to array
                        var index = this.job.skills.indexOf(skillset)

                        this.job.skills[index] = jobSkillable

                        toastr.success('Skill updated successfully.', this.job.title)

                        // clear form
                        this.clearEditing()
                    }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    console.log(error)

                    toastr.error('Failed updating skill. Please try again!', 'Whoops')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            destroy(skillset) {
                this.deleting.processing = true
                this.deleting.id = skillset.id

                axios.delete(
                    this.endpoint + '/' + this.job.slug + '/skills/' + skillset.id
                ).then((response) => {

                    this.job.skills = this.job.skills.filter((el) => {
                        return el.id != skillset.id
                    })

                    toastr.success(
                        skillset.skill.name + ' skill removed successfully.',
                        this.job.title
                    )
                }).catch((error) => {
                    if (error.response && error.response.status === 403) {
                        toastr.error(
                            'Job must have at least one skill.',
                            this.job.title
                        )
                    }

                    console.log(error)

                    toastr.error('Failed deleting skill. Please try again!', 'Whoops')
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