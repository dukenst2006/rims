<template>
    <div class="form-group">
        <label :for="'portfolio-uploads-' + portfolio.identifier"
               class="control-label">
            Upload more images
        </label>

        <div class="my-1" v-if="refreshButton || !uploads.length">
            <p v-if="refreshButton">Mmmh! Looks like portfolio has no uploads.</p>

            <p>
                <b-button variant="link" @click="getPortfolioUploads">
                    <i class="icon-refresh"></i> Refresh
                </b-button> to load files already uploaded or drop files below to start uploading.
            </p>
        </div>

        <div class="mb-3">
            <vue-dropzone ref="portfolioDropzone"
                          id="portfolio-uploads-dropzone"
                          :destroyDropzone="false"
                          :options="dropzoneOptions"
                          :include-styling="false"
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
                    createImageThumbnails: true,
                    thumbnailWidth: 100, // px
                    thumbnailHeight: 100,
                    addRemoveLinks: true,
                    previewTemplate: this.template(),
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> Drop files here to upload",
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
            },
            template() {
                return `<div class="dz-preview dz-file-preview">
                            <div class="dz-image">
                                <div data-dz-thumbnail-bg></div>
                            </div>
                            <div class="dz-details">
                                <div class="dz-size"><span data-dz-size></span></div>
                                <div class="dz-filename"><span data-dz-name></span></div>
                            </div>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                            <div class="dz-error-message"><span data-dz-errormessage></span></div>
                            <div class="dz-success-mark"><i class="fa fa-check"></i></div>
                            <div class="dz-error-mark"><i class="fa fa-close"></i></div>
                        </div>`;
            }
        }
    }
</script>

<style scoped>
    #portfolio-uploads-dropzone {
        background-color: #e0e0e0;
    }
</style>