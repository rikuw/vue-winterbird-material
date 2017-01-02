<template>
  <div>
    <q-layout>
        <div class="layout-view">
            <div class="layout-padding">
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
    import { Platform, Utils, Loading, Toast } from 'quasar'
    import Count from './Count.vue'

    export default {
      components: {
        'Count': Count
      },
      data () {
        return {
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
                    let a = '' + value;
                    let b = a.slice(0, 4);
                    let c = a.slice(4, 6);
                    let d = a.slice(6, 8);

                    return new Date(b + '-' + c + '-' + d).toLocaleString()
                  },
                  width: '120px'
                },
                {
                  label: 'Kunta',
                  field: 'municipality',
                  sort: true,
                  width: '500px'
                },
                {
                    label: 'Reittinumero',
                    field: 'route',
                    sort: true,
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

    },
    methods: {
        loadCount (id) {
            this.$root.$emit('openCount', id);
        },

        refresh () {

        }
    },
    mounted () {
        Loading.show({
            message: 'Haetaan laskentoja...',
            messageColor: '#ffffff',
            spinnerSize: 250
        });

        let year = this.$route.params.year;
        let count = this.$route.params.count;

        this.$http.get('http://localhost/selain/list.php?year=' + year + '&count=' + count).then((response) => {
            if (response.body.length !== 0) {
                this.counts = response.body;
                Loading.hide();
            } else {
                Loading.hide();
                Toast.create.warning({
                    html: 'Laskentoja ei ei löytynyt parametreilla.',
                    icon: 'error_outline',
                    timeout: 20000
                });
            }            
        }, (response) => {
            Loading.hide();
            Toast.create.warning({
                html: 'Laskentoja ei pystytty hakemaan.',
                icon: 'error_outline',
                timeout: 20000
            });
        });
    },
    beforeDestroy () {

    }
}
</script>

<style>
    .modal-content {
      width: 100vw;
      max-width: 90vw !important;
    }

    .q-table {
      width: 100%;
    }
</style>
