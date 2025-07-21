<template>
  <div class="containerInput">
    <form @submit.prevent="inputSaveSetor">
      <div>
        <label for="descricao">Descrição:</label>
        <input
          class="inputDescricao"
          type="text"
          id="descricao"
          v-model="formData.descricao"
          required
        />
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
  descricao: "",
});

const mensagem = ref("");
const mensagemTipo = ref(""); // pode ser erro ou sucesso

function inputCancel() {
  formData.descricao = "";
  mensagem.value = "";
  emit("cancelar");
}

async function inputSaveSetor() {
  mensagem.value = "";
  mensagemTipo.value = "";

  try {
    const response = await axios.post("http://127.0.0.1:8000/api/setor/store", {
      descricao: formData.descricao,
    });
    mensagem.value = "Setor salvo com sucesso!";
    mensagemTipo.value = "sucesso";
    formData.descricao = "";
    setTimeout(() => {
      location.reload();
    }, 500);
  } catch (error) {
    const msg = err.response?.data?.message || "Erro ao salvar setor";
    mensagem.value = msg;
    mensagemTipo.value = "erro";
  }
}
</script>

<style scoped>
.inputDescricao {
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
