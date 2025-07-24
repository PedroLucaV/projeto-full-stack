<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

const email = ref(null);
const senha = ref(null);

const sto = () => {
  localStorage.setItem('tokenName', 'user_token');
  location.href = '/dashboard';
}

onMounted(async () => {
  try {
    const res = await axios.get('http://localhost:8000/api', {method:"GET"})
    console.log(res);
    
  } catch (error) {
    console.error(error);
    
  }
})
</script>

<template>
    <form @submit.prevent="default" @submit="sto">
      <label for="email">Email :</label>
      <input v-model="email" name="email" id="email" placeholder="Digite seu Email" type="text">
      <label for="senha">Senha</label>
      <input name="senha" id="senha" placeholder="Digite sua Senha" type="text">
      <button @click="alert" id="brn">Enviar</button>
    </form>
</template>

<style scoped>
form{
  display: flex;
  flex-direction: column;
  max-width: 500px;
  min-width: 250px;
}
</style>
