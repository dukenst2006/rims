<template>
    <div class="filters">
        <div class="mb-2">
            <b-button variant="secondary" block
                      v-if="filtersInUse"
                      @click="clearFilters">
                Clear all filters
            </b-button>
        </div>

        <!-- Filters to be added at the top -->
        <slot name="top"></slot>

        <template v-for="map, key in filters">
            <b-list-group class="mb-3"><!--  v-if="map.style != 'undefined' && map.style === 'list'" -->
                <b-list-group-item>
                    {{ map.heading }}
                </b-list-group-item>
                <b-list-group-item button
                                   v-for="filter, value in map.map"
                                   :key="key + '-' + value"
                                   @click.prevent="activateFilter(key, value)"
                                   :active="selectedFilters[key] == value">
                    {{ filter }}
                </b-list-group-item>

                <b-list-group-item variant="secondary"
                                   button
                                   v-if="selectedFilters[key]"
                                   @click.prevent="clearFilter(key)">
                    &times; Clear this filter
                </b-list-group-item>
            </b-list-group><!-- /.list-group -->
        </template>

        <!-- Filters to be added at the bottom -->
        <slot name="bottom"></slot>
    </div>
</template>

<script>
    import filter from '../../mixins/filter'

    export default {
        name: "filters",
        mixins: [
          filter
        ]
    }
</script>

<style scoped>

</style>