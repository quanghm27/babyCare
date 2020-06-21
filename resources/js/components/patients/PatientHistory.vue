<template>
    <div style="height: 430px">
        <div class="col-md-3">
            <select class="form-control" v-model="selectedDate">
                <option v-for="(value, name, index) in historyDates">{{ name }}</option>
            </select>
        </div>
        <line-chart  v-if="chartdata!= null" :chartdata="chartdata" :options="options" :key="chartKey"></line-chart>
    </div>
</template>
<script>
    const API_HISTORY = 'http://bbc-api.gl-sci.tech/api/Common/GetDataHistoryByShareKeys'
    export default {
        props:['shareKey', 'minTemp', 'maxTemp'],
        data() {
            return {
                historyDates: null,
                chartdata: null,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAes: [{
                            gridLines: {
                                display:false,
                                lineWidth: 0
                            },
                        }],
                        yAxes: [{
                            gridLines: {
                                display:false,
                                lineWidth: 0
                            },
                            ticks: {
                                min: 32,
                                max: 42,
                                stepSize: 0.5
                            }
                        }]
                    }
                },
                loaded: false,
                selectedDate: '',
                chartKey: 0
            }
        },
        created() {
            this.inital()
        },
        methods: {
            async inital() {
                let params = [{
                    'sharedKey' : this.shareKey,
                    'fromDate': moment().subtract(30, 'days'),
                    'toDate': moment()
                }]
                let result = await axios.post(API_HISTORY, params)
                if (result.data.status) {
                    let datas = result.data.data.reduce(function(acc, data, index) {
                        const date = moment(data.updateTime).date()
                        const month = moment(data.updateTime).month() + 1
                        const day = moment(data.updateTime).format('LL')
                        const history = {
                            dataValue : data.dataValue,
                            time: moment(data.updateTime).format("HH:mm")
                        }

                        if (!acc[day]) {
                            acc[day] = []
                        }
                        acc[day].push(history)

                        return acc
                    }, {})
                    this.historyDates = datas
                    this.selectedDate = Object.keys(this.historyDates)[0]
                }
            }
        },
        computed: {
        },
        watch: {
            selectedDate(newVal, oldVal) {
                let datas = this.historyDates[newVal]
                let data = _.values(_.mapValues(datas, function(item){ return item.dataValue}))
                let labels = _.values(_.mapValues(datas, function(item){ return item.time}))
                this.chartdata =  Object.assign({}, {
                    datasets: [{
                        data: data,
                        fill: false,
                        label: newVal,
                        borderColor: '#00c372',
                        pointStyle: 'line'
                    }, {
                        data: data.map(() => this.minTemp),
                        fill: false,
                        label: 'Thấp nhất',
                        borderColor: '#579dd4',
                        pointStyle: 'line'
                    }, {
                        data: data.map(() => this.maxTemp),
                        fill: false,
                        label: 'Cao nhất',
                        borderColor: '#bd433c',
                        pointStyle: 'line'
                    }],
                    labels: labels
                })
                // Re-render component LineChart
                this.chartKey += 1
            }
        }
    }
</script>