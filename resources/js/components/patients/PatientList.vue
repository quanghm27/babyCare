<template>
    <div class="container-fluid">
        <button id="playAudio" @click="playSound()" style="display: none"></button>
        <audio id="audio" src="/sound/alert_patient.wav" muted></audio>
        <div v-if="alertPatients.length > 0">
            <h6>Bệnh nhân ngoài khoảng nhiệt độ an toàn</h6>
            <div class="row pt-10 bg-antiquewhite">
                <patient-info v-for="item in alertPatients" :key="item.id" :patientInfo="item"></patient-info>
            </div>
        </div>
        <div v-if="orderedPatientInfos.length > 0" style="margin-top: 2%">
            <h6>Bệnh nhân có nhiệt độ an toàn</h6>
            <div class="row">
                <patient-info v-for="item in orderedPatientInfos" :key="item.id" :patientInfo="item"></patient-info>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Cảnh báo nhiệt độ</h5>
                    </div>
                    <div class="modal-body">
                        Có bệnh nhân ngoài khoảng nhiệt độ an toàn
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal" @click="pauseAlert()">Đóng</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="turnOffAlert()">Tắt cảnh báo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const API_URL = 'http://bbc-api.gl-sci.tech/api/Common/GetDataByShareKeys'
    export default {
        props:['patients'],
        data() {
            return {
                patientInfos: [],
                alertPatients: []
            }
        },
         created() {
            //this.getDataByShareKeys()
        },
        methods: {
            playSound() {
                let alertFlg = JSON.parse(localStorage.getItem('alertSound'))
                if (this.alertPatients.length === 0 || !alertFlg) {
                    console.log('not play sound')
                    console.log(this.alertPatients.length)
                    return;
                }
                // Show modal
                $('#exampleModalCenter').modal('show')
                console.log('play sound')
                let audio = document.getElementById('audio');
                audio.autoplay = true;
                audio.loop = true;
                audio.muted = false;
                audio.play()
            },
            async getDataByShareKeys() {
                let shareKeys = this.patients.map(function (item) {
                    return item.sharekey
                })

                let result = await axios.post(API_URL, shareKeys)
                let _this = this
                if (result.data.status) {
                    _this.patients.forEach(function(item){
                        let apiData = _.find(result.data.data, {'deviceUserID' : item.deviceUserId})
                        let patientInfo = _.mergeWith(item, apiData, function (obj, src) {
                            if (_.isNull(src)) {
                                return obj
                            }
                        })
                        if (patientInfo.dataValue < patientInfo.minTemp || patientInfo.dataValue > patientInfo.maxTemp) {
                            _this.alertPatients.push(patientInfo);
                        } else {
                            _this.patientInfos.push(patientInfo);
                        }
                    })
                }
            },
            pauseAlert() {
                let audio = document.getElementById('audio');
                audio.muted = true;
                audio.pause()
            },
            turnOffAlert() {
                this.pauseAlert()
                $('#btn-toggle-alert').click()
            }
        },
        computed: {
            orderedPatientInfos() {
                return _.orderBy(this.patientInfos, 'dataValue', 'desc')
            }
        },
        async mounted() {
            await this.getDataByShareKeys()
            $('#playAudio').click()
        }
    }
</script>

<style scoped="scoped">
    .pt-10 {
        padding-top: 10px;
    }
    .bg-antiquewhite {
        background: antiquewhite;
    }
</style>
