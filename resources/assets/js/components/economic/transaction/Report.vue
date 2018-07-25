<template>
    <layout>
        <h1 slot="title">Движение денежных средств</h1>
        <template slot="body">
            <span v-for="year in years" @click="getReport(year.year)" class="report-year">{{year.year}}</span>
            <table class="table">
                <thead>
                    <tr>
                        <td class="report-cell-center report-cell-category">СТАТЬИ</td>
                        <td v-for="interval in report.intervals" class="report-cell-left">
                            {{interval.name}}
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in report.rows" v-bind:class="{'report-row-category': row.intervals && row.intervals.length == 0}">
                        <td v-if="!row.is_space" class="report-cell-category"
                             v-bind:class="{'report-cell-center': row.center, 'report-cell-bold': row.bold, 'report-cell-right': row.right}">{{row.name}}</td>

                        <td v-if="!row.is_space && row.intervals && row.intervals.length > 0" v-for="cell in row.intervals" class="report-cell-right report-cell-value">
                            {{cell.value}}
                        </td>
                        <td v-if="!row.is_space && row.intervals && row.intervals.length == 0" colspan="13"></td>
                        <td v-if="row.is_space" colspan="14" class="report-cell-space"></td>
                    </tr>
                </tbody>
            </table>
        </template>
    </layout>
</template>

<script>
    import Layout from '../../Layout.vue'
    import vuestore from '../../../vuestore'

    export default {
        data() {
            let maxYear = (new Date()).getFullYear();
            let years = [];
            for (; maxYear >= 2016; maxYear--) {
                years.push({year:maxYear});
            }
            return {
                vuestore: vuestore,
                lroutes,
                years,
                report: {},
                year: this.$route.query.year || (new Date()).getFullYear(),
            }
        },
        created() {
            this.getReport(this.year);
        },
        methods: {
            getReport(year) {
                if(this.vuestore.loading) return;
                else this.vuestore.loading = true;
                let settings = {
                    method: 'get',
                    path: lroutes.api.admin.transaction.report,
                    params: {
                        year
                    }
                };

                this.vuestore.request(settings).then((response) => {
                    this.report = {
                        intervals: response.data.intervals,
                        rows: []
                    };

                    for (const key in response.data.income) {
                        if (response.data.income.hasOwnProperty(key)) {
                            let category = response.data.income[key];
                            // вернуть если будет множественные типы и категории для дохода
                            //this.report.rows.push({name: category.name, intervals: [], bold: true});

                            for (const key2 in category.types) {
                                if (category.types.hasOwnProperty(key2)) {
                                    let row = category.types[key2];
                                    row.center = true;
                                    row.bold = true;
                                    this.report.rows.push(row);
                                }
                            }

                            this.report.rows.push({name: 'Итого', bold: true, right: true, intervals: category.totals});
                            this.report.rows.push({is_space: true});
                        }
                    }

                    for (const key in response.data.cost) {
                        if (response.data.cost.hasOwnProperty(key)) {
                            let category = response.data.cost[key];

                            this.report.rows.push({name: category.name, intervals: [], center: true, bold: true});

                            for (const key in category.types) {
                                if (category.types.hasOwnProperty(key)) {
                                    this.report.rows.push(category.types[key]);
                                }
                            }

                            this.report.rows.push({name: 'Итого', bold: true, right: true, intervals: category.totals});
                            this.report.rows.push({is_space: true});
                        }
                    }
                    this.report.rows.push({is_space: true});
                    this.report.rows.push({is_space: true});
                    this.report.rows.push({
                        name: 'Чистая прибыль',
                        bold: true,
                        center: true,
                        intervals: response.data.total.intervals
                    });

                    this.vuestore.loading = false;
                }).catch((response) => {
                    alert('error');
                    this.vuestore.loading = false;
                });
            },
        },
        components: {
            Layout
        }
    }
</script>
<style>
    .report-year {
        color: #428bca;
        display: inline-block;
        padding: 5px 10px;
        cursor: pointer;
    }
    .report-cell-category {
        width: 200px;
    }
    .report-cell-center {
        text-align: center;
    }
    .report-cell-left {
        text-align: left;
    }
    .report-cell-right {
        text-align: right;
    }
    .report-cell-bold {
        font-weight: bold;
    }
    .report-row-category {
        padding-top: 15px;
    }
    .report-cell-space {
        border-top: 0 !important;
    }
</style>