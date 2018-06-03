export default {
    name: "nested-tree-select",
    computed: {
        tree() {
            var filter = function (obj) {
                if (obj.children && obj.children.length == 0) {
                    _.unset(obj, 'children')
                } else {
                    var children = obj.children

                    _.each(children, filter)
                }
            }

            var tree = _.each(this.nestedoptions, filter)

            return tree
        }
    },
    data() {
        return {
            nestedoptions: [],
            normalizer(node/*, id */) {
                // there is an extra `id` argument,
                // which could be useful if you have multiple instances
                // of vue-treeselect that share the same `normalizer` function
                return {
                    id: node.id,
                    label: node.name,
                    children: node.children,
                }
            }
        }
    }
}
