<template>
    <div>

        <div class="row pb-5 pt-3">
                <div class="col-sm-12">
                    <ul class="list-group">

            <li v-if="entries.length != 0" class="rounded-0 list-group-item pb-0 pt-0 rounded-0">

                <div class="row">
                    <div class="col-sm-6 p-3 text-center">
                    <i class="fa fa-circle text-success float-left"></i>

                        <div v-if="entries.availability == 0" class="border border-danger p-3" style="position: absolute; top: 45%; left: 30%; height: 150xp; width: 400px;">
                            <span class="display-4 text-danger text-uppercase" style="font-weight: bold">
                                DEACTIVATED
                            </span>
                        </div>

                        <img v-if="entries.avatar" class="img-responsive rounded-circle mx-auto" :class="{ 'deactived-img' : entries.availability == 0 }" style="height: 450px; width: auto;" :src="'/storage/' + entries.avatar" align="middle">                    

                    </div>
                    <div class="col-sm-6 p-0 border border-top-0 border-right-0 border-bottom-0">


                        <ul class="list-group list-group-flush" :class="{ 'text-muted' : entries.availability == 0 }">

                            <li class="list-group-item" :class="{ 'list-group-item-danger' : !entries.availability  }">
                                <small class="text-muted">DRIVER NAME:</small><br/>
                                <span style="font-size: 35px;">
                                {{entries.driver_name}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : !entries.availability  }">
                                <small class="text-muted">PLATE NUMBER:</small><br/>
                                <span style="font-size: 35px;">
                                    {{entries.plate_number}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : !entries.availability  }">
                                <small class="text-muted">HAULER NAME:</small><br/>
                                <span style="font-size: 35px;">
                                    {{entries.hauler_name}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : !entries.availability  }">
                                <small class="text-muted">PLANT IN:</small><br/>
                                <span style="font-size: 35px;">
                                    {{ moment(entries.LocalTime)}} 
                                </span>
                            </li>

                              <li class="list-group-item"  v-if="entries.shipment_number" :class="{ 'list-group-item-danger' : entries.availability != 1}">
                                 <small class="text-muted">SHIPMENT NUMBER:</small><br/>
                                  <span class="text-success" style="font-size: 40px;">
                                    SHIPMENT ASSIGNED - {{ entries.shipment_number }}
                                 </span>
                            </li>
                            
                        </ul>
           
           
                    </div>
                </div><!--end row -->

                 </li>


            <li v-if="entries.length == 0" class="rounded-0 list-group-item pb-0 pt-0 rounded-0">

                <div class="row">
                    <div class="col-sm-6 p-3 text-center">
                    <i  class="fa fa-circle text-warning float-left"></i>

                        <div v-if="emptyEntry.availability == 0" class="border border-danger p-3" style="position: absolute; top: 45%; left: 30%; height: 150xp; width: 400px;">
                            <span class="display-4 text-danger text-uppercase" style="font-weight: bold">
                                DEACTIVATED
                            </span>
                        </div>

                        <img v-if="emptyEntry.avatar" class="img-responsive rounded-circle mx-auto" :class="{ 'deactived-img' : emptyEntry.availability == 0 }" style="height: 450px; width: auto;" :src="'/storage/' + emptyEntry.avatar" align="middle">                    

                    </div>
                    <div class="col-sm-6 p-0 border border-top-0 border-right-0 border-bottom-0">


                        <ul class="list-group list-group-flush" :class="{ 'text-muted' : emptyEntry.availability == 0 }">

                            <li class="list-group-item" :class="{ 'list-group-item-danger' : !emptyEntry.availability  }">
                                <small class="text-muted">DRIVER NAME:</small><br/>
                                <span style="font-size: 35px;">
                                {{emptyEntry.driver_name}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : !emptyEntry.availability  }">
                                <small class="text-muted">PLATE NUMBER:</small><br/>
                                <span style="font-size: 35px;">
                                    {{emptyEntry.plate_number}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : !emptyEntry.availability  }">
                                <small class="text-muted">HAULER NAME:</small><br/>
                                <span style="font-size: 35px;">
                                    {{emptyEntry.hauler_name}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : !emptyEntry.availability  }">
                                <small class="text-muted">PLANT IN:</small><br/>
                                <span style="font-size: 35px;">
                                    {{ moment(emptyEntry.LocalTime)}} 
                                </span>
                            </li>

                              <li class="list-group-item"  v-if="emptyEntry.shipment_number" :class="{ 'list-group-item-danger' : emptyEntry.availability != 1}">
                                 <small class="text-muted">SHIPMENT NUMBER:</small><br/>
                                  <span class="text-success" style="font-size: 40px;">
                                    SHIPMENT ASSIGNED - {{ emptyEntry.shipment_number }}
                                 </span>
                            </li>
                            
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
        props: ['driverqueue'],
        data() {
            return {
                entries: [],
                emptyEntry: [],
            }
        },

        created() {

            this.storeEntries()        
            this.emptyPushed()
            this.lastEntry()

        },

        methods: {

            storeEntries() {
                axios.get('/storeGateEntries/'+this.driverqueue)
                .catch((error) => {
                    console.log(error);
                });
                setTimeout(this.storeEntries, 2000);
            },

            emptyPushed() {
                axios.get('/getLastGateEntry/' + this.driverqueue)
                .then(response => this.emptyEntry = response.data);
            },

            lastEntry() {
                Echo.channel('gate.'+ this.driverqueue)
                .listen('GateEntryEvent', (e) => {
                    this.entries = e.gateEntry;
                    // console.log(this.entries);
                });
            },

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