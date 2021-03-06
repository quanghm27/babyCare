<template v-if="hideHistory">
    <div v-bind:class="{'col-md-4': hideHistory, 'col-md-12' : !hideHistory}">
        <div class="card">
            <div class="card-header" v-bind:class="cardHeaderClass">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 col-sm-2 card-header-info ">Phòng: {{ patientInfo.roomNo }}</div>
                        <div class="col-md-8 col-sm-2 card-header-info text-right">Mã y tế: {{ patientInfo.medical_number }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 card-header-info">Giường: {{ patientInfo.bedNo }}</div>
                        <div class="col-md-8 col-sm-8 card-header-info text-right">Số điện thoại: {{ patientInfo.phone_number}}</div>
                    </div>
                </div>
            </div>
            <div class="card-body position-relative" v-bind:class="cardBodyClass">
                <div class="batery-info text-white position-absolute">
                    <i class="fa" v-bind:class="batteryInfo"></i>
                    {{ patientInfo.batteryValue | percent}}</div>
                <div class="patient-avatar position-absolute">
                    <img class="patient-image rounded-circle" style="border: 2px solid white" :src="imageAvatar" alt="avatar">
                    <div v-bind:class="connectStatus"></div>
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
                <component :is="'patient-history'"
                           v-bind:shareKey="patientInfo.sharekey"
                           v-bind:minTemp="patientInfo.minTemp"
                           v-bind:maxTemp="patientInfo.maxTemp"
                           v-if="isShownHistory"></component>
            </div>
            <div class="card-footer text-center"  v-bind:class="cardFooterClass">
                <p class="text-white see-more" @click="goToHistory()">
                    {{ hideHistory ? 'Xem Lịch Sử' : 'Ẩn Lịch Sử'}}
                <i class="fa" v-bind:class="hideHistory ? 'fa-arrow-circle-down' : 'fa-arrow-circle-up'"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import ResizeText from 'vue-resize-text'
    export default {
        directives:  {
            ResizeText
        },
        props: ['patientInfo'],
        data() {
            return {
                hideHistory: true,
                isShownHistory: false,
                cardHeaderClass: '',
                cardBodyClass: '',
                cardFooterClass: ''
            }
        },
        created() {
            this.setCardClass()
        }
        ,
        computed: {
            tempState() {
                let tempState = ''

                if (this.patientInfo.dataValue <= 37.4) {
                    tempState = 'temp-safe'
                } else if ( 37.5 <= this.patientInfo.dataValue &&  this.patientInfo.dataValue <= 38.4) {
                    tempState = 'temp-warn'
                } else {
                    tempState = 'temp-danger'
                }
                return tempState
            },
            birthDay() {
                return moment(this.patientInfo.dateOfBirth).format('DD/MM/YYYY')
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
                return moment(this.patientInfo.updateTime).subtract(2, 'seconds').fromNow()
            },
            imageAvatar() {
                return this.patientInfo.imageUrl || '/images/default-avatar.png'
            },
            connectStatus() {
                return this.patientInfo.isConnecting ? 'status-connect' : ''
            }
        },
        methods: {
            goToHistory(){
                if (!this.isShownHistory) {
                    this.isShownHistory = true
                }
                this.hideHistory = !this.hideHistory
            },
            setCardClass() {
                if (this.patientInfo.dataValue <= 37.4) {
                    this.cardHeaderClass = 'header-safe'
                    this.cardBodyClass = 'body-safe'
                    this.cardFooterClass = 'footer-safe'
                } else if ( 37.5 <= this.patientInfo.dataValue &&  this.patientInfo.dataValue <= 38.4) {
                    this.cardHeaderClass = 'header-warn'
                    this.cardBodyClass = 'body-warn'
                    this.cardFooterClass = 'footer-warn'
                } else {
                    this.cardHeaderClass = 'header-danger'
                    this.cardBodyClass = 'body-danger'
                    this.cardFooterClass = 'footer-danger'
                }
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
    .patient-image {
        height: 100px;
    }
    .last-update {
        left: 35px;
        top: 65%;
        font-size: 14px;
    }
    .header-safe {
        background-image: linear-gradient(45deg, #5594d4, #536eb4);
    }
    .body-safe {
        background-image: linear-gradient(45deg, #85bee8, #829cd1);
    }
    .footer-safe {
        background-image: linear-gradient(45deg, #559fdb, #5378bd);
    }
    .header-warn {
        background-image: linear-gradient(45deg, #f88607, #f26908);
    }
    .body-warn {
        background-image: linear-gradient(45deg, #fdb41b, #f79621);
    }
    .footer-warn {
        background-image: linear-gradient(45deg, #eb941c, #f26a08);
    }
    .header-danger {
        background-image: linear-gradient(45deg, #b24e2f, #af302a);
    }
    .body-danger {
        background-image: linear-gradient(45deg, #bf7044, #bd433c);
    }
    .footer-danger {
        background-image: linear-gradient(45deg, #b36031, #af302a);
    }
    .card-header-info {
        font-weight: 700;
        /*font-size: 1.2em;*/
        color: #fff;
    }
    .see-more {
        cursor: pointer;
        transition: all .2s ease-in-out;
    }
    .see-more:hover {
        transform: scale(1.1);
    }
    .batery-info {
        font-size: 0.9em;
        top: 5%;
    }
    .patient-avatar {
        top: 2em;
    }
    .patient-name {
        top: 15%;
        left: 140px;
        font-size: 30px;
        font-weight: 200;
    }
    .patient-birth, .patient-note {
        left: 140px;
    }
    .patient-birth {
        top: 35%;
        font-size: 14px;
    }
    .patient-note {
        top: 45%;
        font-size: 14px;
    }
    .temperature-range {
        top: 170px;
        right: 5%;
        font-size: 14px;
    }
    .patient-temperature {
        top: 90px;
        right: 5%;
        font-size: 60px;
        font-weight: 500;
    }
    .card-body {
        min-height: 200px;
    }
    .status-connect {
        background: #42B72A;
        height: 20px;
        width: 20px;
        border-radius: 50%!important;
        bottom: 5px;
        left: 10px;
        position: absolute;
        border: 2px solid white;
    }
    @media (max-width: 1366px) {
        .card-header-info {
            font-size: 13px;
            text-align: left !important;
        }
        .patient-image {
            height: 80px;
        }
        .batery-info {
            font-size: 12px;
        }
        .last-update {
            left: 27px;
            top:55%;
            font-size: 12px;
        }
        .patient-name {
            top: 15%;
            left: 110px;
            font-size: 16px;
            font-weight: 400;
        }
        .patient-birth {
            left: 110px;
            top: 28%;
            font-size:12px;
        }
        .patient-note {
            left: 110px;
            top: 38%;
            font-size:12px;
        }
        .patient-temperature {
            top: 120px;
            right: 5%;
            font-size: 40px;
            font-weight: 500;
        }
    }
</style>