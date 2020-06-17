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
                <select v-model="currentIndex">
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
            }
        },
        mounted() {
            dragscroll.reset();
            this.calculateScale();
        },
        methods: {
            setCurrentVariantIndex(index) {
                this.currentVariantIndex = index;
            },
            calculateScale() {
                if (this.scale === 'fit') {
                    this.internalScale = Math.min(window.innerWidth / this.currentImage.width, (window.innerHeight - 50 - 50) / this.currentImage.height) * 100;
                } else {
                    this.internalScale = this.scale;
                }
            }
        },
        computed: {
            currentImage: function () {
                return this.data[this.currentIndex];
            },
            currentVariant: function () {
                return this.data[this.currentIndex].variants[this.currentVariantIndex]
            },
            internalPixelate: function () {
                return this.pixelate && this.currentVariant.pixelate !== false;
            }
        },
        watch: {
            currentImage: function (val) {
                this.currentVariantIndex = 0;
                this.calculateScale();
            },
            scale: function (val) {
                this.calculateScale();
            },
        },
    }
</script>