<template>
    <b-list-group flush>
        <!-- Filter & Options -->
        <b-list-group-item>
            <div class="d-flex justify-content-between align-content-center">
                <div>
                    <h4>Sort by</h4>
                    <b-nav>
                        <b-nav-item active>Ending Soon</b-nav-item>

                        <!-- Time -->
                        <b-nav-item-dropdown text="Time" extra-toggle-classes="nav-link-custom" right>
                            <b-dropdown-item>Latest</b-dropdown-item>
                            <b-dropdown-item>Older</b-dropdown-item>
                        </b-nav-item-dropdown>

                        <!-- Work Site -->
                        <b-nav-item-dropdown text="Work Site" extra-toggle-classes="nav-link-custom" right>
                            <b-dropdown-item>On location</b-dropdown-item>
                            <b-dropdown-item>Remote / Off site</b-dropdown-item>
                        </b-nav-item-dropdown>

                        <!-- Type -->
                        <b-nav-item-dropdown text="Time" extra-toggle-classes="nav-link-custom" right>
                            <b-dropdown-item>Full time</b-dropdown-item>
                            <b-dropdown-item>Part time</b-dropdown-item>
                        </b-nav-item-dropdown>

                        <!-- Status -->
                        <b-nav-item-dropdown text="Status" extra-toggle-classes="nav-link-custom" right>
                            <b-dropdown-item>Open</b-dropdown-item>
                            <b-dropdown-item>Closed</b-dropdown-item>
                        </b-nav-item-dropdown>

                        <!-- Areas -->
                        <b-nav-item-dropdown text="Areas" extra-toggle-classes="nav-link-custom" right>
                            <b-dropdown-item>one</b-dropdown-item>
                            <b-dropdown-item>two</b-dropdown-item>
                            <b-dropdown-divider></b-dropdown-divider>
                            <b-dropdown-item>My Location</b-dropdown-item>
                        </b-nav-item-dropdown>
                    </b-nav>
                </div>

                <div>
                    &nbsp;
                </div>
            </div>
        </b-list-group-item>
    </b-list-group><!-- /.list-group -->
</template>

<script>
    export default {
        name: "tenant-job-filters"
    }
</script>

<style scoped>

</style>