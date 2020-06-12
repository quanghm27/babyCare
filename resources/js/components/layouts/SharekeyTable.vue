<template>
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th></th>
            <th>Tên bệnh nhân</th>
            <th>Mã y tế</th>
            <th>Phòng bệnh</th>
            <th>Mã chia sẻ</th>
            <th>Ngày nhập viện</th>
            <th>Ngày xuất viện</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in sharekeyList">
            <td style="width: 5%">
                <div style="display: inline-block;">
                    <a :href="item.editAction">Chỉnh sửa</a>
                </div>
            </td>
            <td>{{ item.name }}</td>
            <td>{{ item.medical_number }}</td>
            <td>{{ item.roomNo}}</td>
            <td>
                <div>{{ item.sharekey }}
                    <span class="badge badge-success" v-show="!item.isExpired"> {{ 'Còn hạn (' + item.expiredTime + ')'}}</span>
                    <span class="badge badge-danger" v-show="item.isExpired">Hết hạn</span>
                </div>
            </td>
            <td>{{ item.joinDate }}</td>
            <td> {{item.releaseDate}}</td>
        </tr>
        </tbody>
    </table>
</template>
<script>
    export default {
        props: ['sharekeys'],
        data() {
            return {
                sharekeyList: this.sharekeys,
            }
        },
        created() {
            this.sharekeyList.map(function(item) {
                item.editAction = '/sharekeys/' + item.sharekey + '/edit';
                item.isExpired = moment(item.expiredTime).isBefore(moment())
                item.expiredTime = moment(item.expiredTime).fromNow()
                item.joinDate = moment(item.joinDate).format('DD/MM/YYYY HH:mm')
                item.releaseDate = item.releaseDate ? moment(item.releaseDate).format('DD/MM/YYYY HH:mm') : '-'
                item.updated_at = moment(item.updated_at).format('DD/MM/YYYY HH:mm')
                item.created_at = moment(item.created_at).format('DD/MM/YYYY HH:mm')
                return item;
            })
        },
        methods: {

        }
    }
</script>