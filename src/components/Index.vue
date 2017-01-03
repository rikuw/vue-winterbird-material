<template>

    <div>
        <q-ajax-bar ref="bar" :position="position" :reverse="reverse" :size="computedSize" :color="color"></q-ajax-bar>
        <q-layout>

            <div class="layout-view">
                <div class="layout-padding">
                    <div class="card">
                        <div class="card-title">
                            Hae laskentoja
                        </div>
                        <div class="card-content">
                            <q-select
                              type="radio"
                              v-model="selectedYear"
                              :options="yearsOptions" placeholder="Valitse vuosi"></q-select>
                            <q-select
                              type="radio"
                              v-model="selectedCount"
                              :options="countOptions" placeholder="Valitse laskentakausi"></q-select>
                        </div>
                        <div class="card-actions">
                            <button class="primary" @click="loadCounts">Hae</button>
                        </div>
                    </div>

                    <q-data-table
                      :data="counts"
                      :config="config"
                      :columns="columns"
                      @refresh="refresh"
                    >
                        <template slot="col-documentID" scope="cell">
                            <a @click="loadCount(cell.data)">{{ cell.data }}</a>
                        </template>
                    </q-data-table>
                </div>
            </div>
        </q-layout>

        <Count></Count>
        
    </div>

</template>

<script>
    import Quasar, { Platform, Utils, Dialog, Loading, Toast } from 'quasar'
    import Count from './Count.vue'

    export default {
        components: {
          'Count': Count
        },
        data () {
            return {
                position: 'bottom',
                reverse: false,
                size: 8,
                color: '#e21b0c',
                selectedYear: '',
                selectedCount: '',
                yearsOptions: [
                    { label: '2014', value: '2014' },
                    { label: '2015', value: '2015' },
                    { label: '2016', value: '2016' },
                    { label: '2017', value: '2017' }
                ],
                countOptions: [
                    { label: '1', value: 1 },
                    { label: '2', value: 2 },
                    { label: '3', value: 3 }
                ],
                counts: [{
                    "documentID": "N/A",
                    "date": 10000000,
                    "team": "N/A",
                    "route": 0,
                    "areaID": 0,
                    "areaName": "N/A",
                    "speciesCount": 0,
                    "individualCount": 0,
                    "municipality": "N/A"
                }],
                config: {
                    title: 'Laskennat',
                    filter: true,
                    refresh: true,
                    columnPicker: true,
                    leftStickyColumns: 1,
                    rightStickyColumns: 2,
                    bodyStyle: {
                    maxHeight: Platform.is.mobile ? '50vh' : '500px'
                },
                rowHeight: '50px',
                responsive: true,
                pagination: {
                    rowsPerPage: 15,
                    options: [5, 10, 15, 30, 50, 500]
                },
                selection: false,
                messages: {
                    noData: '<i>warning</i> Ei laskentoja näytettäväksi.',
                    noDataAfterFiltering: '<i>warning</i> Ei tuloksia. Muuta hakuasetuksiasi.'
                }
            },
            columns: [
                {
                    label: 'ID',
                    field: 'documentID',
                    width: '80px'
                },
                {
                    label: 'Pvm',
                    field: 'date',
                    format (value) {
                        if (value !== 10000000) {
                            let a = '' + value;
                            let b = a.slice(0, 4);
                            let c = a.slice(4, 6);
                            let d = a.slice(6, 8);

                            return new Date(b + '-' + c + '-' + d).toLocaleString();
                        } else {
                            return 'N/A';
                        }
                    },
                    width: '120px'
                },
                {
                    label: 'Kunta',
                    field: 'municipality',
                    sort: true,
                    filter: true,
                    width: '500px'
                },
                {
                    label: 'Reittinumero',
                    field: 'route',
                    sort: true,
                    filter: true,
                    width: '120px'
                },
                {
                    label: 'Lajimäärä',
                    field: 'speciesCount',
                    sort: true,
                    width: '120px'
                },
                {
                    label: 'Yksilömäärä',
                    field: 'individualCount',
                    sort: true,
                    width: '120px'
                }
            ],
            pagination: true,
            rowHeight: 50,
            bodyHeightProp: 'maxHeight',
            bodyHeight: 500
        }
    },
    computed: {
        computedSize () {
            return this.size + 'px'
        }
    },
    methods: {
        loadCounts() {
            return new Promise((resolve, reject) => {
                if (this.selectedYear !== '' && this.selectedCount !== '') {
                    this.isLoading = true;
                    this.$refs.bar.start();

                    this.$http.get('http://localhost/selain/list.php?year=' + this.selectedYear + '&count=' + this.selectedCount).then((response) => {
                        if (response.body.length !== 0) {
                            this.counts = response.body;
                            this.$refs.bar.stop();
                            this.isLoading = false;
                            resolve();
                        } else {
                            this.$refs.bar.stop();
                            this.isLoading = false;
                            Toast.create.warning({
                                html: 'Laskentoja ei ei löytynyt parametreilla.',
                                icon: 'error_outline',
                                timeout: 20000
                            });

                            reject();
                        }
                    }, (response) => {
                        this.$refs.bar.stop();
                        this.isLoading = false;
                        Toast.create.warning({
                            html: 'Laskentoja ei pystytty hakemaan.',
                            icon: 'error_outline',
                            timeout: 20000
                        });

                        reject();
                    });
                } else {
                    Dialog.create({
                        title: 'Virhe',
                        message: 'Selataksesi laskentoja sinun täytyy valita sekä laskentavuosi että laskentakausi.',
                        buttons: [
                            'Ok'
                        ]
                    });

                    reject();
                }
            });
        },
        loadCount (id) {
            this.$root.$emit('openCount', id);
        },
        refresh(done) {
            this.loadCounts().then(() => {
                done();
            }, () => {
                done();
            });
        }
    },
    mounted () {

    },
    beforeDestroy () {

    }
}
</script>

<style>
    .q-picker-textfield + .q-picker-textfield {
        margin-left: 10px;
    }

    .modal-content {
      width: 100vw;
      max-width: 90vw !important;
    }

    .q-data-table {
        margin-bottom: 20px;
    }

    .q-table {
      width: 100%;
    }
</style>
