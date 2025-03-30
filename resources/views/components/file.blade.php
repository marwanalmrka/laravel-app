@props([
    'id' => uniqid('file-input-'),
    'icon' => null,
    'label' => null,
    'info' => null,
    'class' => null,
    'atts' => [],
    'multiple' => false,
    'container_class' => null,
    'container_atts' => [],
    'model' => null,
    'accept' => 'image/*,video/*,.pdf,.doc,.docx,.zip,.7zip,.tar,.gz',
    'maxSize' => 5,
    'maxFiles' => 20,
    'previews' => null,
])
@php
    $model = $model ?? ($attributes->get('wire:model.live') ?? $attributes->get('wire:model'));
    $multiple = $multiple || $attributes->has('multiple');
    if ($multiple) {
        $atts['multiple'] = '';
    }
    $previewsArray = is_previews($previews) ? $previews->toArray() : [];
    $tmpUrl = livewire_tmp_url();
    $error = $errors->has($model) || $errors->has("$model.*");
@endphp
<fgx:label :for="$model" :icon="$icon" :label="$label" />
<div x-data="formDropZone({
    model: '{{ $model }}',
    accept: '{{ $accept }}',
    maxSize: {{ $maxSize }},
    maxFiles: {{ $maxFiles }},
    tmpUrl: '{{ $tmpUrl }}',
    multiple: @js($multiple),
    previews: @js($previewsArray),
})" id="form-drop-zone-{{ $model }}" class="form-drop-zone"
    :class="{ 'multiple': @js($multiple), 'error': @js($error) }" x-cloak>
    <input
        {{ $attributes->merge(
            array_merge(
                [
                    'type' => 'file',
                    'id' => $id,
                    'accept' => $accept,
                    'x-ref' => 'fileInput',
                    'x-on:change' => 'handleFileSelect',
                    'class' => css_classes(['hidden', $class => $class]),
                ],
                $atts,
            ),
        ) }}>
    <label for="{{ $id }}" class="previews-placeholder z-1" x-bind="dragZone">
        <div class="flex flex-col items-center justify-center p-4">
            @icon('bi-cloud-upload', 'w-8 h-8 mb-1 text-gray-500 dark:text-gray-400')
            <div class="text-xs text-center text-gray-600 dark:text-gray-400">
                {{ __('Click or darg here to upload') }}
            </div>
            <div class="mt-1 text-xxs text-center text-gray-600 dark:text-gray-400">
                {{ __('Max size: :max MB • Allowed: :accept', ['max' => $maxSize, 'accept' => $accept]) }}
            </div>
        </div>
    </label>
    <!-- Previews -->
    <div wire:ignore class="previews-grid" x-ref="grid">
        <template x-for="(file, index) in previews" :key="file.id">
            <div class="previews-item">
                <template x-if="file && file.type && file.type === 'image'">
                    <img :src="file.url">
                </template>
                <template x-if="file && file.type && file.type !== 'image'">
                    <div class="flex items-center justify-center w-full h-full">
                        <div class="text-center">
                            <i class="icon" :class="file.icon"></i>
                            <div class="text-xs mt-2">
                                <div x-text="file.name" class="font-semibold"></div>
                                <div x-text="file.mime_type" class="mt-1"></div>
                                <div x-text="file.humanReadableSize" class="mt-1"></div>
                            </div>
                        </div>
                    </div>
                </template>
                <button type="button" class="previews-item-delete" x-on:click="deletePreview(file)">
                    <i class="icon bi-trash-fill"></i>
                </button>
            </div>
        </template>
        <template x-for="(file, index) in queue" :key="file.id">
            <div class="previews-item">
                <template x-if="file.url">
                    <img :src="file.url">
                </template>
                <template x-if="!file.url">
                    <div class="flex items-center justify-center w-full h-full">
                        <div class="text-center">
                            <i class="icon bi-file"></i>
                            <div class="text-xs mt-2">
                                <div x-text="file.name" class="font-semibold"></div>
                                <div x-text="fileStatus(file)" class="mt-1"></div>
                                <div x-text="formatSize(file.size)" class="mt-1"></div>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Progress Bar -->
                <div class="progress absolute w-3/4 top-1/2 -translate-y-1/5 left-1/2 -translate-x-1/2"
                    x-show="file.progress > 0">
                    <div class="progress-bar" :style="'width: ' + file.progress + '%'" x-text="file.progress+'%'"></div>
                </div>
                <div class="previews-item-toolbar">
                    <template x-if="file.status === 'pending'">
                        <button type="button" class="toolbar-button upload" x-on:click="startUpload(file)">
                            <i class="icon bi-cloud-upload-fill"></i>
                        </button>
                    </template>

                    <template x-if="file.status === 'uploading'">
                        <button type="button" class="toolbar-button pause" x-on:click="pauseUpload(file)">
                            <i class="icon bi-pause-fill"></i>
                        </button>
                    </template>

                    <template x-if="file.status === 'paused'">
                        <button type="button" class="toolbar-button resume" x-on:click="resumeUpload(file)">
                            <i class="icon play-fill"></i>
                        </button>
                    </template>
                </div>
                <button type="button" class="previews-item-delete" x-on:click="cancelUpload(file)">
                    <i class="icon bi-trash-fill"></i>
                </button>
            </div>
        </template>
        <div x-show="hasItems()" class="previews-appender" x-on:click="$refs.fileInput.click()">
            <div class="text-center">
                <i class="icon bi-cloud-upload w-8 h-8 text-gray-500 dark:text-gray-400"></i>
                <div class="mt-1 text-xs text-center text-gray-500 dark:text-gray-400">
                    {{ __('Click or drop to upload') }}
                </div>
            </div>
        </div>
    </div>
    <button x-bind="editButton" x-ref="editButton" type="button"
        class="previews-edit z-30 flex items-center justify-center text-white bg-primary/70 hover:bg-primary text-xs w-8 h-8 rounded-full absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2">
        <i class="icon bi-pencil-square"></i>
    </button>

