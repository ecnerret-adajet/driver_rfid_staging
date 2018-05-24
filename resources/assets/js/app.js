/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('example', require('./components/Example.vue'));
Vue.component('drivers', require('./components/Drivers.vue'));
Vue.component('trucks', require('./components/Trucks.vue'));
Vue.component('haulers', require('./components/Haulers.vue'));
Vue.component('settings', require('./components/Settings.vue'));
Vue.component('prints', require('./components/Prints.vue'));
Vue.component('home', require('./components/Home.vue'));
Vue.component('cards', require('./components/Cards.vue'));
Vue.component('users', require('./components/Users.vue'));
Vue.component('logs', require('./components/Logs.vue'));
Vue.component('pickups', require('./components/Pickups.vue'));
Vue.component('vendor', require('./components/Vendor.vue'));
Vue.component('driverdetails', require('./components/DriverDetails.vue'));
Vue.component('handlers', require('./components/Handlers.vue'));
Vue.component('lineup', require('./components/Lineup.vue'));
Vue.component('graph', require('./components/Graph.vue'));
Vue.component('dates', require('./components/Dateentries.vue'));
Vue.component('top', require('./components/Tophauler.vue'));
Vue.component('topentries', require('./components/TopEntries.vue'));
Vue.component('hauleronline', require('./components/HaulerOnline.vue'));
Vue.component('hauleronlinetruck', require('./components/HaulerOnlineTruck.vue'));
Vue.component('driverque', require('./components/DriverQue.vue'));
Vue.component('pickupUnserve', require('./components/PickupUnserve.vue'));
Vue.component('pickupServed', require('./components/PickupServed.vue'));

Vue.component('driverupload', require('./components/DriverUpload.vue'));
Vue.component('monitorQueuePickups', require('./components/MonitorQueuePickup.vue'));
Vue.component('monitorQueueDeliveries', require('./components/MonitorQueueDeliveries.vue'));
Vue.component('noTruck', require('./components/NoTruck.vue'));
Vue.component('deactivatedDrivers', require('./components/DeactivatedDrivers.vue'));
Vue.component('noDriver', require('./components/NoDriver.vue'));
Vue.component('deactivatedTrucks', require('./components/DeactivatedTrucks.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('pickupCount', require('./components/PickupCount.vue'));
Vue.component('lapazArea', require('./components/LaPazArea.vue'));
Vue.component('manilaArea', require('./components/ManilaArea.vue'));
Vue.component('bataanArea', require('./components/BataanArea.vue'));
Vue.component('resignedDrivers', require('./components/ResignedDrivers.vue'));

Vue.component('monitorQueueDeliveriesBtn', require('./components/MonitorQueueDeliveriesBtn.vue'));
Vue.component('monitorQueueDeliveriesLpz', require('./components/MonitorQueueDeliveriesLpz.vue'));
Vue.component('driverQueueBtn', require('./components/DriverQueueBtn.vue'));
Vue.component('gate', require('./components/MonitorGate.vue'));
Vue.component('driverqueue', require('./components/MonitorQueue.vue'));

Vue.component('gateEntries', require('./components/GateEntries.vue'));
Vue.component('driverqueueEntries', require('./components/QueuesEntries.vue'));

Vue.component('gateArea', require('./components/GateArea.vue'));
Vue.component('queueArea', require('./components/QueueArea.vue'));
Vue.component('queueParent', require('./components/QueueParent.vue'));

const app = new Vue({
    el: '#app'
});