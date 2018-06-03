<template>
    <optgroup :label="language.name" v-if="language.children.length">
        <languages-select :language="child" v-for="child in language.children" :key="child.id"></languages-select>
    </optgroup>

    <option :value="language.id" v-else>{{ language.name }}</option>
</template>

<script>
    export default {
        name: "languages-select",
        props: [
            'language'
        ]
    }
</script>

<style scoped>

</style>