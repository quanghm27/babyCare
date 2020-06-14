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
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in sharekeyList">
            <td style="width: 5%">
                <div style="display: inline-block;">
                    <a class="text-gray text-bold":href="item.detailAction" v-if="item.releaseDate">Chi tiết</a>
                    <a class="text-primary text-bold":href="item.editAction" v-else>Chỉnh sửa</a>
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
            <td>
                <div v-if="item.releaseDate != null">
                    {{item.releaseDate}}
                </div>
                <div v-else>
                    ---
                </div>
            </td>
            <td>
                <div v-if="item.releaseDate != null">
                    <a href="#" class="text-danger" v-confirm="{
                        loader: true,
                        ok: dialog => okCallback(dialog, item.id),
                        cancel: cancelcallback,
                        message: 'Xác nhận bệnh nhân chưa xuất viện ?'}">Chưa xuất viện
                    </a>
                </div>
                <div v-else>
                    <a href="#" class="text-danger" v-confirm="{
                        loader: true,
                        ok: dialog => okCallback(dialog, item.id),
                        cancel: cancelcallback,
                        message: 'Xác nhận bệnh nhân xuất viện ?'}">Xuất viện
                    </a>
                </div>
            </td>
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
                item.detailAction = '/sharekeys/' + item.sharekey + '/detail';
                item.isExpired = moment(item.expiredTime).isBefore(moment())
                item.expiredTime = moment(item.expiredTime).fromNow()
                item.joinDate = moment(item.joinDate).format('DD/MM/YYYY HH:mm')
                item.releaseDate = item.releaseDate ? moment(item.releaseDate).format('DD/MM/YYYY HH:mm') : null
                item.updated_at = moment(item.updated_at).format('DD/MM/YYYY HH:mm')
                item.created_at = moment(item.created_at).format('DD/MM/YYYY HH:mm')
                return item;
            })
        },
        methods: {
            async okCallback(dialog, id) {
                let url = '/release'
                let params = {id: id}
                let result = await axios.post(url, params)
                if (result.data.status) {
                    dialog.close()
                    location.reload()
                }
            },
            cancelcallback() {
                console.log('Clicked on cancel');
            }
        }
    }
</script>