<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors[this.sendAs]  }">
            <label :for="sendAs" class="control-label">Upload portfolio image</label>

            <!-- Spinner -->
            <div class="py-2 my-1" v-if="uploading">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
                <p>Processing...</p>
            </div>

            <input :id="sendAs" type="file"
                   class="form-control"
                   :class="{ 'is-invalid': errors[this.sendAs]  }"
                   :name="sendAs"
                   @change="fileChange" v-else>

            <div class="invalid-feedback" v-if="errors[this.sendAs]">
                <strong>{{ errors[sendAs][0] }}</strong>
            </div>
        </div>

        <div class="form-group">
            <template v-if="image.path">
                <img class="image" :src="image.path" alt="Current portfolio image" width="320px" height="240px">
            </template>

            <div class="help-text" v-else>
                No image uploaded yet. Please upload one.
            </div>
        </div>
    </div>
</template>

<script>
    import upload from '../../mixins/upload'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "portfolio-image",
        props: [
            'currentImage'
        ],
        components: {
            HollowDotsSpinner
        },
        mixins: [
            upload
        ],
        data() {
            return {
                errors: [],
                image: {
                    path: this.currentImage
                },
            }
        },
        mounted() {
            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }
        },
        methods: {
            fileChange(e) {
                this.upload(e).then((response) => {
                    this.image.path = response.data.data.image

                    toastr.success('Portfolio image uploaded successfully.')
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors
                        return
                    }

                    this.errors = 'Something went wrong. Try again.'
                    toastr.error(this.errors, 'Whoops')
                })
            }
        }
    }
</script>

<style scoped>

</style>