</div>
@dump($previewsArray)
<fgx:error :id="$model" />
@script
    <script>
        Alpine.data('formDropZone', (config) => ({
            model: config.model,
            accept: config.accept,
            maxSize: config.maxSize,
            maxFiles: config.maxFiles,
            multiple: config.multiple,
            queue: [],
            currentUpload: null,
            dragover: false,
            container: null,
            listeners: [],
            previews: config.previews,
            content: '',
            hasItems() {
                return this.previews.length > 0 || this.queue.length > 0;
            },
            dragZone: {
                ['@dragover.prevent']() {
                    this.dragover = true;
                },
                ['@dragleave.prevent']() {
                    this.dragover = false;
                },
                /*['@click']() {
                    this.$refs.fileInput.click();
                },*/
                ['x-show']() {
                    return !this.hasItems();
                }
            },
            /*appender: {
                ['@click']() {
                    this.$refs.fileInput.click();
                },
                ['x-show']() {
                    return this.multiple && this.hasItems();
                }
            },*/
            editButton: {
                ['@click']() {
                    this.$refs.fileInput.click();
                },
                ['x-show']() {
                    return !this.multiple && this.hasItems();
                }
            },
            handleFileSelect(e) {
                this.addFiles(Array.from(e.target.files));
                e.target.value = ''; // Reset input
            },
            handleDrop(e) {
                this.dragover = false;
                this.addFiles(Array.from(e.dataTransfer.files));
            },
            mimeToType(mimeType) {
                if (mimeType.startsWith("image/")) {
                    return "image";
                } else if (mimeType.startsWith("video/")) {
                    return "video";
                } else if (mimeType.startsWith("audio/")) {
                    return "audio";
                } else if (mimeType.startsWith("application/")) {
                    return "document";
                } else {
                    return 'file';
                }
            },
            addFiles(files) {
                let i = 0;
                const remainingSlots = config.maxFiles - this.queue.length;
                const filesToAdd = files.slice(0, remainingSlots);

                filesToAdd.forEach(file => {
                    if (!this.validateFile(file)) return;

                    this.queue.push({
                        index: i,
                        id: Math.random().toString(36).substr(2, 9),
                        file,
                        name: file.name,
                        type: this.mimeToType(file.type),
                        mime_type: file.type,
                        size: file.size,
                        humanReadableSize: this.formatSize(file.size),
                        url: file.type.startsWith('image/') ? URL
                            .createObjectURL(file) : null,
                        progress: 0,
                        status: 'pending',
                        error: null
                    });
                    i++;
                });

                this.processNext();
            },

            validateFile(file) {
                return true;
                if (file.size > config.maxSize * 1024 * 1024) {
                    alert(`File ${file.name} exceeds maximum size of ${config.maxSize}MB`);
                    return false;
                }

                const acceptedTypes = config.accept.split(',');
                if (!acceptedTypes.some(type => {
                        if (type.startsWith('.')) {
                            return file.name.toLowerCase().endsWith(type);
                        }
                        return file.type.match(type.replace('/*', '/.*'));
                    })) {
                    alert(`File ${file.name} is not an allowed type`);
                    return false;
                }

                return true;
            },
            fileToPreview(file, name) {
                return {
                    id: Math.random().toString(36).substr(2, 9),
                    name: name,
                    path: null,
                    url: config.tmpUrl + name,
                    type: this.mimeToType(file.type),
                    mime_type: file.type,
                    extension: file.type,
                    size: file.size,
                    icon: 'bi-file',
                    model_type: 'TemporaryUploadedFile',
                    humanReadableSize: this.formatSize(file.size)
                };
            },
            addUploadedToPreviews(file, name) {
                this.previews.push(this.fileToPreview(file, name));
            },
            async startUpload(file) {
                if (this.currentUpload) return;

                file.status = 'uploading';
                this.currentUpload = file;
                const t = this;
                try {
                    await $wire.upload(
                        this.multiple ? `${config.model}.${file.index}` : config.model,
                        file.file,
                        (uploadedFilename) => {
                            // Upload success
                            file.status = 'completed';
                            this.removeFromQueue(file);
                            this.currentUpload = null;
                            this.addUploadedToPreviews(file.file, uploadedFilename);
                            this.processNext();
                        },
                        (error) => {
                            // Upload error
                            file.status = 'error';
                            file.error = error;
                            this.currentUpload = null;
                        },
                        (event) => {
                            // Progress update
                            file.progress = event.detail.progress;
                        },
                        () => {
                            // Cancel callback
                            file.status = 'cancelled';
                            this.currentUpload = null;
                        }
                    );
                } catch (error) {
                    file.status = 'error';
                    file.error = error.message;
                    this.currentUpload = null;
                }
            },

            pauseUpload(file) {
                if (file.status === 'uploading') {
                    file.status = 'paused';
                    $wire.cancelUpload(config.model);
                    this.currentUpload = null;
                }
            },

            resumeUpload(file) {
                if (file.status === 'paused') {
                    this.startUpload(file);
                }
            },

            cancelUpload(file) {
                if (file.status === 'uploading') {
                    $wire.cancelUpload(config.model);
                }
                this.removeFromQueue(file);
                this.initHasPreviews();
            },

            removeFromQueue(file) {
                this.queue = this.queue.filter(f => f.id !== file.id);
                if (file.preview) URL.revokeObjectURL(file.preview);
            },

            processNext() {
                const nextFile = this.queue.find(f => f.status === 'pending');
                if (nextFile) this.startUpload(nextFile);
            },

            formatSize(bytes) {
                if (bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            fileStatus(file) {
                if (file.error) return `Error: ${file.error}`;
                if (file.status === 'uploading') return `Uploading... ${file.progress}%`;
                return file.status.charAt(0).toUpperCase() + file.status.slice(1);
            },
            deletePreview(item) {
                switch (item.model_type) {
                    case 'Media':
                        $wire.$dispatch('delete-media', {
                            property: config.model,
                            id: item.id
                        });
                        break;
                    case 'TemporaryUploadedFile':
                        const fileName = item.name;
                        $wire.removeUpload(config.model, fileName, () => {
                            //this.previews = this.previews.filter(file => file.name !== fileName);
                        });
                        break;
                }
            },
            appender() {
                let appender = document.createElement('label');
                appender.setAttribute('for', this.$refs.fileInput.id);
                appender.className = 'previews-appender cursor-pointer';
                appender.innerHTML = `
                    <div class="text-center">
                        <i class="icon bi-cloud-upload w-8 h-8 text-gray-500 dark:text-gray-400"></i>
                        <div class="mt-1 text-xs text-center text-gray-500 dark:text-gray-400">
                            {{ __('Click or drop to upload') }}
                        </div>
                    </div>`;
                return appender;
            },
            previewItem(preview) {
                let item = document.createElement('div');
                item.id = `previews-item-${preview.id}`;
                item.className = 'previews-item border-green';
                if (!this.multiple) {
                    item.classList.add('absolute', 'inset-0', 'z-4');
                }
                if (preview.type == 'image') {
                    let img = document.createElement('img');
                    img.src = preview.url;
                    item.appendChild(img);
                } else {
                    let details = document.createElement('div');
                    details.className = 'flex items-center justify-center w-full h-full';
                    details.innerHTML = `
                        <div class="text-center">
                            <i class="icon w-8 h-8 ${preview.icon}"></i>
                            <div class="text-xs mt-2 px-1">
                                <div class="font-semibold truncate">${preview.name}</div>
                                <div class="mt-1">${preview.mime_type}</div>
                                <div class="mt-1">${preview.humanReadableSize}</div>
                            </div>
                        </div>`;
                    item.appendChild(details);
                }
                let button = document.createElement('button');
                button.type = 'button';
                button.className = 'previews-item-delete';
                button.innerHTML = '<i class="icon bi-trash-fill"></i>';
                button.addEventListener('click', () => {
                    this.deletePreview(preview);
                });
                item.appendChild(button);
                return item;
            },
            buttonUpload(file) {
                let button = document.createElement('button');
                button.type = 'button';
                button.className = 'toolbar-button upload';
                button.innerHTML = '<i class="icon bi-cloud-upload-fill"></i>';
                button.addEventListener('click', (evt) => {
                    evt.preventDefault();
                    this.startUpload(file);
                });
                return button;
            },
            buttonPause(file) {
                let button = document.createElement('button');
                button.type = 'button';
                button.className = 'toolbar-button pause';
                button.innerHTML = '<i class="icon bi-pause-fill"></i>';
                button.addEventListener('click', (evt) => {
                    evt.preventDefault();
                    this.pauseUpload(file);
                });
                return button;
            },
            buttonResume(file) {
                let button = document.createElement('button');
                button.type = 'button';
                button.className = 'toolbar-button resume';
                button.innerHTML = '<i class="icon bi-play-fill"></i>';
                button.addEventListener('click', (evt) => {
                    evt.preventDefault();
                    this.resumeUpload(file);
                });
                return button;
            },
            buttonCancel(file) {
                let button = document.createElement('button');
                button.type = 'button';
                button.className = 'previews-item-delete';
                button.innerHTML = '<i class="icon bi-trash-fill"></i>';
                button.addEventListener('click', (evt) => {
                    evt.preventDefault();
                    this.cancelUpload(file);
                });
                return button;
            },
            fileItemProgress(file) {
                let el = document.createElement('div');
                el.className = 'progress absolute w-3/4 top-1/2 -translate-y-1/5 left-1/2 -translate-x-1/2';
                let bar = document.createElement('div');
                bar.className = 'progress-bar';
                const percent = `${file.progress}%`;
                bar.style.width = percent;
                bar.textContent = percent;
                el.appendChild(bar);
                return el;
            },
            fileItemToolbar(file) {
                let toolbar = document.createElement('div');
                toolbar.className = 'previews-item-toolbar';
                //upload button
                if (file.status === 'pending') {
                    toolbar.appendChild(this.buttonUpload(file));
                }
                //pause button
                if (file.status === 'uploading') {
                    toolbar.appendChild(this.buttonPause(file));
                }
                //resume button
                if (file.status === 'paused') {
                    toolbar.appendChild(this.buttonResume(file));
                }
                return toolbar;
            },
            fileItem(file) {
                let item = document.createElement('div');
                item.id = `previews-item-${file.id}`;
                item.className = 'previews-item border-orange';
                if (!this.multiple) {
                    item.classList.add('absolute', 'inset-0', 'z-4');
                }
                if (file.url) {
                    let img = document.createElement('img');
                    img.src = file.url;
                    item.appendChild(img);
                } else {
                    let details = document.createElement('div');
                    details.className = 'flex items-center justify-center w-full h-full';
                    details.innerHTML = `
                                <div class="text-center">
                                    <i class="icon w-8 h-8 bi-file"></i>
                                    <div class="text-xs mt-2 px-1">
                                        <div class="font-semibold truncate">${file.name}</div>
                                        <div class="mt-1">${file.type}</div>
                                        <div class="mt-1">${file.size}</div>
                                    </div>
                                </div>`;
                    item.appendChild(details);
                }
                //progress
                if (file.progress > 0) {
                    item.appendChild(this.fileItemProgress(file));
                }

                //toolbar
                item.appendChild(this.fileItemToolbar(file));

                //cancel button
                item.appendChild(this.buttonCancel(file));
                return item;
            },
            initContent() {
                let grid = this.$refs.grid;
                grid.innerHTML = '';
                //previews
                this.previews.forEach(preview => {
                    grid.appendChild(this.previewItem(preview));
                });

                //queue
                this.queue.forEach(file => {
                    //console.log(this.fileItem(file));
                    //c += this.fileItem(file);
                    grid.appendChild(this.fileItem(file));
                });
                if (this.multiple && this.hasItems()) {
                    grid.appendChild(this.appender());
                }
                //this.content = c;
            },
            init() {
                //console.log('init', this.previews);
                //this.initContent();
                /*this.$watch('queue', (value) => {
                    console.log(value);
                    this.initContent();
                });*/
                this.$watch('previews', (value) => {
                    console.log(value);
                    this.initContent();
                });
                this.listeners.push(
                    Livewire.on('media-deleted', (ids) => {
                        try {
                            this.previews = this.previews.filter(preview => !ids.includes(preview.id));
                            console.log('previews', this.previews);
                        } catch (error) {
                            console.log('error', error);
                            //console.error(error);
                        }
                    }),
                );
            },
            destroy() {
                this.listeners.forEach((listener) => {
                    listener();
                });
            }
        }));
    </script>
@endscript
