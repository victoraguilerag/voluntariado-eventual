<template>
    <div>
      <div class="alert alert-warning" v-show='borro'>
        <strong>Te has desinscrito satisfactoriamente de la actividad.</strong>
      </div>
      <div class="alert alert-warning" v-show='!inscripciones.length'>
        <strong>Todavia no estas inscripto a ninguna actividad.</strong>
      </div>
        <div class="row">
            <tarjeta
                v-for="inscripcion in inscripciones"
                v-bind:inscripcion="inscripcion"
                v-bind:key="inscripcion.idInscripcion"
            >
            </tarjeta>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Tarjeta from './tarjeta';

    export default {
        name: "mis-inscripciones",

        data () {
            return {
                act: '',
                inscripciones: [],
                borro: false
            }
        },
        components: {tarjeta: Tarjeta},
        mounted: function() {
            this.traer_inscripciones()
        },
        methods: {
            traer_inscripciones: function() {
                axios.get('/ajax/usuario/inscripciones?date=')
                    .then(response => {
                    this.inscripciones = response.data.data
                })
            }
        }

    }
</script>

<style>

</style>