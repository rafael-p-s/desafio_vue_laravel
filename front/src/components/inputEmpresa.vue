<template>
  <div class="containerInput">
    <form @submit.prevent="inputSaveEmpresa">
      <div>
        <div>
          <label for="razao_social">Razão Social:</label>
          <input
            class="inputRazaoSocial"
            type="text"
            id="razao_social"
            v-model="formData.razao_social"
            required
          />
        </div>
        <div>
          <label for="nome_fantasia">Nome Fantasia:</label>
          <input
            class="inputNomeFantasia"
            type="text"
            id="nome_fantasia"
            v-model="formData.nome_fantasia"
          />
        </div>
        <div>
          <label for="cnpj">CNPJ:</label>
          <input
            class="inputCNPJ"
            type="text"
            id="cnpj"
            v-model="formData.cnpj"
          />
        </div>
      </div>
      <div class="displayBtns">
        <Button
          btnStyle="btnCancel"
          btnText="Cancelar"
          @click="inputCancel"
          type="button"
        />
        <Button btnStyle="btnSave" btnText="Salvar" />
      </div>
      <p v-if="mensagem" :class="mensagemTipo">{{ mensagem }}</p>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import axios from "axios";
import Button from "./button.vue";

const emit = defineEmits(["cancelar"]);

const formData = reactive({
  razao_social: "",
  nome_fantasia: "",
  cnpj: "",
});

const mensagem = ref("");
const mensagemTipo = ref("");

function inputCancel() {
  formData.razao_social = "";
  formData.nome_fantasia = "";
  formData.cnpj = "";
  emit("cancelar");
}
async function inputSaveEmpresa() {
  mensagem.value = "";
  mensagemTipo.value = "";
  const cnpjLimpo = formData.cnpj.replace(/[^\d]/g, "").trim();
  try {
    const response = await axios.post(
      "http://127.0.0.1:8000/api/empresa/store",
      {
        razao_social: formData.razao_social.trim(),
        nome_fantasia: formData.nome_fantasia?.trim() || null,
        cnpj: cnpjLimpo,
      }
    );
    mensagem.value = "Empresa salva com sucesso!";
    mensagemTipo.value = "sucesso";
    formData.razao_social = "";
    formData.nome_fantasia = "";
    formData.cnpj = "";
    setTimeout(() => {
      location.reload();
    }, 500);
  } catch (error) {
    const errors = error.response?.data?.errors;
    if (errors) {
      mensagem.value = Object.values(errors).flat().join(" | ");
    } else {
      mensagem.value =
        error.response?.data?.message || "Erro ao salvar empresa";
    }
  }
}
</script>

<style scoped>
.inputRazaoSocial,
.inputNomeFantasia,
.inputCNPJ {
  margin: 20px;
  height: 20px;
  width: 250px;
}
.containerInput {
  display: flex;
  flex-direction: column;
}

.buttonRow {
  margin-top: 10px;
}

p.sucesso {
  color: green;
  margin-top: 10px;
}

p.erro {
  color: red;
  margin-top: 10px;
}
</style>
