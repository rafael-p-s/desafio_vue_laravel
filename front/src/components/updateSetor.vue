<template>
  <div class="containerUpdate">
    <form @submit.prevent="submitEdit">
      <label for="selectSetor">Selecione o setor para editar:</label>
      <select id="selectSetor" v-model="selectedId" @change="loadSetor">
        <option disabled value="">--Selecione--</option>
        <option v-for="setor in setores" :key="setor.id" :value="setor.id">
          {{ setor.descricao }}
        </option>
      </select>
<!--       <pre>{{ selectedId }}</pre>
      <pre>{{ selectedSetor }}</pre> -->
      <div v-if="selectedSetor" style="margin-top: 10px;">
        <div>
          <label for="descricao">Descrição:</label>
          <input
            id="descricao"
            v-model="formData.descricao"
            type="text"
            required
          />
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
  setores: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["cancelar", "salvo"]);

const selectedId = ref("");
const selectedSetor = ref(null);

const formData = reactive({
  descricao: "",
});

const mensagem = ref("");
const mensagemTipo = ref("");

function loadSetor() {
  const setor = props.setores.find((e) => e.id === selectedId.value);

  if (setor) {
    selectedSetor.value = setor;
    formData.descricao = setor.descricao || "";
    mensagem.value = "";
    mensagemTipo.value = "";
  }
}

function cancelEdit() {
  selectedId.value = "";
  selectedSetor.value = "";
  formData.descricao = "";
  mensagem.value = "";
  emit("cancelar");
}

async function submitEdit() {
  if (!selectedSetor.value) return;

  mensagem.value = "";
  mensagemTipo.value = "";

  try {
    await axios.put(`http://127.0.0.1:8000/api/setor/up/${selectedId.value}`, {
      descricao: formData.descricao.trim(),
    });
    mensagem.value = "Setor atualizada!";
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
        error.response?.data?.message || "Erro ao atualizar setor";
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
