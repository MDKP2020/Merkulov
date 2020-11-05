<template>
    <div class="container non-fixed-width">
        <div v-if="files.length < filesAmount" class="control large-4 medium-4 small-4 filezone">
            <input :accept="acceptMimes" type="file" id="files" ref="files" multiple v-on:change="handleFiles()"/>
            <p>
                Перетащите файл сюда <br>или нажмите для выбора
            </p>
        </div>

        <div v-for="(file, key) in files" class="file-listing">
            <img v-on="getImagePreviews()" class="preview" v-bind:ref="'preview'+parseInt(key)"/>
            {{ (file.size || file.path) ? basename(file.name) : "Файл не выбран"}}
            <div class="success-container" v-if="file.id > 0 && file.size">
                Успешно
                <input type="hidden" :value="file.id"/>
            </div>
            <div class="remove-container" v-else>
                <a class="remove" v-on:click="removeTemporaryFile(key)">Удалить</a>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            'files',
            'filesAmount',
            'acceptMimes'
        ],

        methods: {
            created() {
                this.getImagePreviews();
            },

            removeTemporaryFile(key) {
                this.files.splice(key, 1);
                this.getImagePreviews();
            },

            removeFileFromStorage(key, id) {
                this.files.splice(key, 1);

                axios.delete('/api/files/' + id).then(function (data) {
                    console.log('success');
                }.bind(this)).catch(function (data) {
                    console.log('error');
                });
            },

            handleFiles() {
                let uploadedFiles = this.$refs.files.files;

                if (uploadedFiles.length > this.filesAmount) {
                    alert('Допустима загрузка не более' + this.filesAmount + 'файлов');
                    return;
                }

                for (var i = 0; i < uploadedFiles.length; i++) {

                    if (uploadedFiles[i].size > 10240 * 10240) {
                        alert('Файл слишком большой (> 10MB)');
                        return;
                    }

                    this.files.push(uploadedFiles[i]);
                }
                this.getImagePreviews();
            },

            getImagePreviews() {
                const regex = /\.(jpe?g|png|gif)$/i;
                for (let i = 0; i < this.files.length; i++) {
                    //Если файл не из хранилища attachments
                    if (!this.files[i].attachable_id) {
                        //Если файл - картинка
                        if (regex.test(this.files[i].name)) {
                            let reader = new FileReader();
                            reader.addEventListener("load", function () {
                                this.$refs['preview' + parseInt(i)][0].src = reader.result;
                            }.bind(this), false);
                            reader.readAsDataURL(this.files[i]);
                        } else {
                            this.$nextTick(function () {
                                this.$refs['preview' + parseInt(i)][0].src = '/build/img/generic.png';
                            });
                        }

                    } else {
                        //Если файл - картинка
                        if (regex.test(this.files[i].name)) {
                            this.$nextTick(function () {
                                this.$refs['preview' + parseInt(i)][0].src = '/storage/' + this.files[i].path;
                            })
                        } else {
                            this.$nextTick(function () {
                                this.$refs['preview' + parseInt(i)][0].src = '/build/img/generic.png';
                            });
                        }
                    }
                }
            },

            submitFiles() {
                for (let i = 0; i < this.files.length; i++) {
                    if (this.files[i].id) {
                        continue;
                    }
                    let formData = new FormData();
                    formData.append('file', this.files[i]);
                    axios.post('api/files/uploads/' + this.files[i].name,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(function (data) {
                        this.files[i].path = data['data'];
                        //this.files[i].id = data['data']['id'];
                        this.files.splice(i, 1, this.files[i]);
                        console.log('success');
                    }.bind(this)).catch(function (data) {
                        console.log('error');
                    });
                }
            },

            basename(path) {
                return path.replace(/.*\//, '');
            }
        }
    }
</script>

<style scoped>
    input[type="file"] {
        opacity: 0;
        width: 100%;
        height: 100%;
        position: absolute;
        cursor: pointer;
    }

    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        min-height: 200px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .filezone:hover {
        background: #c0c0c0;
    }

    .filezone p {
        font-size: 1.2em;
    }

    div.file-listing img {
        max-width: 90%;
    }

    div.file-listing {
        margin: auto;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    div.file-listing img {
        height: 100px;
    }

    div.success-container {
        text-align: center;
        color: green;
    }

    div.remove-container {
        margin-top: 5px;
    }

    div.remove-container a {
        color: red;
        cursor: pointer;
    }

    a.submit-button {
        display: block;
        margin: auto;
        text-align: center;
        width: 200px;
        padding: 10px;
        text-transform: uppercase;
        background-color: #CCC;
        color: white;
        font-weight: bold;
        margin-top: 20px;
    }

    .non-fixed-width {
        width: auto;
    }
</style>
