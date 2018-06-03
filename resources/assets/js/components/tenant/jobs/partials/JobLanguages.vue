<template>
    <div>
        <b-list-group flush>
            <b-list-group-item>
                <div class="d-flex justify-content-between align-content-center">
                    <p class="h5">{{ job.title }} languages</p>

                    <b-nav>
                        <template v-if="!fetching">
                            <b-nav-item @click.prevent="getJobLanguages">Refresh</b-nav-item>
                            <b-nav-item v-b-modal="'job-langs-modal'">Add new</b-nav-item>
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

            <!-- Langs -->
            <b-list-group-item v-for="lang in job.languages"
                               :key="lang.id"
                               v-if="job.languages || job.languages.length != 0">
                <template v-if="lang.id != editing.id">
                    <div class="d-flex justify-content-between align-content-center">
                        <h5>
                            <b-badge v-for="ancestor in lang.skillable.ancestors" :key="ancestor.id">
                                {{ ancestor.name }}
                            </b-badge>
                            {{ lang.skillable.name }}
                        </h5>

                        <b-nav v-if="lang.id != deleting.id">
                            <b-nav-item @click.prevent="editing.id = lang.id">
                                Edit
                            </b-nav-item>
                            <b-nav-item @click.prevent="destroy(lang)">
                                Delete
                            </b-nav-item>
                        </b-nav>

                        <div v-else-if="lang.id == deleting.id">
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

                    <p v-if="lang.level != null">
                        Proficiency level: {{ lang.level.name }}
                    </p>

                    <div class="my-1" v-if="lang.details != null">
                        {{ lang.details }}
                    </div>
                </template>

                <!-- Editing Form -->
                <b-form action="#" @submit.prevent="update(lang)" v-else>
                    <h5>Editing {{ lang.skillable.name }}</h5>

                    <!-- Languages -->
                    <b-form-group horizontal
                                  description="The language required."
                                  label="Language"
                                  :state="fieldState('editing', 'language_id')"
                                  :invalid-feedback="invalidFeedback('editing', 'language_id')">
                        <treeselect :flat="true"
                                    :options="tree"
                                    v-model="editing.form.language_id = lang.skillable.id"
                                    :normalizer="normalizer"
                                    :disable-branch-nodes="true"
                                    :show-count="true"
                                    id="edit_language_id"
                                    class="mb-3"/>
                    </b-form-group>

                    <!-- Level -->
                    <b-form-group horizontal
                                  description="The proficiency level required for the language."
                                  label="Level"
                                  :state="fieldState('editing', 'level_id')"
                                  :invalid-feedback="invalidFeedback('editing', 'level_id')">
                        <b-form-select v-model="editing.form.level_id = lang.level_id"
                                       id="edit_level_id"
                                       class="mb-3">
                            <template slot="first">
                                <!-- this slot appears above the options from 'options' prop -->
                                <option :value="null" disabled>-- Please select the proficiency level --</option>
                            </template>
                            <!-- these options will appear after the ones from 'options' prop -->
                            <option :value="level.id" v-for="level in levels">{{ level.name }}</option>
                        </b-form-select>
                    </b-form-group>

                    <!-- Details -->
                    <b-form-group horizontal
                                  description="A short description of the skill requirements."
                                  label="Details"
                                  label-for="edit_details"
                                  :invalid-feedback="invalidFeedback('editing', 'details')">
                        <b-form-textarea id="edit_details"
                                         :state="fieldState('editing', 'details')"
                                         v-model="editing.form.details = lang.details"
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
                </b-form>
            </b-list-group-item>

            <!-- No languages found -->
            <b-list-group-item v-else>
                <p>
                    No languages found.
                </p>
                <p>
                    <b-link @click.prevent="getJobLanguages">Refresh</b-link>
                    or
                    <b-link v-b-modal="'job-langs-modal'">Add new</b-link>
                </p>
            </b-list-group-item>
        </b-list-group>

        <!-- Languages Modal -->
        <b-modal size="lg"
                 id="job-langs-modal"
                 ref="jobLangsModal"
                 :title="job.title + ' languages'"
                 @ok="store"
                 ok-title="Save">

            <!-- Create Languages Form -->
            <b-form action="#" @submit.prevent="store">
                <!-- Languages -->
                <b-form-group horizontal
                              description="The language required."
                              label="Language"
                              :state="fieldState('creating', 'language_id')"
                              :invalid-feedback="invalidFeedback('creating', 'language_id')">
                    <treeselect :flat="true"
                                :options="tree"
                                v-model="creating.form.language_id"
                                :normalizer="normalizer"
                                :disable-branch-nodes="true"
                                :show-count="true"
                                id="language_id"
                                class="mb-3"/>
                </b-form-group>

                <!-- Level -->
                <b-form-group horizontal
                              description="The proficiency level required for the language."
                              label="Level"
                              :state="fieldState('creating', 'level_id')"
                              :invalid-feedback="invalidFeedback('creating', 'level_id')">
                    <b-form-select v-model="creating.form.level_id"
                                   id="level_id"
                                   class="mb-3">
                        <template slot="first">
                            <!-- this slot appears above the options from 'options' prop -->
                            <option :value="null" disabled>-- Please select the proficiency level --</option>
                        </template>
                        <!-- these options will appear after the ones from 'options' prop -->
                        <option :value="level.id" v-for="level in levels">{{ level.name }}</option>
                    </b-form-select>
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
        name: "tenant-job-languages",
        props: [
            'endpoint',
            'job',
            'languages',
            'levels'
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
            if (!this.job.languages || this.job.languages.length == 0) {
                this.getJobLanguages()
            }

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }

            this.nestedoptions = this.languages
        },
        methods: {
            close() {
                this.$parent.langs.id = null
                this.$parent.langs.active = false
            },
            getJobLanguages() {
                this.fetching = true

                axios.get(this.endpoint + '/' + this.job.slug + '/languages').then((response) => {
                    this.job['languages'] = response.data.data
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

                axios.post(this.endpoint + '/' + this.job.slug + '/languages', this.creating.form).then((response) => {
                    var lang = response.data.data

                    // add education response to array
                    this.job['languages'].push(lang)

                    // hide modal
                    this.$refs.jobLangsModal.hide()

                    // clear form
                    this.clearCreating()

                    toastr.success('Language added successfully.', this.job.title)
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 403) {
                            toastr.error('Language already exists.', 'Whoops')
                        }

                        if (error.response.status === 422) {
                            this.creating.errors = error.response.data.errors
                        }
                        console.log(error.response)
                    }

                    console.log(error)

                    toastr.error('Failed adding language. Please try again!', 'Whoops')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            update(lang) {
                this.editing.errors = []
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.job.slug + '/languages/' + lang.id, this.editing.form)
                    .then((response) => {
                        var jobLanguage = response.data.data

                        // add education response to array
                        var index = this.job.languages.indexOf(lang)

                        this.job.languages[index] = jobLanguage

                        toastr.success('Language updated successfully.', this.job.title)

                        // clear form
                        this.clearEditing()
                    }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    console.log(error)

                    toastr.error('Failed updating language. Please try again!', 'Whoops')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            destroy(lang) {
                this.deleting.processing = true
                this.deleting.id = lang.id

                axios.delete(
                    this.endpoint + '/' + this.job.slug + '/languages/' + lang.id
                ).then((response) => {

                    this.job.languages = this.job.languages.filter((el) => {
                        return el.id != lang.id
                    })

                    toastr.success(
                        lang.skillable.name + ' language removed successfully.',
                        this.job.title
                    )
                }).catch((error) => {
                    if (error.response && error.response.status === 403) {
                        toastr.error(
                            'Job must have at least one language.',
                            this.job.title
                        )
                    }

                    console.log(error)

                    toastr.error('Failed deleting language. Please try again!', 'Whoops')
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