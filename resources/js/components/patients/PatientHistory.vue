<template>
    <div style="height: 300px">
        <line-chart  v-if="loaded" :chartdata="chartdata" :options="options"></line-chart>
    </div>
</template>
<script>
    const API_HISTORY = 'http://bbc-api.gl-sci.tech/api/Common/GetDataHistoryByShareKeys'
    export default {
        props:['shareKey'],
        data() {
            return {
                chartdata: null,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                },
                loaded: false
            }
        },
        created() {
            this.inital()
        },
        methods: {
            async inital() {
                let params = [{
                    'sharedKey' : this.shareKey,
                    'fromDate': moment().subtract(5, 'days'),
                    'toDate': moment()
                }]
                let result = await axios.post(API_HISTORY, params)
                if (result.data.status) {
                    let datas = result.data.data.map(function(item) {
                        return {
                            dataValue: item.dataValue,
                            time: moment(item.updateTime).format('YYYY-MM-DD HH:mm:ss')
                        }
                    })
                    let data = _.values(_.mapValues(datas, function(item){ return item.dataValue}))
                    let labels = _.values(_.mapValues(datas, function(item){ return item.time}))
                    this.chartdata =  Object.assign({}, {
                        datasets: [{
                            data: data,
                            fill: false,
                        }],
                        labels: labels
                    })
                    this.loaded = true
                }
            }
        }
    }
</script>