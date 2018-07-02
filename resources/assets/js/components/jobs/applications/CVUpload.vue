<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors[this.sendAs]  }">
            <!-- Spinner -->
            <div class="py-2 my-1" v-if="uploading">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="4"
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
            <input type="hidden" name="cv" :value="cv.name">
            <p v-if="cv.path != ''">
                <b-link :href="cv.path">Preview</b-link> my uploaded CV.
            </p>
        </div>
    </div>
</template>

<script>
    import upload from '../../../mixins/upload'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "cv-upload",
        props: [
            'currentCv',
            'previewLink'
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
                cv: {
                    name: this.currentCv,
                    path: this.previewLink
                }
            }
        },
        mounted() {
            toastr.options = {
                closeButton: true,
                preventDuplicates: true
            }
        },
        methods: {
            fileChange(e) {
                this.upload(e).then((response) => {
                    this.cv = response.data.data
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