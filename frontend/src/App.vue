<script>
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import Home from './components/Home.vue';
import NotFounded from './components/NotFounded.vue';
import axios from 'axios';

const routes = {
    // '/': Home,
    '/login': Login,
    '/dashboard': Dashboard,
    '/': Home
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
      try {
        axios.post('http://localhost:8000/api/auth/logout', null, {headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }});
        localStorage.clear();
        window.location.href = '/login'
      } catch (error) {
        console.error(error);
        
      }
    }   
  }
}
</script>

<template>
    <header v-if="currentPath != '/login'">
        <h1>Força Total</h1>
        <ul class="utility-wrapper list-group list-group-flush">
          <li class="list-group-item" v-if="!tokenName"><a href="/login"><button>Login</button></a></li>
          <li class="list-group-item" v-if="tokenName"><button @click="logout">Logout</button></li>
        </ul>
    </header>
    <component :is="currentView" />
</template>

<style scoped>
  header{
    display: flex;
    width: 100%;
    height: 80px;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
  }
</style>
