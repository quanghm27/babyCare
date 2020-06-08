<template v-if="hideHistory">
    <div v-bind:class="{'col-md-4': hideHistory, 'col-md-8' : !hideHistory}">
        <div class="card">
            <div class="card-header header-safe">
                <div class="card-header-info float-left">Phòng: {{ patientInfo.roomNo }}</div>
                <div class="card-header-info float-right">Mã y tế: {{ patientInfo.medical_number }}</div>
                <div class="clearfix"></div>
                <div class="card-header-info float-left">Giường: {{ patientInfo.bedNo }}</div>
                <div class="card-header-info float-right">Số điện thoại: {{ patientInfo.phone_number}}</div>
            </div>
            <div class="card-body position-relative" v-bind:class="tempState">
                <div class="batery-info text-white position-absolute">
                    <i class="fa" v-bind:class="batteryInfo"></i>
                    {{ patientInfo.batteryValue | percent}}</div>
                <div class="patient-avatar position-absolute">
                    <img class="rounded-circle" :src="imageAvatar" alt="avatar" width="100" height="100">
                    <div class="status" v-bind:class="connectStatus"></div>
                </div>

                <div class="last-update text-white position-absolute"> {{ lastseen}}</div>
                <div class="patient-name text-white position-absolute"> {{ patientInfo.name}}</div>
                <div class="patient-birth text-white position-absolute">
                    <i class="fa fa-birthday-cake"></i>
                    {{ birthDay }}
                </div>
                <div class="patient-note text-white position-absolute">
                    <i class="fa fa-sticky-note"></i>
                    Chú ý: {{ patientInfo.note }}
                </div>
                <div class="patient-temperature text-white position-absolute"> {{ patientInfo.dataValue | celsius}}</div>
                <div class="temperature-range text-white position-absolute"> {{ patientInfo.minTemp | celsius}} ~ {{ patientInfo.maxTemp | celsius}} </div>
            </div>
            <div class="card-body card-graph" v-show="!hideHistory">
                <component :is="'patient-history'" v-bind:shareKey="patientInfo.sharekey" v-if="isShownHistory"></component>
            </div>
            <div class="card-footer header-safe text-center">
                <p class="card-header-info see-more" @click="goToHistory()">
                    {{ hideHistory ? 'Xem Lịch Sử' : 'Ẩn Lịch Sử'}}
                <i class="fa" v-bind:class="hideHistory ? 'fa-arrow-circle-down' : 'fa-arrow-circle-up'"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['patientInfo'],
        data() {
            return {
                hideHistory: true,
                isShownHistory: false
            }
        },
        computed: {
            tempState() {
                let tempState = ''
                if (this.patientInfo.dataValue <= 37.4) {
                    tempState = 'temp-safe'
                } else if ( 37.5 < this.patientInfo.dataValue &&  this.patientInfo.dataValue <= 38.4) {
                    tempState = 'temp-warn'
                } else {
                    tempState = 'temp-danger'
                }
                return tempState
            },
            birthDay() {
                return moment().format('LL')
            },
            batteryInfo() {
                let info = 'fa-battery-'
                let batteryValue = this.patientInfo.batteryValue
                if (batteryValue == 0) {
                    info += 'empty'
                } else if (0 < batteryValue && batteryValue < 25) {
                    info += 'quarter'
                } else if (25 < batteryValue && batteryValue < 75 ) {
                    info += 'half'
                } else if (75 < batteryValue && batteryValue < 100) {
                    info += 'three-quarters'
                } else {
                    info += 'full'
                }
                return info
            },
            lastseen() {
                return moment(this.patientInfo.serverTime).fromNow()
            },
            imageAvatar() {
                return this.patientInfo.imageUrl || '/images/default-avatar.png'
            },
            connectStatus() {
                return this.patientInfo.isConnecting ? 'status-connect' : 'status-not-connect'
            }
        },
        methods: {
            goToHistory(){
                if (!this.isShownHistory) {
                    this.isShownHistory = true
                }
                this.hideHistory = !this.hideHistory
            }
        },
        filters: {
            celsius: function (value) {
                return value + '°C';
            },
            percent: function (value) {
                return value + '%';
            }
        }
    }
</script>
<style scoped="scoped">
    .last-update {
        left: 2.5em;
        top: 9em;
        font-size: 0.9em;
    }
    .header-safe {
        background-image: linear-gradient(45deg, #579dd4, #5d7bb9);
    }
    .card-header-info {
        font-weight: 700;
        font-size: 1.2em;
        color: #fff;
    }
    .see-more {
        cursor: pointer;
    }
    .temp-safe {
        background-image: linear-gradient(45deg, #85c1e9, #829acf);
    }
    .temp-warn {
        background-image: linear-gradient(45deg, #fdb71a, #f79621);
    }
    .temp-danger {
        background-image: linear-gradient(45deg, #c07545, #bd433c);
    }
    .batery-info {
        font-size: 0.9em;
    }
    .patient-avatar, .patient-temperature {
        top: 2em;
    }
    .patient-name {
        top: 1em;
        left: 4em;
    }
    .patient-name {
        font-size: 2em;
        font-weight: 200;
    }
    .patient-birth, .patient-note {
        left: 9.5em;
    }
    .patient-birth {
        top: 75px;
        font-size: 14px;
    }
    .patient-note {
        top: 95px;
        font-size: 14px;
    }
    .temperature-range {
        top: 5em;
        right: 2em;
        font-size: 0.9em;
    }
    .patient-temperature {
        top: 1em;
        right: 0.9em;
        font-size: 2em;
        font-weight: 500;
    }
    .card-body {
        min-height: 200px;
    }
    .status {
        height: 20px;
        width: 20px;
        border-radius: 50%!important;
        bottom: 5px;
        left: 10px;
        border: 2px solid white;
        position: absolute;
    }
    .status-connect {
        background: #42B72A;
    }
    .status-not-connect {
        background: #FFA602;
    }
</style>