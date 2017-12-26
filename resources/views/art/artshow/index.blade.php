@extends("layouts.main")

@section("content")
    <section class="content" id="app">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">展示列表</h3>
                <div class="pull-right">

                    <el-button @click="goCreateArtShow()" round type="primary" size="mini">添加展示</el-button>
                </div>
            </div>

            <div class="box-body">
                <el-row>
                    <el-col :span="4" v-for="(item, index) in tableData" :key="item.id">
                        <el-card :body-style="{ padding: '0px' }">
                            <img :src="item.cover" class="image">
                            <div style="padding: 14px;">
                                <span>@{{ item.name }}</span>
                                <time class="time pull-right">@{{ item.created_at }}</time>

                                <div class="bottom clearfix">
                                    <div>
                                        {{--<a @click="option('del',item.id)" class="btn btn-danger btn-xs">删除</a>--}}
                                        <a @click="option('show',item.id)" class="btn btn-primary btn-xs">详情</a>
                                        <a @click="option('edit',item.id)" class="btn btn-info btn-xs">编辑</a>
                                    </div>

                                </div>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>

                <div class="pull-right">
                    <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page.sync="currentPage"
                    :page-size="perPage"
                    layout="total, sizes, prev, pager, next, jumper"
                    :page-sizes="pageSizes"
                    :total="total">
                    </el-pagination>
                </div>
            </div>
        </div>


    </section>
@endsection
@section('css')
    <style>
        .time {
            font-size: 13px;
            color: #999;
        }

        .bottom {
            margin-top: 13px;
            line-height: 12px;
        }

        .button {
            padding: 0;
            float: right;
        }

        .image {
            width: 100%;
            display: block;
        }

        .clearfix:before,
        .clearfix:after {
            display: table;
            content: "";
        }

        .clearfix:after {
            clear: both
        }
    </style>
    @stop

@section('javascript')
    <script>
        new Vue({
            el: '#app',
            data: {
                filters: [
                    {text: '全部', value: ""},
                    {text: '待支付', value: "0"},
                    {text: '已支付', value: "1"},
                    {text: '已入住', value: "2"},
                    {text: '已完成', value: "10"},
                    {text: '已取消', value: "-10"}
                ],
                multipleSelection: [],
                querys: {'columns': 'id', 'order': 'descending'},
                currentPage: 1,
                perPage: 10,
                total: 1,
                pageSizes: [10, 20, 30, 40],
                searchData: '',
                tableData: [],
                searchStatus: ''//检索订单状态
            },
            mounted(){
                let url= laroute.route('art.index_api');
                this.$http.post(url,this.querys).then(res => {
                    this.tableData = res.data;  //分页数据
                    this.perPage = res.per_page; //每页数量
                    this.total = res.total; //总数量
                })
            },
            methods: {
                option(status,id=''){
                    switch (status){
                        case 'show':
                            window.location.href=laroute.route('art.show',{art:id})
                            break;
                        case "edit":
                            window.location.href=laroute.route('art.edit',{art:id});
                            break;
                    }
                },

                status(val){

                    this.querys.select = val ? {"status": this.searchStatus} : "";
                    this.searchStatus = val;

                    this.$http.post('' + '?page=' + this.currentPage, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.pre_page; //每页数量
                        this.total = res.total; //总数量
                    }).catch(res => {

                    })
                },
                sortChange(column){
                    let url= laroute.route('art.index_api',{page:this.currentPage});
                    this.$http.post(url, {
                        'columns': column.prop,
                        'order': column.order
                    }).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.pre_page; //每页数量
                        this.total = res.total; //总数量
                    }).catch(res => {

                    })
                },
                handleSizeChange(val) {

                    this.querys.prePage = val;
                    this.perPage = val;
                    let url= laroute.route('art.index_api',{page:this.currentPage});
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.per_page;
                        this.total = res.total;
                    })
                },
                handleCurrentChange(val) {
                    console.log(val);
                    this.currentPage = val;
                    let url= laroute.route('art.index_api',{page:val});
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.per_page;
                        this.total = res.total;
                    })
                },
                querySearchAsync(queryString, cb) {
                    // this.searchData=this.tableData;
                    let results = '';
                    clearTimeout(this.timeout2)
                    this.timeout2 = setTimeout(() => {

                        this.querys.search = queryString;
                        let url= laroute.route('art.index_api',{page:this.currentPage});
                        this.$http.post(url, this.querys).then(res => {
                            results = this.tableData = res.data;
                            this.perPage = res.per_page;
                            this.total = res.total;
                        });
                    }, 300)

                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        cb(results);
                    }, 1500 * Math.random());
                },
                handleSelect(item) {
                    console.log(item)
                    this.tableData = [item];
                    this.perPage = 0;
                    this.total = 0;
                },
                goCreateArtShow(){
                    window.location.href='{{route('art.create')}}'
                }
            }
        })
    </script>
@stop