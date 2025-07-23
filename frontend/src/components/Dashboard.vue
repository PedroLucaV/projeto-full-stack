<script setup>
import { ref } from 'vue';

const tokenName = localStorage.getItem('tokenName');
if(!tokenName){
    window.location.href = '/'
}
</script>

<template>
    <div v-if="tokenName == 'admin_token'">
        <p>Area do Admin</p>
    </div>
    <div v-if="tokenName == 'user_token'">
        <p>Area do Usuario</p>
    </div>

    <!--    Se Admin:
                Todos os Equipamentos
                Botão Criar Equipamentos que Abre Modal para Criação de Equipamento
                Ao dar hover no equipamento, aparece a opção de editar localização
                Aparece 3 Opções de Estatisticas: Reservas, Membros e Equipamentos
            Se Usuario:
                Todos Equipamentos
                Ao dar Hover no equipamento, aparece a opção de reservar
                Reservas Ativas
                Historico de Reservas
    -->
</template>

<style>

</style>