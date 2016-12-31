<template>
    <q-modal ref="countModal" class="minimized">
      <div class="card" style="margin-bottom: 0;">
          <div class="card-title bg-primary text-white">Laskennan tiedot</div>
          <div class="card-content list no-border highlight">
              <div class="item two-lines">
                  <i class="item-primary">location_on</i>
                  <div class="item-content">
                      <div class="item-title">Sijanti</div>
                      <div>{{ country }}, {{ city }}</div>
                  </div>
              </div>
              <div class="item two-lines">
                  <i class="item-primary">date_range</i>
                  <div class="item-content">
                      <div class="item-title">Ajankohta</div>
                      <div>{{ date }} - Laskentakausi 3</div>
                  </div>
              </div>
              <div class="item two-lines">
                  <i class="item-primary">show_chart</i>
                  <div class="item-content">
                      <div class="item-title">Yksilömäärä</div>
                      <div>{{ totalCount }}</div>
                  </div>
              </div>
              <table class="q-table striped-odd responsive">
                <thead>
                  <tr>
                    <th>Laji (suom.)</th>
                    <th>Laji</th>
                    <th>YksilömääräBiotooppi</th>
                    <th>Yksilömäärä</th>
                    <th>YksilömääräSukupuolet</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="unit in units">
                    <td data-th="Laji (suom.)">{{ unit.name }}</td>
                    <td data-th="Laji">{{ unit.latinName }}</td>
                    <td data-th="YksilömääräBiotooppi">
                        <span v-for="bio in unit.countBioF" style="display: block;">{{ bio.count }} ({{ bio.name }})</span>
                    </td>
                    <td data-th="Yksilömäärä">{{ unit.count }}</td>
                    <td data-th="YksilömääräSukupuolet">{{ unit.countGender }}</td>
                  </tr>
                  <!--<tr>
                    <td data-th="Laji (suom.)">Tukkasotka</td>
                    <td data-th="Laji">Aythya fuligula</td>
                    <td data-th="YksilömääräBiotooppiF">21</td>
                    <td data-th="Yksilömäärä">21</td>
                    <td data-th="YksilömääräSukupuolet">3/1</td>
                  </tr>
                  <tr>
                    <td data-th="Laji (suom.)">Tukkasotka</td>
                    <td data-th="Laji">Aythya fuligula</td>
                    <td data-th="YksilömääräBiotooppiF">21</td>
                    <td data-th="Yksilömäärä">21</td>
                    <td data-th="YksilömääräSukupuolet">3/1</td>
                  </tr>
                  <tr>
                    <td data-th="Laji (suom.)">Tukkasotka</td>
                    <td data-th="Laji">Aythya fuligula</td>
                    <td data-th="YksilömääräBiotooppiF">21</td>
                    <td data-th="Yksilömäärä">21</td>
                    <td data-th="YksilömääräSukupuolet">3/1</td>
                  </tr>
                  <tr>
                    <td data-th="Laji (suom.)">Tukkasotka</td>
                    <td data-th="Laji">Aythya fuligula</td>
                    <td data-th="YksilömääräBiotooppiF">21</td>
                    <td data-th="Yksilömäärä">21</td>
                    <td data-th="YksilömääräSukupuolet">3/1</td>
                  </tr>
                  <tr>
                    <td data-th="Laji (suom.)">Tukkasotka</td>
                    <td data-th="Laji">Aythya fuligula</td>
                    <td data-th="YksilömääräBiotooppiF">21</td>
                    <td data-th="Yksilömäärä">21</td>
                    <td data-th="YksilömääräSukupuolet">3/1</td>
                  </tr>
                  <tr>
                    <td data-th="Laji (suom.)">Tukkasotka</td>
                    <td data-th="Laji">Aythya fuligula</td>
                    <td data-th="YksilömääräBiotooppiF">21</td>
                    <td data-th="Yksilömäärä">21</td>
                    <td data-th="YksilömääräSukupuolet">3/1</td>
                  </tr>
                  <tr>
                    <td data-th="Laji (suom.)">Tukkasotka</td>
                    <td data-th="Laji">Aythya fuligula</td>
                    <td data-th="YksilömääräBiotooppiF">21</td>
                    <td data-th="Yksilömäärä">21</td>
                    <td data-th="YksilömääräSukupuolet">3/1</td>
                  </tr>
                  <tr>
                    <td data-th="Laji (suom.)">Tukkasotka</td>
                    <td data-th="Laji">Aythya fuligula</td>
                    <td data-th="YksilömääräBiotooppiF">21</td>
                    <td data-th="Yksilömäärä">21</td>
                    <td data-th="YksilömääräSukupuolet">3/1</td>
                  </tr>-->
                </tbody>
              </table>
          </div>
      </div>
    </q-modal>
