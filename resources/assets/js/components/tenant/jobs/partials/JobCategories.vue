<template>
    <div>
        <b-list-group flush>
            <b-list-group-item>
                <div class="d-flex justify-content-between align-content-center">
                    <h5>Job listing categories</h5>

                    <div>
                        <b-link @click.prevent="close">Close</b-link>
                    </div>
                </div>
            </b-list-group-item>

            <!-- Job categories List -->
            <b-list-group-item v-for="job_category in job_categories"
                               :key="job_category.id">
                <div class="d-flex justify-content-between align-content-center">
                    <p class="h6">{{ job_category.category.name }}</p>
                    <div>
                        <!-- Status / Checkout link -->
                        <p v-if="job_category.approved">
                            Approved
                        </p>
                        <div v-else>
                            <!-- Links -->
                            <div v-if="job_category.id != deleting.id">
                                <!-- Checkout -->
                                <span v-if="!job.isPublished">
                                    Checkout to apply changes
                                </span>

                                <!-- Delete link -->
                                <b-link v-if="!job_category.approved"
                                        @click.prevent="destroy(job_category)">
                                    Delete
                                </b-link>
                            </div>

                            <!-- Delete spinner -->
                            <div v-else-if="job_category.id == deleting.id">
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
                    </div>
                </div>
            </b-list-group-item>

            <!-- Upgrade -->
            <b-list-group-item v-if="job_categories.length > 0">
                <p>
                    Do you want to get better results? 
                    <b-button variant="link" @click="upgrade = !upgrade">
                        {{ upgrade == true ? 'Cancel' : 'Upgrade'}}
                    </b-button>
                </p>
            </b-list-group-item>

            <!-- No job category alert -->
            <b-list-group-item v-if="!fetching && job_categories.length == 0">
                Job not listed in any category yet.
            </b-list-group-item>

            <!-- Add categories form -->
            <b-list-group-item v-if="job_categories.length == 0 || upgrade == true">
                <b-form @submit.prevent="store">
                    <b-form-group horizontal
                                  description="Job listing category."
                                  label="Job category"
                                  :state="fieldState('creating', 'category_id')"
                                  :invalid-feedback="invalidFeedback('creating', 'category_id')">
                        <b-form-radio-group stacked v-model="category">
                            <b-form-radio :value="category.id"
                                          v-for="category in categories"
                                          :key="category.id" 
                                          :disabled="upgrade && category.price == 0 && category.children.length == 0 ? true : false">
                                {{ category.name }} 
                                <template v-if="category.children.length == 0">
                                    (${{ category.price}})
                                </template>
                            </b-form-radio>
                        </b-form-radio-group>

                        <b-form-group horizontal
                                      description="Advanced job listing categories enable getting the best results."
                                      label="Advanced listing categories"
                                      v-if="children.length > 0">
                            <b-form-checkbox-group stacked v-model="selected">
                                <b-form-checkbox :value="child.value"
                                                 v-for="child in children"
                                                 :key="child.value" 
                                                 :disabled="activeCategories.indexOf(child.value) >= 0 ? true : false">
                                             {{ child.text }} (${{ child.price}})
                                </b-form-checkbox>
                            </b-form-checkbox-group>

                            <div class="mt-3" v-if="selectedDetails.length > 0">
                                <h4>Selected listing categories:</h4>

                                <!-- Categories -->
                                <div class="my-1" v-for="category in selectedDetails">
                                    <div class="d-flex justify-content-between align-content-center">
                                        <h5>{{ category.name }}</h5>
                                        <h4>${{ category.price }}</h4>
                                    </div>
                                </div>

                                <!-- Estimated Total -->
                                <div class="d-flex justify-content-between align-content-center">
                                    <h5>Estimated total</h5>
                                    <h4>${{ selectedCost }}</h4>
                                </div>
                            </div>
                        </b-form-group>
                    </b-form-group>

                    <b-form-group horizontal>
                        <div class="my-1" v-if="creating.processing">
                            <hollow-dots-spinner
                                    :animation-duration="1000"
                                    :dot-size="15"
                                    :dots-num="3"
                                    :color="'#ff1d5e'"/>
                            <p>Saving...</p>
                        </div>

                        <template v-else>
                            <b-button type="submit" variant="primary">
                                Save
                            </b-button>
                        </template>
                    </b-form-group>
                </b-form>
            </b-list-group-item>
        </b-list-group>
    </div>
