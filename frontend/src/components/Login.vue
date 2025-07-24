<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

const email = ref(null);
const senha = ref(null);
const showSenha = ref(false);

const url = 'http://localhost:8000/api/auth/login';
const submit = async () => {
  try {
    const body= {email: email.value, senha: senha.value}
    const res = (await axios.post(url, body));
    const data = await res.data;
    localStorage.setItem('tokenName', data.token_name);
    localStorage.setItem('token', data.token);
    location.href = '/dashboard';
  } catch (error) {
    console.error(error);
    
  }
}
</script>

<template>
    <main>
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label for="email" class="form-label">Endereço de Email</label>
          <input v-model="email" type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input v-model="senha" type="password" name="senha" class="form-control" id="senha">
        </div>
        <div class="mb-3 form-check">
          <input v-model="showSenha" type="checkbox" class="form-check-input" id="exampleCheck1">
          <label  class="form-check-label" for="exampleCheck1">Mostrar Senha</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </main>
</template>

<style scoped>
main{
  display: flex;
  width: 100vw;
  height: 100vh;
  align-items: center;
  justify-content: center;
}
form{
  display: flex;
  background-color: #e0dfdf;
  padding: 1rem;
  border-radius: 15px;
  flex-direction: column;
  max-width: 500px;
  min-width: 300px;
}
</style>
