<template>
<div style="height: 100%">

    <!-- start entries pushed -->
    <div v-if="entries.length != 0" :class="{ 'active' : entries.driver_availability && entries.truck_availability, 'deactivated' : !entries.driver_availability || !entries.truck_availability }" style="height: 100%">
    <div class="container-fluid pt-3">

        <div class="row pb-3">
            <div class="col text-left">
                <i class="fa fa-circle text-info"></i>
            </div>
        </div>

        <div class="row">
            <div class="col text-center">

                <img v-if="entries.avatar" class="img-responsive rounded-circle mx-auto"  
                :class="{ 'deactived-img deactivate-image' : !entries.driver_availability || !entries.truck_availability, 'active-image' : entries.driver_availability && entries.truck_availability }" 
                style="height: 450px; width: auto;" :src="'/storage/' + entries.avatar" 
                align="middle">

            </div>
        </div>
        <div class="row text-center text-white">
            <div class="col">
                <span style="font-size: 45px;">
                    {{entries.driver_name}}
                </span>
            </div>
        </div>
        <div class="row text-center text-white">
            <div class="col">
               <span style="font-size: 35px;">
                    {{entries.plate_number}}
                </span>
            </div>
        </div>
        <div class="row text-center text-white">
            <div class="col">
               <span style="font-size: 35px;">
                    {{entries.hauler_name}}
                </span>
            </div>
        </div>

    <div class="container mt-3">

    <table v-if="entries.driver_availability && entries.truck_availability" class="table table-bordered bg-white">
    <thead>
      <tr>
        <th class="text-muted">PLANT IN</th>
        <th class="text-muted">SHIPMENT NUMBER</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="50%">
            <span style="font-size: 35px;">
                {{ moment(entries.LocalTime.date) }} 
            </span>
        </td>
        <td>
            <span v-if="entries.shipment_number" style="font-size: 40px;">
                {{ entries.shipment_number }}
            </span>
            <span v-else class="text-muted" style="font-size: 40px;">
                NO ASSIGN SHIPMENT
            </span>
        </td>
      </tr>
    </tbody>
  </table>
   <table v-else class="table table-bordered bg-white">
    <thead>
      <tr>
        <th class="text-muted">STATUS</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-center text-danger font-weight-bold">
            <span v-if="!entries.driver_availability && entries.truck_availability" style="font-size: 35px;">
                DRIVER DEACTIVATED
            </span>
            <span v-if="!entries.truck_availability && entries.driver_availability" style="font-size: 35px;">
                TRUCK DEACTIVATED
            </span>
            <span v-if="!entries.truck_availability && !entries.driver_availability" style="font-size: 35px;">
                DRIVER & TRUCK DEACTIVATED
            </span>
        </td>
      </tr>
    </tbody>
  </table>

    </div>

    </div>
    </div> 
    <!-- end entries pushed -->


    
    <!-- start empty pushed -->
    <div v-if="entries.length == 0" :class="{ 'active' : emptyEntry.driver_availability && emptyEntry.truck_availability, 'deactivated' : !emptyEntry.driver_availability || !emptyEntry.truck_availability }" style="height: 100%">
    <div class="container-fluid pt-3">

        <div class="row pb-3">
            <div class="col text-left">
                <i class="fa fa-circle text-warning"></i>
            </div>
        </div>

        <div class="row">
            <div class="col text-center">

                <img v-if="emptyEntry.avatar" class="img-responsive rounded-circle mx-auto"  
                :class="{ 'deactived-img deactivate-image' : !emptyEntry.driver_availability || !emptyEntry.truck_availability, 'active-image' : emptyEntry.driver_availability && emptyEntry.truck_availability }" 
                style="height: 450px; width: auto;" :src="'/storage/' + emptyEntry.avatar" 
                align="middle">

            </div>
        </div>
        <div class="row text-center text-white">
            <div class="col">
                <span style="font-size: 45px;">
                    {{emptyEntry.driver_name}}
                </span>
            </div>
        </div>
        <div class="row text-center text-white">
            <div class="col">
               <span style="font-size: 35px;">
                    {{emptyEntry.plate_number}}
                </span>
            </div>
        </div>
        <div class="row text-center text-white">
            <div class="col">
               <span style="font-size: 35px;">
                    {{emptyEntry.hauler_name}}
                </span>
            </div>
        </div>

    <div class="container mt-3">

    <table v-if="emptyEntry.driver_availability && emptyEntry.truck_availability" class="table table-bordered bg-white">
    <thead>
      <tr>
        <th class="text-muted">PLANT IN</th>
        <th class="text-muted">SHIPMENT NUMBER</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="50%">
            <span style="font-size: 35px;">
                {{ moment(emptyEntry.LocalTime.date)}} 
            </span>
        </td>
        <td>
            <span v-if="emptyEntry.shipment_number" style="font-size: 40px;">
                {{ emptyEntry.shipment_number }}
            </span>
            <span v-else class="text-muted" style="font-size: 40px;">
                NO ASSIGN SHIPMENT
            </span>
        </td>
      </tr>
    </tbody>
  </table>
   <table v-else class="table table-bordered bg-white">
    <thead>
      <tr>
        <th class="text-muted">STATUS</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-center text-danger font-weight-bold">
            <span v-if="!emptyEntry.driver_availability && emptyEntry.truck_availability" style="font-size: 35px;">
                DRIVER DEACTIVATED
            </span>
            <span v-if="!emptyEntry.truck_availability && emptyEntry.driver_availability" style="font-size: 35px;">
                TRUCK DEACTIVATED
            </span>
            <span v-if="!emptyEntry.truck_availability && !emptyEntry.driver_availability" style="font-size: 35px;">
                DRIVER & TRUCK DEACTIVATED
            </span>
        </td>
      </tr>
    </tbody>
  </table>

    </div>

    </div>
    </div> 
    <!-- end empty pushed -->


    

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
            // this.lastEntry()
            this.emptyPushed()
        
        },

        methods: {

            storeEntries() {
                axios.post('/storeGateEntries/'+this.driverqueue)
                .then(response => this.entries = response.data)
                .catch((error) => {
                    console.log(error);
                });
                setTimeout(this.storeEntries, 2000);
            },

            emptyPushed() {
                axios.get('/getLastGateEntry/' + this.driverqueue)
                .then(response => this.emptyEntry = response.data);
            },

            // lastEntry() {
            //     Echo.channel('gate.'+ this.driverqueue)
            //     .listen('GateEntryEvent', (e) => {
            //         this.entries = e.gateEntry;
            //         // console.log(this.entries);
            //     });
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
    .active {
    background: #667db6;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #667db6, #0082c8, #0082c8, #667db6);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #667db6, #0082c8, #0082c8, #667db6); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
    .deactivated {
    background: #DA4453;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #89216B, #DA4453);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #89216B, #DA4453); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
    .active-image {
        border: 10px solid #3498db;
    }
    .deactivate-image {
        border: 10px solid  #e74c3c;
    }
</style>