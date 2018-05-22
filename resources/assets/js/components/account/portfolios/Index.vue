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
            <form action="#" v-if="portfolio != null && creating.active" @submit.prevent="storePortfolio">

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
        <div class="list-group list-group-flush" v-if="portfolios.length">
            <!-- Spinner -->
            <div class="list-group-item" v-if="fetching">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
                <p>Fetching...</p>
            </div>

            <!-- Portfolio -->
            <div class="list-group-item" v-for="portfolio in portfolios">
                <!-- Item -->
                <template v-if="editing.id != portfolio.identifier">
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
                    <div class="my-1">
                        <b-link href="#" @click.prevent="modalShow = !modalShow">  <!-- switch dynamically -->
                            Skills
                        </b-link>

                        <b-modal size="lg" v-model="modalShow" :title="portfolio.title + ' skills'">
                            Todo: List of skills & languages used in portfolio
                        </b-modal>
                    </div>

                    <!-- Spinner -->
                    <div class="my-1"
                         v-if="deleting.processing && deleting.id == portfolio.identifier || status.processing && status.id == portfolio.identifier">
                        <hollow-dots-spinner
                                :animation-duration="1000"
                                :dot-size="15"
                                :dots-num="3"
                                :color="'#ff1d5e'"
                        />

                        <p v-if="deleting.id == portfolio.identifier">Deleting...</p>

                        <p v-if="status.id == portfolio.identifier">Updating status...</p>
                    </div>
                </template>

                <!-- Editing Form -->
                <form action="#" @submit.prevent="updatePortfolio" v-else>

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
            </div>
        </div>
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
            edit(portfolio) {
                this.editing.id = portfolio.identifier
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

                var form = {
                    live
                }

                this.toggleStatus(portfolio, form).then((response) => {

                    // refresh list of portfolio
                    this.getPortfolios().then(() => {
                        this.status.id = null
                    })

                    if (live == true) {
                        toastr.success('Status: Portfolio is live.', portfolio.title)
                    } else {
                        toastr.success('Status: Portfolio is disabled.', portfolio.title)
                    }
                }).catch((error) => {
                    toastr.error('Whoops! Failed updating portfolio status.', portfolio.title)
                }).finally(() => {
                    this.status.processing = false
                })
            }
        }
    }
</script>

<style scoped>

</style>