<template>
  <div class="deleteContainer">
    <h3>Excluir Empresas</h3>
    <table v-if="empresas.length > 0" class="empresaTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Razão Social</th>
          <th>Nome Fantasia</th>
          <th>CNPJ</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="empresa in empresas" :key="empresa.id">
          <td>{{ empresa.id }}</td>
          <td>{{ empresa.razao_social }}</td>
          <td>{{ empresa.nome_fantasia }}</td>
          <td>{{ empresa.cnpj }}</td>
          <td>
            <button @click="confirmDel(empresa.id)">Deletar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-if="mensagem" :class="mensagemTipo">{{ mensagem }}</p>

    <Button
      btnStyle="btnCancel"
      btnText="Cancelar"
      @click="$emit('cancelar')"
    />
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import Button from "./button.vue";

const props = defineProps({
  empresas: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["cancelar", "empresaDeletada"]);

const mensagem = ref("");
const mensagemTipo = ref("");

async function confirmDel(empresaId) {
  const confirmacao = confirm("Tem certeza que deseja excluir esta empresa?");
  if (!confirmacao) return;

  try {
    await axios.delete(`http://127.0.0.1:8000/api/empresa/del/${empresaId}`);
    mensagem.value = "Empresa deletada!";
    mensagemTipo.value = "sucesso";

    emit("empresaDeletada", empresaId);

  } catch (error) {
    mensagem.value =
      error.response?.data?.message || "Erro ao excluir a empresa.";
    mensagemTipo.value = "erro";
  }
}
</script>

<style scoped>
.deleteContainer {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}
.empresaTable {
  border-collapse: collapse;
  width: 100%;
  margin-top: 1rem;
}
.empresaTable th,
.empresaTable td {
  border: 1px solid #ccc;
  padding: 0.5rem;
  text-align: left;
}
p.sucesso {
  color: green;
}
p.erro {
  color: red;
}
</style>
