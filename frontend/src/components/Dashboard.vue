<script setup>
import axios from 'axios';
import { onBeforeMount, ref } from 'vue';


const equipamentos = ref(null);
const membros = ref(null);
const section = ref('Equipamentos');
//equipamento
const nome = ref(null);
const marca = ref(null);
const modelo = ref(null);
const ano_de_fabricacao = ref(null);
const numero_de_serie = ref(null);
const capacidade_maxima = ref(null);
const localizacao = ref(null);
const estado = ref(null);
const miss = ref(false)

//membro
const nomeM = ref(null)
const cpf = ref(null)
const email = ref(null)
const data_de_nascimento = ref(null)
const endereco = ref(null)
const telefone = ref(null)
const data_de_matricula = ref(null)
const plano_de_contrato = ref(null)

const tokenName = localStorage.getItem('tokenName');
if(!tokenName){
    window.location.href = '/'
}

const submitEquipamento = async () => {
    try {
    const body = {};
    const res = (await axios.post(url, body));
    const data = await res.data;
  } catch (error) {
    console.error(error); 
  }
}

const submitMembro = async () => {
    if(!nomeM.value || !cpf.value || !email.value || !data_de_nascimento.value || !endereco.value || !telefone.value || !data_de_matricula.value || !plano_de_contrato.value){
        miss.value = true;
        return true
    }
    const cpfF = `${cpf.value.split('')[0]}${cpf.value.split('')[1]}${cpf.value.split('')[2]}.${cpf.value.split('')[3]}${cpf.value.split('')[4]}${cpf.value.split('')[5]}.${cpf.value.split('')[6]}${cpf.value.split('')[7]}${cpf.value.split('')[8]}-${cpf.value.split('')[9]}${cpf.value.split('')[10]}`;
    
    
    try {
    const body = {
        nome: nomeM.value,
        cpf: cpfF,
        email: email.value,
        data_de_nascimento: data_de_nascimento.value,
        endereco: endereco.value,
        telefone: telefone.value,
        data_de_matricula: data_de_matricula.value,
        plano_de_contrato: plano_de_contrato.value
    };
    const res = (await axios.post('http://localhost:8000/api/membros/', body, {headers : {
        Authorization: `Bearer ${localStorage.getItem('token')}`
    }}));
    const data = await res.data;
  } catch (error) {
    console.error(error); 
  }
}

onBeforeMount(async () => {
    try {
    const resE = await axios.get('http://localhost:8000/api/equipamentos', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
        }
});

const dataE = await resE.data.data;
equipamentos.value = dataE

const resM = await axios.get('http://localhost:8000/api/membros', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
        }
});

const dataM = await resM.data.data;
membros.value = dataM

} catch (error) {
    console.error(error);
    
}
})

</script>