</template>

<script>
    import { Utils, Toast } from 'quasar';
    import birdNames from './../assets/latin-fin.json';

    export default {
      data () {
        return {
            xml: null,
            country: '',
            city: '',
            date: '',
            totalCount: 0,
            units: []
        }
    },
    computed: {

    },
    methods: {
        parseXML() {
            let namedAreas = this.xml.getElementsByTagName('NamedArea');

            var len = namedAreas.length;
            for (let i = 0; i < len; i++) {
                if (namedAreas[i].childNodes[0].innerHTML === 'Country') {
                    this.country = namedAreas[i].childNodes[1].innerHTML;
                } else if (namedAreas[i].childNodes[0].innerHTML === 'County') {
                    this.city = namedAreas[i].childNodes[1].innerHTML;
                }
            }

            let date = this.xml.getElementsByTagName('DateText');
            date = date[0].innerHTML.slice(0, date[0].innerHTML.indexOf('T'));
            date = new Date(date).toLocaleString('fi-FI');
            this.date = date.slice(0, date.indexOf(' '));

            let units = this.xml.getElementsByTagName('Unit');
            var unitsLen = units.length;

            var totalCount = 0;

            for (let i = 0; i < unitsLen; i++) {
                var measurementData = {
                    name: 'N/A',
                    latinName: '',
                    countBioF: [],
                    count: 0,
                    countGender: 'N/A'
                }

                var measurements = units[i].children[2];

                for (let j = 0; j < measurements.childElementCount; j++) {
                    if (measurements.children[j].children[0].children[0].innerHTML === 'InformalNameString') {
                        measurementData.latinName = measurements.children[j].children[0].children[1].innerHTML;
                        if (birdNames.hasOwnProperty(measurementData.latinName)) {
                            measurementData.name = birdNames[measurementData.latinName];
                        }
                    } else if (measurements.children[j].children[0].children[0].innerHTML === 'Yksilömäärä') {
                        totalCount += parseInt(measurements.children[j].children[0].children[1].innerHTML);
                        measurementData.count = parseInt(measurements.children[j].children[0].children[1].innerHTML);
                    } else if (measurements.children[j].children[0].children[0].innerHTML.includes('YksilömääräBiotooppi')) {
                        let tmp = {
                            name: measurements.children[j].children[0].children[0].innerHTML,
                            count: parseInt(measurements.children[j].children[0].children[1].innerHTML)
                        };

                        measurementData.countBioF.push(tmp);
                    } else if (measurements.children[j].children[0].children[0].innerHTML === 'Yksilömäärä') {
                        measurementData.countGender = measurements.children[j].children[0].children[1].innerHTML;
                    }
                }

                this.units.push(measurementData);
            }

            this.totalCount = totalCount;
        }
    },
    mounted () {
        this.$root.$on('openCount', (id) => {
            this.$http.get('http://localhost/selain/census.php?id=' + id).then((response) => {
                let parser = new DOMParser();
                this.xml = parser.parseFromString(response.body, 'text/xml');
                this.parseXML();
            }, (response) => {
                Toast.create.warning({
                    html: 'Laskennan tietoja ei pystytty hakemaan.',
                    icon: 'error_outline',
                    timeout: 20000
                });
            });

            this.$refs['countModal'].open();
        });
    },
    beforeDestroy () {

    }
}
</script>

<style>
    
</style>
