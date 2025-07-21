<template>
  <div class="deleteContainer">
    <h3>Excluir Setores</h3>
    <table v-if="setores.length > 0" class="setorTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Descrição</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="setor in setores" :key="setor.id">
          <td>{{ setor.id }}</td>
          <td>{{ setor.descricao}}</td>
          <td>
            <button @click="confirmDel(setor.id)">Deletar</button>
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
  setores: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["cancelar", "setorDeletado"]);

const mensagem = ref("");
const mensagemTipo = ref("");

async function confirmDel(setorId) {
  const confirmacao = confirm("Tem certeza que deseja excluir este setor?");
  if (!confirmacao) return;

  try {
    await axios.delete(`http://127.0.0.1:8000/api/setor/del/${setorId}`);
    mensagem.value = "Setor deletado!";
    mensagemTipo.value = "sucesso";

    emit("setorDeletado", setorId);

  } catch (error) {
    mensagem.value =
      error.response?.data?.message || "Erro ao excluir o setor.";
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
