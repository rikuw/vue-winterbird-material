<template>
  <!-- Don't drop "q-app" class -->
  <div id="q-app">
  	  <q-layout>
  	      <div slot="header" class="toolbar">
  	          <q-toolbar-title :padding="1">
  	              Lintujen talviseuranta Suomessa
  	          </q-toolbar-title>
  	      </div>

  	  <!--
  	    Replace following "div" with
  	    "<router-view class="layout-view">" component
  	    if using subRoutes
  	-->
  	      <router-view></router-view>
  	  </q-layout>
  </div>
</template>

<script>
/*
 * Root component
 */
export default {}
</script>

<style></style>
