<template>
  <div class="containerUpdate">
    <form @submit.prevent="submitEdit">
      <label for="selectEmpresa">Selecione a empresa para editar:</label>
      <select id="selectEmpresa" v-model="selectedId" @change="loadEmpresa">
        <option disabled value="">--Selecione--</option>
        <option v-for="emp in empresas" :key="emp.id" :value="emp.id">
          {{ emp.razao_social }}
        </option>
      </select>
      <!-- <pre>{{ selectedId }}</pre>
      <pre>{{ selectedEmpresa }}</pre> -->
      <div v-if="selectedEmpresa">
        <div>
          <label for="razao_social">Razão Social:</label>
          <input
            id="razao_social"
            v-model="formData.razao_social"
            type="text"
            required
          />
        </div>
        <div>
          <label for="nome_fantasia">Nome Fantasia:</label>
          <input
            id="nome_fantasia"
            v-model="formData.nome_fantasia"
            type="text"
          />
        </div>
        <div>
          <label for="cnpj">CNPJ:</label>
          <input id="cnpj" v-model="formData.cnpj" type="text" required />
        </div>
      </div>
      <div class="displayBtns">
        <Button
          btnStyle="btnCancel"
          btnText="Cancelar"
          @click="cancelEdit"
          type="button"
        />
        <Button btnStyle="btnSave" btnText="Salvar" type="submit" />
      </div>
      <p v-if="mensagem" :class="mensagemTipo">{{ mensagem }}</p>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, watch } from "vue";
import axios from "axios";
import Button from "./button.vue";

const props = defineProps({
  empresas: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["cancelar", "salvo"]);

const selectedId = ref("");
const selectedEmpresa = ref(null);

const formData = reactive({
  razao_social: "",
  nome_fantasia: "",
  cnpj: "",
});

const mensagem = ref("");
const mensagemTipo = ref("");

function loadEmpresa() {
  const empresa = props.empresas.find((e) => e.id === selectedId.value);

  if (empresa) {
    selectedEmpresa.value = empresa;
    formData.razao_social = empresa.razao_social || "";
    formData.nome_fantasia = empresa.nome_fantasia || "";
    formData.cnpj = empresa.cnpj || "";
    mensagem.value = "";
    mensagemTipo.value = "";
  }
}

function cancelEdit() {
  selectedId.value = "";
  selectedEmpresa.value = null;
  formData.razao_social = "";
  formData.nome_fantasia = "";
  formData.cnpj = "";
  mensagem.value = "";
  emit("cancelar");
}

async function submitEdit() {
  if (!selectedEmpresa.value) return;

  mensagem.value = "";
  mensagemTipo.value = "";

  try {
    await axios.put(
      `http://127.0.0.1:8000/api/empresa/up/${selectedId.value}`,
      {
        razao_social: formData.razao_social.trim(),
        nome_fantasia: formData.nome_fantasia?.trim() || null,
        cnpj: formData.cnpj.trim(),
      }
    );
    mensagem.value = "Empresa atualizada!";
    mensagemTipo.value = "sucesso";
    emit("salvo");

    setTimeout(() => {
      location.reload();
    }, 500);
  } catch (error) {
    const errors = error.response?.data?.errors;
    if (errors) {
      mensagem.value = Object.values(errors).flat().join(" | ");
    } else {
      mensagem.value =
        error.response?.data?.message || "Erro ao atualizar empresa";
    }
    mensagemTipo.value = "erro";
  }
}
</script>

<style scoped>
.containerUpdate {
  display: flex;
  flex-direction: column;
}
.displayBtns {
  margin-top: 1rem;
  display: flex;
  gap: 1rem;
}
p.sucesso {
  color: green;
}
p.erro {
  color: red;
}
</style>
