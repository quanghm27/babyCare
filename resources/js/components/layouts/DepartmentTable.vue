<template>
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th></th>
            <th>Tên Khoa</th>
            <th>Ngày tạo</th>
            <th>Ngày Cập nhật</th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="item in departmentList">
                <td style="width: 5%">
                    <div style="display: inline-block;">
                        <form v-bind:action="item.updateAction" method="POST">
                            <input type="hidden" name="_token" :value="csrf">
                            <input type="hidden" class="department-name" name="name" v-model="item.name"/>
                            <button type="submit" class="btn btn-default btn-sm">
                                <i class="fa fa-upload"></i>
                            </button>
                        </form>
                    </div>
                    <div style="display: inline-block;">
                        <!--<form v-bind:action="item.deleteAction" method="POST">-->
                            <!--<button type="submit" class="btn btn-default btn-sm">-->
                                <!--<i class="fa fa-trash"></i>-->
                            <!--</button>-->
                        <!--</form>-->
                    </div>
                </td>
                <td><input type="text" class="form-control" v-model="item.name"/></td>
                <td>{{ item.created_at }}</td>
                <td>{{ item.updated_at }}</td>
            </tr>
        </tbody>
    </table>
</template>
<script>
    export default {
        props: ['departments'],
        data() {
            return {
                departmentList: this.departments,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        created() {
            this.departmentList.map(function(item) {
                item.updateAction = '/departments/' + item.id
                item.deleteAction = '/departments/' + item.id + '/delete'
                item.updated_at = moment(item.updated_at).format('DD/MM/YYYY HH:mm')
                item.created_at = moment(item.created_at).format('DD/MM/YYYY HH:mm')
                return item;
            })
        },
        methods: {

        }
    }
</script>