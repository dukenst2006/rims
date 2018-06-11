<template>
    <div class="row" id="jobs-wrapper">
        <div class="col-md-3">
            <filters endpoint="/jobs/filters">
                <template slot="top">
                    <!-- Education -->
                    <b-list-group class="mb-3" v-if="education_levels.length > 0">
                        <!--  v-if="map.style != 'undefined' && map.style === 'list'" -->
                        <b-list-group-item>
                            Education
                        </b-list-group-item>
                        <b-list-group-item button
                                           v-for="{name, slug} in education_levels"
                                           :key="'education-' + slug"
                                           @click.prevent="$root.$emit('filters-added', 'education', slug)"
                                           :active="filters.education == slug">
                            {{ name }}
                        </b-list-group-item>

                        <b-list-group-item variant="secondary"
                                           button
                                           v-if="filters.education"
                                           @click.prevent="$root.$emit('filters-removed', 'education')">
                            &times; Clear this filter
                        </b-list-group-item>
                    </b-list-group><!-- /.list-group -->
                </template>
            </filters>
        </div>
        <div class="col-md-9">
            <!-- Skills filters -->
            <div class="mb-3" v-if="skills.length > 0">
                <h5>Skills</h5>
                <treeselect
                        :multiple="true"
                        :clearable="true"
                        :close-on-select="true"
                        :options="skillsTree"
                        :normalizer="normalizer"
                        placeholder="Sort by skill(s)..."
                        @input="skillFilter"
                        class="mb-2"
                        id="skills-filter"
                        :value="selectedSkills"
                        v-if="!fetching.skills && skills.length != 0"/>
            </div>

            <!-- Spinner -->
            <div class="my-1" v-if="fetching.jobs">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />

                <p>Fetching jobs...</p>
            </div>

            <div class="my-1" v-if="!fetching.jobs && jobs.length == 0">
                <p>No jobs found.
                    <b-link @click.prevent="$root.$emit('filters-clear')">Refresh</b-link>
                </p>
                <p>Or <span class="text-muted">Try again with less or different filters.</span></p>
            </div>

            <div class="job-listings" v-else>
                <job v-for="job in jobs" :job="job" :key="job.identifier"></job>

                <div class="my-1" v-if="jobs.length > 0">
                    <pagination :meta="meta"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Filters from '../filters/Filters'
    import Job from './partials/Job'
    import Pagination from '../pagination/Pagination.vue'
    import {HollowDotsSpinner} from 'epic-spinners'
    import querystring from 'query-string'
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: "jobs-index",
        props: [
            'endpoint'
        ],
        components: {
            HollowDotsSpinner,
            Filters,
            Job,
            Pagination,
            Treeselect
        },
        data() {
            return {
                jobs: [],
                meta: {},
                skills: [],
                education_levels: [],
                fetching: {
                    jobs: false,
                    skills: false,
                    education: false
                },
                selectedSkills: null,
                filters: {
                    education: null
                },
                scrollOptions: {
                    easing: 'ease-in-out'
                },
                normalizer(node/*, id */) {
                    // there is an extra `id` argument,
                    // which could be useful if you have multiple instances
                    // of vue-treeselect that share the same `normalizer` function
                    return {
                        id: node.slug,
                        label: node.name,
                        children: node.children,
                    }
                }
            }
        },
        created: function () {
            this.$root.$on('filters-loaded', this.assignFilters)
        },
        computed: {
            skillsTree() {
                var filter = function (obj) {
                    if (obj.children && obj.children.length == 0) {
                        _.unset(obj, 'children')
                    } else {
                        var children = obj.children

                        _.each(children, filter)
                    }
                }

                var tree = _.each(this.skills, filter)

                return tree
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
            this.getSkills()
            this.getEducation()
            this.getJobs()

            this.$root.$on('filters-changed', this.assignFilters)
                .$on('filters-cleared', this.resetFilters)
        },
        methods: {
            getJobs(page = this.$route.query.page, query = this.$route.query) {
                this.fetching.jobs = true

                axios.get(this.endpoint + '/listings', {
                    params: {
                        page,
                        ...query
                    }
                }).then((response) => {
                    this.jobs = response.data.data
                    this.meta = response.data.meta
                    this.returnToTop()
                }).catch((error) => {
                    if (error.response.status >= 400) {
                    }
                }).finally(() => {
                    this.fetching.jobs = false
                })
            },
            getSkills() {
                this.fetching.skills = true

                axios.get('/skills').then((response) => {
                    this.skills = response.data.data
                }).catch((error) => {
                    console.log(error.response.data)
                }).finally(() => {
                    this.fetching.skills = false
                })
            },
            getEducation() {
                this.fetching.education = true

                axios.get('/education').then((response) => {
                    this.education_levels = response.data.data
                }).catch((error) => {
                    console.log(error.response.data)
                }).finally(() => {
                    this.fetching.education = false
                })
            },
            skillFilter(values, id) {
                if (!values || values.length == 0) {
                    this.$root.$emit('filters-removed', 'skills')
                    return
                }

                var skills = values.join('&')

                this.$root.$emit('filters-added', 'skills', skills)
            },
            assignFilters(filters) {
                this.filters = Object.assign({}, filters)

                if (!_.isEmpty(filters) && !_.isEmpty(filters.skills)) {
                    this.selectedSkills = []
                    this.selectedSkills = filters.skills.split('&')
                }
            },
            resetFilters() {
                this.filters = {
                    education: null
                }
                this.selectedSkills = null
            },
            returnToTop() {
                this.$scrollTo('#jobs-wrapper', 500, this.scrollOptions)
            }
        }
    }
</script>

<style scoped>

</style>