<template>
  <div class="container">
    <h2>Empresas + Setores Vinculados</h2>

    <!-- Filtro por empresa -->
    <label for="filtro">Filtrar por Empresa:</label>
    <select v-model="empresaSelecionadaId" @change="filtrar">
      <option value="">-- Todas as empresas --</option>
      <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
        {{ empresa.razao_social }}
      </option>
    </select>

    <!-- Tabela de vínculos -->
    <table v-if="dadosFiltrados.length > 0" class="vinculoTable">
      <thead>
        <tr>
          <th>ID Empresa</th>
          <th>Razão Social</th>
          <th>Nome Fantasia</th>
          <th>CNPJ</th>
          <th>ID Setor</th>
          <th>Descrição do Setor</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in dadosFiltrados" :key="item.pivot_id">
          <td>{{ item.empresa.id }}</td>
          <td>{{ item.empresa.razao_social }}</td>
          <td>{{ item.empresa.nome_fantasia }}</td>
          <td>{{ item.empresa.cnpj }}</td>
          <td>{{ item.setor.id }}</td>
          <td>{{ item.setor.descricao }}</td>
        </tr>
      </tbody>
    </table>

    <p v-else>Nenhum vínculo encontrado.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const dados = ref([]);
const dadosFiltrados = ref([]);
const empresas = ref([]);
const empresaSelecionadaId = ref("");

// Buscar vínculos
async function fetchVinculos() {
  try {
    const res = await axios.get("http://127.0.0.1:8000/api/empresas-setores");
    dados.value = res.data;
    dadosFiltrados.value = res.data;
  } catch (error) {
    console.error("Erro ao buscar vínculos:", error);
  }
}

// Buscar empresas
async function fetchEmpresas() {
  try {
    const res = await axios.get("http://127.0.0.1:8000/api/empresas");
    empresas.value = res.data;
  } catch (error) {
    console.error("Erro ao buscar empresas:", error);
  }
}

// Filtrar por empresa
function filtrar() {
  if (!empresaSelecionadaId.value) {
    dadosFiltrados.value = dados.value;
    return;
  }

  dadosFiltrados.value = dados.value.filter(
    (item) => item.empresa.id === Number(empresaSelecionadaId.value)
  );
}

onMounted(() => {
  fetchEmpresas();
  fetchVinculos();
});
</script>

<style scoped>
.container {
  padding: 1rem;
}
.vinculoTable {
  border-collapse: collapse;
  width: 100%;
  margin-top: 1rem;
}
.vinculoTable th,
.vinculoTable td {
  border: 1px solid #ccc;
  padding: 0.5rem;
  text-align: center;

}
</style>
