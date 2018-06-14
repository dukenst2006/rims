<template>
    <div class="form-group">
        <label :for="'portfolio-uploads-' + portfolio.identifier"
               class="control-label">
            Upload more images
        </label>

        <div class="my-1">
            <p v-if="uploads.length == 0">Mmmh! Looks like portfolio has no uploads.</p>

            <p v-if="refreshButton">Can't see your uploaded files? Hit
                <b-link @click="getPortfolioUploads">Refresh</b-link>
                to reload them.

                Or just drop files below to start uploading new ones.
            </p>
            <div v-else>
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"
                />
                <p>Fetching uploaded files...</p>
            </div>
        </div>

        <div class="mb-3">
            <vue-dropzone ref="portfolioDropzone"
                          id="portfolio-uploads-dropzone"
                          :destroyDropzone="false"
                          :options="dropzoneOptions"
                          @vdropzone-success="uploadSuccess"
                          @vdropzone-removed-file="destroy"></vue-dropzone>
        </div>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import {HollowDotsSpinner} from 'epic-spinners'

    export default {
        name: "portfolio-uploads",
        props: [
            'endpoint',
            'portfolio'
        ],
        components: {
            HollowDotsSpinner,
            vueDropzone: vue2Dropzone
        },
        data: function () {
            return {
                uploads: [],
                refreshButton: true,
                dropzoneOptions: {
                    url: this.endpoint,
                    createImageThumbnails: true,
                    thumbnailWidth: 150, // px
                    thumbnailHeight: 150,
                    addRemoveLinks: true,
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
                    var oldUploads = this.uploads

                    var newUploads = response.data.data

                    var removeUploadIfExists = function (newUpload) {
                        var exists = oldUploads.filter(function (oldUpload) {
                            return newUpload.id == oldUpload.id
                        })

                        if (exists.length == 1) {
                            _.unset(newUploads, newUploads.indexOf(newUpload))
                        }
                    }

                    var pushToUploads = function(upload) {
                        oldUploads.push(upload)
                    }

                    // filter new uploads
                    newUploads = _.each(newUploads, removeUploadIfExists)

                    // push new uploads to uploads array
                    newUploads.forEach(pushToUploads)
                    this.uploads = oldUploads

                    // add new uploads to dropzone
                    this.addUploads(newUploads)

                }).catch((error) => {
                    // log error to a file or webhook
                    console.log(error)
                }).finally(() => {
                    this.refreshButton = true
                })
            },
            uploadSuccess(upload, response) {
                if (upload.status === 'success') {
                    // remove file
                    this.$refs.portfolioDropzone.removeFile(upload)

                    // add to portfolio uploads
                    this.portfolio.uploads.push(response.data)

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
                    // remove upload from portfolio
                    this.removeFromPortfolio(upload)
                }).catch((error) => {
                    console.log(upload)

                    // add back file
                    this.addManually(upload)

                    // log to file or webhook
                    console.log(error)
                })
            },
            removeFromPortfolio(upload) {
                this.portfolio.uploads = this.portfolio.uploads.filter(function(oldUpload) {
                    return oldUpload.id != upload.id
                })

                this.uploads = this.portfolio.uploads
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