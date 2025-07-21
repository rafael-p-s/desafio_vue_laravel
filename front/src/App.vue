<template>
  <header>
    <h1 class="headerH1">Desafio: Empresa + Setor</h1>
  </header>
  <!--  -->
  <div class="bodyCSS">
    <div class="container">
      <Button
        btnStyle="btnMain"
        btnText="Add Empresa"
        @click="showInputEmpresa"
      />
      <Button btnStyle="btnMain" btnText="Add Setor" @click="showInputSetor" />
    </div>
    <div class="container">
      <InputSetor v-if="mostrarSetor" @cancelar="ocultarInputSetor" />
      <inputEmpresa v-if="mostrarEmpresa" @cancelar="ocultarInputEmpresa" />
    </div>
    <!--  -->

    <div class="container">
      <Button
        v-if="empresas.length > 0"
        btnStyle="btnMain"
        btnText="Editar Empresa"
        @click="showEditEmpresa"
      />
      <Button
        v-if="setores.length > 0"
        btnStyle="btnMain"
        btnText="Editar Setor"
        @click="showEditSetor"
      />
    </div>
    <div class="container">
      <UpdateEmpresa
        v-if="mostrarEditEmpresa"
        :empresas="empresas"
        @cancelar="ocultarEditEmpresa"
      />
      <UpdateSetor
        v-if="mostrarEditSetor"
        :setores="setores"
        @cancelar="ocultarEditSetor"
      />
    </div>
    <!--  -->
    <div class="container">
      <Button
        v-if="empresas.length > 0"
        btnStyle="btnMain"
        btnText="Deletar Empresa"
        @click="showDelEmpresa"
      />
      <Button
        v-if="setores.length > 0"
        btnStyle="btnMain"
        btnText="Deletar Setor"
        @click="showDelSetor"
      />
    </div>
    <div class="container">
      <DeleteEmpresa
        v-if="mostrarDelEmpresa"
        :empresas="empresas"
        @cancelar="ocultarDelEmpresa"
        @empresaDeletada="handleEmpresaDeletada"
      />
      <DeleteSetor
        v-if="mostrarDelSetor"
        :setores="setores"
        @cancelar="ocultarDelSetor"
        @setorDeletado="handleSetorDeletado"
      />
    </div>
    <!--  -->

    <div class="container">
      <Button
        v-if="empresas.length > 0 && setores.length > 0"
        btnStyle="btnMain"
        btnText="Vincular Empresa + Setor"
        @click="showEmpresaSetor"
      />
    </div>
    <div class="container">
      <EmpresaSetor
        v-if="mostrarEmpresaSetor"
        :empresas="empresas"
        :setores="setores"
        @cancelar="ocultarEmpresaSetor"
      />
    </div>
  </div>
  <div style="margin-top: 2rem">
    <EmpresaSetores />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Button from "./components/button.vue";
import InputSetor from "./components/inputSetor.vue";
import inputEmpresa from "./components/inputEmpresa.vue";
import UpdateSetor from "./components/updateSetor.vue";
import UpdateEmpresa from "./components/updateEmpresa.vue";
import EmpresaSetor from "./components/EmpresaSetor.vue";
import DeleteEmpresa from "./components/deleteEmpresa.vue";
import DeleteSetor from "./components/deleteSetor.vue";
import EmpresaSetores from "./components/EmpresaSetores.vue";

const mostrarSetor = ref(false);
const mostrarEmpresa = ref(false);
const mostrarEditEmpresa = ref(false);
const mostrarEditSetor = ref(false);
const mostrarEmpresaSetor = ref(false);
const mostrarDelEmpresa = ref(false);
const mostrarDelSetor = ref(false);

const empresas = ref([]);
const setores = ref([]);

function showInputSetor() {
  mostrarEmpresa.value = false;
  mostrarEditEmpresa.value = false;
  mostrarEditSetor.value = false;
  mostrarEmpresaSetor.value = false;
  mostrarDelEmpresa.value = false;
  mostrarDelSetor.value = false;

  mostrarSetor.value = true;
}

function ocultarInputSetor() {
  mostrarSetor.value = false;
}

function showInputEmpresa() {
  mostrarSetor.value = false;
  mostrarEditEmpresa.value = false;
  mostrarEditSetor.value = false;
  mostrarEmpresaSetor.value = false;
  mostrarDelEmpresa.value = false;
  mostrarDelSetor.value = false;

  mostrarEmpresa.value = true;
}

function ocultarInputEmpresa() {
  mostrarEmpresa.value = false;
}

function showEditEmpresa() {
  mostrarEmpresa.value = false;
  mostrarSetor.value = false;
  mostrarEditSetor.value = false;
  mostrarEmpresaSetor.value = false;
  mostrarDelEmpresa.value = false;
  mostrarDelSetor.value = false;

  mostrarEditEmpresa.value = true;
}

function ocultarEditEmpresa() {
  mostrarEditEmpresa.value = false;
}

function showEditSetor() {
  mostrarEmpresa.value = false;
  mostrarSetor.value = false;
  mostrarEmpresaSetor.value = false;
  mostrarEditEmpresa.value = false;
  mostrarDelEmpresa.value = false;
  mostrarDelSetor.value = false;

  mostrarEditSetor.value = true;
}

function ocultarEditSetor() {
  mostrarEditSetor.value = false;
}

function showDelEmpresa() {
  mostrarEmpresa.value = false;
  mostrarSetor.value = false;
  mostrarEmpresaSetor.value = false;
  mostrarEditSetor.value = false;
  mostrarDelSetor.value = false;
  mostrarEmpresaSetor.value = false;

  mostrarDelEmpresa.value = true;
}

function ocultarDelEmpresa() {
  mostrarDelEmpresa.value = false;
}

function showDelSetor() {
  mostrarEmpresa.value = false;
  mostrarSetor.value = false;
  mostrarEmpresaSetor.value = false;
  mostrarEditSetor.value = false;
  mostrarEmpresaSetor.value = false;
  mostrarDelEmpresa.value = false;

  mostrarDelSetor.value = true;
}

function ocultarDelSetor() {
  mostrarDelSetor.value = false;
}

function showEmpresaSetor() {
  mostrarEmpresa.value = false;
  mostrarSetor.value = false;
  mostrarEmpresaSetor.value = false;
  mostrarEditSetor.value = false;
  mostrarDelEmpresa.value = false;
  mostrarDelSetor.value = false;

  mostrarEmpresaSetor.value = true;
}

function ocultarEmpresaSetor() {
  mostrarEmpresaSetor.value = false;
}

const mensagem = ref("");
const mensagemTipo = ref("");

async function checkEmpresas() {
  try {
    const res = await axios.get("http://127.0.0.1:8000/api/empresas");
    empresas.value = res.data;
  } catch (error) {
    const msg = error.response?.data?.message || "Erro ao buscar empresas.";
    mensagem.value = msg;
    mensagemTipo.value = "erro";
  }
}

async function checkSetores() {
  try {
    const res = await axios.get("http://127.0.0.1:8000/api/setores");
    setores.value = res.data;
  } catch (error) {
    console.error("Erro ao buscar setores:", error);
  }
}

function handleEmpresaDeletada(id) {
  empresas.value = empresas.value.filter((e) => e.id !== id);
}

function handleSetorDeletado(id) {
  setores.value = setores.value.filter((s) => s.id !== id);
}

onMounted(() => {
  checkEmpresas();
  checkSetores();
});
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 2rem;
}
</style>
