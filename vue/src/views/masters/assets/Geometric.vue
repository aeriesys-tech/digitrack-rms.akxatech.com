<template>
    <div class="card">
        <div class="card-body">
            <h4 class="text-center">{{ assets.asset_name }}</h4>
            <div class="row">
                <div class="col-md-6 align-items-center justify-content-center">
                    <h6 class="text-center" :style="`width:${Number(assets.diameter*meter)}px;`">Dia={{Number(assets.diameter*meter)}} (m)</h6>
                    <div class="dimensions-x mb-3" :style="`width:${Number(assets.diameter*meter)}px; margin-left:50px;` "><span class="arrow-left"></span><span class="arrow-right"></span></div>
                    <div class="row">
                        <div class="col-1 d-flex align-items-center justify-content-center">
                            <div class="dimensions-y" :style="`height:${Number(assets.height*meter)}px;`"><span class="arrow-top"></span><span class="arrow-bottom"></span></div>
                            <h6 class="vertical-text mr-0">Heigth={{Number(assets.height*meter)}} (m)</h6>

                        </div>
                        <div class="col-11">

                            <div :title="assets.asset_name" class=""  :style="`height:${Number(assets.height*meter)}px; width:${Number(assets.diameter*meter)}px; border:2px solid gray`" @click="showAssetZones()"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" v-if="display_asset_zone">
                      <h6 class="text-center" :style="`width:${Number(assets.diameter*meter)}px;`">Dia={{Number(assets.diameter*meter)}} (m)</h6>
                    <div class="dimensions-x mb-3" :style="`width:${Number(assets.diameter*meter)}px; ` "><span class="arrow-left"></span><span class="arrow-right"></span></div>
                       <div v-for="zone,key in assets.zone_name" :key="key" class="" :style="`height:${Number(zone.height*meter)}px; width:${Number(assets.diameter*meter)}px; border:1px solid gray`" >

                        <!-- <div class="dimensions-y" :style="`height:${Number(zone.height*meter)}px;`"><span class="arrow-top"></span><span class="arrow-bottom"></span></div>
                            <h6 class="vertical-text mr-0">Heigth={{Number(zone.height*meter)}} (m)</h6> -->

                        <div :title="zone.zone_name" v-for="spare,key1 in zone.asset_spares" :key="key1" :style="`height:${Number((zone.height*meter)/zone.asset_spares.length)}px; width:${Number(assets.diameter*meter)}px; border:1px solid gray; cursor:pointer;text-align: center;align-content: center;`">
                            {{ spare?.spare?.spare_name }}
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
                display_asset_zone:false,

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
             console.log("dcdc",vm.display_asset_zone)
            vm.display_asset_zone = !vm.display_asset_zone
             console.log("dcdc",vm.display_asset_zone)

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
        width: 2px;
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
</style>