</template>

<script>
    import BusEvent from '../../../../bus'
    import validation from '../../../../mixins/validation'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "tenant-job-categories",
        props: [
            'endpoint',
            'job',
            'categories'
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
                upgrade: false,
                selected: [],
                category: _.head(this.categories).id,
                children: _.head(this.categories).children,
                job_categories: this.job.categories
            }
        },
        computed: {
            options() {
                var filter = function (obj) {
                    return {
                        'text': obj.name,
                        'value': obj.id,
                        'children': obj.children
                    }
                }

                return this.categories.map(filter)
            },
            selectedDetails() {     // selected categories details
                var details = []

                var query = (id) => {
                    var search = function (o) {
                        var children = o.children

                        if (o.id == id) {
                            details.push(o)
                        } else {
                            _.find(children, search)
                        }
                    }

                    var cat = _.find(this.categories, search)
                }

                _.each(this.selected, query)

                return details
            },
            selectedCost() {    // selected categories cost
                if (this.selectedDetails.length > 0) {
                    var prices = _.map(this.selectedDetails, function (obj) {
                        return parseFloat(obj.price)
                    })

                    var cost = _.sum(prices)

                    return _.round(cost, 2)
                }

                return parseFloat(0.00)
            },
            activeCategories() {
                var jobCategories = _.map(this.job.categories, function(obj) {
                    return obj.category.id
                })

                return jobCategories
            }
        },
        watch: {
            category(newCategory, oldCategory) {
                this.selected = []

                if (this.category != 'undefined' || this.category != null) {
                    var filter = function (obj) {
                        return obj.id == newCategory
                    }

                    var filtered = this.categories.find(filter)

                    this.children = filtered.children.map(obj => {
                        var option = {
                            'text': obj.name,
                            'value': obj.id,
                            'price': obj.price
                        }

                        return option
                    })
                }
            }
        },
        mounted() {
            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }
        },
        methods: {
            close() {
                this.$parent.category.id = null
                this.$parent.category.active = false
            },
            store() {
                this.creating.processing = true
                this.creating.errors = []

                // assign category to selected if it has no children
                if (this.children == 0 && this.selected == 0) {
                    this.selected.push(this.category)
                }

                this.creating.form.category_id = this.selected

                axios.post(this.endpoint + '/' + this.job.slug + '/categories', this.creating.form).then((response) => {

                    // assign new categories
                    this.job.categories = response.data.data

                    // reassign job categories
                    this.job_categories = this.job.categories

                    // clear form
                    this.clearCreating()

                    toastr.success('Categories added successfully.', this.job.title)
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 403) {
                            toastr.error('Categories cannot be added.', 'Whoops')
                        }

                        if (error.response.status === 422) {
                            this.creating.errors = error.response.data.errors
                        }
                        console.log(error.response)
                    }

                    console.log(error)

                    toastr.error('Failed adding categories. Please try again!', 'Whoops')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            destroy(job_category) {
                this.deleting.processing = true
                this.deleting.id = job_category.id

                axios.delete(this.endpoint + '/' + this.job.slug + '/categories' + '/' + job_category.id).then(() => {
                    // filter out removed job category
                    this.job_categories = this.job_categories.filter(function (oldCategory) {
                        return oldCategory.id != job_category.id
                    })

                    // update job categories
                    this.job.categories = this.job_categories

                    // send event
                    BusEvent.$emit('tenant-job:category-deleted', job_category)

                    //display category
                    toastr.success(job_category.category.name + ' category deleted successfully.', this.job.title)

                }).catch((error) => {
                    if (error.response.status === 403) {
                        toastr.error(error.response.message, 'Whoops')
                    }

                    toastr.error('Failed deleting category. Please try again!', 'Whoops')
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