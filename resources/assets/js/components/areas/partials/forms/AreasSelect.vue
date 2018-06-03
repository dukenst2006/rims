<template>
    <optgroup :label="area.name" v-if="area.children.length">
            <areas-select :area="child" v-for="child in area.children" :key="child.id"></areas-select>
    </optgroup>

    <option :value="area.id" v-else>{{ area.name }}</option>
</template>

<script>
    export default {
        name: "areas-select",
        props: [
            'area'
        ]
    }
</script>

<style scoped>

</style>