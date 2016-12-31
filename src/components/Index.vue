<template>
    <q-layout>

    <!--
      Replace following "div" with
      "<router-view class="layout-view">" component
      if using subRoutes
  -->
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
            </div>
        </div>
    </q-layout>
</template>

<script>
    import Quasar, { Utils, Dialog } from 'quasar'

    export default {
      data () {
        return {
            selectedYear: '',
            selectedCount: '',
            yearsOptions: [
                {
                    label: '2014',
                    value: '2014'
                },
                {
                    label: '2015',
                    value: '2015'
                },
                {
                    label: '2016',
                    value: '2016'
                },
                {
                    label: '2017',
                    value: '2017'
                }
            ],
            countOptions: [
                {
                    label: '1',
                    value: 1
                },
                {
                    label: '2',
                    value: 2
                },
                {
                    label: '3',
                    value: 3
                }
            ]
        }
    },
    computed: {

    },
    methods: {
        loadCounts () {
            if (this.selectedYear !== '' && this.selectedCount !== '') {
                this.$router.push('/show-counts/' + this.selectedYear + '/' + this.selectedCount);
            } else {
                Dialog.create({
                    title: 'Virhe',
                    message: 'Selataksesi laskentoja sinun täytyy valita sekä laskentavuosi että laskentakausi.',
                    buttons: [
                        'Ok'
                    ]
                })
            }            
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
</style>
