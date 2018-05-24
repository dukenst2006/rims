<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>My Portfolios</h4>

                    <a href="#" class="pull-right ml-auto" @click.prevent="initPortfolio" v-if="!startCreating">
                        {{ creating.active ? 'Cancel' : 'Add new' }}
                    </a>
                </div>
            </div>

            <p v-if="startCreating">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />

                Please wait... setting template for portfolio
            </p>

            <!-- Creating Form -->
            <form action="#" v-if="portfolio !== null && creating.active" @submit.prevent="storePortfolio">

                <!-- Image -->
                <portfolio-image
                        send-as="image"
                        :endpoint="endpoint +'/' + portfolio.identifier + '/image'"
                        :current-image="portfolio.image"></portfolio-image>

                <!-- Title -->
                <div class="form-group row" :class="{ 'has-error': creating.errors['title']}">
                    <label for="title" class="control-label col-md-4">Title</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control"
                               :class="{ 'is-invalid': creating.errors['title']}"
                               id="title" placeholder="Title" v-model="creating.form.title">

                        <div class="invalid-feedback" v-if="creating.errors['title']">
                            <strong>{{ creating.errors['title'][0] }}</strong>
                        </div>
                    </div>
                </div><!-- /.form-group -->

                <!-- Short overview -->
                <div class="form-group row" :class="{ 'has-error': creating.errors['overview_short']}">
                    <label for="overview_short" class="control-label col-md-4">Short overview</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control"
                               :class="{ 'is-invalid': creating.errors['overview_short']}"
                               id="overview_short" placeholder="Short overview" v-model="creating.form.overview_short">

                        <div class="invalid-feedback" v-if="creating.errors['overview_short']">
                            <strong>{{ creating.errors['overview_short'][0] }}</strong>
                        </div>
                    </div>
                </div><!-- /.form-group -->

                <!-- Price -->
                <div class="form-group row d-none" :class="{ 'has-error': creating.errors['price']}">
                    <label for="overview" class="control-label col-md-4">Price</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control"
                               :class="{ 'is-invalid': creating.errors['price']}"
                               id="price" placeholder="Price" v-model="creating.form.price">

                        <div class="invalid-feedback" v-if="creating.errors['price']">
                            <strong>{{ creating.errors['price'][0] }}</strong>
                        </div>
                    </div>
                </div><!-- /.form-group -->

                <!-- Overview -->
                <div class="form-group row" :class="{ 'has-error': creating.errors['overview']}">
                    <label for="overview" class="control-label col-md-4">Overview</label>
                    <div class="col-md-6">
                        <textarea class="form-control"
                                  :class="{ 'is-invalid': creating.errors['overview']}"
                                  id="overview"
                                  placeholder="Overview"
                                  v-model="creating.form.overview"></textarea>

                        <div class="invalid-feedback" v-if="creating.errors['overview']">
                            <strong>{{ creating.errors['overview'][0] }}</strong>
                        </div>
                    </div>
                </div><!-- /.form-group -->

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>

                        <!-- Spinner -->
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
            </form><!-- /.form -->
        </div><!-- /.card-body -->

        <!-- Portfolios -->
        <b-list-group flush v-if="portfolios.length">

            <!-- Spinner -->
            <b-list-group-item v-if="fetching">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
                <p>Fetching...</p>
            </b-list-group-item>

            <!-- Portfolio -->
            <template v-for="portfolio in portfolios">
                <!-- Portfolio -->
                <b-list-group-item>
                    <!-- Content -->
                    <template v-if="editing.id !== portfolio.identifier">
                        <div class="d-flex justify-content-between align-content-center">
                            <h4>{{ portfolio.title }}</h4>

                            <div>
                                <a href="#" @click.prevent>Preview</a>
                                <a href="#" @click.prevent="togglePortfolioStatus(portfolio)">
                                    {{ portfolio.live ? 'Disable' : 'Enable' }}
                                </a>
                                <a href="#" @click.prevent="edit(portfolio)">Edit</a>
                                <a href="#" @click.prevent="destroyPortfolio(portfolio)">Delete</a>
                            </div>
                        </div>

                        <p class="card-text">{{ portfolio.overview_short }}</p>

                        <!-- Portfolio Skills -->
                        <p v-if="skillables.portfolio.identifier !== portfolio.identifier">
                            <b-link href="#" @click.prevent="skillables.portfolio = portfolio">
                                Skills
                            </b-link>
                        </p>

                        <!-- Spinner -->
                        <div class="my-1"
                             v-if="deleting.processing && deleting.id === portfolio.identifier || status.processing && status.id === portfolio.identifier">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"
                            />

                            <p v-if="deleting.id === portfolio.identifier">Deleting...</p>

                            <p v-if="status.id === portfolio.identifier">Updating status...</p>
                        </div>
                    </template>

                    <!-- Editing Form -->
                    <form action="#" @submit.prevent="updatePortfolio" v-else>
                        <h4>Edit {{ portfolio.title }}</h4>

                        <!-- Image -->
                        <portfolio-image
                                send-as="image"
                                :endpoint="endpoint +'/' + portfolio.identifier + '/image'"
                                :current-image="portfolio.image"></portfolio-image>

                        <!-- Uploads -->
                        <portfolio-uploads
                                :portfolio="portfolio"
                                :endpoint="endpoint +'/' + portfolio.identifier + '/uploads'"></portfolio-uploads>

                        <!-- Title -->
                        <div class="form-group row" :class="{ 'has-error': editing.errors['title']}">
                            <label :for="'title_' + portfolio.identifier" class="control-label col-md-4">Title</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control"
                                       :class="{ 'is-invalid': editing.errors['title']}"
                                       :id="'title_' + portfolio.identifier"
                                       placeholder="Title"
                                       v-model="editing.form.title = portfolio.title">

                                <div class="invalid-feedback" v-if="editing.errors['title']">
                                    <strong>{{ editing.errors['title'][0] }}</strong>
                                </div>
                            </div>
                        </div><!-- /.form-group -->

                        <!-- Short overview -->
                        <div class="form-group row" :class="{ 'has-error': editing.errors['overview_short']}">
                            <label :for="'overview_short_' + portfolio.identifier" class="control-label col-md-4">
                                Short overview
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control"
                                       :class="{ 'is-invalid': editing.errors['overview_short']}"
                                       :id="'overview_short_' + portfolio.identifier"
                                       placeholder="Short overview"
                                       v-model="editing.form.overview_short = portfolio.overview_short">

                                <div class="invalid-feedback" v-if="editing.errors['overview_short']">
                                    <strong>{{ editing.errors['overview_short'][0] }}</strong>
                                </div>
                            </div>
                        </div><!-- /.form-group -->

                        <!-- Price -->
                        <div class="form-group row d-none" :class="{ 'has-error': editing.errors['price']}">
                            <label :for="'price_' + portfolio.identifier" class="control-label col-md-4">Price</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control"
                                       :class="{ 'is-invalid': editing.errors['price']}"
                                       :id="'price_' + portfolio.identifier"
                                       placeholder="Price"
                                       v-model="editing.form.price = portfolio.price">

                                <div class="invalid-feedback" v-if="editing.errors['price']">
                                    <strong>{{ editing.errors['price'][0] }}</strong>
                                </div>
                            </div>
                        </div><!-- /.form-group -->

                        <!-- Overview -->
                        <div class="form-group row" :class="{ 'has-error': editing.errors['overview']}">
                            <label :for="'overview_' + portfolio.identifier"
                                   class="control-label col-md-4">Overview</label>
                            <div class="col-md-6">
                        <textarea class="form-control"
                                  :class="{ 'is-invalid': editing.errors['overview']}"
                                  :id="'overview_' + portfolio.identifier"
                                  placeholder="Overview"
                                  v-model="editing.form.overview = portfolio.overview"></textarea>

                                <div class="invalid-feedback" v-if="editing.errors['overview']">
                                    <strong>{{ editing.errors['overview'][0] }}</strong>
                                </div>
                            </div>
                        </div><!-- /.form-group -->

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
                                </div>
                            </div>
                        </div>
                    </form><!-- /.form -->
                </b-list-group-item>

                <!-- Portfolio Skills List -->
                <b-list-group flush v-if="skillables.portfolio.identifier === portfolio.identifier">
                    <b-list-group-item>
                        <div class="d-flex justify-content-between align-content-center">
                            <h5>{{ portfolio.title }} Skills</h5>

                            <b-nav>
                                <!-- Add Skill -->
                                <b-nav-item href="#"
                                            @click.prevent="skillables.active = true"
                                            v-b-modal="'portfolio-skills-create-modal'">
                                    Add new skill
                                </b-nav-item>

                                <!-- Close -->
                                <b-nav-item href="#"
                                            @click.prevent="closeSkillables">
                                    <i class="fa fa-close"></i> Close
                                </b-nav-item>
                            </b-nav>
                        </div>
                    </b-list-group-item>

                    <!-- Loop through portfolio skills -->
                    <b-list-group-item v-for="skillset in portfolio.skills" :key="skillset.id">

                        <!-- Content -->
                        <template v-if="skillables.editing.id != skillset.id">
                            <div class="d-flex justify-content-between align-content-center">
                                <h5>
                                    {{ skillset.skillable.name }}
                                    <template v-if="skillset.skillable.ancestors.length">
                                        (
                                        <span v-for="ancestor in skillset.skillable.ancestors"
                                              v-if="skillset.skillable.ancestors && skillset.skillable.ancestors.length > 1">
                                        {{ ancestor.name }} /
                                    </span>
                                        <span v-for="ancestor in skillset.skillable.ancestors" v-else>
                                        {{ ancestor.name }}
                                    </span>
                                        )
                                    </template>
                                </h5>

                                <div v-if="skillables.editing.id == skillset.id ||
                                 skillables.deleting.id == skillset.id">
                                    <hollow-dots-spinner
                                            :animation-duration="1000"
                                            :dot-size="15"
                                            :dots-num="3"
                                            :color="'#ff1d5e'"
                                    />

                                    <p v-if="skillables.deleting.processing">
                                        Deleting...
                                    </p>
                                </div>

                                <b-nav v-else>
                                    <b-nav-item href="#"
                                                @click.prevent="editSkillables(portfolio, skillset)">
                                        Edit
                                    </b-nav-item>
                                    <b-nav-item href="#"
                                                @click.prevent="destroyPortfolioSkill(portfolio, skillset)">
                                        Delete
                                    </b-nav-item>
                                </b-nav>
                            </div>

                            <p>Level: {{ skillset.level.name }}</p>
                        </template>

                        <!-- Skill Editing Form -->
                        <form action="#"
                              @submit.prevent="updatePortfolioSkill"
                              v-else>
                            <h5>Editing {{ skillset.skillable.name }}</h5>

                            <div class="form-group row" :class="{ 'has-error': skillables.errors['skill_id']  }">
                                <label for="edit_skill_id" class="control-label col-md-4">Skill</label>
                                <div class="col-md-6">
                                    <input type="text" readonly
                                           class="form-control-plaintext" id="edit_skill_id"
                                           :class="{ 'is-invalid': skillables.errors['skill_id']  }"
                                           v-model="skillset.skillable.name">
                                    <input type="hidden" v-model="skillables.form.skill_id">

                                    <div class="invalid-feedback" v-if="skillables.errors['skill_id']">
                                        <strong>{{ skillables.errors['skill_id'][0] }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row" :class="{ 'has-error': skillables.errors['level_id']  }">
                                <label for="edit_level_id" class="control-label col-md-4">Level</label>
                                <div class="col-md-4">
                                    <select id="edit_level_id"
                                            class="form-control custom-select"
                                            :class="{ 'is-invalid': skillables.errors['level_id']  }"
                                            v-model="skillables.form.level_id">
                                        <option value="">-----------</option>
                                        <option :value="level.id" v-for="level in levels"
                                                :selected="level.id === skillset.level.id">
                                            {{ level.name }}
                                        </option>
                                    </select>

                                    <div class="invalid-feedback" v-if="skillables.errors['level_id']">
                                        <strong>{{ skillables.errors['level_id'][0] }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save changes
                                    </button>
                                    <button type="button" class="btn btn-secondary" @click="cancelSkillableEditing">
                                        Cancel
                                    </button>

                                    <!-- Spinner -->
                                    <div class="my-1" v-if="skillables.editing.processing">
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
                    </b-list-group-item>
                </b-list-group>
            </template>

        </b-list-group>

        <b-modal size="lg"
                 id="portfolio-skills-create-modal"
                 ref="portfolioSkillCreateModal"
                 :title="skillables.portfolio.title + ' skills'"
                 @ok="handleModalOk"
                 ok-title="Save">

            <form action="#" @submit.prevent="storePortfolioSkill">
                <h5>Add new skill</h5>

                <div class="form-group row" :class="{ 'has-error': skillables.errors['skill_id']  }">
                    <label for="skill_id" class="control-label col-md-4">Skill</label>
                    <div class="col-md-4">
                        <select id="skill_id"
                                class="form-control custom-select"
                                :class="{ 'is-invalid': skillables.errors['skill_id']  }"
                                v-model="skillables.form.skill_id">
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

                        <div class="invalid-feedback" v-if="skillables.errors['skill_id']">
                            <strong>{{ skillables.errors['skill_id'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group row" :class="{ 'has-error': skillables.errors['level_id']  }">
                    <label for="level_id" class="control-label col-md-4">Level</label>
                    <div class="col-md-4">
                        <select id="level_id"
                                class="form-control custom-select"
                                :class="{ 'is-invalid': skillables.errors['level_id']  }"
                                v-model="skillables.form.level_id">
                            <option value="">-----------</option>
                            <option :value="level.id" v-for="level in levels">
                                {{ level.name }}
                            </option>
                        </select>

                        <div class="invalid-feedback" v-if="skillables.errors['level_id']">
                            <strong>{{ skillables.errors['level_id'][0] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="my-1" v-if="skillables.processing">
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
        </b-modal>
    </div>
</template>

<script>
    import portfolio from '../../../mixins/portfolio'
    import PortfolioImage from '../../portfolios/PortfolioImage'
    import PortfolioUploads from '../../portfolios/PortfolioUploads'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "user-portfolios-index",
        components: {
            HollowDotsSpinner,
            PortfolioImage,
            PortfolioUploads
        },
        mixins: [
            portfolio
        ],
        data() {
            return {
                portfolio: null,
                modalShow: false
            }
        },
        mounted() {
            this.getPortfolios()
            this.getSkills()
            this.getLevels()

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }
        },
        methods: {
            initPortfolio() {
                if (this.creating.active) {

                    // delete initialised portfolio
                    this.destroy(this.portfolio)

                    // clear init portfolio
                    this.portfolio = null
                    this.creating.active = false

                    return
                }

                this.create().then((response) => {
                    this.portfolio = response.data.data
                }).catch((error) => {
                    this.creating.active = false
                    toastr.error('Something went wrong. Try again.', 'Whoops')
                }).finally(() => {
                    this.startCreating = false
                })
            },
            storePortfolio() {
                this.creating.errors = []

                this.store(this.portfolio).then((response) => {

                    // temporarily update ui
                    this.portfolios.unshift(response.data.data)

                    // refresh list of portfolio
                    this.getPortfolios().then(() => {
                        this.portfolio = null
                        this.creating.form = {}
                        this.creating.active = false
                    })

                    toastr.success('Portfolio successfully saved.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.creating.errors = error.response.data.errors
                    }

                    toastr.error('Failed saving portfolio.', 'Whoops!')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            updatePortfolio() {
                this.editing.errors = []

                this.update(this.portfolio).then((response) => {

                    // refresh list of portfolio
                    this.getPortfolios().then(() => {
                        this.editing.id = null
                        this.editing.form = {}
                    })

                    toastr.success('Portfolio successfully updated.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.editing.errors = error.response.data.errors
                    }

                    toastr.error('Failed updating portfolio.', 'Whoops!')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            destroyPortfolio(portfolio) {
                this.deleting.id = portfolio.identifier

                this.destroy(portfolio).then((response) => {

                    // refresh list of portfolio
                    this.getPortfolios().then(() => {
                        this.deleting.errors = []
                    })

                    toastr.success('Portfolio successfully deleted.', portfolio.title)
                }).catch((error) => {
                    // log: error.response.data

                    toastr.error('Whoops! Failed deleting portfolio.', portfolio.title)
                }).finally(() => {
                    this.deleting.processing = false
                })
            },
            togglePortfolioStatus(portfolio) {
                this.status.processing = true
                this.status.id = portfolio.identifier

                var live = !portfolio.live

                this.status.form = {
                    live
                }

                this.toggleStatus(portfolio).then((response) => {

                    // refresh list of portfolio
                    this.getPortfolios().then(() => {
                        this.status.id = null
                    })

                    if (live === true) {
                        toastr.success('Status: Portfolio is live.', portfolio.title)
                    } else {
                        toastr.success('Status: Portfolio is disabled.', portfolio.title)
                    }
                }).catch((error) => {
                    toastr.error('Whoops! Failed updating portfolio status.', portfolio.title)
                }).finally(() => {
                    this.status.processing = false
                })
            },
            handleModalOk(e) {
                e.preventDefault()

                this.storePortfolioSkill()
            },
            storePortfolioSkill() {
                this.skillables.errors = []

                this.skillStore(this.skillables.portfolio).then((response) => {
                    // clear form
                    this.skillables.form = {}

                    // refresh portfolio skills
                    this.skillables.active = false

                    // close modal
                    this.$refs.portfolioSkillCreateModal.hide()

                    // show toast
                    toastr.success(
                        response.data.data.skillable.name + ': Skill successfully added.',
                        this.skillables.portfolio.title
                    )

                    // add skill to portfolio skills
                    this.skillables.portfolio.skills.push(response.data.data)

                }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.skillables.errors = error.response.data.errors
                    }

                    if (error.response && !error.response.status) {
                        toastr.error(
                            'Failed adding skill to portfolio.',
                            this.skillables.portfolio.title
                        )
                    }
                }).finally(() => {
                    this.skillables.processing = false
                })
            },
            updatePortfolioSkill() {
                this.skillables.errors = []

                this.skillUpdate(this.skillables.portfolio).then((response) => {

                    // assign new values
                    var index = this.portfolios.indexOf(this.skillables.portfolio)

                    // skill index
                    var skillIndex = this.portfolios[index].skills.indexOf(this.skillables.skillset)

                    // update skill
                    this.portfolios[index].skills[skillIndex] = response.data.data

                    toastr.success(
                        this.skillables.skillset.skillable.name + ': Skill successfully updated.',
                        this.skillables.portfolio.title
                    )

                    // clear editing
                    this.cancelSkillableEditing()
                }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.skillables.errors = error.response.data.errors
                    }

                    toastr.error('Failed updating portfolio.', 'Whoops!')
                }).finally(() => {
                    this.skillables.editing.processing = false
                })
            },
            destroyPortfolioSkill(portfolio, skillset) {
                this.skillables.deleting.id = skillset.id
                this.skillables.portfolio = portfolio

                this.skillDestroy(this.skillables.portfolio).then((response) => {

                    // remove skill from list
                    var index = this.portfolios.indexOf(portfolio)

                    this.portfolios[index].skills = this.portfolios[index].skills.filter((skill) => {
                        return skill.id !== skillset.id
                    })

                    toastr.success(skillset.skillable.name + ': Skill successfully deleted.', portfolio.title)
                }).catch((error) => {
                    // log: error.response.data

                    toastr.error(skillset.skillable.name + ': Failed deleting skill.', portfolio.title)
                }).finally(() => {
                    this.skillables.deleting.processing = false
                })
            }
        }
    }
</script>

<style scoped>

</style>