<script>
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import NotFounded from './components/NotFounded.vue';

const routes = {
    // '/': Home,
    '/login': Login,
    '/dashboard': Dashboard,
};

export default {
  data() {
    return {
      currentPath: window.location.pathname,
      id: localStorage.getItem('id'),
      tokenName: localStorage.getItem('tokenName')
    }
  },
  computed: {
    currentView() {
      return routes[this.currentPath || '/'] || NotFounded
    }
  },
  mounted() {
    window.addEventListener('hashchange', () => {
		  this.currentPath = window.location.pathname
		})
  }, methods: {
    logout () {
      localStorage.clear();
      window.location.href = '/login'
    }   
  }
}
</script>

<template>
    <header v-if="currentPath != '/login'">
        <a v-if="!tokenName" href="/login"><button>Login</button></a>
        <button v-if="tokenName" @click="logout">Logout</button>
    </header>
    <component :is="currentView" />
</template>

<style scoped>

</style>
