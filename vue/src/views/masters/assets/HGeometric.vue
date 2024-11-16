<template>
    <div class="card card-one" v-if="assets">
        <div class="card-header text-center">
            <h6 class="card-title">{{ assets.asset_name }}</h6>
        </div>
        <div class="card-body">
            <h6 class="mb-3"><span class="text-primary">Note:</span> 1 meter = {{ h_meter }}px</h6>
            <div class="col-md-3 mb-4">
                <input class="form-control" type="number" v-model="h_meter" />
            </div>
            <div class="row">
                <div class="col-md-12 mb-4 d-flex justify-content-center align-items-center"> <!-- Center align the row -->
                    <div class="d-flex align-items-center" style="margin-bottom: auto;">
                        <h6 class="text-center ms-2">{{ Number(assets.diameter) }} (m)</h6>
                        <div class="dimensions-y" :style="`height:${Math.min(Number(assets.diameter * h_meter), 400)}px;`">
                            <span class="arrow-top"></span>
                            <span class="arrow-bottom"></span>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-center ms-4" style="margin-bottom: auto;">
                        <div :title="assets.asset_name" :style="`height:${Number(assets.diameter * h_meter)}px; width:${Number(assets.height * h_meter)}px; border:2px solid gray`" @click="showAssetZones()"></div>

                        <div class="dimensions-x mb-3" :style="`width: ${Number(assets.height * h_meter)}px; margin-top: 10px;`">
                            <span class="arrow-left"></span>
                            <span class="arrow-right"></span>
                        </div>
                        <h6 class="text-center mt-1">{{ Number(assets.height) }} (m)</h6>
                    </div>
                </div>

                <br />
                <hr style="border: none; height: 1px; background-color: white;" />
                <hr style="border: none; height: 1px; background-color: white;" />
                <br />

                <div class="col-md-12 d-flex flex-wrap1 justify-content-center" v-if="display_asset_zone">
                    <h6 class="text-center mt-5">{{ Number(assets.diameter) }} (m)</h6>

                    <div class="dimensions-y mt-1" :style="`height:${Number(assets.diameter * h_meter)}px;`">
                        <span class="arrow-top"></span>
                        <span class="arrow-bottom"></span>
                    </div>

                    &nbsp;&nbsp;&nbsp;&nbsp;

                    <div
                        v-for="(zone, key) in assets.zone_name"
                        :key="key"
                        class="zone-container d-flex flex-row"
                        :style="`width:${Number(zone.height * h_meter)}px; height:${Number(assets.diameter * h_meter)}px; border:1px solid gray; position: relative;`"
                    >
                        <div class="zone-name-container" :style="`width:${Number(zone.height * h_meter)}px;`">
                            <span class="zone-text" style="font-weight: bold;">{{ zone.zone_name }}</span>
                            <span> &nbsp;({{ Number(zone.height) }} m )</span>

                            <div class="dimensions-x mb-1" :style="`width: ${Number(zone.height * h_meter)}px; margin-top: 10px;`">
                                <span class="arrow-left"></span>
                                <span class="arrow-right"></span>
                            </div>
                        </div>
                        <div
                            v-for="(spare, key1) in zone.asset_spares"
                            :key="key1"
                            :style="{
                                    width: `${Number((spare.quantity / totalQuantity(zone.asset_spares)) * zone.height * h_meter)}px`, 
                                    height: `${Number(assets.diameter * h_meter)}px`, 
                                    backgroundColor: spareColorCode(spare), 
                                    textAlign: 'center', 
                                    color: 'white',
                                    borderTop: key1 % 2 === 0 ? '1px solid gray' : '',
                                    borderBottom: key1 % 2 !== 0 ? '1px solid gray' : '',
                                    position: 'relative'
                                }"
                            :title="zone.zone_name"
                        >
                            <span style="color: black;">{{ spare?.spare?.spare_name }} ({{ spare.quantity }})</span>
                            <div class="spare-percentage">
                                {{ (Number((spare.quantity / totalQuantity(zone.asset_spares))) * 100).toFixed(2) }} %
                            </div>
                        </div>
                    </div>
                </div>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <div v-if="display_asset_zone" class="dimensions-x mb-3" :style="`width: ${Number(assets.height * h_meter)}px; margin-top: 10px;`">
                    <span class="arrow-left"></span>
                    <span class="arrow-right"></span>
                </div>
                <h6 v-if="display_asset_zone" class="text-center mt-2">{{ Number(assets.height) }} (m)</h6>
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
                h_meter: null,
                display_asset_zone: false,
            };
        },
        mounted() {
            this.h_meter = this.$store.state.h_meter;
        },

        beforeRouteEnter(to, from, next) {
            next((vm) => {
                let uri = { uri: "getAsset", data: { asset_id: to.params.asset_id } };
                vm.$store
                    .dispatch("post", uri)
                    .then(function (response) {
                        vm.assets = response.data.asset;
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.$store.dispatch("error", error.response.data.message);
                    });
            });
        },
        methods: {
            showAssetZones() {
                this.display_asset_zone = !this.display_asset_zone;
            },
            totalQuantity(spare) {
                return spare.reduce((sum, item) => sum + item.quantity, 0);
            },
            spareColorCode(spare) {
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
    .zone-container {   
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .zone-name-container {
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.7);
        padding: 80px 60xpx;
        z-index: 1;
        margin-bottom: 10px;
        text-align: center;
    }

    .spare-percentage {
        font-size: 14px;
        color: #333;
        font-weight: bold;
        position: absolute;
        top: 80%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .zone-text {
        text-align: center;
        color: #292525;
        margin-top: 5px;
    }

    .dimensions-x {
        position: relative;
        height: 1px;
        background-color: black;
        margin-left: auto;
        margin-right: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .arrow-left,
    .arrow-right {
        width: 0;
        height: 0;
        border-style: solid;
        position: absolute;
    }

    .arrow-left {
        border-width: 6px 8px 6px 0;
        border-color: transparent black transparent transparent;
        left: -10px;
    }

    .arrow-right {
        border-width: 6px 0 6px 8px;
        border-color: transparent transparent transparent black;
        right: -10px;
    }

    .dimensions-y {
        position: relative;
        width: 1px;
        background-color: black;
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .arrow-top,
    .arrow-bottom {
        width: 0;
        height: 0;
        border-style: solid;
        position: absolute;
    }

    .arrow-top {
        border-width: 0 4px 6px 4px; 
        border-color: transparent transparent black transparent;
        top: -6px; 
        margin-left: -3px;
    }

    .arrow-bottom {
        border-width: 6px 4px 0 4px; 
        border-color: black transparent transparent transparent;
        bottom: -6px; 
        margin-left: -3px;
    }

    .arrow-left {
        border-width: 4px 6px 4px 0; 
        border-color: transparent black transparent transparent;
        left: 0px; 
    }

    .arrow-right {
        border-width: 4px 0 4px 6px; 
        border-color: transparent transparent transparent black;
        right: 0px;
    }
</style>
