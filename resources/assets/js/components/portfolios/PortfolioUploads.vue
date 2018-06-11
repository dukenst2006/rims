<template>
    <div class="form-group">
        <label :for="'portfolio-uploads-' + portfolio.identifier"
               class="control-label">
            Upload more images
        </label>

        <div class="my-1" v-if="refreshButton || !uploads.length">
            <p v-if="refreshButton">Whoops! Some error occurred.</p>

            <button class="btn btn-primary" @click="getPortfolioUploads">
                <i class="icon-refresh"></i> Refresh if uploads not loaded
            </button>
        </div>

        <div class="mb-3">
            <vue-dropzone ref="portfolioDropzone"
                          id="portfolio-uploads-dropzone"
                          :options="dropzoneOptions"
                          @vdropzone-success="uploadSuccess"
                          @vdropzone-removed-file="destroy"></vue-dropzone>
        </div>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {
        name: "portfolio-uploads",
        props: [
            'endpoint',
            'portfolio'
        ],
        components: {
            vueDropzone: vue2Dropzone
        },
        data: function () {
            return {
                uploads: [],
                refreshButton: false,
                dropzoneOptions: {
                    url: this.endpoint,
                    createImageThumbnails: false,
                    addRemoveLinks: true,
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> CLICK or DRAG N DROP A FILE TO UPLOAD",
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                    }
                }
            }
        },
        mounted() {
            // set uploads
            this.uploads = this.portfolio.uploads

            // add uploads to dropzone
            this.addUploads(this.uploads)
        },
        methods: {
            getPortfolioUploads() {
                this.refreshButton = false

                axios.get(this.endpoint).then((response) => {
                    this.uploads = response.data.data

                    this.addUploads(this.uploads)

                }).catch((error) => {
                    this.refreshButton = true

                    // log error to a file or webhook
                    console.log(error)
                })
            },
            uploadSuccess(upload, response) {
                if (upload.status === 'success') {
                    // remove file
                    this.$refs.portfolioDropzone.removeFile(upload)

                    // add the custom file
                    this.addManually(response.data)
                }
            },
            addUploads(uploads) {
                uploads.forEach(this.addManually)
            },
            addManually(upload) {
                var file = {
                    size: upload.size,
                    name: upload.filename || upload.name,   // fetch filename or name
                    type: upload.type,
                    id: upload.id || null,  // fetch id or set null
                }

                var url = upload.fullPath

                this.$refs.portfolioDropzone.manuallyAddFile(file, url)
            },
            destroy(upload) {
                // do nothing if upload has no id
                if (!upload.id || upload.id == null) {
                    return
                }

                // delete if upload has id
                axios.delete(this.endpoint + '/' + upload.id).then((response) => {
                    // do something here...
                }).catch((error) => {
                    console.log(upload)

                    // add back file
                    this.addManually(upload)

                    // log to file or webhook
                    console.log(error)
                })
            },
            removeAllUploads() {
                this.$refs.portfolioDropzone.removeAllFiles()
            }
        }
    }
</script>

<style scoped>
    #portfolio-uploads-dropzone .dz-preview {
        background-color: #607d8b;
    }
</style>