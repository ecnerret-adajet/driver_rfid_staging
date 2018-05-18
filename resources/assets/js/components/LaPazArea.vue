<template>
    <div>

        <div class="row pb-5 pt-3">
                <div class="col-sm-12">
                    <ul class="list-group">

            <li v-if="entries" class="rounded-0 list-group-item pb-0 pt-0 rounded-0">

                <div class="row" v-if="entries.driver" v-for="(driver,d) in entries.driver" :key="d">
                    <div class="col-sm-6 p-3 text-center">
                        
                        <div v-if="driver.availability == 0 || driver.truck[0].availability == 0" class="border border-danger p-3" style="position: absolute; top: 45%; left: 30%; height: 150xp; width: 400px;">
                            <span class="display-4 text-danger text-uppercase" style="font-weight: bold">
                                DEACTIVATED
                            </span>
                        </div>

                        <img v-if="driver.image" class="img-responsive rounded-circle mx-auto" :class="{ 'deactived-img' : driver.availability == 0 || driver.truck[0].availability == 0 }" style="height: 450px; width: auto;" :src="'/storage/' + driver.image.avatar" align="middle">
                        <img v-else class="img-responsive rounded-circle mx-auto" :class="{ 'deactived-img' : driver.availability == 0 || driver.truck[0].availability == 0 }" style="height: 450px; width: auto;" :src="'/storage/' + driver.avatar" align="middle">
                    

                    </div>
                    <div class="col-sm-6 p-0 border border-top-0 border-right-0 border-bottom-0">


                        <ul class="list-group list-group-flush" :class="{ 'text-muted' : driver.availability == 0 }">

                            <li class="list-group-item" :class="{ 'list-group-item-danger' : driver.availability != 1, 'list-group-item-warning' : driver.truck[0].availability != 1  }">
                                <small class="text-muted">DRIVER NAME:</small><br/>
                                <span style="font-size: 35px;">
                                {{driver.name}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : driver.availability != 1, 'list-group-item-warning' : driver.truck[0].availability != 1  }">
                                <small class="text-muted">PLATE NUMBER:</small><br/>
                                <span style="font-size: 35px;">
                                    {{driver.truck[0].plate_number}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : driver.availability != 1, 'list-group-item-warning' : driver.truck[0].availability != 1  }">
                                <small class="text-muted">HAULER NAME:</small><br/>
                                <span style="font-size: 35px;">
                                    {{driver.hauler[0].name}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : driver.availability != 1, 'list-group-item-warning' : driver.truck[0].availability != 1  }">
                                <small class="text-muted">PLANT IN:</small><br/>
                                <span style="font-size: 35px;">
                                    {{ moment(entries.LocalTime)}} 
                                </span>
                            </li>

                              <li class="list-group-item"  v-if="entries.shipment.length != 0" :class="{ 'list-group-item-danger' : driver.availability != 1}">
                                 <small class="text-muted">SHIPMENT NUMBER:</small><br/>
                                  <span class="text-success" style="font-size: 40px;">
                                    SHIPMENT ASSIGNED - {{ entries.shipment[0].shipment_number }}
                                 </span>
                            </li>

                             <!-- <li class="list-group-item list-group-item-info" v-if="isFromLapaz == 1">
                                <span class="text-dark text-uppercase" style="font-size: 40px;">Already Tapped from lapaz</span>
                            </li> -->
                        </ul>
           
           
                    </div>
                </div><!--end row -->

                 </li>

                    </ul>
                </div>
        </div>

    </div>
</template>
<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                entries: [],
                // currentTrucks: []
            }
        },

        created() {
            this.getEntries()
            // this.getTruckInPlant()
        },

        methods: {
            getEntries () {
                axios.get('/laPazAPI')
                .then(response => this.entries = response.data);

                setTimeout(this.getEntries, 2000);
            },

            // getTruckInPlant () {
            //     axios.get('/getTotalTrucksInPlant')
            //     .then(response => this.currentTrucks = response.data);
            //     setTimeout(this.getTruckInPlant, 3000);
            // },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        }
    }
</script>

<style scoped>
    .deactived-img {
        opacity: 0.5;
        filter: alpha(opacity=50);
    }
</style>