<template>
    <div class="modal fade" aria-hidden="true" id="equiModal">
        <form class="modal-dialog form" @submit.prevent="submitEquipamento">
            <input type="text" v-model="nome">
            <input type="text" v-model="marca">
            <input type="text" v-model="modelo">
            <input type="text" v-model="ano_de_fabricacao">
            <input type="text" v-model="numero_de_serie">
            <input type="text" v-model="capacidade_maxima">
            <input type="text" v-model="localizacao">
            <input type="text" v-model="estado">
            <button type="submit">Enviar</button>
        </form>
    </div>
    <div class="modal fade" aria-hidden="true" id="membroModal">
        <div class="modal-dialog">
            <form class="modal-content form" @submit.prevent="submitMembro">
            <h1 class="text-center modal-content">Criação de Membros</h1>
            <div class="modal-content">
                <div class="formMa">
                    <span class="d-flex flex-column"><input type="text" v-model="nomeM" placeholder="Nome"> <p class="text-danger" v-if="miss.valueOf() && !nomeM">Insira um Nome</p></span>
                    <span class="d-flex flex-column"><input type="text" v-model="cpf" placeholder="CPF"><p class="text-danger" v-if="miss.valueOf() && !cpf">Insira um CPF</p></span>
                </div>
                <div class="formMa">
                    <span class="d-flex flex-column"><input type="text" v-model="email" placeholder="Email"><p class="text-danger" v-if="miss.valueOf() && !nomeM">Insira um Email</p></span>
                    <span class="d-flex flex-column"><input type="date" v-model="data_de_nascimento" placeholder="Data de Nascimento"><p class="text-danger" v-if="miss.valueOf() && !email">Insira uma Data de Nascimento</p>
                    
                    </span>
                </div>
                <div class="formMa">
                    <span class="d-flex flex-column"><input type="text" v-model="endereco" placeholder="Endereço"><p class="text-danger" v-if="miss.valueOf() && !endereco">Insira um Endereço</p></span>
                    <span class="d-flex flex-column"><input type="tel" v-model="telefone" placeholder="Telefone"><p class="text-danger" v-if="miss.valueOf() && !telefone">Insira um Telefone</p></span>
                </div>
                <div class="formMa">
                    <span class="d-flex flex-column"><input type="date" v-model="data_de_matricula" placeholder="data_de_matricula"><p class="text-danger" v-if="miss.valueOf() && !data_de_matricula">Insira uma Data de Matricula</p></span>
                    <span class="d-flex flex-column">
                        <select v-model="plano_de_contrato">
                            <option value="PLUS">PLUS</option>
                            <option value="PRO">PRO</option>
                            <option value="OMEGA">OMEGA</option>
                        </select>
                        <p class="text-danger" v-if="miss.valueOf() && !plano_de_contrato">Insira um Plano de Contrato</p>
                    </span>
                </div>
            </div>
            <button type="submit">Criar Memrbo</button>
        </form>
        </div>
    </div>
    <main v-if="tokenName == 'admin_token'">
        <h1>Dashboard Admin</h1>
        <div class="border-bottom border-top d-flex mb-3 mt-1 flex-row justify-content-center align-items-center gap-5 border-5 border-primary p-3 w-100 text-center">
            <h2>Todos os {{ section }}</h2>
            <select name="Secao" v-model="section" id="">
                <option value="Equipamentos" default>Equipamentos</option>
                <option value="Membros">Membros</option>
            </select>

            <button v-if="section == 'Membros'" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#membroModal">
                Criar Membros
            </button>
            <button v-if="section == 'Equipamentos'" data-bs-toggle="modal" data-bs-target="#equiModal" class="btn btn-primary">
                Criar Equipamento
            </button>
        </div>
        <section v-if="section == 'Equipamentos'" class="section">
            <article class="border border-4 border-primary p-3" v-for="equipamento in equipamentos">
                <h2 class="fs-4">Nome: {{ equipamento.nome }}</h2>
                <h3 class="fs-4">Marca: {{ equipamento.marca }}</h3>
                <p>Localização: {{ equipamento.localizacao }}</p>
            </article>
        </section>
        <section v-if="section == 'Membros'" class="section">
            <article class="border border-4 border-primary p-3" v-for="membro in membros">
                <h2 class="fs-4">Nome: {{ membro.nome }}</h2>
                <h3 class="fs-4">Marca: {{ membro.plano_de_contrato }}</h3>
                <p>Localização: {{ membro.cpf }}</p>
            </article>
        </section>
    </main>
    <main v-if="tokenName == 'user_token'">
        <h1>Dashboard Usuario</h1>
    </main>

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
    main{
        width: 100vw;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .section{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        align-items: center;
        justify-content: center;
        max-width: 1440px;
        gap: 2rem;
        min-width: 300px;
    }

    .equipamentos>article{
        max-width: 300px;
    }

    .text-danger{
        margin: 0 !important;
    }

    form{
        display: flex !important;
        background-color: #e0dfdf;
        padding: 1rem !important;
        gap: 1rem !important;
        border-radius: 15px;
        flex-direction: column !important;
        max-width: 500px !important;
        gap: 1rem !important;
    }

    form>div, .modal-content{
        display: flex !important;
        flex-direction: column;
        gap: 1rem;
        justify-content: space-between !important;
        background-color: #e0dfdf !important;
        border: none !important;
    }

    form>div>div{
        display: flex;
        width: 100%;
        justify-content: space-around;
        gap: 1rem;
    }

    .formMa>span, .formMa>select{
        width: 50%;
    }

    @media screen and (max-width: 750px) {
        .equipamentos{
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media screen and (max-width: 400px) {
        .equipamentos{
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>