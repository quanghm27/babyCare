<template>
    <div class="container-fluid">
        <div class="row">
            <patient-info v-for="item in orderedPatientInfos" :key="item.id" :patientInfo="item"></patient-info>
        </div>
    </div>
</template>

<script>
    const API_URL = 'http://bbc-api.gl-sci.tech/api/Common/GetDataByShareKeys'
    export default {
        props:['patients'],
        data() {
            return {
                patientInfos: []
            }
        },
        created() {
            this.getDataByShareKeys()
        },
        methods: {
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
                        _this.patientInfos.push(patientInfo);
                    })
                }
            }
        },
        computed: {
            orderedPatientInfos() {
                return _.orderBy(this.patientInfos, 'dataValue', 'desc')
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
