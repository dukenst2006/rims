<template>
    <!-- Job Edit Form -->
    <b-form @submit.prevent="update">
        <div class="d-flex justify-content-between align-content-center">
          <h4>Editing {{ job.title }}</h4>
          
          <aside>
              <b-link @click.prevent="cancelEditing">
                  Cancel
              </b-link>
          </aside>          
        </div>

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
                        v-if="areas.length == 0">
                    <i class="icon-refresh"></i> Refresh areas
                </b-link>

                <!-- Spinner -->
                <div class="my-1" v-if="areas.length == 0">
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
            <!-- Spinner -->
            <div class="my-1" v-if="editing.processing">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
            </div>

            <!-- Buttons -->
            <template v-else>
                <b-button type="submit" variant="primary">
                    Save changes
                </b-button>
                <b-button type="button"
                          variant="secondary"
                          @click="cancelEditing">
                    Cancel
                </b-button>
            </template>
        </b-form-group>
    </b-form><!-- /form -->
</template>

<script>
    import BusEvent from '../../../../bus'
    import validation from '../../../../mixins/validation'
    import AreasSelect from '../../../areas/partials/forms/AreasSelect'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "tenant-job-edit",
        props: [
            'endpoint',
            'job',
            'areas'
        ],
        components: {
            HollowDotsSpinner,
            AreasSelect,
        },
        mixins: [
            validation
        ],
        data() {
            return {
                job_types: [
                    {text: 'Full time', value: 'full-time'},
                    {text: 'Part time', value: 'part-time'}
                ],
                job_site: [
                    {text: 'On site', value: 1},
                    {text: 'Remote', value: 0}
                ]
            }
        },
        mounted() {
            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }

            this.edit(this.job)
        },
        methods: {
            edit(job) {
                this.editing.id = job.id
                this.editing.form = job
            },cancelEditing() {
                this.$parent.editing.id = null
                this.$parent.editing.form = []
            },
            update() {
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.editing.form.slug, this.editing.form).then((response) => {
                    var job = response.data.data

                    // emit event on update
                    BusEvent.$emit('tenant-job:updated', job)

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
            }
        }
    }
</script>

<style scoped>

</style>