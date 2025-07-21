<template>
  <div class="containerVincular">
    <form @submit.prevent="submitVinculo">
      <div class="selectContainer">
        <div>
          <label for="empresa">Empresa:</label>
          <select
            id="empresa"
            v-model="selectedEmpresaId"
            @change="fetchSetoresDisponiveis"
          >
            <option disabled value="">-- Selecione uma empresa --</option>
            <option v-for="emp in empresas" :key="emp.id" :value="emp.id">
              {{ emp.razao_social }}
            </option>
          </select>
        </div>

        <div>
          <label for="setor">Setor:</label>
          <select
            id="setor"
            v-model="selectedSetorId"
            :disabled="setoresDisponiveis.length === 0"
          >
            <option disabled value="">-- Selecione um setor --</option>
            <option
              v-for="setor in setoresDisponiveis"
              :key="setor.id"
              :value="setor.id"
            >
              {{ setor.descricao }}
            </option>
          </select>
          <div v-if="setoresDisponiveis.length === 0 && selectedEmpresaId">
            <p style="color: gray">
              Essa Empresa já tem todos os setores vinculados, até o momento.
            </p>
          </div>
        </div>
      </div>

      <div class="botoes">
        <Button
          btnStyle="btnCancel"
          btnText="Cancelar"
          type="button"
          @click="cancelar"
        />
        <Button
          btnStyle="btnSave"
          btnText="Salvar"
          type="submit"
          :disabled="!selectedEmpresaId || !selectedSetorId"
        />
      </div>

      <p v-if="mensagem" :class="mensagemTipo">{{ mensagem }}</p>
    </form>
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

const emit = defineEmits(["cancelar", "salvo"]);

const selectedEmpresaId = ref("");
const selectedSetorId = ref("");
const setoresDisponiveis = ref([]);

const mensagem = ref("");
const mensagemTipo = ref("");

async function fetchSetoresDisponiveis() {
  selectedSetorId.value = "";
  setoresDisponiveis.value = [];

  if (!selectedEmpresaId.value) return;

  try {
    const response = await axios.get(
      `http://127.0.0.1:8000/api/empresa/${selectedEmpresaId.value}/setores-disponiveis`
    );
    setoresDisponiveis.value = response.data;
    mensagem.value = "";
    mensagemTipo.value = "";
  } catch (error) {
    mensagem.value = "Erro ao buscar setores disponíveis.";
    mensagemTipo.value = "erro";
  }
}


// Enviar o vínculo
async function submitVinculo() {
  if (!selectedEmpresaId.value || !selectedSetorId.value) return;

  try {
    await axios.post(
      `http://127.0.0.1:8000/api/empresa/${selectedEmpresaId.value}/vincular-setores`,
      {
        setores: [selectedSetorId.value],
      }
    );

    mensagem.value = "Setor vinculado com sucesso!";
    mensagemTipo.value = "sucesso";
    emit("salvo");

    setTimeout(() => {
      selectedEmpresaId.value = "";
      selectedSetorId.value = "";
      setoresDisponiveis.value = [];
      mensagem.value = "";
    }, 1000);
  } catch (error) {
    mensagem.value = error.response?.data?.message || "Erro ao vincular setor.";
    mensagemTipo.value = "erro";
  }
}

function cancelar() {
  selectedEmpresaId.value = "";
  selectedSetorId.value = "";
  setoresDisponiveis.value = [];
  mensagem.value = "";
  emit("cancelar");
}
</script>

<style scoped>
.containerVincular {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}
.selectContainer {
  display: flex;
  justify-content: center;
  gap: 2rem;
}
.botoes {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}
p.sucesso {
  color: green;
}
p.erro {
  color: red;
}
</style>
