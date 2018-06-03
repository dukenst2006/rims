<template>
    <optgroup :label="skill.name" v-if="skill.children.length">
        <skills-select :skill="child"
                       v-for="child in skill.children"
                       :key="child.id"></skills-select>
    </optgroup>

    <option :value="skill.id" v-else>{{ skill.name }}</option>
</template>

<script>
    export default {
        name: "skills-select",
        props: [
            'skill'
        ]
    }
</script>

<style scoped>

</style>