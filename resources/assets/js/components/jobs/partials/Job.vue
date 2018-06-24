<template>
    <div class="job-wrapper mb-3">
        <b-card no-body>
            <div class="card-body">
                <!-- Title -->
                <div class="card-title">
                    <div class="d-flex justify-content-between align-content-center">
                        <b-link href="#" @click.prevent>
                            <h4>{{ job.title }}</h4>
                        </b-link>

                        <!-- Categories -->
                        <p v-if="job.categories.length > 0">
                            <template v-for="job_category in job.categories">
                                <b-badge variant="primary" 
                                         v-if="job_category.category.price > 0">
                                    {{ job_category.category.name }}
                                </b-badge>&nbsp;
                            </template>
                        </p>
                    </div>
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
                <p>
                    <i class="icon-location-pin"></i>
                    <span v-for="ancestor in job.area.ancestors" v-if="job.area.ancestors.length">
                        {{ ancestor.name }},
                    </span> {{ job.area.name }}
                </p>

                <!-- Site -->
                <p>Job site: {{ job.on_location == false ? 'Remote / Off site' : 'On site'}}</p>

                <!-- Type -->
                <p>Type: {{ job.type == 'full-time' ? 'Full time' : 'Part time'}}</p>

                <!-- Extra Options -->
                <div class="d-flex justify-content-between align-content-center my-1">
                    <div>
                        <!-- Auto-update time every 60 seconds -->
                        Posted
                        <timeago :since="job.published_at" :auto-update="60"></timeago>
                        by

                        <!-- Company -->
                        <strong>
                            {{ job.company.name }}
                        </strong>
                    </div>
                    <div v-if="job.closed_at">
                        Closes in
                        <timeago :since="job.closed_at" :auto-update="60"></timeago>
                    </div>
                </div>

                <b-nav class="my-1">
                    <b-nav-item v-for="skillset in job.skills" :key="skillset.id">
                        <template v-for="ancestor in skillset.skill.ancestors"
                                  v-if="skillset.skill.ancestors.length">
                            <b-badge variant="light" class="lead">
                                {{ ancestor.name }}
                            </b-badge>
                        </template>
                        <b-badge variant="light" class="lead">{{ skillset.skill.name }}</b-badge>
                    </b-nav-item>
                </b-nav>
            </div><!-- /.card-body -->
        </b-card><!-- /.card -->
    </div><!-- /.job-wrapper -->
</template>

<script>
    export default {
        name: "job",
        props: [
            'job'
        ]
    }
</script>

<style scoped>

</style>