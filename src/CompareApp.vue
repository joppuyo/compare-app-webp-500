<template>
    <div class="app">
    <div class="top-bar">
        <button v-for="(variant, index) in this.currentImage.variants"
                v-on:click="setCurrentVariantIndex(index)"
                class="button"
                v-bind:class="{'button-active': index === currentVariantIndex}"
                v-bind:data-text="variant.variant_name"
        >
            {{variant.variant_name}}
        </button>
    </div>
    <div class="middle dragscroll">
        <img v-bind:src="this.currentVariant.path"
             v-bind:class="{pixelate: internalPixelate}"
             v-on:load="imageLoaded = true"
             v-bind:style="{
            height: `${currentImage.height * this.internalScale / 100}px`,
            minHeight: `${currentImage.height * this.internalScale / 100}px`,
            width: `${currentImage.width * this.internalScale / 100}px`,
            minWidth: `${currentImage.width * this.internalScale / 100}px`,
        }">
    </div>
        <div class="bottom-bar">
            <div class="bottom-bar-left">
            <div class="select-container">
                <select v-model="currentIndex" v-on:change="imageChanged">
                    <option v-for="(image, index) in data" v-bind:value="index">{{image.image_name}}</option>
                </select>
                <div class="select-container-icon" v-html="require('!raw-loader!./arrow-down.svg')"></div>
            </div>
            <div class="select-container">
                <select v-model="scale">
                    <option v-bind:value="'fit'">Fit</option>
                    <option v-for="option in zoomOptions" v-bind:value="option">{{option}}%</option>
                </select>
                <div class="select-container-icon" v-html="require('!raw-loader!./arrow-down.svg')"></div>
            </div>
            </div>
            <div class="bottom-bar-right">
            <div class="labels" v-for="label in currentVariant.labels" v-if="currentVariant.labels">
                <div class="label">
                    <div class="label-key">{{label.key}}</div>
                    <div class="label-value">{{label.value}}</div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>
<script>

    import dragscroll from 'dragscroll';
    import data from './data.json';
    import Loader from 'loaderz';

    export default {
        data: function () {
            return {
                currentIndex: 0,
                currentVariantIndex: 0,
                data: data.images,
                scale: data.default_scale,
                internalScale: data.default_scale,
                zoomOptions: [25, 50, 100, 200, 300, 400],
                pixelate: true,
                imageLoaded: false,
            }
        },
        mounted() {
            dragscroll.reset();
            this.calculateScale();
        },
        methods: {
            imageChanged() {
                this.imageLoaded = false;
                this.currentVariantIndex = 0;
                this.calculateScale();
                this.scale = 'fit';
            },
            setCurrentVariantIndex(index) {
                this.currentVariantIndex = index;
            },
            calculateScale() {
                if (this.scale === 'fit') {
                    this.internalScale = Math.min(window.innerWidth / this.currentImage.width, (window.innerHeight - 50 - 50) / this.currentImage.height) * 100;
                } else {
                    this.internalScale = this.scale;
                }
            },
            preloadImages() {
                let loader = new Loader();
                for (let [index, image] of this.currentImage.variants.entries()) {
                    if (index !== 0) {
                        loader.queue('image', image.path);
                    }
                }
                loader.start();
            },
        },
        computed: {
            currentImage: function () {
                return this.data[this.currentIndex];
            },
            currentVariant: function () {
                return this.data[this.currentIndex].variants[this.currentVariantIndex]
            },
            internalPixelate: function () {
                return this.pixelate && this.currentVariant.pixelate !== false && this.internalScale >= 100;
            }
        },
        watch: {
            scale: function (val) {
                this.calculateScale();
            },
            imageLoaded: function (val) {
                if (val === true) {
                    this.preloadImages();
                }
            }
        },
    }
</script>