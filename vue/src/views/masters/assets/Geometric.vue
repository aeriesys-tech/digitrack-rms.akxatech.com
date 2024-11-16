<template>
    <div class="card card-one" v-if="assets">
        <div class="card-header text-center">
            <h6 class="card-title">{{ assets.asset_name }}</h6>

        </div>
        <div class="card-body">
            <h6 class="mb-3"><span class="text-primary">Note:</span> 1 meter={{meter}}px</h6>
            <div class="col-md-3 mb-4">
                <input class="form-control" type="number" v-model="meter">
            </div>
            <div class="row">
                <div class="col-md-6 align-items-center justify-content-center">
                    <h6 class="text-center" :style="`width:${Number(assets.diameter*meter)}px;margin-left:35px;`">Dia={{Number(assets.diameter)}} (m)</h6>
                    <div class="dimensions-x mb-3" :style="`width:${Number(assets.diameter*meter)}px; margin-left:35px;` "><span class="arrow-left"></span><span class="arrow-right"></span></div>
                    <div class="row">
                        <div class="col-1 d-flex align-items-center justify-content-center">
                            <div class="dimensions-y" :style="`height:${Number(assets.height*meter)}px;`"><span class="arrow-top"></span><span class="arrow-bottom"></span></div>
                            <h6 class="vertical-text mr-0">Heigth={{Number(assets.height)}} (m)</h6>
                        </div>
                        <div class="col-11">
                            <div :title="assets.asset_name" class="" :style="`height:${Number(assets.height*meter)}px; width:${Number(assets.diameter*meter)}px; border:2px solid gray`" @click="showAssetZones()"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" v-if="display_asset_zone">
                    <h6 class="text-center" :style="`width:${Number(assets.diameter*meter)}px;`">Dia={{Number(assets.diameter)}} (m)</h6>
                    <div class="dimensions-x mb-3" :style="`width:${Number(assets.diameter*meter)}px; ` "><span class="arrow-left"></span><span class="arrow-right"></span></div>
                    <div v-for="zone,key in assets.zone_name" :key="key" class="zone-container" :style="`height:${Number(zone.height*meter)}px; width:${Number(assets.diameter*meter)}px; border:1px solid gray`">
                        <div class="zone-name-container" style="text-align: center; align-items: center;">
                            <span style="writing-mode: vertical-lr;">{{ Number(zone.height) }} (m)</span>
                            <span class="vertical-text1">&nbsp;&nbsp;{{ zone.zone_name }}</span>
                        </div>
                        <div
                            v-for="(spare, key1) in zone.asset_spares"
                            :key="key1"
                            :style="{
                                height: `${Number(((spare.quantity / totalQuantity(zone.asset_spares)) * Number(zone.height)) * meter)}px`, // Calculate height proportionally
                                width: `${assets.diameter * meter}px`,
                                backgroundColor: spareColorCode(spare),
                                cursor: 'pointer',
                                textAlign: 'center',
                                alignContent: 'center',
                                borderBottom: key1 % 2 === 0 ? '1px solid gray' : '',
                                borderTop: key1 % 2 !== 0 ? '1px solid gray' : '',
                                color: 'white'
                                }"
                            :title="zone.zone_name"
                        >
                            <div class="zone-name-container2" style="color: #41505f;">
                                <span class="text-nowrap">
                                {{ (Number((spare.quantity / totalQuantity(zone.asset_spares))) * 100).toFixed(2) }} %</span>
                            </div>

                            <span style="color: black;">{{ spare?.spare?.spare_name }} ({{ spare.quantity }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Geometric",
        data() {
            return {
                asset: {
                    asset_id: "",
                },
                assets: "",
                meter: null,
                display_asset_zone: false,
            };
        },
        mounted() {
            this.meter = this.$store.state.meter;
        },

        beforeRouteEnter(to, from, next) {
            next((vm) => {
                let uri = { uri: "getAsset", data: { asset_id: to.params.asset_id } };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        vm.assets = response.data.asset;
                        console.log("assets:----", vm.assets);
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            });
        },
        methods: {
            showAssetZones() {
                let vm = this;
                vm.display_asset_zone = !vm.display_asset_zone;
            },
            totalQuantity(spare) {
                return spare.reduce((sum, item) => sum + item.quantity, 0);
            },
            spareColorCode(spare) {
                console.log("spare:----", spare.asset_spare_value);
                for (let i = 0; i < spare.asset_spare_value.length; i++) {
                    if (spare.asset_spare_value[i].field_value.includes("#")) {
                        return spare.asset_spare_value[i].field_value;
                    }
                }
            },
        },
    };
</script>
<style scoped>
    .rotate {
        display: inline-block; /* Necessary for transform to work */
        transform: rotate(-90deg) translateX(-100%);
        /* Adjust positioning */
        position: relative;
        left: 100%; /* Move it into view */
        top: 50%; /* Center vertically */
    }

    .dimensions-x {
        height: 1px;
        background: #000;
    }
    .arrow-left {
        width: 0;
        height: 0;
        border-top: 3px solid transparent;
        border-bottom: 3px solid transparent;
        float: left;
        border-right: 3px solid #000;
        margin-top: -2px;
        margin-left: -3px;
    }
    .arrow-right {
        width: 0;
        height: 0;
        border-top: 3px solid transparent;
        border-bottom: 3px solid transparent;
        border-left: 3px solid #000;
        float: right;
        margin-top: -2px;
        margin-right: -3px;
    }
    .dimensions-y {
        width: 1px;
        background: #000;
        margin-left: 5px;
        display: inline-block;
        /* position: relative; */
        position: absolute;
    }
    .arrow-top {
        width: 0;
        height: 0;
        border-left: 3px solid transparent;
        border-right: 3px solid transparent;
        border-bottom: 3px solid #000;
        margin-left: -2px;
        position: absolute;
    }
    .arrow-bottom {
        width: 0;
        height: 0;
        border-left: 3px solid transparent;
        border-right: 3px solid transparent;
        border-top: 3px solid #000;
        margin-left: -2px;
        position: absolute;
        bottom: 0px;
    }
    .vertical-text {
        writing-mode: vertical-lr;
        text-orientation: mixed;
    }
    .mr-0 {
        margin-right: 0px;
    }
    .zone-container {
        position: relative;
    }

    .zone-name-container {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 105%;
        /* transform: translateX(-50%); */
        z-index: 1;
        display: flex;
        /* align-items: center;
  justify-content: center; */
        border-right: 1px solid gray;
        /* padding: 10px;
  padding-right: 0px; */
    }

    /* Arrow at the top */
    .zone-name-container::before {
        content: "";
        position: absolute;
        right: -6px; /* Slightly outside the border */
        top: 0; /* Positioned at the top of the container */
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-bottom: 6px solid gray; /* Downward-facing triangle */
    }

    /* Arrow at the bottom */
    .zone-name-container::after {
        content: "";
        position: absolute;
        right: -6px; /* Slightly outside the border */
        bottom: 0; /* Positioned at the bottom of the container */
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 6px solid gray; /* Upward-facing triangle */
    }

    .vertical-text1 {
        /* transform: rotate(90deg); */
        white-space: nowrap;
        /* font-weight: bold; */
        color: gray;
        text-align: center;
        position: absolute;
        left: inherit;
    }

    .zone-name-container2 {
        position: absolute;
        /* top: 0;
        bottom: 0; */
        right: 100%;
        transform: translateX(-50%);
        z-index: 1;
        /* display: flex; */
    }
    .vertical-text2 {
        /* transform: rotate(90deg); */
        white-space: nowrap;
        color: orange;
        text-align: center;
    }
</style>
