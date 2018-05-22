<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>My Skills</h4>

                    <a href="#" class="pull-right ml-auto" @click.prevent="creating.active = !creating.active">
                        {{ creating.active ? 'Cancel' : 'Add new' }}
                    </a>
                </div>
            </div>

            <!-- Create Form -->
            <form action="#" v-if="creating.active" @submit.prevent="store">
                <h5>Add new skill</h5>

                <div class="form-group row" :class="{ 'has-error': creating.errors['skill_id']  }">
                    <label for="skill_id" class="control-label col-md-4">Skill</label>
                    <div class="col-md-4">
                        <select id="skill_id"
                                class="form-control custom-select"
                                :class="{ 'is-invalid': creating.errors['skill_id']  }"
                                v-model="creating.form.skill_id">
                            <option value="">-----------</option>
                            <template v-for="skill in skills">
                                <optgroup :label="skill.name" v-if="skill.children.length">
                                    <option :value="child.id" v-for="child in skill.children">
                                        {{ child.name }}
                                    </option>
                                </optgroup>
                                <option :value="skill.id" v-else>
                                    {{ skill.name }}
                                </option>
                            </template>
                        </select>

                        <div class="invalid-feedback" v-if="creating.errors['skill_id']">
                            <strong>{{ creating.errors['skill_id'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group row" :class="{ 'has-error': creating.errors['level_id']  }">
                    <label for="level_id" class="control-label col-md-4">Level</label>
                    <div class="col-md-4">
                        <select id="level_id"
                                class="form-control custom-select"
                                :class="{ 'is-invalid': creating.errors['level_id']  }"
                                v-model="creating.form.level_id">
                            <option value="">-----------</option>
                            <option :value="level.id" v-for="level in levels">
                                {{ level.name }}
                            </option>
                        </select>

                        <div class="invalid-feedback" v-if="creating.errors['level_id']">
                            <strong>{{ creating.errors['level_id'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>

                        <div class="my-1" v-if="creating.processing">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"
                            />
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- /.card-body -->
        <div class="list-group list-group-flush">
            <div class="list-group-item" v-if="fetching">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
                <p>Fetching...</p>
            </div>
            <div class="list-group-item" v-for="skillset in records">

                <!-- List item Content -->
                <template v-if="skillset.id !== editing.id">
                    <div class="d-flex justify-content-between align-content-center">
                        <h4>
                            {{ skillset.skill.name }}
                            <template v-if="skillset.skill.ancestors.length">
                                (
                                <span v-for="ancestor in skillset.skill.ancestors"
                                      v-if="skillset.skill.ancestors.length > 1">
                                        {{ ancestor.name }} /
                                    </span>
                                <span v-for="ancestor in skillset.skill.ancestors" v-else>
                                        {{ ancestor.name }}
                                    </span>
                                )
                            </template>
                        </h4>

                        <div>
                            <a href="#" @click.prevent="toggleStatus(skillset)">
                                {{ skillset.usable == true ? 'Disable' : 'Enable' }}
                            </a>
                            <a href="#" @click.prevent="edit(skillset)">Edit</a>
                            <a href="#" @click.prevent="destroy(skillset)">Delete</a>
                        </div>
                    </div>
                    <p>Level: {{ skillset.level.name }}</p>

                    <!-- Spinner -->
                    <div class="my-1"
                         v-if="deleting.processing && deleting.id == skillset.id || status.processing && status.id == skillset.id">
                        <hollow-dots-spinner
                                :animation-duration="1000"
                                :dot-size="15"
                                :dots-num="3"
                                :color="'#ff1d5e'"
                        />

                        <p v-if="deleting.id == skillset.id">Deleting...</p>

                        <p v-if="status.id == skillset.id">Updating status...</p>
                    </div>
                </template>

                <!-- Edit Form -->
                <template v-else>
                    <form action="#" @submit.prevent="update">

                        <div class="form-group row" :class="{ 'has-error': editing.errors['skill_id']  }">
                            <label for="edit_skill_id" class="control-label col-md-4">Skill</label>
                            <div class="col-md-6">
                                <input type="text" readonly
                                       class="form-control-plaintext" id="edit_skill_id"
                                       :class="{ 'is-invalid': editing.errors['skill_id']  }"
                                       v-model="skillset.skill.name">
                                <input type="hidden" v-model="updateable.skill_id = skillset.skill.id">

                                <div class="invalid-feedback" v-if="editing.errors['skill_id']">
                                    <strong>{{ editing.errors['skill_id'][0] }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row" :class="{ 'has-error': editing.errors['skill_id']  }">
                            <label for="edit_level_id" class="control-label col-md-4">Level</label>
                            <div class="col-md-4">
                                <select id="edit_level_id"
                                        class="form-control custom-select"
                                        :class="{ 'is-invalid': editing.errors['level_id']  }"
                                        v-model="updateable.level_id = skillset.level.id">
                                    <option value="">-----------</option>
                                    <option :value="level.id" v-for="level in levels"
                                            :selected="level.id === skillset.level.id">
                                        {{ level.name }}
                                    </option>
                                </select>

                                <div class="invalid-feedback" v-if="editing.errors['level_id']">
                                    <strong>{{ editing.errors['level_id'][0] }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save changes
                                </button>
                                <button type="button" class="btn btn-secondary" @click="editing.id = null">
                                    Cancel
                                </button>

                                <!-- Spinner -->
                                <div class="my-1" v-if="editing.processing">
                                    <hollow-dots-spinner
                                            :animation-duration="1000"
                                            :dot-size="15"
                                            :dots-num="3"
                                            :color="'#ff1d5e'"
                                    />
                                    <p>Please wait, saving changes...</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "user-skills-index",
        props: [
            'endpoint'
        ],
        components: {
            HollowDotsSpinner
        },
        data() {
            return {
                fetching: false,
                records: [],
                skills: [],
                levels: [],
                creating: {
                    active: false,
                    form: {},
                    errors: [],
                    processing: false
                },
                editing: {
                    id: null,
                    form: {},
                    errors: [],
                    processing: false
                },
                deleting: {
                    id: null,
                    form: {},
                    errors: [],
                    processing: false
                },
                status: {
                    id: null,
                    errors: [],
                    processing: false
                },
                updateable: {}
            }
        },
        mounted() {
            this.getSkills()
            this.getLevels()
            this.getRecords()

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }
        },
        methods: {
            getRecords() {
                this.fetching = true

                return axios.get(this.endpoint + '/index').then((response) => {
                    this.records = response.data.data;
                }).catch((error) => {
                    console.log(error.response.data)
                }).finally(() => {
                    this.fetching = false
                })
            },
            getSkills() {
                axios.get(this.endpoint + '/list').then((response) => {
                    this.skills = response.data.data;
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            getLevels() {
                axios.get(this.endpoint + '/levels').then((response) => {
                    this.levels = response.data.data;
                }).catch((error) => {
                    console.log(error.response.data)
                })
            },
            store() {
                this.creating.processing = true
                this.creating.errors = []

                axios.post(this.endpoint, this.creating.form).then((response) => {
                    this.creating.active = false

                    this.getRecords().then(() => {
                        this.creating.form = {}
                    })

                    toastr.success('Skill added successfully.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.creating.errors = error.response.data.errors
                    }

                    toastr.error('Failed adding skill.', 'Whoops!')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            edit(skillset) {
                this.editing.errors = []
                this.editing.id = skillset.id
                this.editing.form = this.updateable
            },
            update() {
                this.editing.processing = true

                axios.put(this.endpoint + '/' + this.editing.id, this.editing.form).then((response) => {
                    this.getRecords().then(() => {
                        this.editing.id = null
                        this.editing.form = {}
                    })

                    toastr.success('Skill updated successfully.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    toastr.error('Failed updating record.', 'Whoops!')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            destroy(skillset) {
                this.deleting.id = skillset.id
                this.deleting.processing = true

                axios.delete(this.endpoint + '/' + skillset.id).then((response) => {
                    this.getRecords().then(() => {
                    })

                    toastr.success('Skill deleted successfully.', skillset.skill.name)
                }).catch((error) => {
                    toastr.error('Failed deleting skill.', 'Whoops!')
                }).finally(() => {
                    this.deleting.id = null
                    this.deleting.processing = false
                })
            },
            toggleStatus(skillset) {
                this.status.id = skillset.id
                this.status.processing = true

                var usable = !skillset.usable

                axios.put(this.endpoint + '/' + skillset.id + '/status', {
                    usable
                }).then((response) => {
                    this.getRecords().then(() => {
                        // do something here
                    })

                    if (usable == true) {
                        toastr.success('Skill enabled.', skillset.skill.name)
                    } else {
                        toastr.success('Skill disabled.', skillset.skill.name)
                    }
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    toastr.error('Failed updating skill status.', 'Whoops!')
                }).finally(() => {
                    this.status.id = null
                    this.status.processing = false
                })
            }
        }
    }
</script>

<style scoped>

</style>